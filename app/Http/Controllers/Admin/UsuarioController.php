<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; // <-- Adicione esta linha
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::all();
        return view('admin.usuarios.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'cpf'      => 'required|string|unique:users,cpf|size:14', // CPF deve ser único e ter 14 caracteres
            'telefone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:admin,cliente',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'cpf'      => $request->cpf,
            'telefone' => $request->telefone,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Usuário cadastrado com sucesso!', 'user' => $user]);
        }

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuário criado com sucesso!');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
