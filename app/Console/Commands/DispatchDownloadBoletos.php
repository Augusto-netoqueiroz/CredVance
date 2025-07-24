<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pagamento;
use App\Jobs\DownloadBoletoPdfJob;
use App\Models\CronLog;

class DispatchDownloadBoletos extends Command
{
    protected $signature = 'boleto:dispatch-download';

    protected $description = 'Dispara jobs para baixar PDFs de boletos pendentes';

    public function handle()
    {
        $outputLog = [];

        $pagamentos = Pagamento::whereNull('boleto_path')->get();

        if ($pagamentos->isEmpty()) {
            $msg = 'Nenhum pagamento pendente encontrado.';
            $this->info($msg);
            $outputLog[] = $msg;

            CronLog::create([
                'command' => $this->signature,
                'output' => implode("\n", $outputLog),
                'status' => 'success',
                'executed_at' => now(),
            ]);

            return 0;
        }

        foreach ($pagamentos as $pag) {
            DownloadBoletoPdfJob::dispatch($pag->id, $pag->codigo_solicitacao);
            $msg = "Job disparado para pagamento ID {$pag->id}";
            $this->info($msg);
            $outputLog[] = $msg;
        }

        $outputLog[] = 'Jobs disparados com sucesso.';

        CronLog::create([
            'command' => $this->signature,
            'output' => implode("\n", $outputLog),
            'status' => 'success',
            'executed_at' => now(),
        ]);

        return 0;
    }
}
