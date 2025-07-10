<?php

namespace App\Jobs;

use App\Models\Pagamento;
use App\Models\BoletoLog;
use App\Models\CronLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EnvioBoletoDiarioJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $outputLog = []; // acumula mensagens para gravar no log

        $logInfo = function($msg) use (&$outputLog) {
            $outputLog[] = $msg;
            Log::info($msg);
        };

        $logWarn = function($msg) use (&$outputLog) {
            $outputLog[] = $msg;
            Log::warning($msg);
        };

        $logError = function($msg) use (&$outputLog) {
            $outputLog[] = $msg;
            Log::error($msg);
        };

        $logInfo('Iniciando job EnvioBoletoDiarioJob');

        $enviadosHoje = 0;

        try {
            $hoje = now()->startOfDay();
            $limite = now()->addDays(10)->endOfDay();

            $logInfo("Buscando pagamentos com vencimento entre {$hoje} e {$limite}");

            $pagamentos = Pagamento::with('cliente', 'contrato')
                ->whereNull('data_pagamento')
                ->whereBetween('vencimento', [$hoje, $limite])
                ->get();

            $logInfo("Pagamentos encontrados: {$pagamentos->count()}");

            if ($pagamentos->isEmpty()) {
                $logWarn('Nenhum pagamento encontrado para enviar.');
            } else {
                foreach ($pagamentos as $pag) {
                    $logInfo("Processando pagamento ID {$pag->id} vencimento {$pag->vencimento}");

                    $logHoje = BoletoLog::where('pagamento_id', $pag->id)
                        ->whereDate('enviado_em', $hoje)
                        ->first();

                    if ($logHoje && $logHoje->enviado) {
                        $logInfo("Pagamento ID {$pag->id} já teve boleto enviado hoje. Pulando.");
                        continue;
                    }

                    if (!$pag->cliente || !$pag->cliente->email) {
                        $logWarn("Pagamento ID {$pag->id} sem cliente ou email válido. Pulando.");
                        continue;
                    }

                    $logInfo("Enviando email para {$pag->cliente->email}");

                    $dados = [
                        'boleto' => $pag,
                        'cliente' => $pag->cliente,
                        'contrato' => $pag->contrato,
                    ];

                    try {
                        Mail::send('emails.boleto_enviado', $dados, function ($message) use ($pag) {
                            $message->to($pag->cliente->email)
                                ->subject('Seu boleto está disponível - CredVance');

                            if (!empty($pag->boleto_path)) {
                                $pdfPath = storage_path('app/private/' . $pag->boleto_path);
                                if (file_exists($pdfPath)) {
                                    $message->attach($pdfPath);
                                    Log::info("PDF anexado ao email para pagamento ID {$pag->id}");
                                } else {
                                    Log::warning("PDF não encontrado para pagamento ID {$pag->id} no caminho {$pdfPath}");
                                }
                            } else {
                                Log::warning("Pagamento ID {$pag->id} não possui boleto_path.");
                            }
                        });

                        BoletoLog::updateOrCreate(
                            ['pagamento_id' => $pag->id, 'enviado_em' => $hoje],
                            [
                                'contrato_id' => $pag->contrato->id ?? null,
                                'cliente_id' => $pag->cliente->id ?? null,
                                'enviado' => true,
                                'enviado_em' => now(),
                            ]
                        );

                        $logInfo("Log salvo para pagamento ID {$pag->id}");
                        $enviadosHoje++;
                    } catch (\Exception $e) {
                        $logError("Erro ao enviar email para pagamento ID {$pag->id}: {$e->getMessage()}");
                    }
                }
            }

            $logInfo("Processamento finalizado. Total de emails enviados: {$enviadosHoje}");

            CronLog::create([
                'command' => 'EnvioBoletoDiarioJob',
                'output' => implode("\n", $outputLog),
                'status' => 'success',
                'executed_at' => now(),
            ]);
        } catch (\Exception $ex) {
            $msgErro = "Erro geral no job: {$ex->getMessage()}";
            $logError($msgErro);

            CronLog::create([
                'command' => 'EnvioBoletoDiarioJob',
                'output' => implode("\n", $outputLog),
                'status' => 'failure',
                'executed_at' => now(),
            ]);
        }
    }
}
