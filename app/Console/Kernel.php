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
    }

    /**
     * Define o agendamento das tarefas.
     */
   

    protected function schedule(Schedule $schedule)
{
    // roda, por exemplo, a cada hora:
    $schedule->command('pagamentos:atualizar-status')->hourly();
    $schedule->command('pagamentos:download-boletos')->everyMinute();
    $schedule->job(new \App\Jobs\CheckOverduePaymentsJob())->dailyAt('08:00');
    
}
}
