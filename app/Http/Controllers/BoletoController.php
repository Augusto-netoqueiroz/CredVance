<?php

namespace App\Http\Controllers;

use App\Models\Pagamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BoletoController extends Controller
{
    /**
     * Verifica se o usuário é admin.
     */
    private function checkAdmin()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Acesso negado.');
        }
    }

    /**
     * Exibe formulário de upload para um pagamento.
     */
    public function showUploadForm(Pagamento $pagamento)
    {
        $this->checkAdmin();
        return view('boletos.upload', compact('pagamento'));
    }

    /**
     * Processa o upload único de boleto.
     */
    public function upload(Request $request, Pagamento $pagamento)
    {
        $this->checkAdmin();

        $request->validate([
            'boleto' => 'required|file|mimes:pdf|max:2048',
        ]);

        $cliente     = optional($pagamento->contrato->cliente)->name;
        $slugCliente = $cliente ? Str::slug($cliente, '-') : 'cliente';
        $filename    = "{$pagamento->id}_{$slugCliente}.pdf";

        // Garante que a pasta exista
        $dir = (string) $pagamento->id;
        $fullPath = storage_path("app/boletos/{$dir}");
        if (! File::exists($fullPath)) {
            File::makeDirectory($fullPath, 0755, true);
        }

        // Salva em pasta com o ID do pagamento
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

        return redirect()
            ->route('dashboard')
            ->with('success', 'Boleto enviado com sucesso.');
    }

    /**
     * Faz download do boleto associado a um pagamento.
     */
    public function download(Pagamento $pagamento)
    {
        $this->checkAdmin();

        if (! $pagamento->boleto || ! Storage::disk('boletos')->exists($pagamento->boleto)) {
            abort(404, 'Arquivo não encontrado.');
        }

        return Storage::disk('boletos')
            ->download(
                $pagamento->boleto,
                "boleto_{$pagamento->id}.pdf"
            );
    }

    /**
     * Página de gerenciamento de boletos.
     */
    public function manageForm()
    {
        $this->checkAdmin();

        $pagamentos = Pagamento::with('contrato.cliente')
            ->orderBy('vencimento')
            ->get();

        return view('boletos.manage', compact('pagamentos'));
    }

    /**
     * Processa upload pela página de gerenciamento.
     */
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

        // Garante que a pasta exista
        $dir = (string) $pagamento->id;
        $fullPath = storage_path("app/boletos/{$dir}");
        if (! File::exists($fullPath)) {
            File::makeDirectory($fullPath, 0755, true);
        }

        if ($pagamento->boleto) {
            Storage::disk('boletos')->delete($pagamento->boleto);
        }

        // Salva no disco 'boletos'
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

        return redirect()
            ->route('boleto.manage.form')
            ->with('success', 'Boleto enviado com sucesso.');
    }
}
