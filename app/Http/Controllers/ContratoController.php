<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\Consorcio;
use App\Models\User;               // ← usamos o User aqui
use App\Services\PagamentoGenerator;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ContratoController extends Controller
{
    /**
     * Mostra o formulário de criação de contrato.
     */
    public function create()
    {
        // busca apenas usuários com role = 'cliente'
        $clientes = User::where('role', 'cliente')
                         ->orderBy('name')
                         ->get();

        $consorcios = Consorcio::all();

        return view('contratos.create', compact('clientes','consorcios'));
    }

 


public function store(Request $request)
{
    $request->validate([
        'cliente_id'       => 'required|exists:users,id',
        'consorcio_id'     => 'required|exists:consorcios,id',
        'quantidade_cotas' => 'required|integer|min:1',
        'senha_confirm'    => 'required',
        'navegador_info'   => 'required',
        'resolucao_tela'   => 'required',
        'latitude'         => 'required',
        'longitude'        => 'required',
        'data_aceite'      => 'required|date',
    ]);

    if (!Hash::check($request->senha_confirm, Auth::user()->password)) {
        return back()->withErrors(['senha_confirm' => 'Senha inválida.']);
    }

    // Salva o contrato
    $contrato = Contrato::create([
        'cliente_id'       => $request->cliente_id,
        'consorcio_id'     => $request->consorcio_id,
        'quantidade_cotas' => $request->quantidade_cotas,
        'aceito_em' => \Carbon\Carbon::parse($request->data_aceite)->format('Y-m-d H:i:s'),
        'ip'               => $request->ip(),
        'navegador_info'   => $request->navegador_info,
        'resolucao'        => $request->resolucao_tela,
        'latitude'         => $request->latitude,
        'longitude'        => $request->longitude,
    ]);

    // Gera cobranças
    $this->gerarPagamentos($contrato);

    // Gera e salva PDF
    $pdf = Pdf::loadView('pdf.contrato', ['contrato' => $contrato]);
    $path = "contratos/contrato_{$contrato->id}.pdf";
    Storage::put($path, $pdf->output());

    $contrato->update(['pdf_contrato' => $path]);

    return redirect()->route('Inicio')->with('success', 'Contrato Criado !! Bem vindo a Credvance.');
}



public function gerarPagamentos(Contrato $contrato): void
{
    $qtdCotas = $contrato->quantidade_cotas;
    $inicio   = now()->startOfMonth();
    $pagamentos = [];

    $valorAtual = 155; // 1ª parcela
    $reducao    = 5;
    $prazo      = 12;

    for ($i = 0; $i < $prazo; $i++) {
        $valorFinal = $valorAtual * $qtdCotas;

        $pagamentos[] = [
            'contrato_id' => $contrato->id,
            'vencimento'  => $inicio->copy()->addMonths($i),
            'valor'       => $valorFinal,
            'status'      => 'pendente',
            'created_at'  => now(),
            'updated_at'  => now(),
        ];

        $valorAtual -= $reducao;
        if ($valorAtual < 100) $valorAtual = 100; // trava mínima
    }

    \App\Models\Pagamento::insert($pagamentos);

    \Log::info('Pagamentos com parcelas decrescentes gerados', [
        'contrato_id' => $contrato->id,
        'quantidade_cotas' => $qtdCotas,
        'total_parcelas' => $prazo
    ]);
}



}
