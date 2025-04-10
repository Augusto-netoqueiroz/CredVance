<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\LandingRegisterController;



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



require __DIR__.'/auth.php';
