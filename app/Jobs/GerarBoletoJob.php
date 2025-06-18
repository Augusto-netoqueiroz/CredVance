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
    public $tries = 3;

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
            return;
        }
        Log::info("GerarBoletoJob: iniciando para Pagamento ID {$pag->id}");

        // Monta payload (verificações de endereço assumidas feitas antes)
        $nossoNumero = 100 + $pag->id;
        $cliente = $pag->contrato->cliente;
        $sacado = [
            'nome'       => $cliente->name,
            'cpfCnpj'    => $cliente->cpf,
            'tipoPessoa' => strlen($cliente->cpf) === 11 ? 'FISICA' : 'JURIDICA',
            'logradouro' => $cliente->logradouro,
            'numero'     => $cliente->numero,
            'bairro'     => $cliente->bairro,
            'cidade'     => $cliente->cidade,
            'uf'         => $cliente->uf,
            'cep'        => $cliente->cep,
        ];
        $payload = [
            'nosso_numero'    => (string)$nossoNumero,
            'valor'           => (float)$pag->valor,
            'data_vencimento' => $pag->vencimento->format('Y-m-d'),
            'sacado'          => $sacado,
        ];

        try {
            $res = $service->createBoleto($payload);
        } catch (Exception $e) {
            $pag->tentativas = ($pag->tentativas ?? 0) + 1;
            $pag->error_message = $e->getMessage();
            $pag->save();
            Log::error("GerarBoletoJob: erro createBoleto Pagamento ID {$pag->id}: ".$e->getMessage());
            throw $e;
        }

        // Processa retorno de createBoleto
        try {
            $codigo = $res['codigoSolicitacao'];
            $pag->codigo_solicitacao   = $codigo;
            $pag->nosso_numero         = (string)$nossoNumero;
            $pag->status_solicitacao   = $res['status'] ?? 'PENDENTE';
            $pag->data_emissao         = isset($res['dataEmissao'])
                                          ? Carbon::parse($res['dataEmissao'])
                                          : now();
            if (!empty($res['linhaDigitavel'])) {
                $pag->linha_digitavel = $res['linhaDigitavel'];
            }
            $pag->json_resposta = json_encode($res);
            $pag->save();

            Log::info("GerarBoletoJob: sucesso createBoleto Pagamento ID {$pag->id}, codigo {$codigo}");

            // Agendar job para tentativa de download do PDF após atraso
            // Por exemplo, após 5 minutos:
            \App\Jobs\DownloadBoletoPdfJob::dispatch($pag->id, $codigo)
                ->delay(now()->addMinutes(5));

        } catch (Exception $e) {
            $pag->tentativas++;
            $pag->error_message = "Erro pós-create: ".$e->getMessage();
            $pag->save();
            Log::error("GerarBoletoJob: erro salvar dados pós-create Pagamento ID {$pag->id}: ".$e->getMessage());
            throw $e;
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
