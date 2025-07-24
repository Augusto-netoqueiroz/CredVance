<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\DownloadBoletoPdfJob;

class DownloadBoletoPdfCommand extends Command
{
    protected $signature = 'boleto:download-pdf 
                            {pagamentoId : ID do pagamento} 
                            {codigoSolicitacao : Código da solicitação do boleto}';

    protected $description = 'Despacha o job para baixar o PDF do boleto pelo pagamento e código da solicitação';

    public function handle()
    {
        $pagamentoId = (int) $this->argument('pagamentoId');
        $codigoSolicitacao = $this->argument('codigoSolicitacao');

        DownloadBoletoPdfJob::dispatch($pagamentoId, $codigoSolicitacao);

        $this->info("Job DownloadBoletoPdfJob despachado para pagamento ID {$pagamentoId} com código {$codigoSolicitacao}.");
        return 0;
    }
}
