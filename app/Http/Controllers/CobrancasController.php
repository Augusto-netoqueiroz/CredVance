<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Contrato;
use App\Models\Pagamento;
use Carbon\Carbon;

class CobrancasController extends Controller
{
    // Exibe a view estática que fará o fetch
    public function index()
    {
        Log::info('CobrancasController@index called');
        return view('cliente.index');
    }

    // JSON com indicadores e lista de pagamentos do usuário autenticado
    public function data()
    {
        $userId = Auth::id();
        Log::info('CobrancasController@data called', ['user_id' => $userId]);

        try {
            return $this->buildResponse($userId);
        } catch (\Exception $e) {
            Log::error('Error in CobrancasController@data', ['exception' => $e->getMessage()]);
            return response()->json(['error' => 'Dados não puderam ser carregados.'], 500);
        }
    }

    // JSON de teste para user_id = 2
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

    /** Monta o array de resposta JSON para um dado user_id */
    protected function buildResponse(int $userId)
    {
        Log::info('buildResponse start', ['user_id' => $userId]);

        // Busca contrato diretamente por cliente_id = user_id (tabela users)
        $contrato = Contrato::where('cliente_id', $userId)->firstOrFail();
        Log::info('Contrato encontrado', ['contrato_id' => $contrato->id]);

        // Carrega pagamentos do contrato
        $pagamentos = Pagamento::where('contrato_id', $contrato->id)
            ->orderBy('vencimento', 'asc')
            ->get();
        Log::info('Pagamentos carregados', ['total' => $pagamentos->count()]);

        // Calcula indicadores
        $parcela_aberto = $pagamentos->where('status', 'pendente')->count();
        $parcela_paga   = $pagamentos->where('status', 'pago')->count();
        $primeiro_pendente = $pagamentos->where('status', 'pendente')->sortBy('vencimento')->first();

        // Formata a próxima parcela
        $proxima_parcela = null;
        if ($primeiro_pendente) {
            $dt = Carbon::parse($primeiro_pendente->vencimento);
            $proxima_parcela = [
                'vencimento' => $dt->format('d/m/Y'),
                'valor'      => number_format($primeiro_pendente->valor, 2, ',', '.'),
            ];
        }

        // Prepara a lista de faturas
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
        $valorCota = $consorcio->valor_cota; // certifique-se de ter esse campo na tabela consorcios
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
