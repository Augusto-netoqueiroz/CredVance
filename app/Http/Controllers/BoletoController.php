<?php

namespace App\Http\Controllers;

use App\Models\Pagamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Services\ActivityLoggerService;
use App\Services\InterBoletoService;
use App\Models\User;
use Illuminate\Support\Facades\Log;


class BoletoController extends Controller
{


     public function __construct(InterBoletoService $interService)
    {
        $this->interService = $interService;
    }


    private function checkAdmin()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Acesso negado.');
        }
    }

    public function showUploadForm(Pagamento $pagamento)
    {
        $this->checkAdmin();
        return view('boletos.upload', compact('pagamento'));
    }

    public function upload(Request $request, Pagamento $pagamento)
    {
        $this->checkAdmin();

        $request->validate([
            'boleto' => 'required|file|mimes:pdf|max:2048',
        ]);

        $cliente     = optional($pagamento->contrato->cliente)->name;
        $slugCliente = $cliente ? Str::slug($cliente, '-') : 'cliente';
        $filename    = "{$pagamento->id}_{$slugCliente}.pdf";

        $dir = (string) $pagamento->id;
        $fullPath = storage_path("app/boletos/{$dir}");
        if (! File::exists($fullPath)) {
            File::makeDirectory($fullPath, 0755, true);
        }

        $path = Storage::disk('boletos')->putFileAs(
            $dir,
            $request->file('boleto'),
            $filename
        );

        if (! $path) {
            abort(500, 'Falha ao salvar o boleto.');
        }

        $pagamento->boleto = $path;
        $pagamento->save();

        ActivityLoggerService::registrar('Boletos', "Enviou boleto para pagamento ID {$pagamento->id} (upload individual).");

        return redirect()->route('dashboard')->with('success', 'Boleto enviado com sucesso.');
    }

    public function download(Pagamento $pagamento)
    {
        $this->checkAdmin();

        if (! $pagamento->boleto || ! Storage::disk('boletos')->exists($pagamento->boleto)) {
            abort(404, 'Arquivo não encontrado.');
        }

        ActivityLoggerService::registrar('Boletos', "Baixou boleto do pagamento ID {$pagamento->id}.");

        return Storage::disk('boletos')->download(
            $pagamento->boleto,
            "boleto_{$pagamento->id}.pdf"
        );
    }

    public function manageForm(Request $request)
    {
        $this->checkAdmin();
    
        $perPage    = $request->input('per_page', 25);
        $clienteId  = $request->input('cliente');
        $status     = $request->input('status');
        $dataIni    = $request->input('data_ini');
        $dataFim    = $request->input('data_fim');
    
        $pagamentos = Pagamento::with('contrato.cliente')
            ->when($clienteId, function ($query, $clienteId) {
                $query->whereHas('contrato', function ($q) use ($clienteId) {
                    $q->where('cliente_id', $clienteId);
                });
            })
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($dataIni, fn($q) => $q->whereDate('vencimento', '>=', $dataIni))
            ->when($dataFim, fn($q) => $q->whereDate('vencimento', '<=', $dataFim))
            ->orderBy('vencimento')
            ->paginate($perPage)
            ->appends($request->query());
    
        return view('boletos.manage', compact('pagamentos'));
    }
    

    public function manageUpload(Request $request)
    {
        $this->checkAdmin();

        $request->validate([
            'pagamento_id' => 'required|exists:pagamentos,id',
            'boleto'       => 'required|file|mimes:pdf|max:2048',
        ]);

        $pagamento = Pagamento::findOrFail($request->pagamento_id);

        $cliente     = optional($pagamento->contrato->cliente)->name;
        $slugCliente = $cliente ? Str::slug($cliente, '-') : 'cliente';
        $filename    = "{$pagamento->id}_{$slugCliente}.pdf";

        $dir = (string) $pagamento->id;
        $fullPath = storage_path("app/boletos/{$dir}");
        if (! File::exists($fullPath)) {
            File::makeDirectory($fullPath, 0755, true);
        }

        if ($pagamento->boleto) {
            Storage::disk('boletos')->delete($pagamento->boleto);
        }

        $path = Storage::disk('boletos')->putFileAs(
            $dir,
            $request->file('boleto'),
            $filename
        );

        if (! $path) {
            abort(500, 'Falha ao salvar o boleto.');
        }

        $pagamento->boleto = $path;
        $pagamento->save();

        ActivityLoggerService::registrar('Boletos', "Atualizou boleto para pagamento ID {$pagamento->id} (upload via gerenciamento).");

        return redirect()->route('boleto.manage.form')->with('success', 'Boleto enviado com sucesso.');
    }

    public function marcarComoPago(Request $request)
    {
        $this->checkAdmin();

        $request->validate([
            'pagamento_id' => 'required|exists:pagamentos,id',
            'descricao'    => 'nullable|string|max:255',
            'comprovante'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $pagamento = Pagamento::findOrFail($request->pagamento_id);

        if ($request->hasFile('comprovante')) {
            $filename = 'comprovante_' . $pagamento->id . '.' . $request->file('comprovante')->getClientOriginalExtension();
            $path = $request->file('comprovante')->storeAs('private/comprovantes', $filename, 'local');
            $pagamento->comprovante = $path;
        }

        $pagamento->status = 'pago';
        $pagamento->save();

        ActivityLoggerService::registrar('Boletos', "Marcou pagamento ID {$pagamento->id} como pago. Descrição: " . $request->input('descricao'));

        return redirect()->route('boleto.manage.form')->with('success', 'Pagamento atualizado para PAGO com sucesso.');
    }

    public function baixarComprovante(Pagamento $pagamento)
{
    $this->checkAdmin();

    if (! $pagamento->comprovante || ! Storage::disk('local')->exists($pagamento->comprovante)) {
        abort(404, 'Comprovante não encontrado.');
    }

    // Log da atividade
    ActivityLoggerService::registrar(
        'Boletos',
        "Baixou comprovante do pagamento ID {$pagamento->id}."
    );

    return Storage::disk('local')->download(
        $pagamento->comprovante,
        "comprovante_{$pagamento->id}." . pathinfo($pagamento->comprovante, PATHINFO_EXTENSION)
    );
}



public function formBusca(Request $request)
{
    $this->checkAdmin();

    $pagamentos = Pagamento::with('contrato.cliente')
        ->whereNotNull('codigo_solicitacao') // opcional: apenas os que têm
        ->where('status', '!=', 'pago')
        ->orderByDesc('vencimento')
        ->limit(50)
        ->get();

    return view('boletos.remarcar', compact('pagamentos'));
}


public function buscar(Request $request)
{
    $request->validate(['codigo_solicitacao' => 'required|string']);
    try {
        $codigo = $request->input('codigo_solicitacao');
        $boleto = $this->interService->getCobranca($codigo);
        return response()->json($boleto);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Erro ao buscar boleto: ' . $e->getMessage()], 400);
    }
}

public function remarcar(Request $request)
{
    $request->validate([
        'codigo_solicitacao' => 'required|string',
        'nova_data' => 'required|date',
    ]);

    try {
        $codigo = $request->input('codigo_solicitacao');
        $novaData = $request->input('nova_data');

        $original = $this->interService->getCobranca($codigo);
        $cobranca = $original['cobranca'] ?? [];
        $pagadorOriginal = $cobranca['pagador'] ?? [];

        $valorNominal = isset($cobranca['valorNominal']) && $cobranca['valorNominal'] >= 2.5
            ? $cobranca['valorNominal']
            : 2.5;

        // Cancelar o boleto anterior com valor
        $this->interService->cancelarBoletoComValor($codigo, $valorNominal);

        // Garantir que endereço seja array válido
        $enderecoOriginal = $pagadorOriginal['endereco'] ?? [];
        $endereco = is_array($enderecoOriginal) ? $enderecoOriginal : [];

        // Preencher campos obrigatórios de endereço
        $endereco = [
            'logradouro' => $endereco['logradouro'] ?? 'Rua Não Informada',
            'numero'     => $endereco['numero'] ?? 'S/N',
            'bairro'     => $endereco['bairro'] ?? 'Centro',
            'cidade'     => $endereco['cidade'] ?? 'Cidade Desconhecida',
            'uf'         => $endereco['uf'] ?? 'SP',
            'cep'        => $endereco['cep'] ?? '00000000',
        ];

        // Preencher campos obrigatórios de sacado (pagador)
        $pagador = [
            'nome'       => $pagadorOriginal['nome'] ?? 'Cliente Desconhecido',
            'cpfCnpj'    => $pagadorOriginal['cpfCnpj'] ?? '00000000000',
            'tipoPessoa' => $pagadorOriginal['tipoPessoa'] ?? 'FISICA',
            'email'      => $pagadorOriginal['email'] ?? 'sememail@exemplo.com',
            'telefone'   => $pagadorOriginal['telefone'] ?? '11999999999',
            'endereco'   => $endereco,
        ];

        // Criar novo boleto
        $novo = $this->interService->createBoleto([
            'nosso_numero'     => $cobranca['seuNumero'] ?? '',
            'valor'            => $valorNominal,
            'data_vencimento'  => $novaData,
            'sacado'           => $pagador,
            'num_dias_agenda'  => 30,
        ]);

        $boletoFinal = $this->interService->getCobranca($novo['codigoSolicitacao']);

        return response()->json(['novoBoleto' => $boletoFinal]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Erro ao remarcar boleto: ' . $e->getMessage()], 400);
    }
}


public function criarBoletoForm()
{
    $clientes = User::whereNotNull('cpf')->where('ativo', true)->get();
    return view('boletos.novo', compact('clientes'));
}

public function dadosCliente($id)
{
    $cliente = User::findOrFail($id);

    return response()->json([
        'nome' => $cliente->name,
        'cpfCnpj' => $cliente->cpf,
        'email' => $cliente->email,
        'telefone' => $cliente->telefone,
        'logradouro' => $cliente->logradouro,
        'numero' => $cliente->numero,
        'complemento' => $cliente->complemento,
        'bairro' => $cliente->bairro,
        'cidade' => $cliente->cidade,
        'uf' => $cliente->uf,
        'cep' => $cliente->cep,
    ]);
}

public function criarBoleto(Request $request)
{
    $request->validate([
        'nosso_numero' => 'required|string',
        'valor' => 'required|numeric|min:1',
        'data_vencimento' => 'required|date|after_or_equal:today',
        'sacado.nome' => 'required|string',
        'sacado.cpfCnpj' => 'required|string',
        'sacado.tipoPessoa' => 'required|string',
        'sacado.logradouro' => 'required|string',
        'sacado.numero' => 'required|string',
        'sacado.bairro' => 'required|string',
        'sacado.cidade' => 'required|string',
        'sacado.uf' => 'required|string',
        'sacado.cep' => 'required|string',
    ]);

    try {
        $s = $request->input('sacado');

        $sacado = [
            'cpfCnpj'      => preg_replace('/\D/', '', $s['cpfCnpj']),
            'nome'         => $s['nome'],
            'email'        => $s['email'] ?? null,
            'telefone'     => preg_replace('/\D/', '', $s['telefone'] ?? ''),
            'ddd'          => preg_replace('/\D/', '', $s['ddd'] ?? ''),
            'tipoPessoa'   => strtoupper($s['tipoPessoa']),
            'cep'          => preg_replace('/\D/', '', $s['cep']),
            'logradouro'   => $s['logradouro'],
            'bairro'       => $s['bairro'],
            'cidade'       => $s['cidade'],
            'uf'           => strtoupper($s['uf']),
            'numero'       => $s['numero'],
            'complemento'  => $s['complemento'] ?? '',
        ];

        $dados = [
            'valor' => $request->input('valor'),
            'nosso_numero' => $request->input('nosso_numero'),
            'data_vencimento' => $request->input('data_vencimento'),
            'sacado' => $sacado,
            'num_dias_agenda' => (int) $request->input('num_dias_agenda', 30),
        ];

        Log::info('Payload final para createBoleto', $dados);

        $response = $this->interService->createBoleto($dados);
        $boleto = $this->interService->getCobranca($response['codigoSolicitacao']);

        return response()->json(['boleto' => $boleto]);

    } catch (\Exception $e) {
        Log::error('Erro ao criar boleto: ' . $e->getMessage(), [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);
        return response()->json(['error' => 'Erro ao criar boleto: ' . $e->getMessage()], 400);
    }
}


public function boletosPainel(Request $request)
{
    $this->checkAdmin();

    $clientes = User::where('ativo', true)->get();

    $pagamentos = Pagamento::with('contrato.cliente')
        ->when($request->filled('cliente'), function ($q) use ($request) {
            $q->whereHas('contrato', fn($qq) => $qq->where('cliente_id', $request->cliente));
        })
        ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
        ->when($request->filled('data_ini'), fn($q) => $q->whereDate('vencimento', '>=', $request->data_ini))
        ->when($request->filled('data_fim'), fn($q) => $q->whereDate('vencimento', '<=', $request->data_fim))
        ->orderByDesc('vencimento')
        ->paginate(20);

    return view('boletos.painel', compact('pagamentos', 'clientes'));
}

public function destroy(Pagamento $pagamento)
{
    $this->checkAdmin();

    try {
        $pagamento->delete();
        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
}


}