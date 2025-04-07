<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;



//View inicial
Route::get('/', function () {
    return view('construcao');
});

//Template prinicial - rota por enquanto
route::get('/pagina', function () {
    return view('pagina');
});


Route::get('/login', [UsuarioController::class, 'loginForm'])->name('login');
Route::post('/login', [UsuarioController::class, 'authenticate'])->name('login.authenticate');


//View creat
Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
Route::get('/usuarios/cadastrar', [UsuarioController::class, 'create'])->name('usuarios.create');
Route::post('/cadastrar/salvar', [UsuarioController::class, 'store'])->name('usuarios.store');
Route::post('/usuarios/{id}/delete', [UsuarioController::class, 'delete'])->name('usuarios.delete');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
