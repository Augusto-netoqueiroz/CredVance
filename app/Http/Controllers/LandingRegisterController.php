<?php

namespace App\Http\Controllers;

use App\Mail\FinalizeRegistrationMail;
use App\Mail\VerificationMail;
use App\Models\User;
use App\Services\ActivityLoggerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LandingRegisterController extends Controller
{
    /**
     * CATÁLOGO DE MÉTODOS:
     *
     * index()                         - Exibe a landing page com formulário de cadastro.
     * showRegisterPage()              - Exibe a página de registro personalizada.
     * submitRegister(Request)         - Valida e cria usuário, envia e-mail de verificação.
     * verifyAndLogin(Request)         - Verifica link assinado e faz login automático.
     * showForgotPasswordPage()        - Exibe a página de esqueci minha senha.
     * sendResetPasswordLinkCustom(Request)
     *                                  - Gera link de reset e envia e-mail (redirect).
     * sendResetPasswordLinkCustomSameView(Request)
     *                                  - Gera link de reset e envia e-mail (mesma view).
     * showForgotPasswordForm(Request) - Verifica assinatura e exibe formulário de nova senha.
     * showResetPasswordFormCustom(Request)
     *                                  - Garante logout, validacao de link e exibe reset password.
     * resetPasswordCustom(Request)    - Processa redefinição de senha e faz login.
     * updateResetPassword(Request)    - Alternativa para atualizar senha via formulário.
     * storeBasicUser(Request)         - Cria usuário básico e envia link de finalização.
     * finishRegister(Request)         - Exibe formulário para finalizar cadastro.
     * finishRegisterPost(Request)     - Processa definição de senha final e conclui cadastro.
     */

    /**
     * Exibe a landing page com formulário de cadastro.
     */
    public function index(Request $request)
    {
        return view('landing-page');
    }

    /**
     * Exibe a página de registro padrão (blade).
     */
    public function showRegisterPage()
    {
        return view('auth.register');
    }

    /**
     * Processa o registro: valida dados, cria usuário e envia e-mail de verificação.
     */
    public function submitRegister(Request $request)
{
    // 1. Capturar ID do parceiro (se houver)
    $parceiroId = $request->input('parceiro_id');


    // 2. Debug inicial: log do payload e parceiro
    Log::info('DEBUG submitRegister payload:', $request->all());
    Log::info('DEBUG parceiroId:', ['ref' => $parceiroId]);

    // 3. Validação dos campos básicos + extras
    $request->validate([
        'name'        => 'required|string|max:255',
        'email'       => 'required|email|unique:users,email',
        'cpf'         => 'required|string|size:11|unique:users,cpf',
        'telefone'    => 'required|string|min:8',
        'password'    => 'required|string|min:6|confirmed',

        // Campos de endereço:
        'cep'         => ['required','regex:/^\d{5}-?\d{3}$/'],
        'logradouro'  => ['required','string','max:255'],
        'numero'      => ['required','string','max:50'],
        'complemento' => ['required','string','max:255'],
        'bairro'      => ['required','string','max:255'],
        'cidade'      => ['required','string','max:255'],
        'uf'          => ['required','string','size:2'],
    ], [
        'cep.regex' => 'O CEP deve estar no formato 12345-678 ou 12345678.',
        'uf.size'   => 'A UF deve ter exatamente 2 caracteres.',
        'password.confirmed' => 'A confirmação da senha não confere.',
    ]);

    // 4. (Opcional) Validação se parceiro existe
    if ($parceiroId) {
        $parceiroExists = \App\Models\Parceiro::find($parceiroId);
        if (!$parceiroExists) {
            return back()->withErrors(['ref' => 'Parceiro inválido.'])->withInput();
        }
    }

    // 5. Sanitização / formatação
    $cpfDigits = preg_replace('/\D/', '', $request->input('cpf'));       
    $telefoneDigits = preg_replace('/\D/', '', $request->input('telefone'));
    $cepDigits = preg_replace('/\D/', '', $request->input('cep'));
    $cepFormatado = substr($cepDigits, 0, 5) . '-' . substr($cepDigits, 5, 3);
    $uf = strtoupper($request->input('uf'));

    // 6. Criação do usuário
    $user = User::create([
        'name'        => $request->input('name'),
        'email'       => $request->input('email'),
        'cpf'         => $cpfDigits,
        'telefone'    => $telefoneDigits,
        'password'    => Hash::make($request->input('password')),
        'cep'         => $cepFormatado,
        'logradouro'  => $request->input('logradouro'),
        'numero'      => $request->input('numero'),
        'complemento' => $request->input('complemento'),
        'bairro'      => $request->input('bairro'),
        'cidade'      => $request->input('cidade'),
        'uf'          => $uf,
        'parceiro_id' => $parceiroId, // vínculo com parceiro
    ]);

    Log::info('DEBUG user criado:', $user->toArray());

    // 7. Log de evento de cadastro (opcional)
    if ($parceiroId) {
        \App\Models\ParceiroAcesso::create([
            'parceiro_id' => $parceiroId,
            'evento' => 'cadastro',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }

    // 8. Geração do link de verificação por e-mail
    $verificationLink = URL::temporarySignedRoute(
        'landing.verify',
        now()->addMinutes(15),
        ['user' => $user->id]
    );

    // 9. Envio de e-mail
    try {
        Mail::to($user->email)->send(new VerificationMail($verificationLink));
    } catch (\Exception $e) {
        Log::error('Erro ao enviar VerificationMail: ' . $e->getMessage());
        // Decide se bloqueia ou apenas loga o erro
    }

    // 10. Redirecionar com mensagem de sucesso
    return redirect()
        ->route('login')
        ->with('success', 'Cadastro realizado! Verifique seu e-mail.');
}



    /**
     * Verifica assinatura do link de e-mail e autentica o usuário.
     */
    public function verifyAndLogin(Request $request)
    {
        $user = User::find($request->user);

        if (!$user) {
            return redirect()
                ->route('landing.page')
                ->with('error', 'Usuário não encontrado!');
        }

        if (!$request->hasValidSignature()) {
            return redirect()
                ->route('landing.page')
                ->with('error', 'Link inválido ou expirado.');
        }

        $user->email_verified_at = now();
        $user->save();

        Auth::login($user);

        return redirect()
            ->route('Inicio')
            ->with('success', 'E-mail verificado! Você está logado.');
    }

    /**
     * Exibe a página de "Esqueci Minha Senha".
     */
    public function showForgotPasswordPage()
    {
        return view('auth.forgot-password');
    }

    /**
     * Gera link de reset, envia e-mail e redireciona para login.
     */
    public function sendResetPasswordLinkCustom(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Usuário não encontrado.']);
        }

        $resetLink = URL::temporarySignedRoute(
            'landing.reset',
            now()->addMinutes(15),
            ['user' => $user->id]
        );

        Mail::to($user->email)->send(new \App\Mail\PasswordResetMail($resetLink));

        return redirect()->route('login')
            ->with('status', 'Link de reset enviado!');
    }

    /**
     * Gera link de reset, envia e-mail e retorna para a mesma view.
     */
    public function sendResetPasswordLinkCustomSameView(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Usuário não encontrado.']);
        }

        $resetLink = URL::temporarySignedRoute(
            'landing.reset',
            now()->addMinutes(15),
            ['user' => $user->id]
        );

        Mail::to($user->email)->send(new \App\Mail\PasswordResetMail($resetLink));

        return back()->with('status', 'Link de redefinição enviado!');
    }

    /**
     * Verifica assinatura e exibe o formulário de redefinição de senha.
     */
    public function showForgotPasswordForm(Request $request)
    {
        if (!$request->hasValidSignature()) {
            return redirect()->route('password.request')
                             ->with('error', 'Link inválido ou expirado.');
        }

        $user = User::find($request->user);
        if (!$user) {
            return redirect()->route('password.request')
                             ->with('error', 'Usuário não encontrado.');
        }

        return view('auth.reset-password', ['email' => $user->email, 'user' => $user]);
    }

   /**
 * Exibe o formulário de redefinição de senha via link assinado.
 * Força logout imediato para garantir middleware 'guest' / exibição correta.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
 */
public function showResetPasswordFormCustom(Request $request)
{
    // 1) Força logout imediato
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // 2) Verifica assinatura do link
    if (! $request->hasValidSignature()) {
        return redirect()
            ->route('password.request')
            ->with('error', 'Link inválido ou expirado.');
    }

    // 3) Busca o usuário pelo parâmetro da URL
    $user = User::find($request->user);
    if (! $user) {
        return redirect()
            ->route('password.request')
            ->with('error', 'Usuário não encontrado.');
    }

    // 4) Exibe a view de reset de senha
    return view('auth.reset-password', [
        'email' => $user->email,
        'user'  => $user,
    ]);
}


    /**
 * Processa a redefinição de senha e redireciona para o login.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\RedirectResponse
 */
public function resetPasswordCustom(Request $request)
{
    // Validação dos dados enviados pelo formulário de reset
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    // Busca o usuário com base no e-mail
    $user = User::where('email', $request->email)->first();
    if (! $user) {
        return redirect()
            ->route('password.request')
            ->withErrors(['email' => 'Usuário não encontrado.']);
    }

    // Atualiza a senha
    $user->password = Hash::make($request->password);
    $user->save();

    // Ao invés de logar automaticamente, redireciona para a tela de login
    return redirect()
        ->route('login')
        ->with('success', 'Senha atualizada com sucesso! Faça login com a nova senha.');
}

    /**
     * Alternativa para atualização de senha via formulário e login.
     */
    public function updateResetPassword(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return redirect()->route('landing.reset.form')
                             ->with('error', 'Usuário não encontrado.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        Auth::login($user);

        return redirect()->route('login')
                         ->with('success', 'Senha atualizada!');
    }

    /**
     * Cria usuário básico, gera link de finalização e registra atividade.
     */
    public function storeBasicUser(Request $request)
    {
        // 1. Validação
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'cpf'       => 'required|string|size:11|unique:users,cpf',
            'telefone'  => 'required|string|min:8',
            'role'      => 'required|string|in:' . implode(',', array_keys($this->availableRoles())),
            // Endereço:
            'cep'        => ['required','regex:/^\d{5}-?\d{3}$/'],
            'logradouro' => ['required','string','max:255'],
            'numero'     => ['required','string','max:50'],
            'complemento'=> ['required','string','max:255'],
            'bairro'     => ['required','string','max:255'],
            'cidade'     => ['required','string','max:255'],
            'uf'         => ['required','string','size:2'],
        ], [
            'cep.regex' => 'O CEP deve estar no formato 12345-678 ou 12345678.',
            'uf.size'   => 'A UF deve ter exatamente 2 caracteres.',
            // mensagens para outros campos, se quiser:
            'role.in'   => 'Role inválido.',
        ]);

        // 2. Sanitização / Formatação
        $cpfDigits = preg_replace('/\D/', '', $request->input('cpf'));       // 11 dígitos
        $telefoneDigits = preg_replace('/\D/', '', $request->input('telefone'));

        $cepRaw = $request->input('cep');
        $cepDigits = preg_replace('/\D/', '', $cepRaw);                     // 8 dígitos
        // Formatar como 12345-678:
        $cepFormatado = substr($cepDigits, 0, 5) . '-' . substr($cepDigits, 5, 3);

        $uf = strtoupper($request->input('uf'));

        // 3. (Opcional) Verificar CEP via ViaCEP no back-end
        // Descomente se quiser validação extra de existência:
        /*
        try {
            $resp = Http::timeout(5)->get("https://viacep.com.br/ws/{$cepDigits}/json/");
            if ($resp->failed() || isset($resp->json()['erro'])) {
                return redirect()->back()
                    ->withErrors(['cep' => 'CEP não encontrado ou inválido.'])
                    ->withInput();
            }
            $dataCep = $resp->json();
            // Se quiser sobrescrever campos com dados da API:
            // $logradouroApi = $dataCep['logradouro'] ?? null;
            // $bairroApi     = $dataCep['bairro'] ?? null;
            // $cidadeApi     = $dataCep['localidade'] ?? null;
            // $ufApi         = $dataCep['uf'] ?? null;
            // $logradouroFinal = $logradouroApi ?: $request->input('logradouro');
            // $bairroFinal     = $bairroApi ?: $request->input('bairro');
            // $cidadeFinal     = $cidadeApi ?: $request->input('cidade');
            // $uf = $ufApi ?: $uf;
        } catch (\Exception $e) {
            \Log::warning('ViaCEP indisponível ou timeout: ' . $e->getMessage());
            // Decide se bloqueia ou prossegue. Aqui, prosseguimos com o input manual.
        }
        */

        // 4. Geração de senha aleatória
        $randomPassword = Str::random(10);

        // 5. Criação de usuário
        $user = User::create([
            'name'              => $request->input('name'),
            'cpf'               => $cpfDigits,
            'telefone'          => $telefoneDigits,
            'email'             => $request->input('email'),
            'role'              => $request->input('role'),
            'password'          => Hash::make($randomPassword),
            'email_verified_at' => null,

            // Campos de endereço
            'cep'        => $cepFormatado,
            'logradouro' => $request->input('logradouro'),
            'numero'     => $request->input('numero'),
            'complemento'=> $request->input('complemento'),
            'bairro'     => $request->input('bairro'),
            'cidade'     => $request->input('cidade'),
            'uf'         => $uf,
        ]);

        // 6. Link temporário para finalizar cadastro (definir senha, etc.)
        $finishLink = URL::temporarySignedRoute(
            'usuarios.finishRegister',           // ajuste para sua rota de finalização
            now()->addMinutes(30),
            ['user' => $user->id]
        );

        // 7. Envio de e-mail e registro de atividade
        try {
            Mail::to($user->email)->send(new FinalizeRegistrationMail($finishLink));

            ActivityLoggerService::registrar(
                'Usuários',
                'Criou novo usuário ID ' . $user->id . ' Nome ' . $user->name
            );

            return redirect()->back()
                             ->with('success', 'Usuário criado e link enviado!');
        } catch (\Exception $e) {
            \Log::error('Erro ao enviar e-mail: ' . $e->getMessage());
            return redirect()->back()
                             ->with('error', 'Usuário criado, mas falha ao enviar e-mail.');
        }
    }
    /**
     * Exibe o formulário para o usuário finalizar cadastro definindo senha.
     */
    public function finishRegister(Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(401, 'Link inválido ou expirado.');
        }

        $user = User::findOrFail($request->user);

        if ($user->email_verified_at) {
            return redirect()->route('login')
                             ->with('info', 'Cadastro já concluído.');
        }

        return view('auth.finish-register', compact('user'));
    }

    /**
     * Processa definição de senha final e conclui cadastro.
     */
    public function finishRegisterPost(Request $request, User $user)
    {
        $request->validate(['password' => 'required|min:8|confirmed']);

        $user->password = Hash::make($request->password);
        $user->email_verified_at = now();
        $user->save();

        return redirect()->route('Inicio')
                         ->with('success', 'Cadastro finalizado!');
    }

    private function availableRoles()
{
    return [
        'cliente'   => 'Cliente',
        'admin'     => 'Administrador',
        'parceiro'  => 'Parceiro',
    ];
}

}
