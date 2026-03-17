<?php
namespace App\Http\Controllers;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
     try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('login')
                ->with('error', 'Error al autenticar con Google');
        }

       
        $user = Usuario::where('correo', $googleUser->email)->first();

       
        if (!$user) {
            return redirect('login')
                ->with('error', 'Tu correo no está registrado');
        }

      
        Auth::login($user);

        return redirect('/inicio');
    }
}
