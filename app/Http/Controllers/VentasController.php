<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Productos;
use App\Models\Empleado;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Ventas;
use App\Models\Usuario;

class VentasController extends Controller
{
    
   public function index(){
        $detalles = DB::table('detalle_venta as dv')
        ->join('productos as p', 'dv.id_productos', '=', 'p.id_producto')
        ->join('venta as v', 'dv.id_venta', '=', 'v.id_venta')
         ->join('usuario as u', 'v.id_vendedor', '=', 'u.id_empleado')
        ->where('u.rol', 'vendedor')
        ->select(
            'dv.cantidad',
            'dv.precio_venta',
            'dv.total',
            'p.id_producto',
            'p.nombre_producto',
            'u.nombre as nombre_vendedor'
        )
        ->get();

        return view('ventas', compact('detalles'));
    }

   public function store(Request $request){
        try {
            DB::beginTransaction();

            // 1. Crear la cabecera de la venta
            $venta = Ventas::create([
                'id_vendedor' => $request->id_vendedor,
                'id_producto' => $request->id_producto,
                'fecha' => now()
            ]);

            // 2. Crear los detalles
            foreach ($request->productos as $prod) {
                DetalleVenta::create([
                    'id_venta' => $venta->id_venta,
                    'id_productos' => $prod['id_producto'],
                    'cantidad' => $prod['cantidad'],
                    'precio' => $prod['precio'],
                    'total' =>$prod['cantidad'] * $prod['precio']
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Venta realizada correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al guardar la venta');
        }
    }

    public function show($id_venta){
        $venta = Ventas::with(['vendedor', 'detalle'])->find($id);
       
        if (!$venta) {
            return back()->with('error', 'Error al encontrar la venta');
        }

        return view('ventas', compact('venta'));
    }

    
    public function update(Request $request,$id_venta)
    {
        $venta = Ventas::find($id);
        
         DB::beginTransaction();

            // 1. Crear la cabecera de la venta
            $venta->id_vendedor = $request->id_vendedor;
            $venta->id_producto = $request->id_producto;
            $fecha->now();
            $fecha->save();

            $detalle_venta = DetalleVenta::find($id_venta);


            // 2. Crear los detalles
            foreach ($request->productos as $prod) {
                $detalle_venta->id_venta = $venta->id_venta;
                $detalle_venta->id_productos = $request->$prod['id_producto'];
                $detalle_venta->cantidad = $request->cantidad;
                $detalle_venta->save();
            }

            DB::commit();
            return redirect()->back()->with('success', 'Venta actualizada correctamente');

        if (!$venta) {
            return back()->with('error', 'Error al actualizar la venta');
        }
    }

    public function destroy(string $id)
    {
        $venta = Ventas::find($id);
        
        if (!$venta) {
            return back()->with('error', 'Error venta no encontrada');
        }

        try {
            DB::beginTransaction();

            // Devolver stock al eliminar la venta
            $producto = Productos::find($venta->id_producto);
            $producto->existencia += $venta->cantidad;
            $producto->save();

            // Eliminar la venta
            $venta->delete();

            DB::commit();

           return redirect()->back()->with('success', 'Venta eliminada');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar venta');
        }
    }

    public function porRangoFechas(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio'
        ]);

        if ($validator->fails()) {
            return back()->with('error', 'Error algunos datos estan mal');
        }

        $ventas = Ventas::with(['vendedor', 'producto'])
            ->whereBetween('fecha', [$request->fecha_inicio, $request->fecha_fin])
            ->get();

        return view('ventas', compact('ventasPorFechas'));
    }

    public function porEmpleado($empleadoId)
    {
        $ventas = Ventas::with('producto')
            ->where('id_vendedor', $empleadoId)
            ->get();

        return view('ventas', compact('ventasPorEmpleado'));
    }

}
