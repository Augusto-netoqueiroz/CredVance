<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\Pagamento;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Exports\GenericExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use App\Services\ActivityLoggerService;

class DashboardController extends Controller
{
     public function index()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Acesso negado.');
        }

          ActivityLoggerService::registrar(
            'Dashboard',
            'Abriu a página de Dashboard.'
        );

        // Indicadores principais
        $cotasVendidas = Contrato::sum('quantidade_cotas');
        $contratosCount = Contrato::where('status', 'ativo')->count();
        $faturamentoMes = Pagamento::where('status', 'pago')
            ->whereMonth('vencimento', now()->month)
            ->whereYear('vencimento', now()->year)
            ->sum('valor');
        $pendentesCount = Pagamento::where('status', 'pendente')->count();
        $pendentesValor = Pagamento::where('status', 'pendente')->sum('valor');

        // Gráfico de Vendas dos últimos 6 meses
        $graficoMeses = [];
        $graficoValores = [];
        for ($i = 5; $i >= 0; $i--) {
            $data = now()->subMonths($i);
            $label = $data->format('M/Y');
            $somaCotas = Contrato::whereYear('created_at', $data->year)
                ->whereMonth('created_at', $data->month)
                ->sum('quantidade_cotas');
            $graficoMeses[] = $label;
            $graficoValores[] = $somaCotas;
        }

        // Gráfico de Faturamento x Pendências (últimos 6 meses)
        $graficoFaturamentoLabels = [];
        $graficoFaturamentoPago = [];
        $graficoFaturamentoPendente = [];
        for ($i = 5; $i >= 0; $i--) {
            $data = now()->subMonths($i);
            $mesLabel = $data->format('M/Y');

            $pago = Pagamento::whereMonth('vencimento', $data->month)
                ->whereYear('vencimento', $data->year)
                ->where('status', 'pago')
                ->sum('valor');

            $pendente = Pagamento::whereMonth('vencimento', $data->month)
                ->whereYear('vencimento', $data->year)
                ->where('status', 'pendente')
                ->sum('valor');

            $graficoFaturamentoLabels[] = $mesLabel;
            $graficoFaturamentoPago[] = round($pago, 2);
            $graficoFaturamentoPendente[] = round($pendente, 2);
        }

        return view('dashboard', compact(
            'cotasVendidas',
            'contratosCount',
            'faturamentoMes',
            'pendentesCount',
            'pendentesValor',
            'graficoMeses',
            'graficoValores',
            'graficoFaturamentoLabels',
            'graficoFaturamentoPago',
            'graficoFaturamentoPendente'
        ));
    }

    // (os métodos detalhesCotas, detalhesContratos, detalhesFaturamento, detalhesPendentes seguem abaixo como já estão no seu código atual)

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
