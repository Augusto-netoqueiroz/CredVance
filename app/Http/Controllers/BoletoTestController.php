<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\Client\Search\PaymentSearchClient;

class BoletoTestController extends Controller
{
    protected PaymentClient $mpClient;

    public function __construct()
    {
        MercadoPagoConfig::setAccessToken(env('MP_ACCESS_TOKEN'));
        MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);
        $this->mpClient = new PaymentClient();
    }

    /**
     * Exibe o formulário de geração de boleto (manutenção da rota original).
     */
    public function showForm()
    {
        return view('boleto_test');
    }

    /**
     * Cria o boleto a partir dos dados do formulário.
     */
    public function createBoleto(Request $request)
    {
        $data = $request->validate([
            'amount'               => 'required|numeric|min:1',
            'description'          => 'required|string',
            'email'                => 'required|email',
            'first_name'           => 'required|string',
            'last_name'            => 'required|string',
            'identification_type'  => 'required|string|in:CPF,CNPJ',
            'identification_number'=> 'required|digits_between:11,14',
            'zip_code'             => 'required|string',
            'street_name'          => 'required|string',
            'street_number'        => 'required|string',
            'neighborhood'         => 'required|string',
            'city'                 => 'required|string',
            'federal_unit'         => 'required|string|size:2',
        ]);

        return $this->doCreate($data);
    }

    /**
     * Gera boleto “automaticamente” usando dados fornecidos por query string,
     * sem passar pelo formulário.
     *
     * Exemplo de chamada:
     * /boleto-auto?
     *   amount=150.00&
     *   description=Serviço+XYZ&
     *   email=cliente@ex.com&
     *   first_name=Fulano&
     *   last_name=da+Silva&
     *   identification_type=CPF&
     *   identification_number=12345678909&
     *   zip_code=01310200&
     *   street_name=Av.+Paulista&
     *   street_number=1000&
     *   neighborhood=Bela+Vista&
     *   city=São+Paulo&
     *   federal_unit=SP
     */
    public function generateAuto(Request $request)
    {
        // Mesmas regras de validação do formulário
        $data = $request->validate([
            'amount'               => 'required|numeric|min:1',
            'description'          => 'required|string',
            'email'                => 'required|email',
            'first_name'           => 'required|string',
            'last_name'            => 'required|string',
            'identification_type'  => 'required|string|in:CPF,CNPJ',
            'identification_number'=> 'required|digits_between:11,14',
            'zip_code'             => 'required|string',
            'street_name'          => 'required|string',
            'street_number'        => 'required|string',
            'neighborhood'         => 'required|string',
            'city'                 => 'required|string',
            'federal_unit'         => 'required|string|size:2',
        ]);

        // Cria o boleto e retorna JSON com URL
        $result = $this->doCreate($data);

        // Se veio view (erro), retorna ela; se veio array (sucesso), JSON.
        if (is_array($result)) {
            return response()->json($result);
        }

        return $result;
    }

    /**
     * Método interno que faz a chamada ao Mercado Pago.
     * Retorna view() em caso de erros ou redirect() em caso de sucesso.
     * Quando chamado de generateAuto(), devolve array com status e URL.
     */
    protected function doCreate(array $data)
    {
        $payload = [
            'transaction_amount' => (float) $data['amount'],
            'payment_method_id'  => 'bolbradesco',
            'description'        => $data['description'],
            'payer'              => [
                'email'          => $data['email'],
                'first_name'     => $data['first_name'],
                'last_name'      => $data['last_name'],
                'identification' => [
                    'type'   => $data['identification_type'],
                    'number' => $data['identification_number'],
                ],
                'address' => [
                    'zip_code'      => $data['zip_code'],
                    'street_name'   => $data['street_name'],
                    'street_number' => $data['street_number'],
                    'neighborhood'  => $data['neighborhood'],
                    'city'          => $data['city'],
                    'federal_unit'  => strtoupper($data['federal_unit']),
                ],
            ],
        ];

        try {
            $payment = $this->mpClient->create($payload);
        } catch (MPApiException $e) {
            $apiContent  = $e->getApiResponse()->getContent();
            $errorDetail = is_array($apiContent)
                ? json_encode($apiContent, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
                : (string)$apiContent;

            // Se vier da rota automático, devolve array de erro
            if (request()->routeIs('boleto.auto')) {
                return ['error' => $errorDetail];
            }

            // Senão, volta pro form com mensagem
            return back()
                ->withErrors(['mp_api' => "Erro Mercado Pago: {$errorDetail}"])
                ->withInput();
        } catch (\Exception $e) {
            if (request()->routeIs('boleto.auto')) {
                return ['error' => $e->getMessage()];
            }
            return back()
                ->withErrors(['exception' => $e->getMessage()])
                ->withInput();
        }

        $boletoUrl = $payment->transaction_details->external_resource_url ?? null;
        $status    = $payment->status;

        // Se for rota automático, devolve JSON-like array
        if (request()->routeIs('boleto.auto')) {
            return [
                'status'     => $status,
                'boleto_url' => $boletoUrl,
            ];
        }

        // Senão (rota form), redireciona e exibe via sessão
        return back()
            ->with('boleto_url', $boletoUrl)
            ->with('status',    $status);
    }


   public function debugPayments()
{
    // ... mesma chamada via Http::withToken()
    $results = Http::withToken(env('MP_ACCESS_TOKEN'))
        ->get('https://api.mercadopago.com/v1/payments/search', [
            'payment_method_id'=>'bolbradesco',
            'sort'=>'date_created',
            'criteria'=>'desc',
            'limit'=>10,
        ])->json()['results'];

    // Monta uma lista de links
    $html = '<ul>';
    foreach ($results as $p) {
        $url = $p['transaction_details']['external_resource_url'];
        $id  = $p['id'];
        $html .= "<li><a href=\"{$url}\" target=\"_blank\">Boleto #{$id}</a></li>";
    }
    $html .= '</ul>';

    return response($html);
}


}
