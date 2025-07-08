<?php

namespace App\Http\Controllers;

use App\Models\Pagamento;
use App\Services\InterBoletoService;
use Illuminate\Http\Request;

class PagamentoController extends Controller
{
 
public function index(Request $request, InterBoletoService $inter)
{
    $codigo = $request->input('codigo');
    $boletos = [];

    try {
        if ($codigo) {
            // Busca individual
            $cobranca = $inter->getCobranca($codigo);
            if ($cobranca && isset($cobranca['codigoSolicitacao'])) {
                $boletos[] = [
                    'cobranca' => $cobranca,
                    'boleto'   => [], // Se quiser adicionar dados do boleto aqui
                    'pix'      => [],
                ];
            }
        } else {
            // Busca todos (últimos 90 dias)
            $filters = [
                'dataInicio' => now()->subDays(90)->format('Y-m-d'),
                'dataFim'    => now()->format('Y-m-d'),
            ];
            $apiResponse = $inter->listCobrancas($filters);
            $boletos = $apiResponse['cobrancas'] ?? [];
        }
    } catch (\Throwable $e) {
        session()->flash('error', 'Erro ao consultar API Inter: ' . $e->getMessage());
    }

    return view('pagamentos.index', compact('boletos', 'codigo'));
}





public function show($codigoSolicitacao, InterBoletoService $inter)
{
    try {
        // O método pode variar de nome, mas o seu service tem um para buscar detalhes:
        $cobranca = $inter->getCobranca($codigoSolicitacao);
    } catch (\Throwable $e) {
        return back()->with('error', 'Erro ao buscar boleto: ' . $e->getMessage());
    }

    // Retorne para uma view (ex: pagamentos/show.blade.php)
    return view('pagamentos.show', compact('cobranca'));
}



    public function create()
    {
        // Aqui você pode carregar contratos ou clientes para selecionar
        $contratos = \App\Models\Contrato::all();
        return view('pagamentos.create', compact('contratos'));
    }

    public function store(Request $request, InterBoletoService $inter)
    {
        $dados = $request->all();
        // Prepare o payload conforme o service espera!
        $retorno = $inter->createBoleto($dados);

        $pagamento = Pagamento::create([
            'contrato_id'        => $dados['contrato_id'],
            'vencimento'         => $dados['data_vencimento'],
            'valor'              => $dados['valor'],
            'nosso_numero'       => $dados['nosso_numero'],
            'codigo_solicitacao' => $retorno['codigoSolicitacao'],
            'json_resposta'      => json_encode($retorno['rawResponse']),
            // Preencha outros campos conforme necessário
        ]);

        return redirect()->route('pagamentos.index')->with('success', 'Pagamento (boleto) criado!');
    }

    public function edit(Pagamento $pagamento)
    {
        $contratos = \App\Models\Contrato::all();
        return view('pagamentos.edit', compact('pagamento', 'contratos'));
    }

    public function update(Request $request, Pagamento $pagamento)
    {
        $pagamento->update($request->only([
            'contrato_id', 'vencimento', 'valor', 'status', // etc
        ]));
        return redirect()->route('pagamentos.index')->with('success', 'Pagamento atualizado!');
    }

    public function destroy(Pagamento $pagamento)
    {
        $pagamento->delete();
        return redirect()->route('pagamentos.index')->with('success', 'Pagamento removido.');
    }

    public function showPix(Pagamento $pagamento, InterBoletoService $inter)
    {
        $cobranca = $inter->getCobranca($pagamento->codigo_solicitacao);
        $pix = $cobranca['pixCopiaECola'] ?? null;
        return view('pagamentos.pix', compact('pagamento', 'pix'));
    }
}
