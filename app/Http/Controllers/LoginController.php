<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
  
    public function showLoginForm()
    {
        return view('login'); 
    }

    
    public function login(Request $request)
    {
        
        $request->validate([
            'correo' => 'required|correo',
            'password' => 'required|min:6',
            
        ]);

      
        $credentials = $request->only('email', 'password');
        
        
        $usuario = Usuario::where('correo', $request->email)->first();

        if ($usuario && Hash::check($request->password, $usuario->password)) {
           
             if($usuario->estado == 1){
      
         return redirect('/login')
                ->with('error','Tu correo esta desactivado.');
        }
        
        Auth::login($usuario, $request->remember ?? false);
            
            return redirect('dashboard');
        }
           
      return redirect('/login')
                ->with('error','Tus credenciales son incorrectas.');
    }

   
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}