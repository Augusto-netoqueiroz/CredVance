<?php

namespace App\Jobs;

use App\Models\Pagamento;
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
    public $tries = 5;

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
        $pag = Pagamento::find($this->pagamentoId);
        if (! $pag) {
            Log::warning("DownloadBoletoPdfJob: Pagamento ID {$this->pagamentoId} não encontrado.");
            return;
        }
        if ($pag->boleto_path) {
            Log::info("DownloadBoletoPdfJob: já existe boleto_path para Pagamento ID {$pag->id}, pulando.");
            return;
        }
        Log::info("DownloadBoletoPdfJob: tentando baixar PDF para Pagamento ID {$pag->id}, código {$this->codigoSolicitacao}");

        try {
            $pdfBinary = $service->downloadBoletoPdf($this->codigoSolicitacao);

            // Salvar em storage/app/boletos/...
            $relativePath = "boletos/{$pag->id}/boleto_{$this->codigoSolicitacao}.pdf";
            Storage::disk('local')->put($relativePath, $pdfBinary);

            $pag->boleto_path = $relativePath;
            $pag->save();

            Log::info("DownloadBoletoPdfJob: PDF salvo para Pagamento ID {$pag->id} em {$relativePath}");
        } catch (Exception $e) {
            $msg = $e->getMessage();
            if (str_contains($msg, 'Não foi possível gerar o boleto PDF')) {
                Log::warning("DownloadBoletoPdfJob: PDF não pronto para Pagamento ID {$pag->id}, reprogramando: ".$msg);
                throw $e; // para retry/backoff
            }
            // Erro permanente: grava e não relança
            $pag->error_message = "Erro download PDF: ".$msg;
            $pag->save();
            Log::error("DownloadBoletoPdfJob: erro permanente ao baixar PDF para Pagamento ID {$pag->id}: ".$msg);
        }
    }

    public function backoff()
    {
        return [300, 600, 1200];
    }

    public function failed(Exception $e)
    {
        $pag = Pagamento::find($this->pagamentoId);
        if ($pag) {
            $pag->error_message = "Falha definitiva download PDF: ".$e->getMessage();
            $pag->save();
        }
        Log::error("DownloadBoletoPdfJob: falha definitiva Pagamento ID {$this->pagamentoId}: ".$e->getMessage());
    }
}
