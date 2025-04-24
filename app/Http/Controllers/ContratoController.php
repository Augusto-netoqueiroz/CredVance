<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\Consorcio;
use App\Models\User;               // ← usamos o User aqui
use App\Services\PagamentoGenerator;
use Illuminate\Http\Request;

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

    /**
     * Armazena o contrato e gera as parcelas.
     */
    public function store(Request $request, PagamentoGenerator $generator)
    {
        $data = $request->validate([
            'cliente_id'       => 'required|exists:users,id',
            'consorcio_id'     => 'required|exists:consorcios,id',
            'quantidade_cotas' => 'required|integer|min:1',
        ]);

        $data['status'] = 'ativo';

        /** @var Contrato $contrato */
        $contrato = Contrato::create($data);

        // gera as parcelas (multiplicadas pela qtd de cotas)
        $generator->generate($contrato);

        return redirect()->route('dashboard')
                         ->with('success','Contrato criado com sucesso!');
    }
}
