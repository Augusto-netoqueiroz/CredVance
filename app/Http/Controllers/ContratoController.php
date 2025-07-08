<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\Consorcio;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Services\ActivityLoggerService;
use Illuminate\Support\Facades\Log;
use App\Services\PagamentoGenerator; 
use Illuminate\Support\Facades\Mail;
use App\Mail\ContratoCriadoMail;

class ContratoController extends Controller
{
    public function create()
    {
        $clientes   = User::where('role', 'cliente')->orderBy('name')->get();
        $consorcios = Consorcio::all();

        ActivityLoggerService::registrar(
            'Contratos',
            'Abriu a página de contratação.'
        );

        return view('contratos.create', compact('clientes','consorcios'));
    }

   public function store(Request $request, PagamentoGenerator $pagamentoGenerator)
{
    // Validações básicas
    $request->validate([
        'cliente_id'       => 'required|exists:users,id',
        'consorcio_id'     => 'required|exists:consorcios,id',
        'quantidade_cotas' => 'required|integer|min:1',
        'dia_vencimento'   => 'required|integer|min:1|max:31',
        'senha_confirm'    => 'required',
        'navegador_info'   => 'required',
        'resolucao_tela'   => 'required',
        'latitude'         => 'required',
        'longitude'        => 'required',
        'data_aceite'      => 'required|date',
    ]);

    // Verifica senha
    if (! \Hash::check($request->senha_confirm, \Auth::user()->password)) {
        ActivityLoggerService::registrar(
            'Contratos',
            'Tentou criar contrato, mas informou senha inválida. UserID: ' . \Auth::user()->id
        );
        return back()->withErrors(['senha_confirm' => 'Senha inválida.'])->withInput();
    }

    // Determina o cliente (admin pode escolher outro; cliente normal usa Auth)
    if (\Auth::user()->role === 'admin') {
        $cliente = User::find($request->cliente_id);
        if (! $cliente) {
            ActivityLoggerService::registrar(
                'Contratos',
                'Tentou criar contrato, mas cliente_id inválido: ' . $request->cliente_id . ' (Usuário: ' . \Auth::user()->id . ')'
            );
            return back()->withErrors(['cliente_id' => 'Cliente inválido'])->withInput();
        }
    } else {
        $cliente = \Auth::user();
    }

    // Verificar endereço completo no usuário e validar CEP
    $faltantes = [];
    foreach (['logradouro', 'numero', 'bairro', 'cidade', 'uf', 'cep'] as $campo) {
        if (empty($cliente->$campo)) {
            $faltantes[] = $campo;
        }
    }
    if (!empty($faltantes)) {
        $msg = 'Endereço incompleto do cliente: ' . implode(', ', $faltantes);
        ActivityLoggerService::registrar(
            'Contratos',
            'Tentou criar contrato, mas endereço incompleto para cliente ID ' . $cliente->id . '. Campos: ' . implode(', ', $faltantes)
        );
        return back()
            ->withErrors(['endereco' => $msg])
            ->withInput();
    }

    // Validação extra do formato do CEP (apenas 8 números, com ou sem hífen)
    $cepLimpo = preg_replace('/\D/', '', $cliente->cep);
    if (strlen($cepLimpo) !== 8) {
        $msg = 'CEP inválido! Informe exatamente 8 números. Exemplo: 12345678 ou 12345-678';
        ActivityLoggerService::registrar(
            'Contratos',
            'Tentou criar contrato, mas CEP inválido para cliente ID ' . $cliente->id . ' (' . $cliente->cep . ')'
        );
        return back()
            ->withErrors(['endereco' => $msg])
            ->withInput();
    }

    // Criar contrato
    try {
        $contrato = Contrato::create([
            'cliente_id'       => $cliente->id,
            'consorcio_id'     => $request->consorcio_id,
            'quantidade_cotas' => $request->quantidade_cotas,
            'aceito_em'        => \Carbon\Carbon::parse($request->data_aceite)->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'),
            'ip'               => $request->ip(),
            'navegador_info'   => $request->navegador_info,
            'resolucao'        => $request->resolucao_tela,
            'latitude'         => $request->latitude,
            'longitude'        => $request->longitude,
        ]);

        \Log::info('Contrato criado com sucesso', ['contrato_id' => $contrato->id]);
        ActivityLoggerService::registrar(
            'Contratos',
            'Contrato criado com sucesso. Contrato ID: ' . $contrato->id . ' | Cliente ID: ' . $contrato->cliente_id . ' | Quantidade de cotas: ' . $contrato->quantidade_cotas
        );

        // Disparar geração de parcelas e jobs de boleto via PagamentoGenerator
        \Log::info("Chamando PagamentoGenerator->generate para Contrato ID {$contrato->id}");
        $pagamentoGenerator->generate($contrato);

        // Geração de PDF do contrato, agora incluindo frameBase64
        try {
            \Log::info('Iniciando geração do PDF', ['contrato_id' => $contrato->id]);
            $pagamentos = $contrato->pagamentos()->orderBy('vencimento')->get();

            // Lê a moldura e converte para Base64
            $frameBase64 = null;
            $framePath = public_path('assets/img/moldura-contrato.jpg');
            if (file_exists($framePath)) {
                $type = pathinfo($framePath, PATHINFO_EXTENSION);
                $data = @file_get_contents($framePath);
                if ($data !== false) {
                    $frameBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                } else {
                    \Log::warning('Falha ao ler moldura-contrato.jpg para Base64', ['path' => $framePath]);
                }
            } else {
                \Log::warning('Arquivo de moldura não encontrado em assets/img', ['path' => $framePath]);
            }

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.contrato', [
                'contrato'    => $contrato,
                'pagamentos'  => $pagamentos,
                'frameBase64' => $frameBase64,
            ]);

            $path = "contratos/contrato_{$contrato->id}.pdf";
            \Storage::makeDirectory('contratos');
            \Storage::put($path, $pdf->output());
            $contrato->update(['pdf_contrato' => $path]);
            \Log::info('PDF gerado e salvo com sucesso', ['path' => $path]);
            ActivityLoggerService::registrar(
                'Contratos',
                'PDF gerado e salvo para o contrato ID: ' . $contrato->id
            );
        } catch (\Exception $e) {
            \Log::error('Erro ao gerar PDF do contrato', [
                'contrato_id' => $contrato->id,
                'erro'        => $e->getMessage(),
                'trace'       => $e->getTraceAsString()
            ]);
            ActivityLoggerService::registrar(
                'Contratos',
                'Erro ao gerar PDF do contrato ID ' . $contrato->id . ': ' . $e->getMessage()
            );
        }

        // ===============================================
        //  Envio do e-mail de criação de contrato
        // ===============================================
        try {
            \Log::info('Tentando enviar e-mail de contrato para: ' . $cliente->email, [
                'contrato_id' => $contrato->id,
                'cliente_email' => $cliente->email,
                'pdf_anexo' => $contrato->pdf_contrato,
            ]);
            \Mail::to($cliente->email)->send(new \App\Mail\ContratoCriadoMail($contrato));
            \Log::info('E-mail de contrato enviado com sucesso para: ' . $cliente->email, [
                'contrato_id' => $contrato->id
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao enviar e-mail de contrato', [
                'contrato_id' => $contrato->id,
                'cliente_email' => $cliente->email,
                'erro'        => $e->getMessage(),
                'trace'       => $e->getTraceAsString()
            ]);
            ActivityLoggerService::registrar(
                'Contratos',
                'Erro ao enviar e-mail do contrato ID ' . $contrato->id . ' para ' . $cliente->email . ': ' . $e->getMessage()
            );
        }

        return redirect()->route('Inicio')->with('success', 'Contrato criado! Geração de boletos iniciada.');
    } catch (\Exception $e) {
        \Log::error('Erro ao criar contrato', [
            'erro'        => $e->getMessage(),
            'trace'       => $e->getTraceAsString()
        ]);
        ActivityLoggerService::registrar(
            'Contratos',
            'Erro ao criar contrato para cliente ID ' . ($cliente->id ?? '-') . ': ' . $e->getMessage()
        );
        return back()
            ->withErrors(['contrato' => 'Erro ao criar contrato: ' . $e->getMessage()])
            ->withInput();
    }
}





    /**
     * Gera pagamentos e despacha jobs (exemplo interno).
     * Ajuste para chamar GerarBoletoJob::dispatch($pag->id).
     */
public function gerarPagamentos(Contrato $contrato, int $diaVencimento): void
{
    $qtdCotas   = $contrato->quantidade_cotas;
    $consorcio  = $contrato->consorcio;
    $prazo      = $consorcio->prazo;
    $valorTotal = $consorcio->valor_total;
    $valorInicialParcela = $consorcio->parcela_mensal;

    $parcelasArray = [];
    if (method_exists($consorcio, 'parcelas') && $consorcio->parcelas()->count()) {
        foreach ($consorcio->parcelas as $p) {
            $parcelasArray[] = $p->valor_parcela;
        }
    } elseif ($prazo === 12) {
        $v = $valorInicialParcela;
        for ($i = 0; $i < 12; $i++) {
            $parcelasArray[] = max($v, 100);
            $v = max($v - 5, 100);
        }
    } elseif ($prazo === 24) {
        $v = $valorInicialParcela;
        for ($i = 0; $i < 24; $i++) {
            $parcelasArray[] = max($v, 100);
            if (($i + 1) % 2 === 0) {
                $v = max($v - 5, 100);
            }
        }
    } else {
        $base = round($valorTotal / $prazo, 2);
        for ($i = 0; $i < $prazo; $i++) {
            $parcelasArray[] = $base;
        }
    }

    $dataAceite = \Carbon\Carbon::parse($contrato->aceito_em);
    $datasVencimentos = [];

    for ($idx = 0; $idx < count($parcelasArray); $idx++) {
        if ($idx === 0) {
            $vencimento = $dataAceite->copy()->addDay();
        } else {
            $dataBase = $dataAceite->copy()->addDay();
            $proximaData = $dataBase->copy()->addMonthsNoOverflow($idx - 1);
            $ultimoDiaDoMes = $proximaData->copy()->endOfMonth()->day;
            $dia = min($diaVencimento, $ultimoDiaDoMes);
            $vencimento = $proximaData->copy()->day($dia);
        }
        $datasVencimentos[] = [
            'idx' => $idx,
            'vencimento' => $vencimento->format('d/m/Y'),
            'valor' => number_format($parcelasArray[$idx] * $qtdCotas, 2, ',', '.'),
        ];
    }

    // PARA DEBUG NA TELA (não precisa do log!):
    dd([
        'datas' => $datasVencimentos,
        'dia_vencimento_escolhido' => $diaVencimento,
        'aceite_em' => $dataAceite->format('Y-m-d'),
        'primeiro_vencimento_base' => $dataAceite->copy()->addDay()->format('d/m/Y'),
    ]);

    // ... (o resto do código fica abaixo do dd())
}








}
