<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InicioController extends Controller
{
 public function index(Request $request)
{
    $idadmin = $request->id_admin;

    $totalProductos = DB::table('productos')->count();

    $ventasHoy = DB::table('venta')
        ->join('detalle_venta','venta.id_venta','=','detalle_venta.id_venta')
        ->whereDate('venta.fecha', Carbon::today())
        ->sum('detalle_venta.subtotal');

    $totalVendedores = DB::table('usuario')
        ->where('rol','vendedor')
        ->count();

    $ventasMes = DB::table('venta')
        ->join('detalle_venta','venta.id_venta','=','detalle_venta.id_venta')
        ->select(
            DB::raw('MONTH(venta.fecha) as mes'),
            DB::raw('SUM(detalle_venta.subtotal) as total')
        )
        ->groupBy('mes')
        ->orderBy('mes')
        ->get();

    $productosVendidos = DB::table('detalle_venta')
        ->join('productos','detalle_venta.id_productos','=','productos.id_producto')
        ->select(
            'productos.nombre_producto',
            DB::raw('SUM(detalle_venta.cantidad) as total')
        )
        ->groupBy('productos.nombre_producto')
        ->orderByDesc('total')
        ->limit(4)
        ->get();

    $comisionesPendientes = DB::table('comisiones')
        ->where('pagado',0)
        ->count();

    return response()->json([
        'success' => true,
        'totalProductos' => $totalProductos,
        'ventasHoy' => $ventasHoy,
        'totalVendedores' => $totalVendedores,
        'ventasMes' => $ventasMes,
        'productosVendidos' => $productosVendidos,
        'comisionesPendientes' => $comisionesPendientes
    ],200);
}
   public function indexemple(Request $request)
{
    $idVendedor = $request->id_vendedor;

    $totalProductos = DB::table('productos')->count();

    $ventasHoy = DB::table('venta')
        ->join('detalle_venta','venta.id_venta','=','detalle_venta.id_venta')
        ->where('venta.id_vendedor', $idVendedor)
        ->whereDate('venta.fecha', Carbon::today())
        ->sum('detalle_venta.subtotal');


    $ventasMes = DB::table('venta')
        ->join('detalle_venta','venta.id_venta','=','detalle_venta.id_venta')
        ->where('venta.id_vendedor', $idVendedor)
        ->select(
            DB::raw('MONTH(venta.fecha) as mes'),
            DB::raw('SUM(detalle_venta.subtotal) as total')
        )
        ->groupBy('mes')
        ->orderBy('mes')
        ->get();


    $productosVendidos = DB::table('detalle_venta')
        ->join('venta','detalle_venta.id_venta','=','venta.id_venta')
        ->join('productos','detalle_venta.id_productos','=','productos.id_producto')
        ->where('venta.id_vendedor', $idVendedor)
        ->select(
            'productos.nombre_producto',
            DB::raw('SUM(detalle_venta.cantidad) as total')
        )
        ->groupBy('productos.nombre_producto')
        ->orderByDesc('total')
        ->limit(4)
        ->get();

    return response()->json([
        'success' => true,
        'totalProductos' => $totalProductos,
        'ventasHoy' => $ventasHoy,
        'ventasMes' => $ventasMes,
        'productosVendidos' => $productosVendidos
    ],200);
}
    
    }
