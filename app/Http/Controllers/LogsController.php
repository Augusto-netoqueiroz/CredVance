<?php

namespace App\Http\Controllers;

use App\Models\BoletoLog;
use App\Models\Pagamento;
use App\Models\CronLog;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class LogsController extends Controller
{

public function index(Request $request)
    {
        $tipo = $request->query('tipo', 'atividade');

        if ($tipo === 'boletos') {
            $logs = BoletoLog::with(['contrato', 'cliente', 'pagamento'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        } elseif ($tipo === 'cron') {
            $logs = CronLog::orderBy('executed_at', 'desc')
                ->paginate(20);
        } else {
            $logs = ActivityLog::orderBy('data', 'desc')->paginate(20);
        }

        return view('relatorio.atividade_logs.index', compact('logs', 'tipo'));
    }


public function downloadBoletolog($pagamentoId)
{
    $pagamento = Pagamento::find($pagamentoId);

    if (!$pagamento) {
        \Log::warning("Pagamento ID {$pagamentoId} não encontrado.");
        abort(404, 'Pagamento não encontrado.');
    }

    if (empty($pagamento->boleto_path)) {
        \Log::warning("Pagamento ID {$pagamentoId} não possui boleto_path definido.");
        abort(404, 'Arquivo do boleto não está disponível.');
    }

    $filePath = storage_path('app/private/' . $pagamento->boleto_path);

    \Log::info("Tentando acessar arquivo do boleto para pagamento ID {$pagamentoId}: {$filePath}");

    if (!file_exists($filePath)) {
        \Log::error("Arquivo do boleto não encontrado no servidor para pagamento ID {$pagamentoId}: {$filePath}");
        abort(404, 'Arquivo do boleto não encontrado no servidor.');
    }

    $fileName = 'boleto-contrato-' . $pagamento->contrato_id . '.pdf';

    return response()->download($filePath, $fileName, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
    ]);
}







}
