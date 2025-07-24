<?php

namespace App\Jobs;

use App\Models\BoletoLog;
use App\Models\CronLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

class EnvioWhatsappBoletoLogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;

    /**
     * Create a new job instance.
     *
     * @param int|null $id Opcional para filtrar um boleto_log específico.
     */
    public function __construct(int $id = null)
    {
        $this->id = $id;
    }

    public function handle()
    {
        $outputLog = [];

        $logInfo = function ($msg) use (&$outputLog) {
            $outputLog[] = "[INFO] " . $msg;
            Log::info($msg);
        };

        $logWarn = function ($msg) use (&$outputLog) {
            $outputLog[] = "[WARN] " . $msg;
            Log::warning($msg);
        };

        $logError = function ($msg) use (&$outputLog) {
            $outputLog[] = "[ERROR] " . $msg;
            Log::error($msg);
        };

        $logInfo('Iniciando job EnvioWhatsappBoletoLogJob');

        try {
            $query = BoletoLog::with(['cliente', 'pagamento'])
                ->where('enviado', 1)
                ->where('whatsapp', 0)
                ->whereDate('enviado_em', now()->toDateString());

            if ($this->id) {
                $query->where('id', $this->id);
                $logInfo("Filtrando boleto_log pelo id = {$this->id}");
            }

            $logs = $query->get();

            $logInfo("Boletos com WhatsApp pendente para envio: {$logs->count()}");

            foreach ($logs as $log) {
                if (!$log->cliente || !$log->cliente->telefone) {
                    $logWarn("Log ID {$log->id} sem telefone do cliente, pulando");
                    continue;
                }

                $telefoneCliente = preg_replace('/\D/', '', $log->cliente->telefone);
                if (substr($telefoneCliente, 0, 2) !== '55') {
                    $telefoneCliente = '55' . $telefoneCliente;
                }

                $nomeCliente = $log->cliente->name ?? 'Cliente';
                $pixCode = $log->pagamento->pix ?? '';
                $dataVencimento = optional($log->pagamento->vencimento)->format('d/m/Y') ?? 'data não disponível';

                $mensagemWhats = "👋 Olá, {$nomeCliente}! 🎉\n\n"
                    . "Parabéns por contratar seu consórcio com a CredVance! Estamos muito felizes em ter você conosco. 🙌✨\n\n"
                    . "💰 Seu boleto com vencimento em *{$dataVencimento}* está disponível para pagamento. Para facilitar, utilize o código PIX abaixo:\n\n"
                    . "─────────────────────────\n"
                    . "📲 PIX para pagamento:\n"
                    . "{$pixCode}\n"
                    . "─────────────────────────\n\n"
                    . "📋 Basta copiar o código acima e colar no seu aplicativo bancário para realizar o pagamento com rapidez e segurança.\n\n"
                    . "❓ Se precisar de ajuda ou tiver qualquer dúvida, nossa equipe está à disposição para atender você. 🤝\n\n"
                    . "🙏 Obrigado por confiar na CredVance!\n\n"
                    . "Atenciosamente,\n"
                    . "Equipe CredVance 💙";


                $payload = [
                    'telefone' => $telefoneCliente,
                    'mensagem' => $mensagemWhats,
                ];

                $logInfo("Enviando WhatsApp para Log ID {$log->id} com payload: " . print_r($payload, true));

                try {
                    $exitCode = Artisan::call('whatsapp:send', $payload);
                    $output = Artisan::output();

                    $logInfo("Comando whatsapp:send executado com exit code {$exitCode}. Output: {$output}");

                    if ($exitCode === 0) {
                        $log->whatsapp = 1;
                        $log->save();
                        $logInfo("Atualizado Log ID {$log->id} para whatsapp = 1");
                    } else {
                        $logWarn("Falha ao enviar WhatsApp para Log ID {$log->id}, exitCode: {$exitCode}");
                    }
                } catch (\Exception $e) {
                    $logError("Erro ao enviar WhatsApp para Log ID {$log->id}: " . $e->getMessage());
                }
            }

            $logInfo("Envio WhatsApp finalizado.");

            // Salvar log do cron com status success
            CronLog::create([
                'command' => 'EnvioWhatsappBoletoLogJob',
                'output' => implode("\n", $outputLog),
                'status' => 'success',
                'executed_at' => now(),
            ]);
        } catch (\Exception $ex) {
            $logError("Erro geral no job EnvioWhatsappBoletoLogJob: {$ex->getMessage()}");

            // Salvar log do cron com status failure
            CronLog::create([
                'command' => 'EnvioWhatsappBoletoLogJob',
                'output' => implode("\n", $outputLog),
                'status' => 'failure',
                'executed_at' => now(),
            ]);
        }
    }
}
