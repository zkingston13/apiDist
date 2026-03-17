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
    // Calcular sueldo_neto (sueldo_base + comisión si aplica)
    // Por defecto comisión en 0
    $comision = 0;
    $sueldo_neto = $request->sueldo_base + $comision;

    // Generar ID autoincremental si no es auto en BD
    $ultimoEmpleado = Usuario::orderBy('id_empleado', 'desc')->first();
    $nuevoId = $ultimoEmpleado ? $ultimoEmpleado->id_empleado + 1 : 1;

    $usuario = Usuario::create([
        'id_empleado' => $nuevoId,
        'nombre' => $request->nombre,
        'apellidoP' => $request->apellidoP,
        'apellidoM' => $request->apellidoM ?? '', // Valor por defecto si es null
        'telefono' => $request->telefono,
        'turno' => $request->turno ?? 'Matutino', // Valor por defecto
        'sueldo_base' => $request->sueldo_base,
        'comision' => 0, // Valor por defecto
        'sueldo_neto' => $sueldo_neto,
        'rol' => $request->rol,
        'correo' => $request->correo,
        'password' => Hash::make($request->password),
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

    public function show($id_usuario){
          $validator = Validator::make(
              ['id_empleado'=>$id_usuario],
              ['id_empleado'=>'required|integer|min:1|exists:usuario,id_empleado']
        );

        if($validator->fails()){
             return response()->json(['resultado'=>false, 'datos' =>null,'errors'=>$validator->errors()], 422);

        }
        
        $usuario = Usuario::find($id_usuario);
        
        if (!$usuario) {
          return response()->json(['resultado'=>false, 'datos' =>null,'errors'=>$validator->errors()], 400);

        }

        return response()->json(['resultado'=>true, 'datos' =>$usuario], 200);
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

    public function destroy($id){
        $usuario = Usuario::find($id);
        if (!$usuario) {
            return back()->with('error', 'Error usuario no encontrado');

        }
        // Cambiar estado a inactivo
        $usuario->update([
            'activo' => 0
            ]);
        return redirect()->back()->with('success', 'usuario eliminado');
    }

    public function showLoginForm(){
        return view('layouts.login'); 
    }

    public function login(Request $request){
        // Validar datos
        $request->validate([
            'correo' => 'required|email',
        ]);

        // Intentar autenticación
        $credentials = $request->only('correo', 'password');
        
        // Buscar usuario por correo
        $usuario = Usuario::where('correo', $request->correo)->first();

        if ($usuario) {
            // Credenciales correctas, iniciar sesión
            Auth::login($usuario, $request->remember ?? false);
            
          return redirect('/inicio');
        }
        
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}
