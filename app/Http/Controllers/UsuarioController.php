<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ActivityLoggerService; // ← Importando o serviço de log
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    // Registro de atividade para a listagem de usuários
   public function index()
{
    if (auth()->user()->role !== 'admin') {
        return redirect()
            ->route('Inicio')
            ->with('error', 'Você não tem permissão para acessar essa página');
    }

    // Retorna todos os usuários ativos (sem paginação)
    $usuarios = User::where('ativo', 1)
                    ->orderBy('name')
                    ->get();

    ActivityLoggerService::registrar(
        'Usuário',
        'Abriu a página de Usuários.'
    );

    return view('usuarios.index', compact('usuarios'));
}


    // Registro de atividade para o cadastro de usuário
    public function create()
    {
        // Log de atividade
        ActivityLoggerService::registrar(
            'Usuários',
            'Abriu o formulário para cadastrar um novo usuário.'
        );

        return view('usuarios.cadastrar');
    }

    // Registro de atividade para armazenar um novo usuário
    public function store(Request $request)
    {
        // Validações
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'cpf'      => 'required|string|unique:users,cpf|size:11',
            'telefone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
            'role'     => 'required|in:admin,usuario',
        ], [
            'name.required'     => 'O nome é obrigatório.',
            'email.required'    => 'O e-mail é obrigatório.',
            'email.email'       => 'Digite um e-mail válido.',
            'email.unique'      => 'Este e-mail já está cadastrado.',
            'cpf.required'      => 'O CPF é obrigatório.',
            'cpf.size'          => 'O CPF deve ter exatamente 11 dígitos.',
            'cpf.unique'        => 'Este CPF já está cadastrado.',
            'password.required' => 'A senha é obrigatória.',
            'password.min'      => 'A senha deve ter no mínimo 6 caracteres.',
            'password.confirmed'=> 'A confirmação da senha não confere.',
            'role.required'     => 'O cargo é obrigatório.',
        ]);

        // Criar o usuário no banco
        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'cpf'      => $validated['cpf'],
            'telefone' => $request->telefone,
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
        ]);

        // Log de atividade
        ActivityLoggerService::registrar(
            'Usuários',
            'Criou um novo usuário com o nome ' . $user->name . ' e e-mail ' . $user->email
        );

        return redirect()->route('usuarios.create')->with('success', 'Usuário cadastrado com sucesso!');
    }

    // Registro de atividade para atualização de usuário
   public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    // Validação dos campos, incluindo endereço e telefone
    $request->validate([
        'name'        => 'required|string|max:255',
        'email'       => 'required|email|unique:users,email,' . $id,
        'role'        => 'required|in:cliente,admin,usuario',
        'telefone'    => 'nullable|string|max:20',
        'logradouro'  => 'required|string|max:255',
        'numero'      => 'required|string|max:255',
        'complemento' => 'nullable|string|max:255',
        'bairro'      => 'required|string|max:255',
        'cidade'      => 'required|string|max:255',
        'uf'          => 'required|string|max:2',
        'cep'         => 'required|string|max:20',
    ]);

    // Atualiza os dados do usuário, incluindo endereço
    $user->update([
        'name'        => $request->name,
        'email'       => $request->email,
        'role'        => $request->role,
        'telefone'    => $request->telefone,
        'logradouro'  => $request->logradouro,
        'numero'      => $request->numero,
        'complemento' => $request->complemento,
        'bairro'      => $request->bairro,
        'cidade'      => $request->cidade,
        'uf'          => strtoupper($request->uf),
        'cep'         => $request->cep,
    ]);

    // Log de atividade
    ActivityLoggerService::registrar(
        'Usuários',
        'Atualizou o usuário com ID ' . $user->id . ' para o novo nome ' . $user->name
    );

    return redirect()->route('Inicio')->with('success', 'Usuário atualizado com sucesso!');
}


    // Registro de atividade para deletar (desabilitar) usuário
    public function delete($id)
    {
        $user = User::findOrFail($id);

        // Desabilita o usuário
        $user->ativo = 0;
        $user->save();

        // Log de atividade
        ActivityLoggerService::registrar(
            'Usuários',
            'Desabilitou o usuário com ID ' . $user->id . ' e e-mail ' . $user->email
        );

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuário desabilitado com sucesso!');
    }

    // Registro de atividade para login
    public function loginForm()
    {
        
      

        return view('auth.login');
    }

    // Registro de atividade para autenticar login
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Log de atividade
            ActivityLoggerService::registrar(
                'Autenticação',
                'Usuário ' . Auth::user()->name . ' fez login com sucesso.'
            );

            if (auth()->user()->role === 'admin') {
                return redirect()->route('dashboard')
                    ->with('success', 'Login realizado com sucesso!');
            } else {
                return redirect()->route('Inicio')
                    ->with('success', 'Login realizado com sucesso!');
            }
        }

        // Log de atividade em caso de falha no login
        ActivityLoggerService::registrar(
            'Autenticação',
            'Falha na tentativa de login com o e-mail ' . $request->email
        );

        return back()->withErrors([
            'email' => 'As credenciais fornecidas não correspondem aos nossos registros.',
        ])->onlyInput('email');
    }

    // Registro de atividade para logout
    public function logout(Request $request)
    {
        // Log de atividade
        ActivityLoggerService::registrar(
            'Autenticação',
            'Usuário ' . Auth::user()->name . ' fez logout com sucesso.'
        );

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Você saiu com sucesso!');
    }

    // Registro de atividade para mostrar a página inicial do cliente
    public function ShowInicio()
    {
        // Log de atividade
        ActivityLoggerService::registrar(
            'Clientes',
            'Abriu a área do cliente.'
        );

        return view('cliente.area');
    }
}
