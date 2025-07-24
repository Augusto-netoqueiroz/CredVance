<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class TesteEnvioWhatsapp extends Command
{
    protected $signature = 'teste:whatsapp';

    protected $description = 'Teste envio de mensagem WhatsApp via comando whatsapp:send';

    public function handle()
    {
        $telefone = '5561981503910';
        $mensagem = 'Mensagem de teste via comando teste:whatsapp';

        $this->info("Chamando comando whatsapp:send para telefone {$telefone} com mensagem '{$mensagem}'");
        Log::info("TesteEnvioWhatsapp iniciado");

        try {
            $exitCode = Artisan::call('whatsapp:send', [
                'telefone' => $telefone,
                'mensagem' => $mensagem,
            ]);

            $output = Artisan::output();

            $this->info("Comando whatsapp:send executado com exit code {$exitCode}");
            $this->info("Output do comando: {$output}");

            Log::info("Comando whatsapp:send executado com exit code {$exitCode}. Output: {$output}");

            return $exitCode === 0 ? 0 : 1;
        } catch (\Exception $e) {
            $this->error("Erro ao executar comando whatsapp:send: " . $e->getMessage());
            Log::error("Erro no TesteEnvioWhatsapp: " . $e->getMessage());
            return 1;
        }
    }
}
