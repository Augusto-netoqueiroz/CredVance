<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\CheckOverduePaymentsJob;

class CheckOverduePaymentsCommand extends Command
{
    protected $signature = 'pagamentos:check-overdue';
    protected $description = 'Checa status dos pagamentos via API Inter e atualiza tabela';

    public function handle()
    {
        CheckOverduePaymentsJob::dispatch();
        $this->info('Job CheckOverduePaymentsJob despachado.');
        return 0;
    }
}
