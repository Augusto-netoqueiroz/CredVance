<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::with('user')->get();
        return view('admin.clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('admin.clientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'cpf' => 'required|unique:clientes',
            'telefone' => 'required',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'cliente',
        ]);

        Cliente::create([
            'user_id' => $user->id,
            'cpf' => $request->cpf,
            'telefone' => $request->telefone,
            'endereco' => $request->endereco,
        ]);

        return redirect()->route('admin.clientes.index')->with('success', 'Cliente cadastrado com sucesso.');
    }

    public function edit($id)
    {
        $cliente = Cliente::with('user')->findOrFail($id);
        return view('admin.clientes.edit', compact('cliente'));
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);
        $user = $cliente->user;

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'cpf' => 'required|unique:clientes,cpf,' . $cliente->id,
            'telefone' => 'required',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $cliente->update([
            'cpf' => $request->cpf,
            'telefone' => $request->telefone,
            'endereco' => $request->endereco,
        ]);

        return redirect()->route('admin.clientes.index')->with('success', 'Cliente atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->user()->delete(); // Deleta o user automaticamente
        $cliente->delete();

        return redirect()->route('admin.clientes.index')->with('success', 'Cliente removido com sucesso.');
    }
}
