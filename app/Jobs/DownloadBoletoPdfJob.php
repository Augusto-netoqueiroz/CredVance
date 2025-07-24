<?php

namespace App\Jobs;

use App\Models\Pagamento;
use App\Models\CronLog;
use App\Services\InterBoletoService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;

class DownloadBoletoPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $pagamentoId;
    public $codigoSolicitacao;

    public function __construct(int $pagamentoId, string $codigoSolicitacao)
    {
        $this->pagamentoId = $pagamentoId;
        $this->codigoSolicitacao = $codigoSolicitacao;
    }

    public function middleware()
    {
        return [ new RateLimited('download-boletos-pdf') ];
    }

    public function handle(InterBoletoService $service)
    {
        $outputLog = [];

        $logInfo = function($msg) use (&$outputLog) {
            $outputLog[] = $msg;
            Log::info($msg);
        };

        $logWarn = function($msg) use (&$outputLog) {
            $outputLog[] = $msg;
            Log::warning($msg);
        };

        $logError = function($msg) use (&$outputLog) {
            $outputLog[] = $msg;
            Log::error($msg);
        };

        $logInfo("Iniciando job DownloadBoletoPdfJob para pagamento ID {$this->pagamentoId}");

        try {
            $pag = Pagamento::find($this->pagamentoId);
            if (! $pag) {
                $logWarn("Pagamento ID {$this->pagamentoId} não encontrado.");
                // Registra no cron_logs antes de sair
                CronLog::create([
                    'command' => 'DownloadBoletoPdfJob',
                    'output' => implode("\n", $outputLog),
                    'status' => 'failure',
                    'executed_at' => now(),
                ]);
                return;
            }

            if ($pag->boleto_path && file_exists(storage_path("app/{$pag->boleto_path}"))) {
                $logInfo("PDF já existe para Pagamento ID {$pag->id}, pulando download.");
                CronLog::create([
                    'command' => 'DownloadBoletoPdfJob',
                    'output' => implode("\n", $outputLog),
                    'status' => 'success',
                    'executed_at' => now(),
                ]);
                return;
            }

            $logInfo("Baixando PDF para Pagamento ID {$pag->id}, código {$this->codigoSolicitacao}");

            $pdfBinary = $service->downloadBoletoPdf($this->codigoSolicitacao);

            $relativePath = "boletos/{$pag->id}/boleto_{$this->codigoSolicitacao}.pdf";
            $fullPath = storage_path("app/boletos/{$pag->id}");
            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0775, true);
                $logInfo("Diretório criado: {$fullPath}");
            }

            Storage::disk('local')->put($relativePath, $pdfBinary);
            $logInfo("PDF salvo em {$relativePath}");

            $resposta = $service->getCobranca($this->codigoSolicitacao);
            $pixCopiaCola = $resposta['pix']['pixCopiaECola'] ?? null;
            $linhaDigitavel = $resposta['boleto']['linhaDigitavel'] ?? null;

            $mudou = false;
            if ($pixCopiaCola && $pag->pix !== $pixCopiaCola) {
                $pag->pix = $pixCopiaCola;
                $mudou = true;
                $logInfo("Campo pix atualizado.");
            }
            if ($linhaDigitavel && $pag->linha_digitavel !== $linhaDigitavel) {
                $pag->linha_digitavel = $linhaDigitavel;
                $mudou = true;
                $logInfo("Campo linha_digitavel atualizado.");
            }

            $pag->boleto_path = $relativePath;
            $pag->tentativas = 0;
            $pag->error_message = null;
            $pag->save();

            $logInfo("Pagamento atualizado com sucesso.");

            CronLog::create([
                'command' => 'DownloadBoletoPdfJob',
                'output' => implode("\n", $outputLog),
                'status' => 'success',
                'executed_at' => now(),
            ]);
        } catch (Exception $e) {
            $logError("Erro ao baixar PDF para Pagamento ID {$this->pagamentoId}: ".$e->getMessage());

            $pag = Pagamento::find($this->pagamentoId);
            if ($pag) {
                $pag->tentativas = ($pag->tentativas ?? 0) + 1;
                $pag->error_message = "Erro download PDF: " . $e->getMessage();
                $pag->save();
            }

            CronLog::create([
                'command' => 'DownloadBoletoPdfJob',
                'output' => implode("\n", $outputLog),
                'status' => 'failure',
                'executed_at' => now(),
            ]);

            // relança para tentar retry, se configurado
            throw $e;
        }
    }

    public function backoff()
    {
        return [30, 60, 300];
    }

    public function failed(Exception $e)
    {
        $pag = Pagamento::find($this->pagamentoId);
        if ($pag) {
            $pag->error_message = "Falha definitiva download PDF: ".$e->getMessage();
            $pag->save();
        }
        Log::error("DownloadBoletoPdfJob: falha definitiva Pagamento ID {$this->pagamentoId}: ".$e->getMessage());

        // Registra falha definitiva no cron_logs
        CronLog::create([
            'command' => 'DownloadBoletoPdfJob',
            'output' => "Falha definitiva: " . $e->getMessage(),
            'status' => 'failure',
            'executed_at' => now(),
        ]);
    }
}
