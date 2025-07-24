<?php

namespace App\Console\Commands;

use App\Jobs\SendWhatsappTextJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendWhatsappMessageCommand extends Command
{
    protected $signature = 'whatsapp:send {telefone} {mensagem*}';

    protected $description = 'Envia uma mensagem WhatsApp via job e faz log do processo';

    public function handle()
    {
        $telefone = $this->argument('telefone');
        $mensagemArgument = $this->argument('mensagem');

        if (is_array($mensagemArgument)) {
            $mensagem = implode(' ', $mensagemArgument);
        } else {
            $mensagem = $mensagemArgument;
        }

        Log::info("Comando whatsapp:send iniciado para telefone {$telefone}");

        $this->info("Enviando mensagem para {$telefone}:");
        $this->info($mensagem);

        try {
            SendWhatsappTextJob::dispatch($telefone, $mensagem);

            Log::info("Job SendWhatsappTextJob despachado com sucesso para {$telefone}");

            $this->info('Job despachado com sucesso. Verifique os logs para confirmação do envio.');
        } catch (\Exception $e) {
            Log::error("Erro ao despachar job SendWhatsappTextJob para {$telefone}: {$e->getMessage()}");
            $this->error('Erro ao despachar o job. Veja o log para detalhes.');
        }

        return 0;
    }
}
