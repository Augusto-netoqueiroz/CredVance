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

// Importa a classe Mailable que usaremos para enviar o link de verificação (para cadastro)
use App\Mail\VerificationMail;

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



}