<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\InterBoletoService;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class InterBoletoTestController extends Controller
{
    protected InterBoletoService $service;

    public function __construct(InterBoletoService $service)
    {
        $this->service = $service;
    }

    // Exibe a view única com abas
    public function showForm()
    {
        // A view lerá session('boleto_data'), session('detalhes'), session('lista') e session('active_tab')
        return view('inter_boleto');
    }

    // Emissão de cobrança
    public function createBoleto(Request $request)
    {
        $data = $request->validate([
            'nosso_numero'      => 'required|string|max:15',
            'valor'             => 'required|numeric|min:2.5',
            'data_vencimento'   => 'required|date_format:Y-m-d',
            'num_dias_agenda'   => 'nullable|integer|min:0|max:60',
            'sacado.nome'       => 'required|string',
            'sacado.cpf_cnpj'   => 'required|string',
            'sacado.email'      => 'nullable|email',
            'sacado.ddd'        => 'nullable|string',
            'sacado.telefone'   => 'nullable|string',
            'sacado.tipoPessoa' => 'required|string|in:FISICA,JURIDICA',
            'sacado.logradouro' => 'required|string',
            'sacado.numero'     => 'required|string',
            'sacado.complemento'=> 'nullable|string',
            'sacado.bairro'     => 'required|string',
            'sacado.cidade'     => 'required|string',
            'sacado.uf'         => 'required|string|size:2',
            'sacado.cep'        => 'required|string',

            'desconto'                 => 'nullable|array',
            'desconto.taxa'            => 'nullable|numeric',
            'desconto.codigo'          => 'nullable|string',
            'desconto.quantidadeDias'  => 'nullable|integer',

            'multa'                    => 'nullable|array',
            'multa.taxa'               => 'nullable|numeric',
            'multa.codigo'             => 'nullable|string',

            'mora'                     => 'nullable|array',
            'mora.taxa'                => 'nullable|numeric',
            'mora.codigo'              => 'nullable|string',

            'mensagem'                 => 'nullable|array',
            'mensagem.linha1'          => 'nullable|string',
            'mensagem.linha2'          => 'nullable|string',
            'mensagem.linha3'          => 'nullable|string',
            'mensagem.linha4'          => 'nullable|string',
            'mensagem.linha5'          => 'nullable|string',

            'beneficiarioFinal'                  => 'nullable|array',
            'beneficiarioFinal.nome'             => 'nullable|string',
            'beneficiarioFinal.cpfCnpj'          => 'nullable|string',
            'beneficiarioFinal.tipoPessoa'       => 'nullable|string|in:FISICA,JURIDICA',
            'beneficiarioFinal.logradouro'       => 'nullable|string',
            'beneficiarioFinal.numero'           => 'nullable|string',
            'beneficiarioFinal.complemento'      => 'nullable|string',
            'beneficiarioFinal.bairro'           => 'nullable|string',
            'beneficiarioFinal.cidade'           => 'nullable|string',
            'beneficiarioFinal.uf'               => 'nullable|string|size:2',
            'beneficiarioFinal.cep'              => 'nullable|string',
        ]);

        try {
            $result = $this->service->createBoleto($data);
        } catch (RuntimeException $e) {
            $msg = $e->getMessage();
            if (preg_match('/\{"title"/', $msg)) {
                $jsonStr = substr($msg, strpos($msg, '{'));
                $json = json_decode($jsonStr, true);
                if (isset($json['violacoes']) && is_array($json['violacoes'])) {
                    $formatted = [];
                    foreach ($json['violacoes'] as $v) {
                        $formatted[] = ($v['propriedade'] ?? '') . ': ' . ($v['razao'] ?? json_encode($v));
                    }
                    $msg = implode(' | ', $formatted);
                }
            }
            return redirect()->route('inter.boletos')
                ->withErrors(['error' => $msg])
                ->withInput()
                ->with('active_tab', 'emitir');
        }

        return redirect()->route('inter.boletos')
            ->with('boleto_data', $result)
            ->with('status', 'created')
            ->with('active_tab', 'emitir');
    }

    // Consulta de cobrança por códigoSolicitacao
    public function showCobranca(Request $request)
    {
        $request->validate([
            'codigoSolicitacao' => 'required|string',
        ]);
        $codigo = $request->input('codigoSolicitacao');
        try {
            $detalhes = $this->service->getCobranca($codigo);
        } catch (RuntimeException $e) {
            return redirect()->route('inter.boletos')
                ->withErrors(['error' => $e->getMessage()])
                ->with('active_tab', 'consultar');
        }
        return redirect()->route('inter.boletos')
            ->with('detalhes', $detalhes)
            ->with('active_tab', 'consultar');
    }

    // Listagem de cobranças com filtros: usa GET para permitir URLs /inter/boletos/listagem?dataInicio=...&dataFim=...&status=...
    public function listCobrancas(Request $request)
{
    $filters = $request->validate([
        'dataInicio' => 'nullable|date_format:Y-m-d',
        'dataFim'    => 'nullable|date_format:Y-m-d',
        'status'     => 'nullable|string',
    ]);
    try {
        $lista = $this->service->listCobrancas($filters);
    } catch (RuntimeException $e) {
        return redirect()->route('inter.boletos')
            ->withErrors(['error'=>$e->getMessage()])
            ->withInput()
            ->with('active_tab', 'listar');
    }
    return redirect()->route('inter.boletos')
        ->with('lista', $lista)
        ->with('active_tab', 'listar')
        ->withInput();
}


    // Ping de conexão
    public function pingConnection()
    {
        try {
            $token = $this->service->getToken();
        } catch (RuntimeException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
        return response()->json([
            'status'  => 'success',
            'message' => 'Conexão mTLS e OAuth2 válida.',
            'token_sample' => substr($token, 0, 10) . '...'
        ], 200);
    }

      public function downloadBoleto(Request $request)
    {
        $data = $request->validate([
            'codigoSolicitacao' => 'required|string',
        ]);

        session(['active_tab' => 'consultar']);

        try {
            $pdfBinary = $this->service->downloadBoletoPdf($data['codigoSolicitacao']);
        } catch (RuntimeException $e) {
            return back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }

        $fileName = 'boleto_' . $data['codigoSolicitacao'] . '.pdf';

        return response($pdfBinary, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    public function downloadPdf(Request $request)
    {
        $data = $request->validate([
            'codigoSolicitacao' => 'required|string',
        ]);
        $codigo = $data['codigoSolicitacao'];
        try {
            $pdfBase64 = $this->service->getBoletoPdf($codigo);
        } catch (RuntimeException $e) {
            session()->flash('active_tab', 'consultar');
            return back()->withErrors(['error' => 'Erro ao obter PDF: ' . $e->getMessage()]);
        }
        $binary = base64_decode($pdfBase64);
        if ($binary === false) {
            session()->flash('active_tab', 'consultar');
            return back()->withErrors(['error' => 'Erro ao decodificar PDF.']);
        }
        // Retorna download
        return response($binary, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="boleto_'.$codigo.'.pdf"');
    }
    
    public function pdfView(string $codigo)
{
    try {
        $base64 = $this->service->getCobrancaPdf($codigo);
        $pdfContent = base64_decode($base64);
        if ($pdfContent === false) {
            throw new RuntimeException('Falha ao decodificar Base64 do PDF.');
        }
    } catch (RuntimeException $e) {
        // Log ou abort conforme convenção
        abort(500, 'Erro ao obter PDF: ' . $e->getMessage());
    }

    return response($pdfContent, 200)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'inline; filename="boleto_'.$codigo.'.pdf"');
}

}
