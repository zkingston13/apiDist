<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ventas;
use App\Models\DetalleVenta;
use App\Models\Productos;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;

class VentasController extends Controller
{

public function index()
{
    $ventas = Ventas::with(['vendedor', 'detalles'])->get();

    $vendedores = Usuario::where('rol', 'vendedor')->get();

    $productos = Productos::with('categoria')->get();

    return response()->json([
        'success' => true,
        'ventas' => $ventas,
        'vendedores' => $vendedores,
        'productos' => $productos
    ],200);
}

   public function store(Request $request)
{
    try {

        DB::beginTransaction();

        $venta = Ventas::create([
            'id_vendedor' => $request->id_vendedor,
            'fecha' => now()
        ]);

        foreach ($request->productos as $prod) {

            $subtotal = $prod['cantidad'] * $prod['precio'];

            DetalleVenta::create([
                'id_venta' => $venta->id_venta,
                'id_productos' => $prod['id'],
                'cantidad' => $prod['cantidad'],
                'precio_venta' => $prod['precio'],
                'subtotal' => $subtotal,
                'total' => $subtotal
            ]);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Venta registrada'
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}

    public function show($id)
    {
        $venta = Ventas::with(['vendedor','detalle'])->find($id);

        if(!$venta){
            return response()->json([
                'success'=>false,
                'message'=>'Venta no encontrada'
            ],404);
        }

        return response()->json([
            'success'=>true,
            'data'=>$venta
        ]);
    }

    public function destroy($id)
    {
        $venta = Ventas::find($id);

        if(!$venta){
            return response()->json([
                'success'=>false,
                'message'=>'Venta no encontrada'
            ],404);
        }

        $venta->delete();

        return response()->json([
            'success'=>true,
            'message'=>'Venta eliminada'
        ]);
    }

}