<?php

namespace App\Jobs;

use App\Services\InterBoletoService;
use App\Models\Pagamento;
use App\Models\CronLog;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
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
        $outputLog = [];

        $logInfo = function ($msg) use (&$outputLog) {
            $outputLog[] = $msg;
            Log::info($msg);
        };

        $logError = function ($msg) use (&$outputLog) {
            $outputLog[] = $msg;
            Log::error($msg);
        };

        $logInfo('Início da checagem de status dos pagamentos');

        try {
            $pagamentos = Pagamento::whereNull('data_pagamento')->get();

            $logInfo("Pagamentos a verificar: {$pagamentos->count()}");

            foreach ($pagamentos as $pagamento) {
                try {
                    $codigoSolicitacao = $pagamento->codigo_solicitacao ?? null;

                    if (!$codigoSolicitacao) {
                        $logInfo("Pagamento ID {$pagamento->id} sem código de solicitação. Pulando.");
                        continue;
                    }

                    $response = $service->getCobranca($codigoSolicitacao);
                    $statusApi = strtoupper($response['cobranca']['situacao'] ?? '');

                    $logInfo("Pagamento ID {$pagamento->id} status API: {$statusApi}");

                    switch ($statusApi) {
                        case 'A_RECEBER':
                            $status = 'pendente';
                            break;
                        case 'PAGO':
                            $status = 'pago';
                            break;
                        case 'VENCIDO':
                            $status = 'vencido';
                            break;
                        case 'CANCELADO':
                            $status = 'cancelado';
                            break;
                        default:
                            $status = strtolower($statusApi);
                            break;
                    }

                    $logInfo("Pagamento ID {$pagamento->id} status atualizado para: {$status}");

                    $pagamento->status = $status;

                    if ($status === 'pago' && !$pagamento->data_pagamento) {
                        $dataPagamentoRaw = $response['cobranca']['dataPagamento'] ?? null;
                        if ($dataPagamentoRaw) {
                            $pagamento->data_pagamento = \Carbon\Carbon::parse($dataPagamentoRaw);
                        } else {
                            $pagamento->data_pagamento = now();
                        }
                    }

                    $pagamento->save();

                } catch (\Exception $exPag) {
                    $logError("Erro ao processar pagamento ID {$pagamento->id}: {$exPag->getMessage()}");
                }
            }

            $logInfo('Checagem de status finalizada com sucesso.');

            CronLog::create([
                'command' => 'CheckOverduePaymentsJob',
                'output' => json_encode($outputLog, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
                'status' => 'success',
                'executed_at' => now(),
            ]);
        } catch (\Exception $ex) {
            $msgErro = "Erro geral na checagem de pagamentos: {$ex->getMessage()}";
            $logError($msgErro);

            CronLog::create([
                'command' => 'CheckOverduePaymentsJob',
                'output' => json_encode($outputLog, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
                'status' => 'failure',
                'executed_at' => now(),
            ]);
        }
    }
}
