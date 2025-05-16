<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class NewPasswordController extends Controller
{
    /**
     * Exibe o formulário de redefinição de senha via link assinado.
     * Força logout imediato para garantir acesso.
     *
     * @param  Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showResetPasswordFormCustom(Request $request)
    {
        // Força logout em caso de usuário autenticado
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Valida assinatura do link
        if (! $request->hasValidSignature()) {
            return redirect()
                ->route('password.forgot')
                ->with('error', 'Link inválido ou expirado.');
        }

        // Recupera usuário pelo ID na URL
        $user = User::find($request->user);
        if (! $user) {
            return redirect()
                ->route('password.forgot')
                ->with('error', 'Usuário não encontrado.');
        }

        // Exibe formulário de redefinição
        return view('auth.reset-password', [
            'email' => $user->email,
            'user'  => $user,
        ]);
    }

    /**
     * Processa a redefinição de senha e redireciona para login.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPasswordCustom(Request $request)
    {
        // Validação dos dados do formulário
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Busca usuário pelo e-mail
        $user = User::where('email', $request->email)->first();
        if (! $user) {
            return redirect()
                ->route('password.forgot')
                ->withErrors(['email' => 'Usuário não encontrado.']);
        }

        // Atualiza senha
        $user->password = Hash::make($request->password);
        $user->save();

        // Redireciona para login explicitamente
        return redirect()
            ->route('login')
            ->with('success', 'Senha atualizada! Faça login com a nova senha.');
    }
}
