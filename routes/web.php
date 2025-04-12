<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\LandingRegisterController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;






//View inicial
Route::get('/', function () {
    return view('construcao');
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


//View creat
Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
Route::get('/usuarios/cadastrar', [UsuarioController::class, 'create'])->name('usuarios.create');
Route::post('/cadastrar/salvar', [UsuarioController::class, 'store'])->name('usuarios.store');
Route::post('/usuarios/{id}/delete', [UsuarioController::class, 'delete'])->name('usuarios.delete');
Route::patch('/usuario/{id}', [UsuarioController::class, 'update'])->name('usuario.update');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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

// Rota para o link de reset (usado no e-mail)
Route::get('/reset-password', [LandingRegisterController::class, 'showResetPasswordFormCustom'])
    ->middleware('guest')
    ->name('landing.reset');



// Rota para processar a nova senha
Route::post('/reset-password', [LandingRegisterController::class, 'resetPasswordCustom'])
    ->middleware('guest')
    ->name('password.update.custom');


    Route::post('/landing/reset-password/update', [LandingRegisterController::class, 'updateResetPassword'])
    ->middleware('guest')
    ->name('landing.reset.update');



    Route::middleware('guest')->group(function () {
        //Route::get('register', [RegisteredUserController::class, 'create'])
       // ->name('register');

    //Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);


    Route::post('/forgot-password', [LandingRegisterController::class, 'sendResetPasswordLinkCustom'])
  
    ->name('password.email');



    Route::post('/landing/reset-password/update', [LandingRegisterController::class, 'updateResetPassword'])
    ->middleware('guest')
    ->name('landing.reset.update');



Route::get('/reset-password', [LandingRegisterController::class, 'showForgotPasswordForm'])
    ->middleware('guest')
    ->name('password.reset'); 

// Rota para o link de reset (usado no e-mail)
Route::get('/reset-password', [LandingRegisterController::class, 'showResetPasswordFormCustom'])
    ->middleware('guest')
    ->name('landing.reset');

    // Rota para processar a nova senha
Route::post('/reset-password', [LandingRegisterController::class, 'resetPasswordCustom'])
->middleware('guest')
->name('password.update.custom');


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
