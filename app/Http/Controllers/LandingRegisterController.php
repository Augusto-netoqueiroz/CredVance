<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

// Importa a classe do Mailable (vamos criá-la depois)
use App\Mail\OtpMail;

class LandingRegisterController extends Controller
{
    public function index(Request $request)
    {
        // Retorna a página (Blade) que contém o formulário e o modal OTP
        return view('landing-page');
    }

    // Cria o usuário e gera OTP
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

        // Gera código OTP (6 dígitos)
        $otpCode = rand(100000, 999999);

        // Salva o OTP e expiração no mesmo registro do usuário
        $user->otp_code = $otpCode;
        $user->otp_expires_at = Carbon::now()->addMinutes(15);
        $user->save();

        // Envia e-mail usando a classe Mailable
        Mail::to($user->email)->send(new OtpMail($otpCode));

        // Retorna para a mesma página com flash data para abrir modal de OTP
        return redirect()
            ->route('landing.page')
            ->with('success', 'Cadastro realizado! Enviamos um código de verificação para seu e-mail.')
            ->with('show_otp_modal', true)
            ->with('user_id', $user->id);
    }

    // Verifica o OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'user_id'  => 'required|integer',
            'otp_code' => 'required|numeric',
        ]);

        $user = User::find($request->user_id);

        if (! $user) {
            return redirect()
                ->route('landing.page')
                ->with('error', 'Usuário não encontrado!');
        }

        if ($user->otp_code != $request->otp_code) {
            return redirect()
                ->route('landing.page')
                ->with('error', 'Código inválido. Tente novamente.')
                ->with('show_otp_modal', true)
                ->with('user_id', $user->id);
        }

        if ($user->otp_expires_at && $user->otp_expires_at < now()) {
            return redirect()
                ->route('landing.page')
                ->with('error', 'O código expirou. Solicite um novo cadastro.')
                ->with('show_otp_modal', true)
                ->with('user_id', $user->id);
        }

        // Marca email como verificado
        $user->email_verified_at = now();
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        return redirect()
            ->route('landing.page')
            ->with('success', 'E-mail verificado com sucesso!');
    }
}
