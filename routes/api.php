<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ArticulosController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\ProveedoresController;
use App\Http\Controllers\VentasController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

// Login
Route::get('/login', [UsuarioController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UsuarioController::class, 'login'])->name('login.post');
Route::post('/logout', [UsuarioController::class, 'logout'])->name('logout');





    Route::view('/inicio', 'inicio');
    Route::resource('productos', ArticulosController::class);

    Route::prefix('articulos')->group(function(){
       Route::get('/',[ArticulosController::class,'index']);
       Route::get('/{id_producto}',[ArticulosController::class,'show']);
       Route::put('/{id_producto}',[ArticulosController::class,'update']);
       Route::post('/',[ArticulosController::class,'store']);
       Route::delete('/{id_producto}',[ArticulosController::class,'destroy']);
    });

    //Route::get('/vendedores', [EmpleadoController::class, 'index'])->name('vendedores');
    Route::resource('/ventas', VentasController::class);
   

    Route::prefix('vendedores')->group(function(){
 Route::get('/',[ UsuarioController::class,'index']);
 Route::get('/{id_usuario}',[UsuarioController::class,'show']);
 Route::post('/',[UsuarioController::class,'store']);
 Route::put('/{id_usuario}',[UsuarioController::class,'update']);
 Route::delete('/{id_usuario}',[UsuarioController::class,'destroy']);
    });
   
