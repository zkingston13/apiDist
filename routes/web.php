<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ArticulosController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\ProveedoresController;
use App\Http\Controllers\VentasController;

Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

// Login
Route::get('/login', [UsuarioController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UsuarioController::class, 'login'])->name('login.post');
Route::post('/logout', [UsuarioController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return redirect()->route('login');
});


 //Route::middleware(['auth'])->group(function () {
    Route::view('/inicio', 'inicio');

    //Route::get('/vendedores', [EmpleadoController::class, 'index'])->name('vendedores');
    Route::resource('/ventas', VentasController::class);
    //Route::resource('/vendedores', UsuarioController::class);
    Route::resource('/vendedores', UsuarioController::class);
    //Route::view('/vendedor/',[UsuarioController::class, 'show']);
 //});
