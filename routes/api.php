<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ArticulosController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\ProveedoresController;
use App\Http\Controllers\VentasController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\DetalleCompra;
use App\Http\Controllers\InicioController;

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

Route::get('/auth/google', [GoogleController::class, 'redirect']);
Route::get('/auth/google/empl', [GoogleController::class, 'redirectempl']);
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);
Route::get('/auth/google/callback/empl', [GoogleController::class, 'callbackempl']);

// Login
Route::get('/login', [UsuarioController::class, 'showLoginForm'])->name('login');

Route::post('/login', [UsuarioController::class, 'login']);
Route::post('/login-vendedor', [UsuarioController::class, 'loginVendedor']);
Route::post('/logout', [UsuarioController::class, 'logout']);

Route::resource('productos', ArticulosController::class);

    Route::prefix('articulos')->group(function(){
       Route::get('/',[ArticulosController::class,'index']);
       Route::get('/{id_producto}',[ArticulosController::class,'show']);
       Route::put('/{id_producto}',[ArticulosController::class,'update']);
       Route::post('/store',[ArticulosController::class,'store']);
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
    
    Route::prefix('compra')->group(function(){
     Route::get('/',[ CompraController::class,'index']);
     Route::post('/',[CompraController::class,'store']);
     Route::delete('/{id_compra}',[CompraController::class,'destroy']);
    });

    Route::prefix('reporte')->group(function(){
      Route::get('/',[ReporteController::class,'index']);
    });
    Route::prefix('detalles')->group(function(){
Route::get('/',[DetalleCompra::class,'index']);
    });
Route::prefix('ventas')->group(function(){
    Route::get('/', [VentasController::class,'index']);
Route::post('/', [VentasController::class,'store']);
Route::get('{id}', [VentasController::class,'show']);
Route::delete('/{id}', [VentasController::class,'destroy']);
});

Route::get('/inicio',[InicioController::class,'index']);
Route::get('/inicio_empleado',[InicioController::class,'indexemple']);
Route::get('/vendedores/{id_usuario}/ventas', [UsuarioController::class,'ventasVendedor']);

 Route::put('/perfil/update/{id}', [UsuarioController::class, 'updatePerfil']);
 Route::put('/perfil/password/{id}', [UsuarioController::class, 'updatePassword']);