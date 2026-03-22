<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class DetalleCompra extends Controller
{
    public function index(){
    $detalles = DB::table('detalle_compra as dc')
    ->join('productos as p', 'dc.id_producto', '=', 'p.id_producto')
    ->select(
        'dc.id_producto',
        'dc.total',
        'dc.cantidad',
        'dc.precio as costo_articulo',
        'p.precio as precio_articulo',
    )
    ->get();

    return response()->json([
            'success' => true,
            'detalles' => $detalles,
            
        ],200);
    }
}
