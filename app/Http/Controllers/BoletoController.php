<?php

namespace App\Http\Controllers;

use App\Models\Pagamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Services\ActivityLoggerService;

class BoletoController extends Controller
{
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

}
