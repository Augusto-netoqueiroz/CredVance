<?php

use Illuminate\Support\Facades\Route;
 
use App\Http\Controllers\Admin\UsuarioController;


 

route::get('/', function () {
    return view('construcao');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('usuarios', UsuarioController::class);
});


route::get('/pagina', function () {
    return view('pagina');
});


Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    });
});


Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');

    Route::resource('clientes', App\Http\Controllers\Admin\ClienteController::class);
    Route::resource('consorcios', App\Http\Controllers\Admin\ConsorcioController::class);
    Route::resource('contratos', App\Http\Controllers\Admin\ContratoController::class);
    Route::resource('pagamentos', App\Http\Controllers\Admin\PagamentoController::class);
});



Route::middleware(['auth'])->group(function () {
    Route::get('/home', function () {
        return view('admin.dashboard'); // ou outra view que você queira
    })->name('home');
});



Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
Route::get('/usuarios/cadastrar', [UsuarioController::class, 'create'])->name('usuarios.create');
Route::post('/usuarios/cadastrar', [UsuarioController::class, 'store'])->name('usuarios.store');