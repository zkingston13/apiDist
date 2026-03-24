<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;


class ReporteController extends Controller{


public function index()
{
    $datos = DB::table('productos as p')
        ->leftJoin('detalle_compra as dc', 'p.id_producto', '=', 'dc.id_producto')
        ->leftJoin('detalle_venta as dv', 'p.id_producto', '=', 'dv.id_productos')
        ->select(
            'p.id_producto',
            'p.nombre_producto',
            'p.existencia as existencia_actual',
            DB::raw('COALESCE(SUM(dc.cantidad),0) as compras'),
            DB::raw('COALESCE(SUM(dv.cantidad),0) as ventas')
        )
        ->groupBy('p.id_producto', 'p.nombre_producto', 'p.existencia')
        ->get();

    foreach ($datos as $d) {

        $d->existencia_antes = $d->existencia_actual - $d->compras + $d->ventas;
        $d->existencia_despues = $d->existencia_actual;
    }

    return response()->json([
        'success' => true,
        'datos' => $datos,
    ],200);
}
}