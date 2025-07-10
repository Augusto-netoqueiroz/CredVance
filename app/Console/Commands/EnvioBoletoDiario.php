<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\EnvioBoletoDiarioJob;

class EnvioBoletoDiario extends Command
{
    protected $signature = 'envioboletodiario';
    protected $description = 'Dispara o job para envio diário de boletos';

    public function handle()
    {
        EnvioBoletoDiarioJob::dispatch();
        $this->info('Job EnvioBoletoDiarioJob despachado.');
        return 0;
    }
}
