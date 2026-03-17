<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    public function index(){
        $empleados = Empleado::all();
        return view('vendedores', compact('empleados'));
    }

    public function store(Request $req){
        $idUsuario = Usuario::insertGetId([
        'correo' => $req->correo,
        'password' => Hash::make($req->password)
    ]);

    $empleado = new Empleado();
    $empleado->id_usuario = $idUsuario;
    $empleado->nombre = $req->nombre;
    $empleado->apellidoP = $req->apellidoP;
    $empleado->apellidoM = $req->apellidoM;
    $empleado->telefono = $req->telefono;
    $empleado->turno = $req->turno;

    return redirect()->back();
    
    }
}
