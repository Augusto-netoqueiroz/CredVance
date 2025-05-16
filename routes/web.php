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