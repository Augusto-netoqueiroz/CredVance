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

        $logInfo('Iniciando job EnvioBoletoDiarioJob');

        try {
            $inicio = now()->subDays(10)->startOfDay();
            $fim = now()->addDays(10)->endOfDay();

            $logInfo("Buscando pagamentos no período {$inicio} até {$fim} para verificar status");

            $pagamentos = Pagamento::with('contrato.cliente')
                ->whereBetween('vencimento', [$inicio, $fim])
                ->get();

            $logInfo("Pagamentos encontrados para checagem: {$pagamentos->count()}");

            foreach ($pagamentos as $pag) {
                $logInfo("========================================================");
                $logInfo("Processando pagamento ID {$pag->id} - contrato ID " . ($pag->contrato->id ?? 'N/A'));
                $logInfo("Status atual: {$pag->status}");

                $novoStatus = $this->verificarStatusPagamento($pag);
                $logInfo("Status retornado da verificação: {$novoStatus}");

                if ($novoStatus !== $pag->status) {
                    $logInfo("Atualizando status pagamento ID {$pag->id} de {$pag->status} para {$novoStatus}");
                    $pag->status = $novoStatus;
                    $pag->save();
                } else {
                    $logInfo("Status do pagamento ID {$pag->id} permanece {$pag->status}");
                }

                // ** FILTRO PRINCIPAL PARA NÃO ENVIAR BOLETO PARA PAGAMENTOS RECEBIDOS OU PAGOS **
                if (in_array($pag->status, ['recebido', 'pago'])) {
                    $logInfo("Pagamento ID {$pag->id} com status '{$pag->status}' - não envia boleto.");
                    continue;
                }

                $logHoje = BoletoLog::where('pagamento_id', $pag->id)
                    ->whereDate('enviado_em', now()->toDateString())
                    ->first();

                if ($logHoje && $logHoje->enviado) {
                    $logInfo("Pagamento ID {$pag->id} já teve boleto enviado hoje. Pulando.");
                    continue;
                }

                if (!$pag->contrato || !$pag->contrato->cliente || !$pag->contrato->cliente->email) {
                    $logWarn("Pagamento ID {$pag->id} sem cliente ou email válido. Pulando.");
                    continue;
                }

                $logInfo("Enviando email para {$pag->contrato->cliente->email}");

                $dados = [
                    'boleto' => $pag,
                    'cliente' => $pag->contrato->cliente,
                    'contrato' => $pag->contrato,
                ];

                try {
                    Mail::send('emails.boleto_enviado', $dados, function ($message) use ($pag, $logInfo, $logWarn) {
                        $message->to($pag->contrato->cliente->email)
                            ->subject('Seu boleto está disponível - CredVance');

                        if (!empty($pag->boleto_path)) {
                            $pdfPath = storage_path('app/private/' . $pag->boleto_path);
                            if (file_exists($pdfPath)) {
                                $message->attach($pdfPath);
                                $logInfo("PDF anexado ao email para pagamento ID {$pag->id}");
                            } else {
                                $logWarn("PDF não encontrado para pagamento ID {$pag->id} no caminho {$pdfPath}");
                            }
                        } else {
                            $logWarn("Pagamento ID {$pag->id} não possui boleto_path.");
                        }
                    });

                    BoletoLog::updateOrCreate(
                        ['pagamento_id' => $pag->id, 'enviado_em' => now()->startOfDay()],
                        [
                            'contrato_id' => $pag->contrato->id ?? null,
                            'cliente_id' => $pag->contrato->cliente->id ?? null,
                            'enviado' => true,
                            'enviado_em' => now(),
                        ]
                    );

                    $logInfo("Email enviado e log salvo para pagamento ID {$pag->id}");
                } catch (\Exception $e) {
                    $logError("Erro ao enviar email para pagamento ID {$pag->id}: {$e->getMessage()}");
                }

                $logInfo("--------------------------------------------------------");
            }

            $logInfo("Processamento finalizado. Total de pagamentos processados: {$pagamentos->count()}");

            CronLog::create([
                'command' => 'EnvioBoletoDiarioJob',
                'output' => implode("\n", $outputLog),
                'status' => 'success',
                'executed_at' => now(),
            ]);
        } catch (\Exception $ex) {
            $logError("Erro geral no job: {$ex->getMessage()}");

            CronLog::create([
                'command' => 'EnvioBoletoDiarioJob',
                'output' => implode("\n", $outputLog),
                'status' => 'failure',
                'executed_at' => now(),
            ]);
        }
    }

    private function verificarStatusPagamento(Pagamento $pagamento): string
    {
        // Aqui sua lógica para checar/atualizar status do pagamento
        return $pagamento->status;
    }
}
