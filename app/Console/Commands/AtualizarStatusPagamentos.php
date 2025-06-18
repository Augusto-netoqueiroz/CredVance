<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pagamento;
use App\Services\InterBoletoService;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class AtualizarStatusPagamentos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Ajuste para combinar com o agendamento:
     * protected $signature = 'app:atualizar-status-pagamentos';
     * Vamos usar:
     */
    protected $signature = 'pagamentos:atualizar-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consulta a API do Inter e atualiza o status dos pagamentos conforme situação retornada';

    /**
     * Execute the console command.
     *
     * @param InterBoletoService $service
     * @return int
     */
    public function handle(InterBoletoService $service)
    {
        Log::info('Iniciando AtualizarStatusPagamentos');

        // Buscar pagamentos pendentes que já possuem código de solicitação gerado
        // Ajuste filtro conforme seu campo de status: aqui supomos status = 'pendente'
        $hoje = Carbon::today();
        $pagamentos = Pagamento::whereNotNull('codigo_solicitacao')
            ->where('status', 'pendente')
            ->get();

        Log::info("Encontrados {$pagamentos->count()} pagamentos pendentes para consulta de status.");

        foreach ($pagamentos as $pag) {
            $codigo = $pag->codigo_solicitacao;
            Log::info("Consultando pagamento ID {$pag->id}, códigoSolicitacao {$codigo}");
            try {
                $resp = $service->getCobranca($codigo);
            } catch (Exception $e) {
                Log::error("Erro ao consultar API para Pagamento ID {$pag->id}: ".$e->getMessage());
                // em caso de erro de rede ou API, continuar para o próximo
                continue;
            }

            // A estrutura esperada: $resp['cobranca']['situacao'], $resp['cobranca']['dataVencimento'], etc.
            $situacao = data_get($resp, 'cobranca.situacao');
            $dataVencimentoStr = data_get($resp, 'cobranca.dataVencimento'); // formato "YYYY-MM-DD"
            $dataVencimento = null;
            if ($dataVencimentoStr) {
                try {
                    $dataVencimento = Carbon::parse($dataVencimentoStr);
                } catch (\Exception $ex) {
                    Log::warning("Formato de dataVencimento inválido para Pagamento ID {$pag->id}: {$dataVencimentoStr}");
                }
            }

            Log::info("Pagamento ID {$pag->id}: situacao retornada = {$situacao}, vencimento = {$dataVencimentoStr}");

            $novoStatus = null;
            $agora = Carbon::now();

            if ($situacao && strtoupper($situacao) !== 'A_RECEBER') {
                // Situação diferente de A_RECEBER: provavelmente já foi liquidado ou cancelado
                $sit = strtoupper($situacao);
                if (in_array($sit, ['LIQUIDADO', 'LIQUIDADA', 'PAGA', 'PAGO'])) {
                    $novoStatus = 'pago';
                } elseif (in_array($sit, ['CANCELADA', 'CANCELADO'])) {
                    $novoStatus = 'cancelado';
                } else {
                    // Outros possíveis status: trate conforme documentação
                    // Por exemplo: ‘EXCLUIDA’, ‘EXPIRADA’ etc, dependendo de como o Inter define
                    // Aqui marcamos como cancelado por segurança:
                    $novoStatus = 'cancelado';
                }
            } else {
                // Se ainda A_RECEBER, mas vencido (data de vencimento anterior a hoje)
                if ($dataVencimento && $dataVencimento->lt($hoje)) {
                    $novoStatus = 'vencido';
                }
                // caso contrário, permanece 'pendente'
            }

            if ($novoStatus) {
                // Atualizar apenas se diferente do status atual
                if ($pag->status !== $novoStatus) {
                    Log::info("Atualizando Pagamento ID {$pag->id} de status '{$pag->status}' para '{$novoStatus}'.");
                    $pag->status = $novoStatus;
                    // Se for pago, preencha data_pagamento se desejar (por ex: agora)
                    if ($novoStatus === 'pago') {
                        $pag->data_pagamento = $agora;
                    }
                    // Se for vencido, pode armazenar data de vencimento passado se desejar. Ou apenas o status.
                    $pag->save();
                } else {
                    Log::info("Pagamento ID {$pag->id} já possui status '{$pag->status}', sem alteração.");
                }
            } else {
                Log::info("Pagamento ID {$pag->id} permanece como 'pendente'.");
            }
        }

        Log::info('Finalizado AtualizarStatusPagamentos');
        return 0;
    }
}
