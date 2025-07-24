<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class InterBoletoService
{
    protected string $tokenUrl;
    protected string $baseResourceUrl;
    protected array  $certOptions;
    protected string $tokenScope;
    protected string $contaCorrente;

public function __construct()
{
    $useSandbox = config('inter.use_sandbox', false);

    if ($useSandbox) {
        $tokenUrl = config('inter.api.token_url_sandbox');
        $base = config('inter.api.base_url_sandbox');
        $path = config('inter.api.cobranca_path_sandbox', '/cobranca/v3/cobrancas');

        if (empty($tokenUrl) || empty($base)) {
            throw new RuntimeException("INTER_TOKEN_URL_SANDBOX ou INTER_BASE_URL_SANDBOX não configurados.");
        }

        $this->tokenUrl = $tokenUrl;
        $this->baseResourceUrl = rtrim($base, '/') . '/' . ltrim($path, '/');
        $verifyOption = false;
    } else {
        $tokenUrl = config('inter.api.token_url_prod');
        $base = config('inter.api.base_url_prod');
        $path = config('inter.api.cobranca_path_prod', '/cobranca/v3/cobrancas');

        if (empty($tokenUrl) || empty($base)) {
            throw new RuntimeException("INTER_TOKEN_URL_PROD ou INTER_BASE_URL_PROD_RESOURCES não configurados.");
        }

        $this->tokenUrl = $tokenUrl;
        $this->baseResourceUrl = rtrim($base, '/') . '/' . ltrim($path, '/');
        $caPath = config('inter.ca_path');
        $verifyOption = $caPath ?: true;
    }

    $this->tokenScope = config('inter.token_scope', '');
    $this->contaCorrente = config('inter.conta_corrente', '');

    $certPath = config('inter.cert_path');

    if (empty($certPath)) {
        throw new RuntimeException("INTER_CERT_PATH não configurado.");
    }

    if (!file_exists($certPath)) {
        throw new RuntimeException("Certificado Inter não encontrado: $certPath");
    }

    $this->certOptions = [
        'cert'   => $certPath,
        'verify' => $verifyOption,
    ];

    Log::info('InterBoletoService inicializado', [
        'useSandbox'       => $useSandbox,
        'tokenUrl'         => $this->tokenUrl,
        'baseResourceUrl'  => $this->baseResourceUrl,
        'certPath'         => $certPath,
        'verifyOption'     => $verifyOption,
        'tokenScope'       => $this->tokenScope,
        'contaCorrente'    => $this->contaCorrente,
    ]);
}




    /**
     * Obtém token e cacheia até perto do vencimento.
     *
     * @return string
     * @throws RuntimeException
     */
public function getToken(): string
{
    $cacheKey = 'inter_access_token';

    if (Cache::has($cacheKey)) {
        return Cache::get($cacheKey);
    }

    $clientId     = config('inter.client_id');
    $clientSecret = config('inter.client_secret');

    if (!$clientId || !$clientSecret) {
        throw new RuntimeException("INTER_CLIENT_ID ou INTER_CLIENT_SECRET não configurados.");
    }

    $client = Http::withBasicAuth($clientId, $clientSecret)
        ->withOptions($this->certOptions)
        ->withHeaders(['Accept' => 'application/json']);

    $form = ['grant_type' => 'client_credentials'];

    if (!empty($this->tokenScope)) {
        $form['scope'] = $this->tokenScope;
    }

    $resp = $client->asForm()->post($this->tokenUrl, $form);

    if (!$resp->ok()) {
        Log::error('Erro ao obter token Inter', [
            'status' => $resp->status(),
            'body'   => $resp->body(),
            'url'    => $this->tokenUrl,
        ]);
        throw new RuntimeException("Erro ao obter token Inter: HTTP {$resp->status()} - {$resp->body()}");
    }

    $json = $resp->json();

    if (!isset($json['access_token'])) {
        throw new RuntimeException("Resposta de token sem access_token: " . json_encode($json, JSON_UNESCAPED_UNICODE));
    }

    $accessToken = $json['access_token'];
    $expiresIn = isset($json['expires_in']) ? (int)$json['expires_in'] : 3600;

    Log::info('Token obtido com sucesso', [
        'scope_returned' => $json['scope'] ?? null,
        'expires_in'     => $expiresIn,
    ]);

    $ttlSeconds = max($expiresIn - 60, 60);
    Cache::put($cacheKey, $accessToken, Carbon::now()->addSeconds($ttlSeconds));

    return $accessToken;
}




    /**
     * Cria boleto na API Inter. Espera $data contendo chave 'sacado' e demais opcionais.
     *
     * @param array $data
     * @return array
     * @throws RuntimeException
     */
    public function createBoleto(array $data): array
    {
        $token = $this->getToken();

        // 1. Validar existência de data de vencimento
        if (empty($data['data_vencimento'])) {
            throw new RuntimeException("createBoleto: 'data_vencimento' ausente em data.");
        }
        try {
            $dataVenc = Carbon::parse($data['data_vencimento']);
        } catch (\Exception $e) {
            throw new RuntimeException("Formato de data inválido em 'data_vencimento': " . $e->getMessage());
        }

        // 2. Validar sacado
        if (empty($data['sacado']) || !is_array($data['sacado'])) {
            throw new RuntimeException("createBoleto: chave 'sacado' ausente ou não é array.");
        }
        $sacado = $data['sacado'];

        // Campos obrigatórios em sacado: nome, tipoPessoa, logradouro, numero, bairro, cidade, uf, cep;
        foreach (['nome', 'tipoPessoa', 'logradouro', 'numero', 'bairro', 'cidade', 'uf', 'cep'] as $campo) {
            if (empty($sacado[$campo])) {
                throw new RuntimeException("createBoleto: em 'sacado', falta campo obrigatório '$campo'.");
            }
        }

        // 2.1 Obter CPF/CNPJ do sacado: pode vir como 'cpf_cnpj' ou 'cpfCnpj'
        $cpfCnpjRaw = $sacado['cpf_cnpj'] ?? $sacado['cpfCnpj'] ?? null;
        if (empty($cpfCnpjRaw)) {
            throw new RuntimeException("createBoleto: em 'sacado', falta 'cpf_cnpj' ou 'cpfCnpj'.");
        }
        // Normalizar: remover tudo que não for dígito
        $cpfCnpjLimpo = preg_replace('/\D/', '', $cpfCnpjRaw);
        if (empty($cpfCnpjLimpo)) {
            throw new RuntimeException("createBoleto: 'cpf_cnpj' inválido após limpar caracteres não numéricos.");
        }

        // Montar endereço do sacado
        $logradouro = trim($sacado['logradouro']);
        $numero     = trim($sacado['numero']);
        $enderecoStr = $logradouro . ', ' . $numero;
        if (! empty($sacado['complemento'] ?? null)) {
            $enderecoStr .= ' ' . trim($sacado['complemento']);
        }

        // Montar array pagador
        $pagador = [
            'nome'      => $sacado['nome'],
            'cpfCnpj'   => $cpfCnpjLimpo,
            'tipoPessoa'=> $sacado['tipoPessoa'],
            'endereco'  => $enderecoStr,
            'bairro'    => $sacado['bairro'],
            'cidade'    => $sacado['cidade'],
            'uf'        => strtoupper($sacado['uf']),
            'cep'       => $sacado['cep'],
        ];
        if (! empty($sacado['email'])) {
            $pagador['email'] = $sacado['email'];
        }
        if (! empty($sacado['ddd'])) {
            $pagador['ddd'] = $sacado['ddd'];
        }
        if (! empty($sacado['telefone'])) {
            $pagador['telefone'] = $sacado['telefone'];
        }

        // 3. Montar payload básico
        // Validar nosso_numero
        if (empty($data['nosso_numero'])) {
            throw new RuntimeException("createBoleto: falta 'nosso_numero' em data.");
        }
        // Validar valor
        if (! isset($data['valor'])) {
            throw new RuntimeException("createBoleto: falta 'valor' em data.");
        }
        $valor = $data['valor'];
        if (! is_numeric($valor)) {
            throw new RuntimeException("createBoleto: 'valor' deve ser numérico.");
        }

        $payload = [
            'seuNumero'     => (string) $data['nosso_numero'],
            'valorNominal'  => (float) $valor,
            'dataVencimento'=> $dataVenc->format('Y-m-d'),
            'numDiasAgenda' => isset($data['num_dias_agenda']) ? (int)$data['num_dias_agenda'] : 0,
            'pagador'       => $pagador,
        ];

        // 4. Desconto (opcional)
        if (! empty($data['desconto']) && is_array($data['desconto'])) {
            $d = $data['desconto'];
            if (isset($d['taxa'], $d['codigo'], $d['quantidadeDias'])
                && $d['taxa'] !== '' && $d['codigo'] !== '' && $d['quantidadeDias'] !== ''
            ) {
                if (! is_numeric($d['taxa']) || ! is_numeric($d['quantidadeDias'])) {
                    throw new RuntimeException("createBoleto: campos de desconto devem ser numéricos ('taxa', 'quantidadeDias').");
                }
                $payload['desconto'] = [
                    'taxa'           => (float) $d['taxa'],
                    'codigo'         => $d['codigo'],
                    'quantidadeDias' => (int) $d['quantidadeDias'],
                ];
            }
        }

        // 5. Multa (opcional)
        if (! empty($data['multa']) && is_array($data['multa'])) {
            $m = $data['multa'];
            if (isset($m['taxa'], $m['codigo'])
                && $m['taxa'] !== '' && $m['codigo'] !== ''
            ) {
                if (! is_numeric($m['taxa'])) {
                    throw new RuntimeException("createBoleto: campo multa.taxa deve ser numérico.");
                }
                $payload['multa'] = [
                    'taxa'   => (float) $m['taxa'],
                    'codigo' => $m['codigo'],
                ];
            }
        }

        // 6. Mora (opcional)
        if (! empty($data['mora']) && is_array($data['mora'])) {
            $mo = $data['mora'];
            if (isset($mo['taxa'], $mo['codigo'])
                && $mo['taxa'] !== '' && $mo['codigo'] !== ''
            ) {
                if (! is_numeric($mo['taxa'])) {
                    throw new RuntimeException("createBoleto: campo mora.taxa deve ser numérico.");
                }
                $payload['mora'] = [
                    'taxa'   => (float) $mo['taxa'],
                    'codigo' => $mo['codigo'],
                ];
            }
        }

        // 7. Mensagem (opcional)
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

        // 8. Beneficiário Final (opcional)
        if (! empty($data['beneficiarioFinal']) && is_array($data['beneficiarioFinal'])) {
            $bf = $data['beneficiarioFinal'];
            $requiredBF = ['nome','cpfCnpj','tipoPessoa','logradouro','numero','bairro','cidade','uf','cep'];
            $okBF = true;
            foreach ($requiredBF as $campo) {
                if (empty($bf[$campo])) {
                    $okBF = false;
                    break;
                }
            }
            if ($okBF) {
                $cpfCnpjBF = preg_replace('/\D/', '', $bf['cpfCnpj']);
                if (empty($cpfCnpjBF)) {
                    throw new RuntimeException("createBoleto: beneficiarioFinal.cpfCnpj inválido após limpar caracteres.");
                }
                $endBF = trim($bf['logradouro']) . ', ' . trim($bf['numero']);
                if (! empty($bf['complemento'] ?? null)) {
                    $endBF .= ' ' . trim($bf['complemento']);
                }
                $payload['beneficiarioFinal'] = [
                    'nome'      => $bf['nome'],
                    'cpfCnpj'   => $cpfCnpjBF,
                    'tipoPessoa'=> $bf['tipoPessoa'],
                    'endereco'  => $endBF,
                    'bairro'    => $bf['bairro'],
                    'cidade'    => $bf['cidade'],
                    'uf'        => strtoupper($bf['uf']),
                    'cep'       => $bf['cep'],
                ];
            }
            // Se não vier completo, ignora. Se quiser forçar, descomente:
            // else {
            //     throw new RuntimeException("createBoleto: beneficiarioFinal incompleto.");
            // }
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
            try {
                $errorDetail = $resp->json();
            } catch (\Exception $e) {
                $errorDetail = $bodyRaw;
            }
            Log::error('Erro ao criar cobrança v3 Inter', [
                'status' => $status,
                'body'   => $errorDetail,
                'payload'=> $payload,
            ]);
            throw new RuntimeException('Erro na API de Cobrança Inter v3 (POST): HTTP ' . $status . ' - ' . json_encode($errorDetail, JSON_UNESCAPED_UNICODE));
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

    /**
     * Consulta cobrança pela solicitação.
     *
     * @param string $codigoSolicitacao
     * @return array
     * @throws RuntimeException
     */
   /**
 * Consulta uma cobrança no Banco Inter.
 * Retorna array com todos os dados, incluindo cobranca['nossoNumero'].
 *
 * @param string $codigoSolicitacao
 * @return array
 * @throws RuntimeException
 */
public function getCobranca(string $codigoSolicitacao): array
{
    $token = $this->getToken();
    $url = rtrim($this->baseResourceUrl, '/') . '/' . urlencode($codigoSolicitacao);

    Log::info('Inter getCobranca v3 - GET', ['url' => $url]);

    $resp = \Http::withToken($token)
        ->withOptions($this->certOptions)
        ->withHeaders([
            'Accept' => 'application/json',
            'x-conta-corrente' => $this->contaCorrente,
        ])
        ->get($url);

    $status = $resp->status();
    $bodyRaw = $resp->body();
    Log::info('Inter getCobranca v3 - resposta GET', [
        'status' => $status,
        'bodyRaw' => $bodyRaw,
    ]);

    if ($status !== 200) {
        $errorDetail = null;
        try {
            $errorDetail = $resp->json();
        } catch (\Exception $e) {
            $errorDetail = $bodyRaw;
        }
        Log::error('Erro ao consultar cobrança v3 Inter', [
            'status' => $status,
            'body'   => $errorDetail,
        ]);
        throw new \RuntimeException('Erro na API de Cobrança Inter v3 (GET): HTTP ' . $status . ' - ' . json_encode($errorDetail, JSON_UNESCAPED_UNICODE));
    }

    // Aqui retorna todo o array da resposta, incluindo o nossoNumero dentro de 'cobranca'
    return $resp->json();
}


    /**
     * Lista cobranças com filtros opcionais.
     *
     * @param array $filters Exemplos: ['dataInicio'=>'YYYY-MM-DD','dataFim'=>'YYYY-MM-DD','status'=>'PENDENTE']
     * @return array
     * @throws RuntimeException
     */
    public function listCobrancas(array $filters = []): array
    {
        $token = $this->getToken();
        $url = $this->baseResourceUrl;

        $query = [];
        if (!empty($filters['dataInicio'])) {
            $query['dataInicial'] = $filters['dataInicio'];
        }
        if (!empty($filters['dataFim'])) {
            $query['dataFinal'] = $filters['dataFim'];
        }
        if (!empty($filters['status'])) {
            $query['status'] = $filters['status'];
        }
        // adicionar outros filtros conforme doc

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
            try {
                $errorDetail = $resp->json();
            } catch (\Exception $e) {
                $errorDetail = $bodyRaw;
            }
            Log::error('Erro ao listar cobranças v3 Inter', [
                'status'=>$status,'body'=>$errorDetail,
            ]);
            throw new RuntimeException('Erro na API de Cobrança Inter v3 (LIST): HTTP ' . $status . ' - ' . json_encode($errorDetail, JSON_UNESCAPED_UNICODE));
        }

        return $resp->json();
    }

    /**
     * Baixa JSON base64 do PDF do boleto.
     *
     * @param string $codigoSolicitacao
     * @return string Conteúdo binário do PDF
     * @throws RuntimeException
     */
    public function downloadBoletoPdf(string $codigoSolicitacao): string
    {
        $token = $this->getToken();
        $url = rtrim($this->baseResourceUrl, '/') . '/' . urlencode($codigoSolicitacao) . '/pdf';

        Log::info('Inter downloadBoletoPdf v3 - GET JSON Base64', ['url' => $url]);

        $resp = Http::withToken($token)
            ->withOptions($this->certOptions)
            ->withHeaders([
                'Accept' => 'application/json',
                'x-conta-corrente' => $this->contaCorrente,
            ])->get($url);

        $status = $resp->status();
        $bodyRaw = $resp->body();
        Log::info('Inter downloadBoletoPdf v3 - resposta GET', [
            'status'   => $status,
            'bodyRaw'  => strlen($bodyRaw) > 1000 ? substr($bodyRaw, 0, 1000).'...': $bodyRaw,
        ]);

        if ($status !== 200) {
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

        $pdfBase64 = $json['pdf'];
        $pdfBinary = base64_decode($pdfBase64, true);
        if ($pdfBinary === false) {
            Log::error('Falha ao decodificar Base64 do campo "pdf"', ['length'=>strlen($pdfBase64)]);
            throw new RuntimeException('Falha ao decodificar Base64 do PDF.');
        }

        return $pdfBinary;
    }

    /**
     * Retorna diretamente o Base64 do PDF do boleto (campo 'pdf').
     *
     * @param string $codigoSolicitacao
     * @return string Base64 do PDF
     * @throws RuntimeException
     */
    public function getBoletoPdf(string $codigoSolicitacao): string
    {
        $token = $this->getToken();
        $url = rtrim($this->baseResourceUrl, '/') . '/' . urlencode($codigoSolicitacao) . '/pdf';

        Log::info('Inter getBoletoPdf v3 - GET', ['url' => $url]);

        $resp = Http::withToken($token)
            ->withOptions($this->certOptions)
            ->withHeaders([
                'Accept' => 'application/json',
                'x-conta-corrente' => $this->contaCorrente,
            ])->get($url);

        $status = $resp->status();
        $bodyRaw = $resp->body();
        Log::info('Inter getBoletoPdf v3 - resposta GET', ['status'=>$status, 'bodyRaw'=>substr($bodyRaw,0,200)]);

        if ($status !== 200) {
            $errorDetail = null;
            try {
                $errorDetail = $resp->json();
            } catch (\Exception $e) {
                $errorDetail = $bodyRaw;
            }
            Log::error('Erro ao obter PDF da cobrança v3 Inter', [
                'status' => $status,
                'body'   => $errorDetail,
                'url'    => $url,
            ]);
            throw new RuntimeException('Erro na API de Cobrança Inter v3 (GET PDF): HTTP ' . $status . ' - ' . json_encode($errorDetail, JSON_UNESCAPED_UNICODE));
        }

        $json = $resp->json();
        if (!isset($json['pdf'])) {
            Log::error('getBoletoPdf: campo "pdf" não encontrado', ['json'=>$json]);
            throw new RuntimeException('Resposta sem campo "pdf": ' . json_encode($json, JSON_UNESCAPED_UNICODE));
        }
        return $json['pdf'];
    }

public function cancelarBoletoPorNossoNumero(string $nossoNumero): array
{
    $token = $this->getToken();
    $url = rtrim($this->baseResourceUrl, '/') . '/' . urlencode($nossoNumero);

    $payload = ['status' => 'CANCELADA']; // <- CORRIGIDO AQUI!

    \Log::debug('[DEBUG] Endpoint de cancelamento: ' . $url);
    \Log::debug('[DEBUG] Payload de cancelamento: ' . json_encode($payload));

    $resp = \Http::withToken($token)
        ->withOptions($this->certOptions)
        ->withHeaders([
            'Accept' => 'application/json',
            'x-conta-corrente' => $this->contaCorrente,
        ])
        ->patch($url, $payload);

    if ($resp->status() !== 200) {
        throw new \RuntimeException('Erro ao cancelar cobrança: HTTP ' . $resp->status() . ' - ' . $resp->body());
    }

    return $resp->json();
}




public function getCancelEndpoint($nossoNumero)
{
    return rtrim($this->baseResourceUrl, '/') . '/' . $nossoNumero;
}


public function getBaseResourceUrl(): string
{
    return $this->baseResourceUrl;
}

public function cancelarBoletoPorCodigoSolicitacao(string $codigoSolicitacao, string $novaDataVencimento, $valorNominal): array
{
    $token = $this->getToken();
    $url = rtrim($this->baseResourceUrl, '/') . '/' . urlencode($codigoSolicitacao);

    $payload = [
        'status'        => 'CANCELADA',
        'dataVencimento'=> $novaDataVencimento,
        'valorNominal'  => $valorNominal,
    ];

    \Log::debug('[DEBUG] PATCH endpoint: ' . $url);
    \Log::debug('[DEBUG] PATCH payload: ' . json_encode($payload));

    $resp = \Http::withToken($token)
        ->withOptions($this->certOptions)
        ->withHeaders([
            'Accept' => 'application/json',
            'x-conta-corrente' => $this->contaCorrente,
        ])
        ->patch($url, $payload);

    if ($resp->status() !== 200) {
        throw new \RuntimeException('Erro ao cancelar cobrança: HTTP ' . $resp->status() . ' - ' . $resp->body());
    }

    return $resp->json();
}



public function cancelarBoletoComValor(string $codigoSolicitacao, float $valorNominal): array
{
    $token = $this->getToken();
    $url = rtrim($this->baseResourceUrl, '/') . '/' . urlencode($codigoSolicitacao) . '/cancelar';

    $payload = [
        'valorNominal' => $valorNominal,
        'motivoCancelamento' => 'Remarcação de vencimento',
    ];

    \Log::debug('[DEBUG] POST cancelamento COM VALOR: ' . $url);
    \Log::debug('[DEBUG] Payload:', $payload);

    $resp = \Http::withToken($token)
        ->withOptions($this->certOptions)
        ->withHeaders([
            'Accept' => 'application/json',
            'x-conta-corrente' => $this->contaCorrente,
        ])
        ->post($url, $payload);

    if ($resp->failed()) {
        \Log::error('[ERRO] Cancelamento com valor falhou', [
            'status' => $resp->status(),
            'body' => $resp->body(),
        ]);

        throw new \RuntimeException('Erro ao cancelar cobrança (com valor): HTTP ' . $resp->status() . ' - ' . $resp->body());
    }

    return $resp->json() ?? [];
}





}
