<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pagamento;
use App\Models\BoletoLog;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EnviarBoletosPendentes extends Command
{
    protected $signature = 'boletos:enviar-teste';
    protected $description = 'Envia boletos com vencimento até 10 dias ou vencidos que ainda não foram enviados hoje';

    public function handle()
    {
        $this->info('Iniciando comando boletos:enviar-pendentes');
        Log::info('Iniciando comando boletos:enviar-pendentes');

        try {
            $hoje = now()->startOfDay();
            $limite = now()->addDays(10)->endOfDay();

            $this->info("Buscando pagamentos entre {$hoje} e {$limite}");
            Log::info("Buscando pagamentos entre {$hoje} e {$limite}");

            $pagamentos = Pagamento::with('cliente', 'contrato')
                ->whereNull('data_pagamento')
                ->whereBetween('vencimento', [$hoje, $limite])
                ->get();

            $this->info("Pagamentos encontrados: {$pagamentos->count()}");
            Log::info("Pagamentos encontrados: {$pagamentos->count()}");

            if ($pagamentos->isEmpty()) {
                $this->warn("Nenhum pagamento para enviar.");
                return 0;
            }

            $enviadosHoje = 0;

            foreach ($pagamentos as $pag) {
                $this->info("Processando pagamento ID {$pag->id} com vencimento {$pag->vencimento}");
                Log::info("Processando pagamento ID {$pag->id} com vencimento {$pag->vencimento}");

                $logHoje = BoletoLog::where('pagamento_id', $pag->id)
                    ->whereDate('enviado_em', $hoje)
                    ->first();

                if ($logHoje && $logHoje->enviado) {
                    $this->info("Pagamento ID {$pag->id} já teve boleto enviado hoje. Pulando.");
                    Log::info("Pagamento ID {$pag->id} já teve boleto enviado hoje. Pulando.");
                    continue;
                }

                if (!$pag->cliente || !$pag->cliente->email) {
                    $this->warn("Pagamento ID {$pag->id} sem cliente ou email válido. Pulando.");
                    Log::warning("Pagamento ID {$pag->id} sem cliente ou email válido. Pulando.");
                    continue;
                }

                $this->info("Enviando email para {$pag->cliente->email}");
                Log::info("Enviando email para {$pag->cliente->email}");

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
                                Log::info("Anexado PDF para pagamento ID {$pag->id}");
                            } else {
                                Log::warning("PDF não encontrado para pagamento ID {$pag->id} no caminho {$pdfPath}");
                            }
                        } else {
                            Log::warning("Pagamento ID {$pag->id} não possui boleto_path.");
                        }
                    });

                    // Aqui você pode trocar updateOrCreate por create se preferir um log por envio
                    $log = BoletoLog::updateOrCreate(
                        ['pagamento_id' => $pag->id, 'enviado_em' => $hoje],
                        [
                            'contrato_id' => $pag->contrato->id ?? null,
                            'cliente_id' => $pag->cliente->id ?? null,
                            'enviado' => true,
                            'enviado_em' => now(),
                        ]
                    );

                    $this->info("Log salvo para pagamento ID {$pag->id}");
                    Log::info("Log salvo para pagamento ID {$pag->id}");

                    $enviadosHoje++;
                } catch (\Exception $e) {
                    $this->error("Erro enviando email para pagamento ID {$pag->id}: {$e->getMessage()}");
                    Log::error("Erro enviando email para pagamento ID {$pag->id}: {$e->getMessage()}");
                }
            }

            $this->info("Processamento finalizado. Emails enviados: {$enviadosHoje}");
            Log::info("Processamento finalizado. Emails enviados: {$enviadosHoje}");

        } catch (\Exception $ex) {
            $this->error("Erro geral no comando: {$ex->getMessage()}");
            Log::error("Erro geral no comando: {$ex->getMessage()}");
        }

        return 0;
    }
}
