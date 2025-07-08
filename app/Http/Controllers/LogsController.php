<?php

namespace App\Http\Controllers;

use App\Models\BoletoLog;
use App\Models\Pagamento;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class LogsController extends Controller
{

public function index(Request $request)
    {
        $tipo = $request->query('tipo', 'atividade'); // padrão: atividade

        if ($tipo === 'boletos') {
            $logs = BoletoLog::with(['contrato', 'cliente', 'pagamento'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        } else {
            // logs de atividade
            $logs = ActivityLog::orderBy('data', 'desc')->paginate(20);
        }

        return view('relatorio.atividade_logs.index', compact('logs', 'tipo'));
    }

public function downloadBoletolog($pagamentoId)
{
    // Busca o pagamento pelo id
    $pagamento = Pagamento::find($pagamentoId);

    if (!$pagamento) {
        abort(404, 'Pagamento não encontrado.');
    }

    if (empty($pagamento->boleto_path)) {
        abort(404, 'Arquivo do boleto não está disponível.');
    }

    // Caminho completo do arquivo na storage privada
    $filePath = storage_path('app/private/' . $pagamento->boleto_path);

    if (!file_exists($filePath)) {
        abort(404, 'Arquivo do boleto não encontrado no servidor.');
    }

    // Nome do arquivo para download
    $fileName = 'boleto-contrato-' . $pagamento->contrato_id . '.pdf';

    // Força o download do arquivo
    return response()->download($filePath, $fileName, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
    ]);
}




}
