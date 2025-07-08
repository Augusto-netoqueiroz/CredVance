<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pagamento;
use App\Models\SistemaLog;
use App\Services\InterBoletoService;
use Illuminate\Support\Facades\Storage;

class ReemiteBoletoPagamento extends Command
{
    protected $signature = 'pagamento:reemite-boleto {pagamento_id?} {--all} {--contrato=}';
    protected $description = 'Sincroniza dados dos boletos/pagamentos com a API do Inter. Reemite apenas vencidos ou vencendo hoje.';

    public function handle(InterBoletoService $service)
    {
        $hoje = now()->format('Y-m-d');

        if ($this->option('all')) {
            $pagamentos = Pagamento::whereNull('data_pagamento')
                ->whereNotNull('codigo_solicitacao')
                ->whereDate('vencimento', '<=', $hoje)
                ->get();

            $this->info("Sincronizando {$pagamentos->count()} pagamentos vencidos ou com vencimento hoje...");
            foreach ($pagamentos as $pag) {
                $this->sincronizarPagamento($pag, $service);
            }
            $this->info("Processamento finalizado.");
            return;
        }

        if ($contratoId = $this->option('contrato')) {
            $pagamentos = Pagamento::where('contrato_id', $contratoId)
                ->whereNull('data_pagamento')
                ->whereNotNull('codigo_solicitacao')
                ->whereDate('vencimento', '<=', $hoje)
                ->get();

            $this->info("Sincronizando {$pagamentos->count()} pagamentos do contrato $contratoId vencidos ou com vencimento hoje...");
            foreach ($pagamentos as $pag) {
                $this->sincronizarPagamento($pag, $service);
            }
            $this->info("Processamento finalizado.");
            return;
        }

        // Pagamento único
        $pagamentoId = $this->argument('pagamento_id');
        $pag = null;
        if ($pagamentoId) {
            $pag = Pagamento::find($pagamentoId);
        }
        if (! $pag) {
            $this->error("Pagamento não encontrado.");
            SistemaLog::create([
                'modulo'        => 'pagamento',
                'referencia_id' => $pagamentoId,
                'acao'          => 'BUSCA',
                'mensagem'      => 'Pagamento não encontrado no comando de sincronização.',
                'resultado'     => 'ERRO',
            ]);
            return;
        }

        $this->sincronizarPagamento($pag, $service);
    }

    private function sincronizarPagamento($pag, $service)
    {
        if (! $pag->codigo_solicitacao) {
            $this->error("Pagamento id {$pag->id} sem codigo_solicitacao.");
            SistemaLog::create([
                'modulo'        => 'pagamento',
                'referencia_id' => $pag->id,
                'acao'          => 'BUSCA',
                'mensagem'      => 'Pagamento sem codigo_solicitacao.',
                'resultado'     => 'ERRO',
            ]);
            return;
        }

        $cliente = $pag->cliente;
        if (! $cliente) {
            $this->error("Pagamento id {$pag->id} sem cliente via relacionamento.");
            SistemaLog::create([
                'modulo'        => 'pagamento',
                'referencia_id' => $pag->id,
                'acao'          => 'BUSCA',
                'mensagem'      => 'Cliente não encontrado via relacionamento.',
                'resultado'     => 'ERRO',
            ]);
            return;
        }

        try {
            // Consulta dados atuais do boleto
            $resposta = $service->getCobranca($pag->codigo_solicitacao);
            $cobranca = $resposta['cobranca'] ?? [];
            $boleto   = $resposta['boleto']   ?? [];
            $pix      = $resposta['pix']      ?? [];
            $situacao = $cobranca['situacao'] ?? null;

            // Atualiza campos do pagamento conforme o retorno atual da cobrança
            $atualizou = false;
            $mudancas  = [];

            if (!empty($boleto['nossoNumero']) && $pag->nosso_numero !== $boleto['nossoNumero']) {
                $mudancas[] = "nosso_numero: {$pag->nosso_numero} => {$boleto['nossoNumero']}";
                $pag->nosso_numero = $boleto['nossoNumero'];
                $atualizou = true;
            }
            if (!empty($pix['pixCopiaECola']) && $pag->pix !== $pix['pixCopiaECola']) {
                $pag->pix = $pix['pixCopiaECola'];
                $atualizou = true;
            }
            if (!empty($boleto['linhaDigitavel']) && $pag->linha_digitavel !== $boleto['linhaDigitavel']) {
                $pag->linha_digitavel = $boleto['linhaDigitavel'];
                $atualizou = true;
            }
            if (!empty($cobranca['dataVencimento']) && ($pag->vencimento ? $pag->vencimento->format('Y-m-d') : null) !== $cobranca['dataVencimento']) {
                $pag->vencimento = $cobranca['dataVencimento'];
                $atualizou = true;
            }
            // Atualiza status_api SEM mexer em status!
            if (!empty($situacao) && $pag->status_api !== $situacao) {
                $pag->status_api = $situacao;
                $atualizou = true;
            }

            if ($atualizou) {
                $pag->save();
                $this->info("[Pagamento ID {$pag->id}] Campos sincronizados: " . implode(', ', $mudancas));
            }

            // Cancela só se estiver "A_RECEBER"
            if ($situacao === 'A_RECEBER') {
                $this->info("[Pagamento ID {$pag->id}] Cancelando boleto anterior para poder reemitir...");

                $cancelEndpoint = rtrim($service->getBaseResourceUrl(), '/') . '/' . $pag->codigo_solicitacao;
                $cancelPayload = [
                    'status'        => 'CANCELADA',
                    'dataVencimento'=> $pag->vencimento instanceof \Carbon\Carbon ? $pag->vencimento->format('Y-m-d') : $pag->vencimento,
                    'valorNominal'  => $pag->valor,
                ];

                \Log::debug('[DEBUG] Endpoint de cancelamento: ' . $cancelEndpoint);
                \Log::debug('[DEBUG] Payload de cancelamento: ' . json_encode($cancelPayload));

                try {
                    $service->cancelarBoletoPorCodigoSolicitacao(
                        $pag->codigo_solicitacao,
                        $pag->vencimento instanceof \Carbon\Carbon ? $pag->vencimento->format('Y-m-d') : $pag->vencimento,
                        $pag->valor
                    );
                    $this->info("[Pagamento ID {$pag->id}] Boleto anterior cancelado!");
                    SistemaLog::create([
                        'modulo'        => 'pagamento',
                        'referencia_id' => $pag->id,
                        'acao'          => 'CANCELAMENTO',
                        'mensagem'      => "Boleto anterior cancelado antes de reemissão.",
                        'resultado'     => 'OK',
                    ]);
                } catch (\Exception $e) {
                    $this->error("[Pagamento ID {$pag->id}] Falha ao cancelar boleto anterior: " . $e->getMessage());
                    SistemaLog::create([
                        'modulo'        => 'pagamento',
                        'referencia_id' => $pag->id,
                        'acao'          => 'CANCELAMENTO',
                        'mensagem'      => "Erro ao cancelar boleto anterior: " . $e->getMessage(),
                        'resultado'     => 'ERRO',
                    ]);
                    return;
                }
            }

            // Monta dados do sacado/cliente para novo boleto
            $tel = preg_replace('/\D/', '', $cliente->telefone ?? '');
            $ddd = strlen($tel) >= 10 ? substr($tel, 0, 2) : null;
            $numeroSemDdd = strlen($tel) > 2 ? substr($tel, 2) : null;
            if ($numeroSemDdd && strlen($numeroSemDdd) > 9) {
                $numeroSemDdd = substr($numeroSemDdd, -9);
            }
            $cepLimpo = preg_replace('/\D/', '', $cliente->cep);

            // Data de vencimento nova = vencimento atual + 1 dia
            $vencimentoAtual = $pag->vencimento instanceof \Carbon\Carbon ? $pag->vencimento->copy() : \Carbon\Carbon::parse($pag->vencimento);
            $novaDataVenc = $vencimentoAtual->addDay()->format('Y-m-d');

            $dadosBoleto = [
                'nosso_numero'    => $pag->nosso_numero,
                'valor'           => $pag->valor,
                'data_vencimento' => $novaDataVenc,
                'sacado'          => [
                    'nome'        => $cliente->name,
                    'tipoPessoa'  => 'FISICA',
                    'logradouro'  => $cliente->logradouro,
                    'numero'      => $cliente->numero,
                    'complemento' => $cliente->complemento ?? '',
                    'bairro'      => $cliente->bairro,
                    'cidade'      => $cliente->cidade,
                    'uf'          => $cliente->uf,
                    'cep'         => $cepLimpo,
                    'cpf_cnpj'    => $cliente->cpf,
                    'email'       => $cliente->email,
                    'ddd'         => $ddd,
                    'telefone'    => $numeroSemDdd,
                ],
            ];

            // Guarda o código de solicitação anterior
            $codigoAntigo = $pag->codigo_solicitacao;

            // Cria novo boleto na API
            $novo = $service->createBoleto($dadosBoleto);
            $novoCodigo = $novo['codigoSolicitacao'];

            // Tenta obter pix e linha digitável até conseguir (obrigatório!)
            $tentativas = 5;
            $intervalo  = 3;
            $dadosOk = false;

            while ($tentativas-- > 0) {
                $respostaNova = $service->getCobranca($novoCodigo);
                $pixOk = !empty($respostaNova['pix']['pixCopiaECola']);
                $linhaOk = !empty($respostaNova['boleto']['linhaDigitavel']);

                if ($pixOk && $linhaOk) {
                    $pag->pix = $respostaNova['pix']['pixCopiaECola'];
                    $pag->linha_digitavel = $respostaNova['boleto']['linhaDigitavel'];
                    $dadosOk = true;
                    break;
                }
                sleep($intervalo);
            }

            if (! $dadosOk) {
                $this->error("[Pagamento ID {$pag->id}] Não foi possível obter pix e linha digitável após múltiplas tentativas.");
                SistemaLog::create([
                    'modulo'        => 'boleto',
                    'referencia_id' => $pag->id,
                    'acao'          => 'REENVIO',
                    'mensagem'      => 'Erro ao obter pix/linha digitável após criar boleto',
                    'resultado'     => 'ERRO',
                ]);
                return;
            }

            // Agora tenta baixar o PDF:
            $pdfOk = false;
            $tentativasPdf = 3;
            while ($tentativasPdf-- > 0) {
                try {
                    $pdfBinary = $service->downloadBoletoPdf($novoCodigo);
                    $relativePath = "boletos/{$pag->id}/boleto_{$novoCodigo}.pdf";
                    \Storage::disk('local')->put($relativePath, $pdfBinary);
                    $pag->boleto_path = $relativePath;
                    $pdfOk = true;
                    break;
                } catch (\Exception $e) {
                    if ($tentativasPdf == 0) {
                        $this->error("[Pagamento ID {$pag->id}] Erro ao baixar PDF do boleto: " . $e->getMessage());
                        SistemaLog::create([
                            'modulo'        => 'boleto',
                            'referencia_id' => $pag->id,
                            'acao'          => 'PDF_DOWNLOAD',
                            'mensagem'      => 'Erro ao baixar PDF: ' . $e->getMessage(),
                            'resultado'     => 'ERRO',
                        ]);
                    } else {
                        sleep(2);
                    }
                }
            }

            if (! $pdfOk) {
                return;
            }

            // Atualiza dados finais e salva
            $pag->codigo_solicitacao_anterior = $codigoAntigo;
            $pag->codigo_solicitacao = $novoCodigo;
            $pag->vencimento = $novaDataVenc;
            // NÃO altera o campo status, só status_api
            $pag->save();

            $this->info("[Pagamento ID {$pag->id}] Novo boleto/Pix/linha digitável/pdf salvos! Antigo: $codigoAntigo | Novo: $novoCodigo");

            SistemaLog::create([
                'modulo'        => 'pagamento',
                'referencia_id' => $pag->id,
                'acao'          => 'REENVIO',
                'mensagem'      => "Boleto reemitido. Código anterior: $codigoAntigo. Código novo: $novoCodigo. Novo vencimento: $novaDataVenc",
                'resultado'     => 'OK',
            ]);

        } catch (\Exception $e) {
            $this->error("[Pagamento ID {$pag->id}] Erro: " . $e->getMessage());
            SistemaLog::create([
                'modulo'        => 'pagamento',
                'referencia_id' => $pag->id ?? null,
                'acao'          => 'ERRO',
                'mensagem'      => $e->getMessage(),
                'resultado'     => 'ERRO',
            ]);
        }
    }
}
