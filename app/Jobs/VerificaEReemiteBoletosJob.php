<?php

namespace App\Jobs;

use App\Models\Pagamento;
use App\Services\InterBoletoService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Exception;

class VerificaEReemiteBoletosJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(InterBoletoService $service)
    {
        // 1. Buscar boletos não pagos e não cancelados (ajuste conforme sua regra)
        $pagamentos = Pagamento::whereNull('data_pagamento') // ou 'status' != 'PAGO'
            ->whereNotNull('codigo_solicitacao') // tem boleto gerado
            ->get();

        foreach ($pagamentos as $pag) {
            try {
                $resposta = $service->getCobranca($pag->codigo_solicitacao);
                $situacao = $resposta['cobranca']['situacao'] ?? null;

                if ($situacao === 'EXPIRADO') {
                    Log::info("Pagamento ID {$pag->id} está expirado, gerando novo boleto...");

                    // Montar os dados do boleto como da primeira vez
                    $dadosBoleto = [
                        'nosso_numero'    => $pag->nosso_numero, // ajuste conforme seu modelo
                        'valor'           => $pag->valor,
                        'data_vencimento' => now()->addDays(2)->format('Y-m-d'), // por exemplo: 2 dias à frente
                        'sacado'          => [
                            // Complete com os campos do cliente!
                        ],
                    ];

                    $novo = $service->createBoleto($dadosBoleto);
                    $novoCodigo = $novo['codigoSolicitacao'];
                    $respostaNova = $service->getCobranca($novoCodigo);

                    // Atualiza os campos do pagamento existente (ou crie um novo registro, se preferir versionar)
                    $pag->codigo_solicitacao = $novoCodigo;
                    $pag->boleto_path = null; // para forçar baixar novo PDF depois
                    $pag->pix = $respostaNova['pix']['pixCopiaECola'] ?? null;
                    $pag->status = 'REENVIADO'; // ou outro status que você queira usar
                    $pag->save();

                    Log::info("Novo boleto/pix gerado para Pagamento ID {$pag->id}");
                }
            } catch (Exception $e) {
                Log::error("Erro ao verificar/reemitir boleto Pagamento ID {$pag->id}: " . $e->getMessage());
            }
        }
    }
}
