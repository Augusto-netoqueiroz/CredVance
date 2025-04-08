<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UsuarioController extends Controller
{

    public function index()
{
    // Filtra para retornar apenas usuários que tenham 'ativo' = 1
    $usuarios = User::where('ativo', 1)->get();

    return view('usuarios.index', compact('usuarios'));
}


    public function create()
    {
        return view('usuarios.cadastrar');
    }

    public function store(Request $request)
    {
        // Validações
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'cpf'      => 'required|string|unique:users,cpf|size:11', // CPF com 11 caracteres numéricos
            'telefone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed', // Adicionado "confirmed"
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
        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'cpf'      => $validated['cpf'],
            'telefone' => $request->telefone,
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
        ]);

        return redirect()->route('usuarios.create')->with('success', 'Usuário cadastrado com sucesso!');
    }


    public function update(Request $request, $id)
{
    try {
        // Localiza o usuário
        $user = User::findOrFail($id);

        // Validação
        $validatedData = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255',
            'telefone' => 'required|string|max:20',
            // CPF não é alterado, pois está readonly
        ]);

        // Atualiza somente os campos validados
        $user->update($validatedData);

        // Redireciona de volta com mensagem de sucesso (flash message)
        return redirect()->back()->with('success', 'Usuário atualizado com sucesso!');
    } catch (\Exception $e) {
        // Em caso de algum erro inesperado
        return redirect()->back()->with('error', 'Ocorreu um erro ao atualizar o usuário.');
    }
}






    public function delete($id)
    {
        // Carrega o usuário pelo ID
        $user = User::findOrFail($id);

        // Desabilita o usuário
        $user->ativo = 0;
        $user->save();

        // Redireciona para a lista de usuários (ou para onde quiser)
        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuário desabilitado com sucesso!');
    }


    public function loginForm()
    {
        return view('auth.login'); // Criaremos essa view
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard')->with('success', 'Login realizado com sucesso!');
        }

        return back()->withErrors([
            'email' => 'As credenciais fornecidas não correspondem aos nossos registros.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Você saiu com sucesso!');
    }
}
