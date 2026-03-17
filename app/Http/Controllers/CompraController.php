<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompraController extends Controller
{
     public function index(){
        $compras = Compra::with('proveedor')->get();
        return response()->json([
            'success' => true,
            'data' => $compras
        ], Response::HTTP_OK);
    }

    public function store(Request $request){
        $request->validate([
            'id_proveedor' => 'required|integer|exists:proveedor,id_proveedor',
            'fecha' => 'nullable|date',
            'cantidad' => 'nullable|integer|min:1',
            'total' => 'nullable|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();
            
            $compra = Compra::create([
                'id_proveedor' => $request->id_proveedor,
                'fecha' => $request->fecha ?? now()
            ]);

            foreach ($request->productos as $prod) {
                $detalle_compra = DetalleCompra::create([
                'id_compra' => $compra->id_compra,
                'id_productos' => $prod['id_producto'],
                'cantidad' => $prod['cantidad']
            ]);
            }
            

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Compra creada exitosamente',
                'data' => $compra->load('proveedor')
            ], Response::HTTP_CREATED);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la compra: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id_compra){
        $compra = Compra::with('proveedor', 'detallesCompra.producto')->find($id_compra);
        
        if (!$compra) {
            return response()->json([
                'success' => false,
                'message' => 'Compra no encontrada'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'data' => $compra
        ], Response::HTTP_OK);
    }

    public function update(Request $request, $id_compra){
        $compra = Compra::find($id_compra);
        
        if (!$compra) {
            return response()->json([
                'success' => false,
                'message' => 'Compra no encontrada'
            ], Response::HTTP_NOT_FOUND);
        }

        $request->validate([
            'id_proveedor' => 'sometimes|integer|exists:proveedor,id_proveedor',
            'fecha' => 'sometimes|date',
            'cantidad' => 'sometimes|integer|min:1',
            'total' => 'sometimes|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();
            
            $compra->update($request->only(['id_proveedor', 'fecha', 'cantidad', 'total']));

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Compra actualizada exitosamente',
                'data' => $compra->load('proveedor')
            ], Response::HTTP_OK);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la compra: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id_compra){
        $compra = Compra::find($id_compra);
        
        if (!$compra) {
            return response()->json([
                'success' => false,
                'message' => 'Compra no encontrada'
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            DB::beginTransaction();
            
            $compra->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Compra eliminada exitosamente'
            ], Response::HTTP_OK);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la compra: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
