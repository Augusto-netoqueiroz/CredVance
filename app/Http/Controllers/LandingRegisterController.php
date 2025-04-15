<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Password;
use App\Mail\VerificationMail;
use App\Mail\FinalizeRegistrationMail;


class LandingRegisterController extends Controller
{
    // FUNÇÕES DE CADASTRO / VERIFICAÇÃO

    public function index(Request $request)
    {
        // Retorna a página (Blade) que contém o formulário de cadastro
        return view('landing-page');
    }

    // Cria o usuário e gera um link de verificação
    public function submitRegister(Request $request)
    {
        // Validação dos campos
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'cpf'      => 'required|string|size:11|unique:users,cpf',
            'password' => 'required|min:6',
        ]);

        // Cria o usuário sem email verificado
        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'cpf'               => $request->cpf,
            'password'          => Hash::make($request->password),
            'email_verified_at' => null,
        ]);

        // Gera link de verificação assinado, com validade de 15 minutos
        $verificationLink = URL::temporarySignedRoute(
            'landing.verify',
            now()->addMinutes(15),
            ['user' => $user->id]
        );

        // Envia e-mail com o link de verificação usando a classe Mailable
        Mail::to($user->email)->send(new VerificationMail($verificationLink));

        // Retorna para a página com uma mensagem de sucesso
        return redirect()
            ->route('login')
            ->with('success', 'Cadastro realizado! Verifique seu e-mail e clique no link de verificação para acessar.');
    }

    // Verifica o link de e-mail e faz o login automático
    public function verifyAndLogin(Request $request)
    {
        // Recupera o usuário passando o parâmetro "user" na URL
        $user = User::find($request->user);

        if (!$user) {
            return redirect()
                ->route('landing.page')
                ->with('error', 'Usuário não encontrado!');
        }

        // Verifica se o link possui uma assinatura válida e não expirou
        if (!$request->hasValidSignature()) {
            return redirect()
                ->route('landing.page')
                ->with('error', 'Link de verificação inválido ou expirado.');
        }

        // Marca o e-mail como verificado
        $user->email_verified_at = now();
        $user->save();

        // Autentica o usuário automaticamente
        Auth::login($user);

        // Redireciona para a área logada (por exemplo, dashboard)
        return redirect()
            ->route('dashboard')
            ->with('success', 'E-mail verificado com sucesso! Você está logado.');
    }

   // ... (as funções de cadastro e verificação que você já tem)

    /**
     * Processa a solicitação de reset de senha: gera um link assinado e envia por e-mail.
     */
    public function sendResetPasswordLinkCustom(Request $request)
    {
        // Valida o e-mail informado no formulário de "Forgot Password"
        $request->validate([
            'email' => 'required|email',
        ]);

        // Busca o usuário pelo e-mail
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Usuário não encontrado.']);
        }

        // Gera um link de reset de senha assinado, com validade (por exemplo, 15 minutos)
        $resetLink = URL::temporarySignedRoute(
            'landing.reset',            // nome da rota que processará o reset
            now()->addMinutes(15),       // validade do link
            ['user' => $user->id]        // parâmetros da URL (ex: usuário)
        );

        // Envia um e-mail com esse link usando um Mailable customizado (PasswordResetMail)
        Mail::to($user->email)->send(new \App\Mail\PasswordResetMail($resetLink));

        return redirect()->route('login')
        ->with('status', 'Link de reset enviado! Verifique seu e-mail.');
    }




    public function sendResetPasswordLinkCustomSameView(Request $request)
{
    // Valida o e-mail informado
    $request->validate([
        'email' => 'required|email',
    ]);

    // Busca o usuário pelo e-mail
    $user = User::where('email', $request->email)->first();

    if (!$user) {
        // Retorna para a mesma view com mensagem de erro (validation errors)
        return back()->withErrors(['email' => 'Usuário não encontrado.']);
    }

    // Gera link de reset assinado (válido por 15 minutos, por exemplo)
    $resetLink = URL::temporarySignedRoute(
        'landing.reset',  // Nome da rota que processará o reset
        now()->addMinutes(15),
        ['user' => $user->id]  // Parâmetro user na URL
    );

    // Envia o e-mail com esse link usando seu Mailable customizado
    Mail::to($user->email)->send(new \App\Mail\PasswordResetMail($resetLink));

    // Retorna para a mesma view com mensagem de sucesso
    // 'status' ou 'success' como quiser
    return back()->with('status', 'Link de Redefinição enviado! Verifique seu e-mail.');
}



    public function showResetPasswordFormCustom(Request $request)
{
    // Verifica se existe usuário autenticado e desloga-o
    if (Auth::check()) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    // Verifica assinatura válida e se não expirou
    if (!$request->hasValidSignature()) {
        return redirect()->route('password.request')
                         ->with('error', 'Link inválido ou expirado.');
    }

    // Recupera o usuário da URL
    $user = User::find($request->user);
    if (!$user) {
        return redirect()->route('password.request')
                         ->with('error', 'Usuário não encontrado.');
    }

    // Exibe a view do formulário
    return view('auth.reset-password', [
        'token' => '', 
        'email' => $user->email,
    ]);
}

    




    public function showForgotPasswordPage()
    {
        return view('auth.forgot-password');
    }

    public function showRegisterPage()
    {
        return view('auth.register');
    }



    /**
     * Exibe a view para o usuário digitar a nova senha via link assinado.
     * Esse método é chamado quando o usuário clica no link recebido por e-mail.
     */
    public function showForgotPasswordForm(Request $request)
    {
        // Verifica se o link possui assinatura válida e não expirou
        if (!$request->hasValidSignature()) {
            return redirect()->route('password.request')
                             ->with('error', 'Link inválido ou expirado.');
        }
        
        // Recupera o usuário via parâmetro na URL
        $user = User::find($request->user);
        if (!$user) {
            return redirect()->route('password.request')
                             ->with('error', 'Usuário não encontrado.');
        }
    
        // Exibe a view de reset de senha (resources/views/auth/reset-password.blade.php)
        return view('auth.reset-password', ['email' => $user->email, 'user' => $user]);
    }
    

    /**
     * Processa o reset: recebe a nova senha e atualiza o usuário.
     */
    public function resetPasswordCustom(Request $request)
    {
        // Valida os dados enviados pelo formulário de reset
        $request->validate([
            'email'                 => 'required|email',
            'password'              => 'required|min:8|confirmed',
        ]);

        // Busca o usuário com base no e-mail (ou, se preferir, via ID passado oculto no formulário)
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return redirect()->route('landing.page')->with('error', 'Usuário não encontrado.');
        }

        // Atualiza a senha
        $user->password = Hash::make($request->password);
        $user->save();

        // Autentica o usuário
        Auth::login($user);

        // Redireciona para a área logada, por exemplo, dashboard
        return redirect()->route('dashboard')->with('success', 'Senha atualizada com sucesso! Você está logado.');
    }


    public function updateResetPassword(Request $request)
{
    // Valida os dados enviados pelo formulário de reset de senha
    $request->validate([
        'email'                 => 'required|email',
        'password'              => 'required|min:8|confirmed',
    ]);

    // Busca o usuário pelo e-mail
    $user = User::where('email', $request->email)->first();
    if (!$user) {
        return redirect()->route('landing.reset.form')
                         ->with('error', 'Usuário não encontrado.');
    }

    // Atualiza a senha do usuário
    $user->password = Hash::make($request->password);
    $user->save();

    // Autentica o usuário
    Auth::login($user);

    // Redireciona para o dashboard (ou para a área desejada) com mensagem de sucesso
    return redirect()->route('dashboard')
                     ->with('success', 'Senha atualizada com sucesso! Você está logado.');
}

public function storeBasicUser(Request $request)
{
    // Valida dados etc...
    $request->validate([
        'name'     => 'required|max:255',
        'email'    => 'required|email|unique:users,email',
        'cpf'      => 'required|string|size:11|unique:users,cpf',
        'telefone' => 'required|string|min:8',
        'role'     => 'required|string',
    ]);

    $randomPassword = \Str::random(10); 
    $user = User::create([
        'name'              => $request->name,
        'cpf'               => $request->cpf,
        'telefone'          => $request->telefone,
        'email'             => $request->email,
        'role'              => $request->role,
        'password'          => Hash::make($randomPassword),
        'email_verified_at' => null,
    ]);

    $finishLink = URL::temporarySignedRoute(
        'usuarios.finishRegister', 
        now()->addMinutes(30),
        ['user' => $user->id]
    );

    try {
        // Envie o e-mail com o link
        Mail::to($user->email)->send(new FinalizeRegistrationMail($finishLink));

        // Se chegar até aqui, deu tudo certo.
        return response()->json([
            'message' => 'Usuário criado com sucesso e link de finalização enviado!'
        ]);

    } catch (\Exception $e) {
        // Captura a exceção real do envio de email
        \Log::error('Erro ao enviar e-mail: ' . $e->getMessage());

        // Retorna 500 + a mensagem de erro (opcional)
        return response()->json([
            'message' => 'Usuário criado, mas ocorreu falha ao enviar e-mail: ' . $e->getMessage()
        ], 500);
    }
}

public function finishRegister(Request $request)
{
    if (!$request->hasValidSignature()) {
        abort(401, 'Link inválido ou expirado.');
    }

    $user = User::findOrFail($request->user);

    if ($user->email_verified_at) {
        return redirect()->route('login')->with('info', 'Cadastro já foi finalizado anteriormente.');
    }

    // Mostra a view para o usuário definir a senha / finalizar cadastro
    return view('auth.finish-register', compact('user'));
}

public function finishRegisterPost(Request $request, User $user)
{
    // Aqui você valida a nova senha e finaliza
    $request->validate([
        'password' => 'required|min:8|confirmed',
    ]);

    $user->password = Hash::make($request->password);
    $user->email_verified_at = now();
    $user->save();

    // Autentica ou só redireciona
    // Auth::login($user);
    return redirect()->route('dashboard')->with('success', 'Cadastro finalizado!');
}

}
