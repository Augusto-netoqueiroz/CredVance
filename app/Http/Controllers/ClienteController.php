<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pagamento;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ClienteController extends Controller
{
    /**
     * Exibe a view inicial do cliente (blade)
     */
    public function index()
    {
        return view('cliente.area');
    }

    /**
     * Retorna os dados JSON para alimentar a dashboard do cliente
     */
    public function data(Request $request)
    {
        $user = $request->user();

        // Parcelas em aberto
        $parcelaAberto = Pagamento::whereHas('contrato', function($q) use ($user) {
                $q->where('cliente_id', $user->id);
            })
            ->where('status', 'pendente')
            ->count();

        // Parcelas pagas
        $parcelaPaga = Pagamento::whereHas('contrato', function($q) use ($user) {
                $q->where('cliente_id', $user->id);
            })
            ->where('status', 'pago')
            ->count();

        // Próxima parcela (mais próxima e pendente)
        $proxima = Pagamento::whereHas('contrato', function($q) use ($user) {
                $q->where('cliente_id', $user->id);
            })
            ->where('status', 'pendente')
            ->orderBy('vencimento')
            ->first([
                'id', 'vencimento', 'valor'
            ]);

        // Status do consórcio (ativo ou concluído)
        $status = optional(
            $user->contratos()->latest()->first()
        )->status;

        // Lista de pagamentos do cliente
        $pagamentos = Pagamento::whereHas('contrato', function($q) use ($user) {
                $q->where('cliente_id', $user->id);
            })
            ->orderBy('vencimento')
            ->get(['id','vencimento','valor','status']);

        return response()->json([
            'parcela_aberto'     => $parcelaAberto,
            'parcela_paga'       => $parcelaPaga,
            'proxima_parcela'    => $proxima,
            'status_consorcio'   => $status,
            'pagamentos'         => $pagamentos,
        ]);
    }
}

 
