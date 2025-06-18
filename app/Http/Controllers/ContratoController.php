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

class ContratoController extends Controller
{
    public function create()
    {
        $clientes   = User::where('role', 'cliente')->orderBy('name')->get();
        $consorcios = Consorcio::all();

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
    if (! Hash::check($request->senha_confirm, Auth::user()->password)) {
        return back()->withErrors(['senha_confirm' => 'Senha inválida.'])->withInput();
    }

    // Determina o cliente (admin pode escolher outro; cliente normal usa Auth)
    if (Auth::user()->role === 'admin') {
        $cliente = User::find($request->cliente_id);
        if (! $cliente) {
            return back()->withErrors(['cliente_id' => 'Cliente inválido'])->withInput();
        }
    } else {
        $cliente = Auth::user();
    }

    // Verificar endereço completo no usuário
    $faltantes = [];
    foreach (['logradouro','numero','bairro','cidade','uf','cep'] as $campo) {
        if (empty($cliente->$campo)) {
            $faltantes[] = $campo;
        }
    }
    if (! empty($faltantes)) {
        $msg = 'Endereço incompleto do cliente: ' . implode(', ', $faltantes);
        return back()
            ->withErrors(['endereco' => $msg])
            ->withInput();
    }

    // Criar contrato
    $contrato = Contrato::create([
        'cliente_id'       => $cliente->id,
        'consorcio_id'     => $request->consorcio_id,
        'quantidade_cotas' => $request->quantidade_cotas,
        'aceito_em'        => Carbon::parse($request->data_aceite)->format('Y-m-d H:i:s'),
        'ip'               => $request->ip(),
        'navegador_info'   => $request->navegador_info,
        'resolucao'        => $request->resolucao_tela,
        'latitude'         => $request->latitude,
        'longitude'        => $request->longitude,
    ]);

    Log::info('Contrato criado com sucesso', ['contrato_id' => $contrato->id]);

    // Disparar geração de parcelas e jobs de boleto via PagamentoGenerator
    Log::info("Chamando PagamentoGenerator->generate para Contrato ID {$contrato->id}");
    $pagamentoGenerator->generate($contrato);

    // Geração de PDF do contrato, agora incluindo frameBase64
    try {
        Log::info('Iniciando geração do PDF', ['contrato_id' => $contrato->id]);

        // Recupera pagamentos como antes
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
                Log::warning('Falha ao ler moldura-contrato.jpg para Base64', ['path' => $framePath]);
            }
        } else {
            Log::warning('Arquivo de moldura não encontrado em assets/img', ['path' => $framePath]);
        }

        // Chama a view como antes, mas incluindo o frameBase64
        $pdf = Pdf::loadView('pdf.contrato', [
            'contrato'    => $contrato,
            'pagamentos'  => $pagamentos,
            'frameBase64' => $frameBase64,
        ]);

        $path = "contratos/contrato_{$contrato->id}.pdf";
        Storage::makeDirectory('contratos');
        Storage::put($path, $pdf->output());
        $contrato->update(['pdf_contrato' => $path]);
        Log::info('PDF gerado e salvo com sucesso', ['path' => $path]);
        ActivityLoggerService::registrar(
            'Contratos',
            'Criou contrato para o cliente ID ' . $contrato->cliente_id . ' com ' . $contrato->quantidade_cotas . ' cotas.'
        );
    } catch (\Exception $e) {
        Log::error('Erro ao gerar PDF do contrato', [
            'contrato_id' => $contrato->id,
            'erro'        => $e->getMessage(),
            'trace'       => $e->getTraceAsString()
        ]);
    }

    return redirect()->route('Inicio')->with('success', 'Contrato criado! Geração de boletos iniciada.');
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

        // Monta array de valores conforme regra existente...
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

        $dataContrato = Carbon::parse($contrato->aceito_em);
        foreach ($parcelasArray as $idx => $valorParcela) {
            if ($idx === 0) {
                $vencimento = $dataContrato;
            } else {
                $vencimento = $dataContrato->copy()
                    ->addMonthsNoOverflow($idx)
                    ->day($diaVencimento);
            }
            $pag = \App\Models\Pagamento::create([
                'contrato_id' => $contrato->id,
                'vencimento'  => $vencimento,
                'valor'       => $valorParcela * $qtdCotas,
                'status'      => 'pendente',
            ]);
            Log::info("Pagamento criado ID {$pag->id}, vencimento {$pag->vencimento}");
            // Dispara o job que chama o serviço InterBoletoService
            \App\Jobs\GerarBoletoJob::dispatch($pag->id);
            Log::info("GerarBoletoJob despachado para Pagamento ID {$pag->id}");
        }

        Log::info('Pagamentos gerados e jobs despachados', [
            'contrato_id'    => $contrato->id,
            'parcelas_count' => count($parcelasArray),
            'dia_vencimento' => $diaVencimento,
        ]);
    }


}
