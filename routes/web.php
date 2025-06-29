<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\LandingRegisterController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
 
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\CobrancasController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\BoletoController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\NewPasswordController;
use App\Http\Controllers\BoletoTestController;
use App\Http\Controllers\InterBoletoTestController;
use App\Http\Controllers\InterWebhookController;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
 






Route::post('/verifica-senha', function (Illuminate\Http\Request $request) {
    return Hash::check($request->senha, auth()->user()->password)
        ? response()->json(['ok' => true])
        : response()->json(['ok' => false], 401);
})->middleware('auth');


//------------------inicio das rotas não protegidas-----------------// 


//View inicial
Route::get('/', function () {
    return view('pagina');
});

//Template prinicial - rota por enquanto
route::get('/pagina', function () {
    return view('pagina');
});

//Template prinicial - rota por enquanto
route::get('/pagina2', function () {
    return view('paginanova');
});

//Template service - rota por enquanto
route::get('/Service', function () {
    return view('service');
});


//Template service - rota por enquanto
route::get('/reset', function () {
    return view('auth.reset-password');
});




//Template service - rota por enquanto
//route::get('/register', function () {
    //return view('auth.register');
//});


Route::get('/register', [LandingRegisterController::class, 'showRegisterPage'])->name('register.page');

Route::post('/register', [LandingRegisterController::class, 'submitRegister'])
    ->middleware('guest')
    ->name('register');


Route::get('/login', [UsuarioController::class, 'loginForm'])->name('login');
Route::post('/login', [UsuarioController::class, 'authenticate'])->name('login.authenticate');


//------------------Fim das rotas não protegidas-----------------// 
 



Route::middleware('auth')->group(function () {
     Route::get('/activity-logs', [ActivityLogController::class, 'index'])
          ->name('activity_logs.index');
});


Route::middleware('auth')->group(function () {
    Route::get('/Inicio', [ClienteController::class, 'index'])->name('Inicio');
});

// Dentro do grupo de middleware 'auth' / 'verified', conforme seu projeto
Route::middleware(['auth', 'verified'])->group(function() {
    // Outras rotas do cliente...

    // Rota para download do boleto
    Route::get('/pagamentos/{pagamento}/download-boleto', [ClienteController::class, 'downloadBoleto'])
        ->name('pagamentos.download-boleto');

    // Rota para AJAX data, por exemplo:
    Route::get('/cliente/data', [ClienteController::class, 'data'])->name('cliente.data');
    // Rota para download de contrato:
    Route::get('/cliente/contrato/{contrato}/download', [ClienteController::class, 'downloadContrato'])
        ->name('cliente.contrato.download');
});

Route::get('/cliente/data', [ClienteController::class, 'data'])
     ->name('cliente.data')
     ->middleware(['auth','verified']);



Route::middleware('auth')->group(function () {
Route::get('/usuarios', [UsuarioController::class, 'index'])
->middleware(['auth', 'verified'])
->name('usuarios.index');
Route::get('/usuarios/cadastrar', [UsuarioController::class, 'create'])->name('usuarios.create');
Route::post('/cadastrar/salvar', [UsuarioController::class, 'store'])->name('usuarios.store');
Route::post('/usuarios/{id}/delete', [UsuarioController::class, 'delete'])->name('usuarios.delete');
Route::put('/usuario/editar/{id}', [UsuarioController::class, 'update'])->name('usuario.update');
});


Route::get('/dashboard', [DashboardController::class, 'index'])
     ->middleware(['auth', 'verified'])
     ->name('dashboard');


     Route::middleware(['auth'])->prefix('dashboard')->group(function () {
        Route::get('/detalhes/cotas', [DashboardController::class, 'detalhesCotas'])->name('dashboard.detalhes.cotas');
        Route::get('/detalhes/contratos', [DashboardController::class, 'detalhesContratos'])->name('dashboard.detalhes.contratos');
        Route::get('/detalhes/faturamento', [DashboardController::class, 'detalhesFaturamento'])->name('dashboard.detalhes.faturamento');
        Route::get('/detalhes/pendentes', [DashboardController::class, 'detalhesPendentes'])->name('dashboard.detalhes.pendentes');
    });

Route::get('/finalizar-cadastro', [LandingRegisterController::class, 'finishRegister'])
     ->middleware('signed')
     ->name('usuarios.finishRegister');

     Route::post('/finalizar-cadastro/{user}', [LandingRegisterController::class, 'finishRegisterPost'])
     ->name('usuarios.finishRegister.post');

     Route::post('/usuarios/store-basic', [LandingRegisterController::class, 'storeBasicUser'])
     ->name('usuarios.storeBasic');




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/landing', [LandingRegisterController::class, 'index'])->name('landing.page');

// Recebe os dados do formulário de cadastro (POST), gera OTP e retorna a mesma view
Route::post('/landing/register', [LandingRegisterController::class, 'submitRegister'])
     ->name('landing.register.submit');

// Recebe o OTP (POST) do modal, valida e confirma o email
Route::post('/landing/verify-otp', [LandingRegisterController::class, 'verifyOtp'])
     ->name('landing.register.verify');


     Route::get('/landing', [LandingRegisterController::class, 'index'])->name('landing.page');
Route::post('/landing/register', [LandingRegisterController::class, 'submitRegister'])->name('landing.register.submit');

// Rota para verificação via link assinado
Route::get('/landing/verify', [LandingRegisterController::class, 'verifyAndLogin'])->name('landing.verify');


    // Fluxo customizado de Reset de Senha:
    //Route::get('/forgot-password', [LandingRegisterController::class, 'showForgotPasswordForm'])
    //->middleware('guest')
    //->name('password.forgot');  // Nome customizado, se preferir

        //Template service - rota por enquanto
        Route::get('/forgot-password', [LandingRegisterController::class, 'showForgotPasswordPage'])
        ->middleware('guest')
        ->name('password.forgot');




Route::post('/forgot-password', [LandingRegisterController::class, 'sendResetPasswordLinkCustom'])
    ->middleware('guest')
    ->name('password.email'); // Mantém o mesmo nome se o template usar essa rota


    Route::post('/forgot-password-auth', [LandingRegisterController::class, 'sendResetPasswordLinkCustomSameView'])
    ->name('password.email.auth'); // Mantém o mesmo nome se o template usar essa rota

 
// Rotas públicas de reset de senha (fora de qualquer grupo 'guest'):
Route::get('/reset-password', 
    [NewPasswordController::class, 'showResetPasswordFormCustom'])
    ->middleware('signed')
    ->name('landing.reset');

Route::post('/reset-password',
    [NewPasswordController::class, 'resetPasswordCustom'])
    ->middleware('guest')
    ->name('password.update.custom');

// Rota para processar a nova senha
//Route::post('/reset-password', [LandingRegisterController::class, 'resetPasswordCustom'])
   // ->middleware('guest')
//->name('password.update.custom');


    Route::post('/landing/reset-password/update', [LandingRegisterController::class, 'updateResetPassword'])
    ->middleware('guest')
    ->name('landing.reset.update');



    Route::middleware('guest')->group(function () {
        //Route::get('register', [RegisteredUserController::class, 'create'])
       // ->name('register');

    //Route::post('register', [RegisteredUserController::class, 'store']);

    //Route::get('login', [AuthenticatedSessionController::class, 'create'])
        //->name('login');

    //Route::post('login', [AuthenticatedSessionController::class, 'store']);


    Route::post('/forgot-password', [LandingRegisterController::class, 'sendResetPasswordLinkCustom'])
  
    ->name('password.email');



    Route::post('/landing/reset-password/update', [LandingRegisterController::class, 'updateResetPassword'])
    ->middleware('guest')
    ->name('landing.reset.update');



//Route::get('/reset-password', [LandingRegisterController::class, 'showForgotPasswordForm'])
  //  ->middleware('guest')
    //->name('password.reset'); 

// Rota do link de reset (gerado por URL::temporarySignedRoute)
//Route::get('/reset-password', 
//    [LandingRegisterController::class, 'showResetPasswordFormCustom'])
//    ->middleware('signed')
//    ->name('landing.reset');


    // Rota para processar a nova senha
//Route::post('/reset-password', [LandingRegisterController::class, 'resetPasswordCustom'])
//->middleware('guest')
//->name('password.update.custom');


    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});





//---------------------------------------


Route::middleware(['auth'])->group(function () {
    // somente create e store para contratos
    Route::resource('contratos', ContratoController::class)
         ->only(['create','store']);
});



// Form de upload de boleto
Route::get('boleto/upload/{pagamento}', [BoletoController::class, 'showUploadForm'])
    ->name('boleto.upload.form')
    ->middleware('auth');

// Processar upload
Route::post('boleto/upload/{pagamento}', [BoletoController::class, 'upload'])
    ->name('boleto.upload')
    ->middleware('auth');

// Download do boleto
Route::get('boleto/download/{pagamento}', [BoletoController::class, 'download'])
    ->name('boleto.download')
    ->middleware('auth');


    Route::middleware(['auth'])->group(function () {
        // Página de gerenciamento
        Route::get('boleto/gerenciar', [BoletoController::class, 'manageForm'])
            ->name('boleto.manage.form');
        Route::post('boleto/gerenciar', [BoletoController::class, 'manageUpload'])
            ->name('boleto.manage.upload');
    });



    Route::get('/cliente/contrato/{contrato}/download', [ClienteController::class, 'downloadContrato'])
    ->name('cliente.contrato.download')
    ->middleware('auth');


    Route::post('/boletos/status/pago', [BoletoController::class, 'marcarComoPago'])
    ->name('boleto.marcar.pago')
    ->middleware('auth');

    Route::get('/boletos/comprovante/{pagamento}', [BoletoController::class, 'baixarComprovante'])
    ->name('boleto.comprovante.download')
    ->middleware('auth');


    //teste

 // rota atual para teste via formulário
Route::get('/boleto-test',  [BoletoTestController::class, 'showForm'])->name('boleto.form');
Route::post('/boleto-test', [BoletoTestController::class, 'createBoleto'])->name('boleto.create');

 
Route::get('/boleto-auto',  [BoletoTestController::class, 'generateAuto'])->name('boleto.auto');

Route::get('/debug-payments', [BoletoTestController::class, 'debugPayments'])
     ->name('debug.payments');



// Formulário de teste
Route::get('/inter-boleto-test', [InterBoletoTestController::class, 'showForm'])
    ->name('inter.boleto.form');

// Geração de boleto
Route::post('/inter-boleto-test', [InterBoletoTestController::class, 'createBoleto'])
    ->name('inter.boleto.create');

// Ping de conexão mTLS + OAuth2
Route::get('/inter-boleto-ping', [InterBoletoTestController::class, 'pingConnection'])
    ->name('inter.boleto.ping');

    Route::get('/inter/ping', [InterBoletoTestController::class, 'pingConnection']);
Route::get('/inter/form', [InterBoletoTestController::class, 'showForm']);
Route::post('/inter/boletos', [InterBoletoTestController::class, 'createBoleto']);



// Mostrar formulário de emissão de cobrança
Route::get('/inter/boletos', [InterBoletoTestController::class, 'showForm'])->name('inter.boletos.form');
// Enviar formulário para criar cobrança
Route::post('/inter/boletos', [InterBoletoTestController::class, 'createBoleto'])->name('inter.boletos.create');

// Formulário de consulta de cobrança pelo código
Route::get('/inter/boletos/consulta', function(){
    return view('inter_boleto_consulta');
})->name('inter.boletos.consulta.form');
// Submissão para consultar
Route::post('/inter/boletos/consulta', [InterBoletoTestController::class, 'showCobranca'])->name('inter.boletos.consulta');

// Listagem de cobranças: exibe formulário com filtros
Route::get('/inter/boletos/listagem', function(){
    return view('inter_boleto_listagem_form');
})->name('inter.boletos.listagem.form');
// Submissão dos filtros para listar
Route::get('/inter/boletos/listagem/result', [InterBoletoTestController::class, 'listCobrancas'])->name('inter.boletos.listagem.result');

// Ping/health check
Route::get('/inter/ping', [InterBoletoTestController::class, 'pingConnection'])->name('inter.ping');
 

Route::post('/inter/webhook/cobranca', [InterWebhookController::class, 'handleCobranca'])
     ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);



     Route::get('/inter/boletos', [InterBoletoTestController::class, 'showForm'])->name('inter.boletos');
Route::post('/inter/boletos/create', [InterBoletoTestController::class, 'createBoleto'])->name('inter.boletos.create');
Route::post('/inter/boletos/consulta', [InterBoletoTestController::class, 'showCobranca'])->name('inter.boletos.consulta');
Route::get('/inter/boletos/listagem', [InterBoletoTestController::class, 'listCobrancas'])->name('inter.boletos.listagem');

Route::post('/inter/boletos/download', [InterBoletoTestController::class, 'downloadBoleto'])
    ->name('inter.boletos.download');

      // Visualização inline do PDF do boleto (GET)
    Route::get('/{codigo}/pdf', [InterBoletoTestController::class, 'pdfView'])
        ->where('codigo', '[0-9a-fA-F\-]+')
        ->name('inter.boletos.pdfview');

    // Download forçado do PDF do boleto (POST)
    Route::post('/download', [InterBoletoTestController::class, 'downloadPdf'])
        ->name('inter.boletos.download');
        
        Route::get('/inter/boletos/{codigo}/pdf', [InterBoletoTestController::class, 'pdfView'])
    ->name('inter.boletos.pdfview');


    Route::get('/teste-pdf-contrato', function() {
    // Opção A: usar um contrato existente (se tiver pelo menos um no banco)
    // $contratoReal = \App\Models\Contrato::with(['cliente','consorcio','pagamentos'])->first();
    // if ($contratoReal) {
    //     $frameBase64 = null;
    //     $path = public_path('assets/img/moldura-contrato.jpg');
    //     if (file_exists($path)) {
    //         $type = pathinfo($path, PATHINFO_EXTENSION);
    //         $data = @file_get_contents($path);
    //         if ($data !== false) {
    //             $frameBase64 = 'data:image/'.$type.';base64,'.base64_encode($data);
    //         }
    //     }
    //     $pdf = Pdf::loadView('pdf.contrato', [
    //         'contrato'    => $contratoReal,
    //         'pagamentos'  => $contratoReal->pagamentos()->orderBy('vencimento')->get(),
    //         'frameBase64' => $frameBase64,
    //     ]);
    //     return $pdf->stream('teste_contrato_real.pdf');
    // }

    // Opção B: criar um objeto “fake” em memória:
    $clienteFake = new \stdClass();
    $clienteFake->name = 'Cliente Teste';
    $clienteFake->cpf = '000.000.000-00';
    // Se a view acessa campos adicionais (logradouro, etc.), você pode definir como vazio ou dummy:
    // $clienteFake->logradouro = 'Rua X'; ...

    $consorcioFake = new \stdClass();
    $consorcioFake->plano = 'Plano Teste';
    $consorcioFake->prazo = 12;
    // definir outros campos necessários se a view os utilizar

    $contratoFake = new \stdClass();
    $contratoFake->id = 123;
    $contratoFake->aceito_em = Carbon::now()->toDateTimeString();
    $contratoFake->ip = '127.0.0.1';
    $contratoFake->cliente = $clienteFake;
    $contratoFake->consorcio = $consorcioFake;
    $contratoFake->quantidade_cotas = 1;
    // Caso seja acessado $contrato->latitude, longitude, etc., defina também:
    // $contratoFake->latitude = '...'; $contratoFake->longitude = '...';

    // Pagamentos: se a view não itera pagamentos, passe empty collection; 
    // se iterar, crie uma Collection com objetos que satisfaçam o loop:
    $pagamentosFake = collect();
    // Exemplo de adicionar um pagamento fake:
    // $pag1 = new \stdClass();
    // $pag1->vencimento = Carbon::now()->addMonth();
    // $pag1->valor = 100;
    // $pag1->status = 'pendente';
    // $pagamentosFake->push($pag1);

    // Monta frameBase64
    $frameBase64 = null;
    $path = public_path('assets/img/moldura-contrato.jpg');
    if (file_exists($path)) {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = @file_get_contents($path);
        if ($data !== false) {
            $frameBase64 = 'data:image/'.$type.';base64,'.base64_encode($data);
        }
    }

    $pdf = Pdf::loadView('pdf.contrato', [
        'contrato'    => $contratoFake,
        'pagamentos'  => $pagamentosFake,
        'frameBase64' => $frameBase64,
    ]);
    // Exibe no navegador:
    return $pdf->stream('teste_contrato_fake.pdf');
});