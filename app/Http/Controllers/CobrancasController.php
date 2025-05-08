<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Contrato;
use App\Models\Pagamento;
use Carbon\Carbon;
use App\Services\ActivityLoggerService; // ← AQUI

class CobrancasController extends Controller
{
    public function index()
    {
        Log::info('CobrancasController@index called');
        return view('cliente.index');
    }

    public function data()
    {
        $userId = Auth::id();
        Log::info('CobrancasController@data called', ['user_id' => $userId]);

        try {
            $response = $this->buildResponse($userId);

            // LOG DE ATIVIDADE ← AQUI
            ActivityLoggerService::registrar(
                'Cobranças',
                'Visualizou a área de faturas e status financeiro.'
            );

            return $response;

        } catch (\Exception $e) {
            Log::error('Error in CobrancasController@data', ['exception' => $e->getMessage()]);
            return response()->json(['error' => 'Dados não puderam ser carregados.'], 500);
        }
    }

    public function testData()
    {
        Log::info('CobrancasController@testData called');

        try {
            return $this->buildResponse(2);
        } catch (\Exception $e) {
            Log::error('Error in CobrancasController@testData', ['exception' => $e->getMessage()]);
            return response()->json(['error' => 'Teste falhou.'], 500);
        }
    }

    protected function buildResponse(int $userId)
    {
        Log::info('buildResponse start', ['user_id' => $userId]);

        $contrato = Contrato::where('cliente_id', $userId)->firstOrFail();
        Log::info('Contrato encontrado', ['contrato_id' => $contrato->id]);

        $pagamentos = Pagamento::where('contrato_id', $contrato->id)
            ->orderBy('vencimento', 'asc')
            ->get();
        Log::info('Pagamentos carregados', ['total' => $pagamentos->count()]);

        $parcela_aberto = $pagamentos->where('status', 'pendente')->count();
        $parcela_paga   = $pagamentos->where('status', 'pago')->count();
        $primeiro_pendente = $pagamentos->where('status', 'pendente')->sortBy('vencimento')->first();

        $proxima_parcela = null;
        if ($primeiro_pendente) {
            $dt = Carbon::parse($primeiro_pendente->vencimento);
            $proxima_parcela = [
                'vencimento' => $dt->format('d/m/Y'),
                'valor'      => number_format($primeiro_pendente->valor, 2, ',', '.'),
            ];
        }

        $listaPagamentos = $pagamentos->map(function($f) {
            $dt = Carbon::parse($f->vencimento);
            return [
                'id'         => $f->id,
                'vencimento' => $dt->format('m/Y'),
                'valor'      => number_format($f->valor, 2, ',', '.'),
                'status'     => $f->status,
            ];
        })->values();

        $response = [
            'parcela_aberto'   => $parcela_aberto,
            'parcela_paga'     => $parcela_paga,
            'proxima_parcela'  => $proxima_parcela,
            'status_consorcio' => $contrato->status,
            'pagamentos'       => $listaPagamentos,
        ];

        Log::info('buildResponse complete', ['response_keys' => array_keys($response)]);
        return response()->json($response);
    }

    public function gerarPagamentos(Contrato $contrato): void
    {
        $consorcio = $contrato->consorcio;
        $clienteId = $contrato->cliente_id;
        $qtdCotas = $contrato->quantidade_cotas;
        $valorCota = $consorcio->valor_cota;
        $prazo = $consorcio->prazo;

        $inicio = Carbon::now()->startOfMonth();
        $pagamentos = [];

        for ($i = 0; $i < $prazo; $i++) {
            $vencimento = $inicio->copy()->addMonths($i);
            $pagamentos[] = [
                'contrato_id' => $contrato->id,
                'vencimento'  => $vencimento,
                'valor'       => $valorCota * $qtdCotas,
                'status'      => 'pendente',
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        \App\Models\Pagamento::insert($pagamentos);
    }
}
