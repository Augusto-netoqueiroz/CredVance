<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pagamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClienteController extends Controller
{
    /**
     * Exibe a view inicial do cliente (blade).
     */
    public function index()
    {
        // não precisamos passar $pagamentos aqui
        // pois a view carrega tudo via AJAX em data()
        return view('cliente.area');
    }

    /**
     * Retorna os dados JSON para alimentar a dashboard do cliente via AJAX.
     */
    public function data(): JsonResponse
    {
        $user = Auth::user();

        // Busca os pagamentos do cliente
        $pagamentosRaw = Pagamento::whereHas('contrato', function($q) use ($user) {
                $q->where('cliente_id', $user->id);
            })
            ->orderBy('vencimento')
            ->get(['id', 'vencimento', 'valor', 'status', 'boleto']);

        Log::info('Pagamentos carregados', ['total' => $pagamentosRaw->count()]);

        // Mapeia cada registro adicionando o boleto_url quando existir
        $pagamentos = $pagamentosRaw->map(function($p) {
            $url = null;
            if ($p->boleto && Storage::disk('boletos')->exists($p->boleto)) {
                $url = route('boleto.download', ['pagamento' => $p->id]);
            }

            return [
                'id'         => $p->id,
                // assumindo que vencimento é um Carbon; ajuste se for string!
                'vencimento' => is_object($p->vencimento)
                                  ? $p->vencimento->format('m/Y')
                                  : $p->vencimento,
                'valor'      => number_format($p->valor, 2, ',', '.'),
                'status'     => $p->status,
                'boleto_url' => $url,
            ];
        });

        Log::info('buildResponse complete', ['response_keys' => ['parcela_aberto','parcela_paga','proxima_parcela','status_consorcio','pagamentos']]);

        // Indicadores
        $parcela_aberto   = $pagamentosRaw->where('status', 'pendente')->count();
        $parcela_paga     = $pagamentosRaw->where('status', 'pago')->count();
        $proxima_raw      = $pagamentosRaw->firstWhere('status', 'pendente');
        $proxima_parcela  = $proxima_raw
                             ? [
                                 'vencimento' => $proxima_raw['vencimento'],
                                 'valor'      => $proxima_raw['valor'],
                               ]
                             : null;
        $status_consorcio = $user->contrato->status ?? null;

        return response()->json([
            'parcela_aberto'   => $parcela_aberto,
            'parcela_paga'     => $parcela_paga,
            'proxima_parcela'  => $proxima_parcela,
            'status_consorcio' => $status_consorcio,
            'pagamentos'       => $pagamentos,
        ]);
    }
}