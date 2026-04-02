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

    $producto = Productos::find($prod['id']);

    if(!$producto){
        throw new \Exception("Producto no encontrado");
    }

    if($producto->existencia < $prod['cantidad']){
        throw new \Exception("Stock insuficiente para ".$producto->nombre_producto);
    }

    $subtotal = $prod['cantidad'] * $prod['precio'];
    $iva = $subtotal * 0.16;
    $total = $subtotal + $iva;

    DetalleVenta::create([
        'id_venta' => $venta->id_venta,
        'id_productos' => $prod['id'],
        'cantidad' => $prod['cantidad'],
        'precio_venta' => $prod['precio'],
        'subtotal' => $subtotal,
        'total' => $total
    ]);

   
    $producto->existencia -= $prod['cantidad'];
    $producto->save();
}

        DB::commit();

      return response()->json([
    'success' => true,
    'message' => 'Venta registrada correctamente',
    'venta_id' => $venta->id_venta
],200);

    } catch (\Exception $e) {

        DB::rollBack();

        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ],500);
    }
}
    public function show($id)
    {
       $venta = Ventas::with(['vendedor','detalles.producto'])->find($id);

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