<?php
namespace App\Jobs;

use App\Models\Pagamento;
use App\Services\InterBoletoService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Exception;
use Carbon\Carbon;

class GerarBoletoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $pagamentoId;

    public function __construct(int $pagamentoId)
    {
        $this->pagamentoId = $pagamentoId;
    }

    public function middleware()
    {
        return [ new RateLimited('gerar-boletos') ];
    }

public function handle(InterBoletoService $service)
{
    $pag = Pagamento::find($this->pagamentoId);
    if (!$pag || $pag->codigo_solicitacao) {
        Log::warning("GerarBoletoJob: Pagamento inexistente ou já processado (id: {$this->pagamentoId}).");
        return;
    }

    // Validando relacionamento com contrato/cliente
    $contrato = $pag->contrato;
    $cliente = $contrato ? $contrato->cliente : null;
    if (!$cliente) {
        $msg = "Cliente não encontrado para Pagamento ID {$pag->id}";
        $pag->error_message = $msg;
        $pag->save();
        Log::error($msg);
        return;
    }

    // Validação do CEP e dados básicos
    $cepLimpo = preg_replace('/\D/', '', $cliente->cep);
    if(strlen($cepLimpo) !== 8) {
        $msg = "CEP inválido: original [{$cliente->cep}] | filtrado [{$cepLimpo}] | cliente_id [{$cliente->id}]";
        $pag->error_message = $msg;
        $pag->tentativas = ($pag->tentativas ?? 0) + 1;
        $pag->save();
        Log::error($msg);
        return;
    }

    // Gera o nosso_numero de forma menos "previsível" se possível
    $nossoNumero = 'NP' . str_pad($pag->id, 8, '0', STR_PAD_LEFT); // Exemplo mais seguro

    // Garante valor válido
    $valor = (float) $pag->valor;
    if ($valor <= 0) {
        $pag->error_message = "Valor inválido para gerar boleto.";
        $pag->tentativas = ($pag->tentativas ?? 0) + 1;
        $pag->save();
        Log::error("GerarBoletoJob: valor zero/negativo Pagamento ID {$pag->id}");
        return;
    }

    $payload = [
        'nosso_numero'    => (string)$nossoNumero,
        'valor'           => $valor,
        'data_vencimento' => $pag->vencimento->format('Y-m-d'),
        'sacado'          => [
            'nome'       => $cliente->name,
            'cpfCnpj'    => $cliente->cpf,
            'tipoPessoa' => strlen($cliente->cpf) === 11 ? 'FISICA' : 'JURIDICA',
            'logradouro' => $cliente->logradouro,
            'numero'     => $cliente->numero,
            'bairro'     => $cliente->bairro,
            'cidade'     => $cliente->cidade,
            'uf'         => $cliente->uf,
            'cep'        => $cepLimpo,
        ],
    ];

    try {
        $res = $service->createBoleto($payload);
    } catch (Exception $e) {
        $pag->tentativas = ($pag->tentativas ?? 0) + 1;
        $pag->error_message = $e->getMessage();
        $pag->save();
        Log::error("GerarBoletoJob: erro ao criar boleto para Pagamento ID {$pag->id}: ".$e->getMessage());
        return;
    }

    try {
        $codigo = $res['codigoSolicitacao'] ?? null;
        if (!$codigo) {
            $msg = "API não retornou codigoSolicitacao. Resposta: " . json_encode($res);
            $pag->tentativas = ($pag->tentativas ?? 0) + 1;
            $pag->error_message = $msg;
            $pag->save();
            Log::error("GerarBoletoJob: " . $msg);
            return;
        }
        $pag->codigo_solicitacao   = $codigo;
        $pag->nosso_numero         = (string)$nossoNumero;
        $pag->status_solicitacao   = $res['status'] ?? 'PENDENTE';
        $pag->data_emissao         = isset($res['dataEmissao'])
                                      ? Carbon::parse($res['dataEmissao'])
                                      : now();
        // Já salva pix e linha digitável se já vierem
        $pag->pix = $res['pix']['pixCopiaECola'] ?? null;
        $pag->linha_digitavel = $res['linhaDigitavel'] ?? null;
        $pag->json_resposta = json_encode($res);
        $pag->tentativas = 0;
        $pag->error_message = null;
        $pag->save();

        Log::info("GerarBoletoJob: sucesso createBoleto Pagamento ID {$pag->id}, codigo {$codigo}");

        // PDF e Pix garantidos via DownloadBoletoPdfJob, mas você já salva o que vier aqui
        \App\Jobs\DownloadBoletoPdfJob::dispatch($pag->id, $codigo)
            ->delay(now()->addMinutes(5));

    } catch (Exception $e) {
        $pag->tentativas = ($pag->tentativas ?? 0) + 1;
        $pag->error_message = "Erro pós-create: ".$e->getMessage();
        $pag->save();
        Log::error("GerarBoletoJob: erro ao salvar pós-create Pagamento ID {$pag->id}: ".$e->getMessage());
        return;
    }
}





    public function failed(Exception $e)
    {
        $pag = Pagamento::find($this->pagamentoId);
        if ($pag) {
            $pag->error_message = "Job falhou: ".$e->getMessage();
            $pag->save();
        }
        Log::error("GerarBoletoJob: falha definitiva Pagamento ID {$this->pagamentoId}: ".$e->getMessage());
    }
}
