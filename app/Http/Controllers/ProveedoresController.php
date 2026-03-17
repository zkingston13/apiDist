<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProveedoresController extends Controller
{
    public function index(){
        $proveedores = Proveedor::all();
        return response()->json([
            'success' => true,
            'data' => $proveedores
        ], Response::HTTP_OK);
    }

    public function store(Request $request){
        $request->validate([
            'nombre' => 'required|string|max:50',
            'rfc' => 'nullable|string|max:33',
            'direccion' => 'nullable|string|max:30',
            'telefono' => 'nullable|string|max:30'
        ]);

        $proveedor = Proveedor::create([
            'nombre' => $request->nombre,
            'rfc' => $request->rfc,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Proveedor creado exitosamente',
            'data' => $proveedor
        ], Response::HTTP_CREATED);
    }

    public function show($id_proveedor){
        $proveedor = Proveedor::find($id_proveedor);
        
        if (!$proveedor) {
            return response()->json([
                'success' => false,
                'message' => 'Proveedor no encontrado'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'data' => $proveedor
        ], Response::HTTP_OK);
    }

    public function update(Request $request,$id_proveedor){
        $proveedor = Proveedor::find($id_proveedor);
        
        if (!$proveedor) {
            return response()->json([
                'success' => false,
                'message' => 'Proveedor no encontrado'
            ], Response::HTTP_NOT_FOUND);
        }

        $request->validate([
            'nombre' => 'sometimes|string|max:50',
            'rfc' => 'sometimes|string|max:33',
            'direccion' => 'sometimes|string|max:30',
            'telefono' => 'sometimes|string|max:30'
        ]);

        $proveedor->update($request->only(['nombre', 'rfc', 'direccion', 'telefono']));

        return response()->json([
            'success' => true,
            'message' => 'Proveedor actualizado exitosamente',
            'data' => $proveedor
        ], Response::HTTP_OK);
    }

    public function destroy($id_proveedor){
        $proveedor = Proveedor::find($id_proveedor);
        
        if (!$proveedor) {
            return response()->json([
                'success' => false,
                'message' => 'Proveedor no encontrado'
            ], Response::HTTP_NOT_FOUND);
        }

        $proveedor->delete();

        return response()->json([
            'success' => true,
            'message' => 'Proveedor eliminado exitosamente'
        ], Response::HTTP_OK);
    }

}
