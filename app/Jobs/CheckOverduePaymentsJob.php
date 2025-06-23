<?php

namespace App\Jobs;

use App\Mail\OverduePaymentReminderMail;
use App\Services\InterBoletoService;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CheckOverduePaymentsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $service = new InterBoletoService();

        $today = now()->format('Y-m-d');

        try {
            $result = $service->listCobrancas([
                'dataInicio' => $today,
                'dataFim' => $today,
                'status' => 'VENCIDO',
            ]);

            if (empty($result['cobrancas'])) {
                Log::info('Nenhum boleto vencido encontrado para hoje.');
                return;
            }

            foreach ($result['cobrancas'] as $cobranca) {
                // Exemplo de lógica de envio de aviso
                $sacado = $cobranca['pagador'] ?? null;

                if ($sacado && !empty($sacado['email'])) {
                    Mail::to($sacado['email'])->send(new OverduePaymentReminderMail($cobranca));

                    Log::info("Aviso enviado para {$sacado['email']} - Boleto {$cobranca['seuNumero']}");
                }
            }

        } catch (\Exception $e) {
            Log::error('Erro ao checar boletos vencidos: ' . $e->getMessage());
        }
    }
}
