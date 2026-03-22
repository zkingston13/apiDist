<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;


class ReporteController extends Controller{



public function index()
{
   $datos = DB::table('productos as p')
        ->leftJoin('detalle_compra as dc', function ($join) {
            $join->on('p.id_producto', '=', 'dc.id_producto');
        })
        ->select(
            'p.id_producto',
            'p.nombre_producto',
            'p.existencia as existencia_actual',
            DB::raw('COALESCE(SUM(dc.cantidad),0) as compras')
        )
        ->groupBy('p.id_producto', 'p.nombre_producto', 'p.existencia')
        ->get();

    foreach ($datos as $d) {

        
        if ($d->compras == 0) {
            $d->existencia_antes = $d->existencia_actual;
        } else {
            $d->existencia_antes = $d->existencia_actual - $d->compras;
        }

        $d->existencia_despues = $d->existencia_actual;
    }

     return response()->json([
            'success' => true,
            'datos' => $datos,
            
        ],200);
}
}