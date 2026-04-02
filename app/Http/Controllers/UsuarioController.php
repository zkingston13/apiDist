<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{
    public function index(){
     $vendedores = DB::table('usuario as u')
        ->leftJoin('venta as v', 'u.id_empleado', '=', 'v.id_vendedor')
        ->leftJoin('detalle_venta as dv', 'v.id_venta', '=', 'dv.id_venta')
        ->select(
            'u.id_empleado',
            'u.nombre',
            'u.apellidoP',
            'u.apellidoM',
            'u.telefono',
            'u.sueldo_base',
            'u.comision',
            'u.sueldo_neto',
            'u.activo',
            DB::raw('COUNT(v.id_vendedor) as ventas_realizadas'),
             DB::raw('COALESCE(SUM(dv.total),0) as total_ventas')
        )
        ->where('u.rol', 'vendedor')
        ->groupBy(
            'u.id_empleado',
            'u.nombre',
            'u.apellidoP',
            'u.apellidoM',
            'u.telefono',
            'u.sueldo_base',
            'u.comision',
            'u.sueldo_neto',
            'activo'
        )
        ->get();

   return response()->json(['resultado'=>true, 'datos' =>$vendedores], 200);
    }

    public function store(Request $request)
    {
     $validator = Validator::make($request->all(), [
        'nombre' => 'required|string|max:30',
        'apellidoP' => 'required|string|max:30',
        'apellidoM' => 'nullable|string|max:30', // Cambiado a nullable
        'telefono' => 'nullable|string|max:30',
        'turno' => 'nullable|string|in:Matutino,Vespertino',
        'sueldo_base' => 'required|numeric|min:0',
        'rol' => 'required|in:vendedor,administrador',
        'correo' => 'required|email|max:255|unique:usuario,correo', // Cambiado a required
        'password' => 'required|string|min:6',
        'activo' => 'nullable|boolean'
    ]);

      if ($validator->fails()) {
       return response()->json([
            'success' => false,
            'errors'  => $validator->errors()
        ], 422);
    }
   
    $comision = 0;
    $sueldo_neto = $request->sueldo_base + $comision;

    $ultimoEmpleado = Usuario::orderBy('id_empleado', 'desc')->first();
    $nuevoId = $ultimoEmpleado ? $ultimoEmpleado->id_empleado + 1 : 1;

    $usuario = Usuario::create([
        'id_empleado' => $nuevoId,
        'nombre' => $request->nombre,
        'apellidoP' => $request->apellidoP,
        'apellidoM' => $request->apellidoM ?? '', 
        'telefono' => $request->telefono,
        'turno' => $request->turno ?? 'Matutino', 
        'sueldo_base' => $request->sueldo_base,
        'comision' => 0, 
        'sueldo_neto' => $sueldo_neto,
        'rol' => $request->rol,
        'correo' => $request->correo,
        'password' => Hash::make($request->password),
    ]);
     $request->merge([
    'rol' => strtolower(trim($request->rol))
]);
     return response()->json([
            'success' => true,
            'message' => 'Usuario registrado correctamente',
            'data' => [
                'usuario' => $usuario,
                
            ]
        ], 200);
}

private function generateNextId()
{
    $lastUser = Usuario::orderBy('id_empleado', 'desc')->first();
    return $lastUser ? $lastUser->id_empleado + 1 : 1;
}

     public function show($id){

        $usuario = Usuario::find($id);

        if(!$usuario){
            return response()->json([
                'resultado'=>false,
                'mensaje'=>'Usuario no encontrado'
            ],404);
        }

        return response()->json([
            'resultado'=>true,
            'datos'=>$usuario
        ]);
    }

    public function update(Request $request, $id_usuario){
        $usuario = Usuario::find($id_usuario);
        
        if (!$usuario) {
            return response()->json([
            'resultado'=>false,
            'datos'=> null,
            'errors' => 'Usuario no encontrado'
        ],422);
        }

      $validator = Validator::make($request->all(), [
    'nombre' => 'nullable|string|max:30',
    'apellidoP' => 'nullable|string|max:30',
    'apellidoM' => 'nullable|string|max:30',
    'telefono' => 'nullable|string|max:30',
    'turno' => 'nullable|string|max:30',
    'sueldo_base' => 'nullable|numeric|min:0',
    'rol' => 'nullable|in:vendedor,administrador',
    'correo' => 'nullable|email|max:255|unique:usuario,correo,' . $id_usuario . ',id_usuario'
]);
            if ($validator->fails()) {
        return response()->json([
            'resultado'=>false,
            'datos'=> null,
            'errors' => $validator->errors()
        ],400);
    }

        $data = array_filter(
    $request->except('password'),
    fn($value) => !is_null($value) && $value !== ''
);
        
        if ($request->has('password')) {
            $data['password'] = Hash::make($request->password);
        }

       $usuario->fill($data);
          $usuario->save();

       return response()->json([
        'resultado'=>true,
        'datos'=>$usuario
    ],200);
    }

   public function destroy($id_usuario)
{

    $usuario = Usuario::find($id_usuario);

    if(!$usuario){
        return response()->json([
            'success'=>false,
            'message'=>'Usuario no encontrado'
        ],404);
    }

    $usuario->update([
        'activo'=>0
    ]);

    return response()->json([
        'success'=>true,
        'message'=>'Usuario eliminado'
    ]);
}
    public function showLoginForm(){
        return view('layouts.login'); 
    }

    public function login(Request $request){
       
         $request->validate([
        'correo' => 'required|email',
        'password' => 'required|min:6'
    ]);

    $usuario = Usuario::where('correo', $request->correo)->first();

    if(!$usuario){
        return response()->json([
            'success' => false,
            'message' => 'Credenciales incorrectas'
        ],401);
    }

    if(!Hash::check($request->password,$usuario->password)){
        return response()->json([
            'success'=>false,
            'message'=>'Credenciales incorrectas'
        ],401);
    }

    if($usuario->activo == 0){
        return response()->json([
            'success'=>false,
            'message'=>'Tu cuenta está desactivada'
        ],403);
    }

    if($usuario->rol === 'vendedor'){
        return response()->json([
            'success'=>false,
            'message'=>'No tienes acceso al sistema'
        ],403);
    }

    return response()->json([
        'success'=>true,
        'usuario'=>$usuario
    ]);
}
        
    

    public function logout()
{
    Auth::logout();

    return response()->json([
        'success'=>true,
        'message'=>'Sesión cerrada'
    ]);
}

public function ventasVendedor($id_usuario)
{
    $ventas = DB::table('venta as v')
        ->leftJoin('detalle_venta as dv','v.id_venta','=','dv.id_venta')
        ->select(
            'v.id_venta',
            'v.fecha',
            DB::raw('SUM(dv.subtotal) as subtotal'),
            DB::raw('SUM(dv.total) as total')
        )
        ->where('v.id_vendedor',$id_usuario)
        ->groupBy('v.id_venta','v.fecha')
        ->get();

    $vendedor = Usuario::find($id_usuario);

    return response()->json([
        'success'=>true,
        'ventas'=>$ventas,
        'vendedor'=>$vendedor
    ]);
}
   public function loginVendedor(Request $request){
       
         $request->validate([
        'correo' => 'required|email',
        'password' => 'required|min:6'
    ]);

    $usuario = Usuario::where('correo', $request->correo)->first();

    if(!$usuario){
        return response()->json([
            'success' => false,
            'message' => 'Credenciales incorrectas'
        ],401);
    }

    if(!Hash::check($request->password,$usuario->password)){
        return response()->json([
            'success'=>false,
            'message'=>'Credenciales incorrectas'
        ],401);
    }

    if($usuario->activo == 0){
        return response()->json([
            'success'=>false,
            'message'=>'Tu cuenta está desactivada'
        ],403);
    }

    if($usuario->rol === 'administrador'){
        return response()->json([
            'success'=>false,
            'message'=>'No tienes acceso al sistema'
        ],403);
    }

    return response()->json([
        'success'=>true,
        'usuario'=>$usuario
    ]);
}

public function updatePerfil(Request $request, $id_usuario)
{
    $usuario = Usuario::find($id_usuario);

    if (!$usuario) {
        return response()->json([
            'success' => false,
            'message' => 'Usuario no encontrado'
        ], 404);
    }

    $request->validate([
        'nombre' => 'required|string|max:30',
        'correo' => 'required|email|max:255|unique:usuario,correo,' . $id_usuario . ',id_empleado'
    ]);

    $usuario->update([
        'nombre' => $request->nombre,
        'correo' => $request->correo
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Perfil actualizado correctamente',
        'usuario' => $usuario
    ], 200);
}

public function updatePassword(Request $request, $id_usuario)
{
    $usuario = Usuario::find($id_usuario);

    if (!$usuario) {
        return response()->json([
            'success' => false,
            'message' => 'Usuario no encontrado'
        ], 404);
    }

   
    $request->validate([
        'new_password' => 'required|min:6|confirmed'
    ]);
    if ($request->new_password !== $request->new_password_confirmation) {
        return response()->json([
            'success' => false,
            'message' => 'Las contraseñas no coinciden'
        ], 422);
    }
      $request->validate([
        'new_password' => 'required|min:6|same:new_password_confirmation',
        'new_password_confirmation' => 'required|min:6'
    ]);

    $usuario->password = Hash::make($request->new_password);
    $usuario->save();

    return response()->json([
        'success' => true,
        'message' => 'Contraseña actualizada correctamente'
    ], 200);
}

}
