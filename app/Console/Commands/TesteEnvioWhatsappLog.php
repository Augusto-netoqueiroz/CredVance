<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\EnvioWhatsappBoletoLogJob;

class TesteEnvioWhatsappLog extends Command
{
    protected $signature = 'teste:whatsapplog {id?}';

    protected $description = 'Dispara job de envio WhatsApp para boleto_logs, opcionalmente filtrando por ID específico';

    public function handle()
    {
        $id = $this->argument('id');

        if ($id) {
            $this->info("Disparando job EnvioWhatsappBoletoLogJob para boleto_logs com id={$id}");
            dispatch(new EnvioWhatsappBoletoLogJob($id));
        } else {
            $this->info("Disparando job EnvioWhatsappBoletoLogJob para todos registros pendentes");
            dispatch(new EnvioWhatsappBoletoLogJob());
        }

        $this->info("Job disparado. Verifique os logs para resultados.");
    }
}
