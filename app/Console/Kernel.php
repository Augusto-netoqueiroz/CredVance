<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define os comandos do Artisan para registrar.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
        \App\Console\Commands\TesteEnvioBoleto::class;
        \App\Console\Commands\EnviarBoletoEmail::class;
        
    }

    /**
     * Define o agendamento das tarefas.
     */
   

    protected function schedule(Schedule $schedule)
{
    // roda, por exemplo, a cada hora:
    $schedule->command('pagamentos:atualizar-status')->hourly();
    $schedule->command('pagamentos:download-boletos')->everyMinute();
    $schedule->command('pagamento:reemite-boleto --all')->everyFiveMinutes();
    $schedule->command('envioboletodiario')->cron('0 8,14,20 * * *');
    $schedule->command('queue:retry all')->everyFiveMinutes();
    $schedule->job(new \App\Jobs\CheckOverduePaymentsJob())->everyMinute();
    
    
    
}
}
