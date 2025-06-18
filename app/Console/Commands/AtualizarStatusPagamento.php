<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pagamento;
use App\Services\InterBoletoService;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class AtualizarStatusPagamento extends Command
{
    protected $signature = 'pagamentos:atualizar-status';
    protected $description = 'Atualiza status de pagamentos pendentes via API';

    public function handle(InterBoletoService $service)
    {
        $pendentes = Pagamento::whereNotNull('codigo_solicitacao')
            ->where('status_solicitacao','PENDENTE')
            // opcional: filtrar vencimento próximo ou vencido
            ->get();
        foreach ($pendentes as $pag) {
            try {
                Log::info("Consultando pagamento {$pag->id}");
                $json = $service->getCobranca($pag->codigo_solicitacao);
                $novo = $json['status'] ?? null;
                if ($novo && $novo !== $pag->status_solicitacao) {
                    $pag->status_solicitacao = $novo;
                    if ($novo==='LIQUIDADO' && isset($json['dataPagamento'])) {
                        $pag->data_pagamento = Carbon::parse($json['dataPagamento']);
                        $pag->status = 'pago';
                    }
                    if ($novo==='CANCELADO' && isset($json['dataCancelamento'])) {
                        $pag->data_cancelamento = Carbon::parse($json['dataCancelamento']);
                        $pag->status = 'cancelado';
                    }
                    if (isset($json['linhaDigitavel'])) {
                        $pag->linha_digitavel = $json['linhaDigitavel'];
                    }
                    $pag->json_resposta = json_encode($json);
                    $pag->save();
                    Log::info("Status atualizado para pagamento {$pag->id}: {$novo}");
                }
            } catch (Exception $e) {
                Log::error("Erro ao consultar pagamento {$pag->id}: ".$e->getMessage());
                $pag->tentativas++;
                $pag->json_resposta = json_encode(['error'=>$e->getMessage()]);
                $pag->save();
            }
        }
    }
}
