<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{

public function redirect()
{
    return Socialite::driver('google')->stateless()->redirect();
}

public function callback()
{

    try {
        $googleUser = Socialite::driver('google')->stateless()->user();
    } catch (\Exception $e) {
        return response()->json([
            'success'=>false,
            'message'=>'Error al autenticar con Google'
        ],500);
    }

    $user = Usuario::where('correo',$googleUser->email)->first();

    if(!$user){
     return redirect('http://127.0.0.1:8001/login')
        ->with('google_error','Tu correo no está registrado');
    }

    if($user->activo == 0){
      return redirect('http://127.0.0.1:8001/login')
        ->with('google_error','Usuario desactivado');
    }

    return redirect('http://127.0.0.1:8001/inicio')
        ->with('sucess','Usuario encontrado');
}

}