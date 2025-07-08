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
    if ($pag->boleto_path && file_exists(storage_path("app/{$pag->boleto_path}"))) {
        Log::info("DownloadBoletoPdfJob: PDF já existe para Pagamento ID {$pag->id}, pulando.");
        return;
    }
    Log::info("DownloadBoletoPdfJob: Baixando PDF para Pagamento ID {$pag->id}, código {$this->codigoSolicitacao}");

    try {
        $pdfBinary = $service->downloadBoletoPdf($this->codigoSolicitacao);

        // Cria diretório se não existir
        $relativePath = "boletos/{$pag->id}/boleto_{$this->codigoSolicitacao}.pdf";
        $fullPath = storage_path("app/boletos/{$pag->id}");
        if (!file_exists($fullPath)) {
            mkdir($fullPath, 0775, true);
        }

        \Storage::disk('local')->put($relativePath, $pdfBinary);

        // Busca Pix/linha_digitavel se ainda não tem
        $resposta = $service->getCobranca($this->codigoSolicitacao);
        $pixCopiaCola = $resposta['pix']['pixCopiaECola'] ?? null;
        $linhaDigitavel = $resposta['boleto']['linhaDigitavel'] ?? null;

        $mudou = false;
        if ($pixCopiaCola && $pag->pix !== $pixCopiaCola) {
            $pag->pix = $pixCopiaCola;
            $mudou = true;
        }
        if ($linhaDigitavel && $pag->linha_digitavel !== $linhaDigitavel) {
            $pag->linha_digitavel = $linhaDigitavel;
            $mudou = true;
        }

        $pag->boleto_path = $relativePath;
        $pag->tentativas = 0;
        $pag->error_message = null;
        $pag->save();

        Log::info("DownloadBoletoPdfJob: PDF/Pix/Linha Digitavel salvo para Pagamento ID {$pag->id}");
    } catch (Exception $e) {
        $pag->tentativas = ($pag->tentativas ?? 0) + 1;
        $pag->error_message = "Erro download PDF: " . $e->getMessage();
        $pag->save();
        Log::error("DownloadBoletoPdfJob: Erro baixar PDF para Pagamento ID {$pag->id}: ".$e->getMessage());

        // Decide aqui se é caso de retry ou não
        throw $e; // se for erro temporário
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
    }
}
