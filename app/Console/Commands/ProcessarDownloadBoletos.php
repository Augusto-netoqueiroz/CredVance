<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pagamento;
use App\Jobs\DownloadBoletoPdfJob;
use Illuminate\Support\Facades\Log;

class ProcessarDownloadBoletos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Podemos chamar de pagamentos:download-boletos
     */
    protected $signature = 'pagamentos:download-boletos';

    /**
     * The console command description.
     */
    protected $description = 'Verifica pagamentos com código de solicitação mas sem boleto (boleto_path) e agenda DownloadBoletoPdfJob';

    public function handle()
    {
        // Busca pagamentos que têm código mas ainda não possuem boleto_path
        // Ajuste condições conforme seus campos: 
        // supondo campo boleto_path nulo ou vazio
        $pagamentos = Pagamento::whereNotNull('codigo_solicitacao')
            ->where(function($q) {
                $q->whereNull('boleto_path')
                  ->orWhere('boleto_path', '');
            })
            // opcional: filtrar status_solicitacao ou vencimento
            ->get();

        $count = $pagamentos->count();
        $this->info("Encontrados {$count} pagamentos sem PDF. Agendando download...");

        foreach ($pagamentos as $pag) {
            // Agendar job imediatamente ou com pequeno delay
            // Se quiser respeitar backoff, o DownloadBoletoPdfJob lida com retry internamente.
            DownloadBoletoPdfJob::dispatch($pag->id, $pag->codigo_solicitacao)
                ->delay(now()->addMinutes(1));
            Log::info("ProcessarDownloadBoletos: job agendado para Pagamento ID {$pag->id}");
        }

        $this->info("Processamento concluído. {$count} jobs agendados.");
    }
}
