<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Carbon\Carbon;

class InterBoletoService
{
    protected string $tokenUrl;
    protected string $baseResourceUrl;
    protected array  $certOptions;
    protected string $tokenScope;
    protected string $contaCorrente;

    public function __construct()
    {
        $useSandbox = filter_var(env('INTER_USE_SANDBOX', false), FILTER_VALIDATE_BOOLEAN);

        if ($useSandbox) {
            $this->tokenUrl = env('INTER_TOKEN_URL_SANDBOX');
            $base = env('INTER_BASE_URL_SANDBOX');
            $path = env('INTER_COBRANCA_PATH_SANDBOX', '/cobranca/v3/cobrancas');
            if (empty($this->tokenUrl) || empty($base)) {
                throw new RuntimeException("INTER_TOKEN_URL_SANDBOX ou INTER_BASE_URL_SANDBOX não configurados.");
            }
            $this->baseResourceUrl = rtrim($base, '/') . '/' . ltrim($path, '/');
            $verifyOption = false;
        } else {
            $this->tokenUrl = env('INTER_TOKEN_URL_PROD');
            $base = env('INTER_BASE_URL_PROD_RESOURCES');
            $path = env('INTER_COBRANCA_PATH_PROD', '/cobranca/v3/cobrancas');
            if (empty($this->tokenUrl) || empty($base)) {
                throw new RuntimeException("INTER_TOKEN_URL_PROD ou INTER_BASE_URL_PROD_RESOURCES não configurados.");
            }
            $this->baseResourceUrl = rtrim($base, '/') . '/' . ltrim($path, '/');
            $caPath = env('INTER_CA_PATH');
            $verifyOption = $caPath ? $caPath : true;
        }

        $this->tokenScope = env('INTER_TOKEN_SCOPE', '');
        if (empty($this->tokenScope)) {
            Log::warning('INTER_TOKEN_SCOPE não configurado; talvez falte escopo.');
        }

        $this->contaCorrente = env('INTER_CONTA_CORRENTE', '');
        if (empty($this->contaCorrente)) {
            Log::warning('INTER_CONTA_CORRENTE não configurado; cabeçalho x-conta-corrente pode faltar.');
        }

        $certPath = env('INTER_CERT_PATH');
        $keyPath  = env('INTER_CERT_KEY_PATH');
        if (empty($certPath) || empty($keyPath)) {
            throw new RuntimeException("INTER_CERT_PATH ou INTER_CERT_KEY_PATH não configurados.");
        }
        if (! file_exists($certPath) || ! file_exists($keyPath)) {
            throw new RuntimeException("Certificado ou chave Inter não encontrados: $certPath, $keyPath");
        }
        $this->certOptions = [
            'cert'    => $certPath,
            'ssl_key' => $keyPath,
            'verify'  => $verifyOption,
        ];

        Log::info('InterBoletoService inicializado', [
            'tokenUrl' => $this->tokenUrl,
            'baseResourceUrl' => $this->baseResourceUrl,
            'certOptions' => $this->certOptions,
            'tokenScope' => $this->tokenScope,
            'contaCorrente' => $this->contaCorrente,
        ]);
    }

    public function getToken(): string
    {
        $clientId     = env('INTER_CLIENT_ID');
        $clientSecret = env('INTER_CLIENT_SECRET');
        if (! $clientId || ! $clientSecret) {
            throw new RuntimeException("INTER_CLIENT_ID ou INTER_CLIENT_SECRET não configurados.");
        }

        $client = Http::withBasicAuth($clientId, $clientSecret)
                      ->withOptions($this->certOptions)
                      ->withHeaders(['Accept' => 'application/json']);

        $form = ['grant_type' => 'client_credentials'];
        if (! empty($this->tokenScope)) {
            $form['scope'] = $this->tokenScope;
        }

        $resp = $client->asForm()->post($this->tokenUrl, $form);
        if (! $resp->ok()) {
            Log::error('Erro ao obter token Inter', [
                'status' => $resp->status(),
                'body'   => $resp->body(),
                'url'    => $this->tokenUrl,
            ]);
            throw new RuntimeException("Erro ao obter token Inter: HTTP {$resp->status()} - {$resp->body()}");
        }
        $json = $resp->json();
        if (! isset($json['access_token'])) {
            throw new RuntimeException("Resposta de token sem access_token: " . json_encode($json, JSON_UNESCAPED_UNICODE));
        }
        Log::info('Token obtido com sucesso', [
            'scope_returned' => $json['scope'] ?? null,
            'expires_in'     => $json['expires_in'] ?? null,
        ]);
        return $json['access_token'];
    }

    public function createBoleto(array $data): array
    {
        $token = $this->getToken();

        try {
            $dataVenc = Carbon::parse($data['data_vencimento']);
        } catch (\Exception $e) {
            throw new RuntimeException("Formato de data inválido: " . $e->getMessage());
        }

        // Montar pagador
        $logradouro = $data['sacado']['logradouro'];
        $numero     = $data['sacado']['numero'];
        $enderecoStr = trim($logradouro) . ', ' . trim($numero);
        if (! empty($data['sacado']['complemento'] ?? null)) {
            $enderecoStr .= ' ' . trim($data['sacado']['complemento']);
        }
        $pagador = [
            'nome'    => $data['sacado']['nome'],
            'cpfCnpj' => $data['sacado']['cpf_cnpj'],
        ];
        if (! empty($data['sacado']['email'] ?? null)) {
            $pagador['email'] = $data['sacado']['email'];
        }
        if (! empty($data['sacado']['ddd'] ?? null)) {
            $pagador['ddd'] = $data['sacado']['ddd'];
        }
        if (! empty($data['sacado']['telefone'] ?? null)) {
            $pagador['telefone'] = $data['sacado']['telefone'];
        }
        $pagador['tipoPessoa'] = $data['sacado']['tipoPessoa'];
        $pagador['endereco']   = $enderecoStr;
        $pagador['bairro']     = $data['sacado']['bairro'];
        $pagador['cidade']     = $data['sacado']['cidade'];
        $pagador['uf']         = strtoupper($data['sacado']['uf']);
        $pagador['cep']        = $data['sacado']['cep'];

        $payload = [
            'seuNumero'     => (string) $data['nosso_numero'],
            'valorNominal'  => (float) $data['valor'],
            'dataVencimento'=> $dataVenc->format('Y-m-d'),
            'numDiasAgenda' => isset($data['num_dias_agenda']) ? (int)$data['num_dias_agenda'] : 0,
            'pagador'       => $pagador,
        ];

        // Desconto
        if (! empty($data['desconto']['taxa'] ?? null)
            && ! empty($data['desconto']['codigo'] ?? null)
            && ! empty($data['desconto']['quantidadeDias'] ?? null)
        ) {
            $payload['desconto'] = [
                'taxa'           => (float) $data['desconto']['taxa'],
                'codigo'         => $data['desconto']['codigo'],
                'quantidadeDias' => (int) $data['desconto']['quantidadeDias'],
            ];
        }
        // Multa
        if (! empty($data['multa']['taxa'] ?? null)
            && ! empty($data['multa']['codigo'] ?? null)
        ) {
            $payload['multa'] = [
                'taxa'   => (float) $data['multa']['taxa'],
                'codigo' => $data['multa']['codigo'],
            ];
        }
        // Mora
        if (! empty($data['mora']['taxa'] ?? null)
            && ! empty($data['mora']['codigo'] ?? null)
        ) {
            $payload['mora'] = [
                'taxa'   => (float) $data['mora']['taxa'],
                'codigo' => $data['mora']['codigo'],
            ];
        }
        // Mensagem
        if (! empty($data['mensagem']) && is_array($data['mensagem'])) {
            $msgArray = [];
            for ($i = 1; $i <= 5; $i++) {
                $key = 'linha' . $i;
                if (! empty($data['mensagem'][$key] ?? null)) {
                    $msgArray[$key] = $data['mensagem'][$key];
                }
            }
            if (! empty($msgArray)) {
                $payload['mensagem'] = $msgArray;
            }
        }
        // Beneficiário Final
        if (! empty($data['beneficiarioFinal'])
            && is_array($data['beneficiarioFinal'])
            && ! empty($data['beneficiarioFinal']['nome'] ?? null)
            && ! empty($data['beneficiarioFinal']['cpfCnpj'] ?? null)
            && ! empty($data['beneficiarioFinal']['tipoPessoa'] ?? null)
            && ! empty($data['beneficiarioFinal']['logradouro'] ?? null)
            && ! empty($data['beneficiarioFinal']['numero'] ?? null)
            && ! empty($data['beneficiarioFinal']['bairro'] ?? null)
            && ! empty($data['beneficiarioFinal']['cidade'] ?? null)
            && ! empty($data['beneficiarioFinal']['uf'] ?? null)
            && ! empty($data['beneficiarioFinal']['cep'] ?? null)
        ) {
            $benef = [
                'nome'      => $data['beneficiarioFinal']['nome'],
                'cpfCnpj'   => $data['beneficiarioFinal']['cpfCnpj'],
                'tipoPessoa'=> $data['beneficiarioFinal']['tipoPessoa'],
                'endereco'  => trim($data['beneficiarioFinal']['logradouro']) . ', ' . trim($data['beneficiarioFinal']['numero']),
                'bairro'    => $data['beneficiarioFinal']['bairro'],
                'cidade'    => $data['beneficiarioFinal']['cidade'],
                'uf'        => strtoupper($data['beneficiarioFinal']['uf']),
                'cep'       => $data['beneficiarioFinal']['cep'],
            ];
            if (! empty($data['beneficiarioFinal']['complemento'] ?? null)) {
                $benef['endereco'] .= ' ' . $data['beneficiarioFinal']['complemento'];
            }
            $payload['beneficiarioFinal'] = $benef;
        }

        Log::info('Inter createBoleto v3 - payload POST /cobrancas', [
            'url' => $this->baseResourceUrl,
            'payload' => $payload,
        ]);

        $client = Http::withToken($token)
            ->withOptions($this->certOptions)
            ->withHeaders([
                'Accept' => 'application/json',
                'x-conta-corrente' => $this->contaCorrente,
            ]);

        $resp = $client->post($this->baseResourceUrl, $payload);
        $status = $resp->status();
        $bodyRaw = $resp->body();
        Log::info('Inter createBoleto v3 - resposta POST', [
            'status'  => $status,
            'bodyRaw' => $bodyRaw,
        ]);

        if (! in_array($status, [200, 201], true)) {
            $errorDetail = null;
            try { $errorDetail = $resp->json(); } catch (\Exception $e) {}
            Log::error('Erro ao criar cobrança v3 Inter', [
                'status' => $status,
                'body'   => $errorDetail ?: $bodyRaw,
                'payload'=> $payload,
            ]);
            throw new RuntimeException('Erro na API de Cobrança Inter v3 (POST): HTTP ' . $status . ' - ' . json_encode($errorDetail ?: $bodyRaw, JSON_UNESCAPED_UNICODE));
        }

        $json = $resp->json();
        if (! isset($json['codigoSolicitacao'])) {
            Log::error('createBoleto v3: campo codigoSolicitacao não encontrado', ['json' => $json]);
            throw new RuntimeException('Resposta de criação sem codigoSolicitacao: ' . json_encode($json, JSON_UNESCAPED_UNICODE));
        }
        return [
            'codigoSolicitacao' => $json['codigoSolicitacao'],
            'rawResponse'       => $json,
        ];
    }

public function getCobranca(string $codigoSolicitacao): array
{
    $token = $this->getToken();
    $url = rtrim($this->baseResourceUrl, '/') . '/' . urlencode($codigoSolicitacao);

    Log::info('Inter getCobranca v3 - GET', ['url' => $url]);

    $resp = Http::withToken($token)
        ->withOptions($this->certOptions)
        ->withHeaders([
            'Accept' => 'application/json',
            'x-conta-corrente' => $this->contaCorrente,
        ])->get($url);

    $status = $resp->status();
    $bodyRaw = $resp->body();
    Log::info('Inter getCobranca v3 - resposta GET', [
        'status' => $status,
        'bodyRaw' => $bodyRaw,
    ]);

    if ($status !== 200) {
        $errorDetail = null;
        try { $errorDetail = $resp->json(); } catch (\Exception $e) {}
        Log::error('Erro ao consultar cobrança v3 Inter', [
            'status' => $status,
            'body'   => $errorDetail ?: $bodyRaw,
        ]);
        throw new RuntimeException('Erro na API de Cobrança Inter v3 (GET): HTTP ' . $status . ' - ' . json_encode($errorDetail ?: $bodyRaw, JSON_UNESCAPED_UNICODE));
    }

    $json = $resp->json();
    return $json;
}

/**
 * Lista cobranças com filtros opcionais.
 *
 * @param array $filters Exemplos: ['dataInicio'=>'2025-06-01','dataFim'=>'2025-06-30','status'=>'PENDENTE']
 * @return array Lista de cobranças e metadados de paginação
 * @throws RuntimeException
 */
public function listCobrancas(array $filters = []): array
{
    $token = $this->getToken();
    $url = $this->baseResourceUrl; // e.g. https://cdpj.partners.bancointer.com.br/cobranca/v3/cobrancas

    // Montar query string com nomes esperados pela API:
    $query = [];
    // dataInicial/dataFinal conforme a API exige:
    if (!empty($filters['dataInicio'])) {
        // A API espera dataInicial, no formato YYYY-MM-DD
        $query['dataInicial'] = $filters['dataInicio'];
    }
    if (!empty($filters['dataFim'])) {
        $query['dataFinal'] = $filters['dataFim'];
    }
    if (!empty($filters['status'])) {
        // Verifique se a API aceita filtro status; usar a string conforme documentação, ex.: "PENDENTE"
        $query['status'] = $filters['status'];
    }
    // Outros filtros conforme documentação (e.g., seuNumero etc.) podem ser adicionados aqui

    Log::info('Inter listCobrancas v3 - GET', ['url'=>$url, 'query'=>$query]);

    $resp = Http::withToken($token)
        ->withOptions($this->certOptions)
        ->withHeaders([
            'Accept' => 'application/json',
            'x-conta-corrente' => $this->contaCorrente,
        ])->get($url, $query);

    $status = $resp->status();
    $bodyRaw = $resp->body();
    Log::info('Inter listCobrancas v3 - resposta GET', [
        'status'=>$status, 'bodyRaw'=>$bodyRaw,
    ]);

    if ($status !== 200) {
        $errorDetail = null;
        try { $errorDetail = $resp->json(); } catch (\Exception $e) {}
        Log::error('Erro ao listar cobranças v3 Inter', [
            'status'=>$status,'body'=>$errorDetail ?: $bodyRaw,
        ]);
        throw new RuntimeException('Erro na API de Cobrança Inter v3 (LIST): HTTP ' . $status . ' - ' . json_encode($errorDetail ?: $bodyRaw, JSON_UNESCAPED_UNICODE));
    }

    return $resp->json();
}

  public function downloadBoletoPdf(string $codigoSolicitacao): string
    {
        $token = $this->getToken();

        // Montar URL do endpoint de PDF conforme doc:
        $url = rtrim($this->baseResourceUrl, '/') . '/' . urlencode($codigoSolicitacao) . '/pdf';

        Log::info('Inter downloadBoletoPdf v3 - GET JSON Base64', ['url' => $url]);

        $resp = Http::withToken($token)
            ->withOptions($this->certOptions)
            ->withHeaders([
                'Accept' => 'application/json', // doc mostra content-type application/json com campo "pdf"
                'x-conta-corrente' => $this->contaCorrente,
            ])
            ->get($url);

        $status = $resp->status();
        $bodyRaw = $resp->body();

        Log::info('Inter downloadBoletoPdf v3 - resposta GET', [
            'status'   => $status,
            'bodyRaw'  => strlen($bodyRaw) > 1000 ? substr($bodyRaw, 0, 1000).'...': $bodyRaw,
        ]);

        if ($status !== 200) {
            // Tenta extrair JSON de erro
            $errorDetail = null;
            try {
                $errorDetail = $resp->json();
            } catch (\Exception $e) {
                $errorDetail = $bodyRaw;
            }
            Log::error('Erro ao obter JSON Base64 do PDF', [
                'status' => $status,
                'body'   => $errorDetail,
                'url'    => $url,
            ]);
            throw new RuntimeException('Erro ao baixar PDF do boleto: HTTP ' . $status . ' - ' . json_encode($errorDetail, JSON_UNESCAPED_UNICODE));
        }

        // Obter JSON
        try {
            $json = $resp->json();
        } catch (\Exception $e) {
            Log::error('Resposta não é JSON válido para PDF Base64', ['bodyRaw'=>$bodyRaw]);
            throw new RuntimeException('Resposta inválida ao obter PDF (não é JSON).');
        }

        if (!isset($json['pdf']) || empty($json['pdf'])) {
            Log::error('Campo "pdf" ausente ou vazio no JSON de PDF', ['json'=>$json]);
            throw new RuntimeException('Resposta JSON não contém campo "pdf" com conteúdo Base64.');
        }

        // Decodificar Base64
        $pdfBase64 = $json['pdf'];
        $pdfBinary = base64_decode($pdfBase64, true);
        if ($pdfBinary === false) {
            Log::error('Falha ao decodificar Base64 do campo "pdf"', ['length'=>strlen($pdfBase64)]);
            throw new RuntimeException('Falha ao decodificar Base64 do PDF.');
        }

        return $pdfBinary;
    }



     public function getBoletoPdf(string $codigoSolicitacao): string
    {
        $token = $this->getToken();
        // Montar URL: baseResourceUrl termina com '/cobrancas', adicionamos '/{codigo}/pdf'
        $url = rtrim($this->baseResourceUrl, '/') . '/' . urlencode($codigoSolicitacao) . '/pdf';

        Log::info('Inter getBoletoPdf v3 - GET', ['url' => $url]);

        $resp = Http::withToken($token)
            ->withOptions($this->certOptions)
            ->withHeaders([
                'Accept' => 'application/json',
                'x-conta-corrente' => $this->contaCorrente,
            ])
            ->get($url);

        $status = $resp->status();
        $bodyRaw = $resp->body();
        Log::info('Inter getBoletoPdf v3 - resposta GET', ['status'=>$status, 'bodyRaw'=>substr($bodyRaw,0,200)]);

        if ($status !== 200) {
            $errorDetail = null;
            try { $errorDetail = $resp->json(); } catch (\Exception $e) {}
            Log::error('Erro ao obter PDF da cobrança v3 Inter', [
                'status' => $status,
                'body'   => $errorDetail ?: $bodyRaw,
                'url'    => $url,
            ]);
            throw new RuntimeException('Erro na API de Cobrança Inter v3 (GET PDF): HTTP ' . $status . ' - ' . json_encode($errorDetail ?: $bodyRaw, JSON_UNESCAPED_UNICODE));
        }

        $json = $resp->json();
        if (!isset($json['pdf'])) {
            Log::error('getBoletoPdf: campo "pdf" não encontrado', ['json'=>$json]);
            throw new RuntimeException('Resposta sem campo "pdf": ' . json_encode($json, JSON_UNESCAPED_UNICODE));
        }
        return $json['pdf'];
    }


}
