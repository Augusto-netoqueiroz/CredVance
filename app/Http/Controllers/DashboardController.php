<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\Pagamento;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Permite acesso apenas para administradores
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Acesso negado.');
        }

        // Soma total de cotas vendidas
        $cotasVendidas = Contrato::sum('quantidade_cotas');

        // Quantidade de contratos ativos
        $contratosCount = Contrato::where('status', 'ativo')->count();

        // Faturamento do mês (somente pagamentos pagados neste mês e ano)
        $faturamentoMes = Pagamento::where('status', 'pago')->sum('valor');

        // Pagamentos pendentes: quantidade e valor total
        $pendentesCount = Pagamento::where('status', 'pendente')->count();
        $pendentesValor = Pagamento::where('status', 'pendente')->sum('valor');

        return view('dashboard', compact(
            'cotasVendidas',
            'contratosCount',
            'faturamentoMes',
            'pendentesCount',
            'pendentesValor'
        ));
    }
}
