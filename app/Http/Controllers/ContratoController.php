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

class ContratoController extends Controller
{
    public function create()
    {
        $clientes   = User::where('role', 'cliente')->orderBy('name')->get();
        $consorcios = Consorcio::all();

        return view('contratos.create', compact('clientes','consorcios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id'       => 'required|exists:users,id',
            'consorcio_id'     => 'required|exists:consorcios,id',
            'quantidade_cotas' => 'required|integer|min:1',
            // permitir até dia 31
            'dia_vencimento'   => 'required|integer|min:1|max:31',
            'senha_confirm'    => 'required',
            'navegador_info'   => 'required',
            'resolucao_tela'   => 'required',
            'latitude'         => 'required',
            'longitude'        => 'required',
            'data_aceite'      => 'required|date',
        ]);

        if (!Hash::check($request->senha_confirm, Auth::user()->password)) {
            return back()->withErrors(['senha_confirm' => 'Senha inválida.']);
        }

        $contrato = Contrato::create([
            'cliente_id'       => $request->cliente_id,
            'consorcio_id'     => $request->consorcio_id,
            'quantidade_cotas' => $request->quantidade_cotas,
            'aceito_em'        => Carbon::parse($request->data_aceite)->format('Y-m-d H:i:s'),
            'ip'               => $request->ip(),
            'navegador_info'   => $request->navegador_info,
            'resolucao'        => $request->resolucao_tela,
            'latitude'         => $request->latitude,
            'longitude'        => $request->longitude,
        ]);

        \Log::info('Contrato criado com sucesso', ['contrato_id' => $contrato->id]);

        // pega o dia escolhido para vencimento das parcelas
        $diaVencimento = (int) $request->dia_vencimento;
        $this->gerarPagamentos($contrato, $diaVencimento);

        try {
            \Log::info('Iniciando geração do PDF', ['contrato_id' => $contrato->id]);

            $pdf = Pdf::loadView('pdf.contrato', [
                'contrato'   => $contrato,
                'pagamentos' => $contrato->pagamentos()->orderBy('vencimento')->get()
            ]);

            $path = "contratos/contrato_{$contrato->id}.pdf";

            Storage::makeDirectory('contratos');
            Storage::put($path, $pdf->output());

            $contrato->update(['pdf_contrato' => $path]);

            \Log::info('PDF gerado e salvo com sucesso', ['path' => $path]);

            ActivityLoggerService::registrar(
                'Contratos',
                'Criou contrato para o cliente ID ' . $contrato->cliente_id . ' com ' . $contrato->quantidade_cotas . ' cotas.'
            );

        } catch (\Exception $e) {
            \Log::error('Erro ao gerar PDF do contrato', [
                'contrato_id' => $contrato->id,
                'erro'        => $e->getMessage(),
                'trace'       => $e->getTraceAsString()
            ]);
        }

        return redirect()->route('Inicio')->with('success', 'Contrato Criado !! Bem vindo a Credvance.');
    }

    /**
     * Gera pagamentos: 1ª no dia do contrato, demais no dia escolhido
     */
    public function gerarPagamentos(Contrato $contrato, int $diaVencimento): void
    {
        $qtdCotas = $contrato->quantidade_cotas;
        $consorcio = $contrato->consorcio;
        $prazo     = $consorcio->prazo;
        $valorTotalPlano     = $consorcio->valor_total;
        $taxaJuros           = $consorcio->juros;
        $valorFinalPlano     = $consorcio->valor_final;
        $valorInicialParcela = $consorcio->parcela_mensal;

        // monta array de valores das parcelas
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
            $base = round($valorTotalPlano / $prazo, 2);
            for ($i = 0; $i < $prazo; $i++) {
                $parcelasArray[] = $base;
            }
        }

        $pagamentos = [];
        // data inicial: aceito_em ou now
        $dataContrato = Carbon::parse($contrato->aceito_em);

        foreach ($parcelasArray as $idx => $valorParcela) {
            if ($idx === 0) {
                // 1ª parcela: dia do contrato
                $vencimento = $dataContrato;
            } else {
                // demais: adiciona meses e fixa dia escolhido
                $vencimento = $dataContrato->copy()
                    ->addMonthsNoOverflow($idx)
                    ->day($diaVencimento);
            }

            $pagamentos[] = [
                'contrato_id' => $contrato->id,
                'vencimento'  => $vencimento,
                'valor'       => $valorParcela * $qtdCotas,
                'status'      => 'pendente',
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        \App\Models\Pagamento::insert($pagamentos);

        \Log::info('Pagamentos gerados com vencimentos ajustados', [
            'contrato_id'      => $contrato->id,
            'quantidade_cotas' => $qtdCotas,
            'parcelas'         => count($parcelasArray),
            'dia_vencimento'   => $diaVencimento
        ]);
    }
}
