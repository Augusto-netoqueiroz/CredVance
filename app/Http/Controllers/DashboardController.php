<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\Pagamento;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Exports\GenericExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

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

        // Faturamento do mês baseado na data de VENCIMENTO dos pagamentos
        $faturamentoMes = Pagamento::where('status', 'pago')
            ->whereMonth('vencimento', now()->month)
            ->whereYear('vencimento',  now()->year)
            ->sum('valor');

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

    public function detalhesCotas(Request $request)
    {
        $collection = Contrato::with('cliente')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->map(function ($contrato) {
                return [
                    'Cliente'   => $contrato->cliente->name ?? '-',
                    'Cotas'     => $contrato->quantidade_cotas,
                    'Criado Em' => $contrato->created_at->format('d/m/Y'),
                ];
            });

        $data     = $collection->toArray();
        $headings = array_keys($data[0] ?? []);
        $title    = 'Relatório de Cotas Vendidas';

        if ($format = $request->query('export')) {
            if ($format === 'pdf') {
                $pdf = PDF::loadView('exports.relatorio', compact('data', 'headings', 'title'));
                return $pdf->download('cotas.pdf');
            }
            $ext = $format === 'excel' ? 'xlsx' : 'csv';
            return Excel::download(new GenericExport($data, $headings), "cotas.{$ext}");
        }

        return response()->json($collection);
    }

    public function detalhesContratos(Request $request)
    {
        $collection = Contrato::with('cliente')
            ->where('status', 'ativo')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->map(function ($contrato) {
                return [
                    'Cliente'   => $contrato->cliente->name ?? '-',
                    'Cotas'     => $contrato->quantidade_cotas,
                    'Criado Em' => $contrato->created_at->format('d/m/Y'),
                ];
            });

        $data     = $collection->toArray();
        $headings = array_keys($data[0] ?? []);
        $title    = 'Relatório de Contratos Ativos';

        if ($format = $request->query('export')) {
            if ($format === 'pdf') {
                $pdf = PDF::loadView('exports.relatorio', compact('data', 'headings', 'title'));
                return $pdf->download('contratos.pdf');
            }
            $ext = $format === 'excel' ? 'xlsx' : 'csv';
            return Excel::download(new GenericExport($data, $headings), "contratos.{$ext}");
        }

        return response()->json($collection);
    }

    public function detalhesFaturamento(Request $request)
    {
        $collection = Pagamento::with('contrato.cliente')
            ->where('status', 'pago')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->map(function ($pagamento) {
                return [
                    'Cliente'    => optional($pagamento->contrato->cliente)->name ?? '-',
                    'Valor'      => number_format($pagamento->valor, 2, ',', '.'),
                    'Vencimento' => Carbon::parse($pagamento->vencimento)->format('d/m/Y'),
                    'Pago Em'    => $pagamento->created_at->format('d/m/Y'),
                ];
            });

        $data     = $collection->toArray();
        $headings = array_keys($data[0] ?? []);
        $title    = 'Relatório de Faturamento';

        if ($format = $request->query('export')) {
            if ($format === 'pdf') {
                $pdf = PDF::loadView('exports.relatorio', compact('data', 'headings', 'title'));
                return $pdf->download('faturamento.pdf');
            }
            $ext = $format === 'excel' ? 'xlsx' : 'csv';
            return Excel::download(new GenericExport($data, $headings), "faturamento.{$ext}");
        }

        return response()->json($collection);
    }

    public function detalhesPendentes(Request $request)
    {
        $collection = Pagamento::with('contrato.cliente')
            ->where('status', 'pendente')
            ->orderBy('vencimento')
            ->limit(10)
            ->get()
            ->map(function ($pagamento) {
                return [
                    'Cliente'    => optional($pagamento->contrato->cliente)->name ?? '-',
                    'Valor'      => number_format($pagamento->valor, 2, ',', '.'),
                    'Vencimento' => Carbon::parse($pagamento->vencimento)->format('d/m/Y'),
                ];
            });

        $data     = $collection->toArray();
        $headings = array_keys($data[0] ?? []);
        $title    = 'Relatório de Pagamentos Pendentes';

        if ($format = $request->query('export')) {
            if ($format === 'pdf') {
                $pdf = PDF::loadView('exports.relatorio', compact('data', 'headings', 'title'));
                return $pdf->download('pendentes.pdf');
            }
            $ext = $format === 'excel' ? 'xlsx' : 'csv';
            return Excel::download(new GenericExport($data, $headings), "pendentes.{$ext}");
        }

        return response()->json($collection);
    }
}
