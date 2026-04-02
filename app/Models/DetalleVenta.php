<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Productos;

class DetalleVenta extends Model
{
   protected $table = 'detalle_venta';
    protected $primaryKey = 'id_detalle';
    public $timestamps = false;

    protected $fillable = ['id_venta', 'id_productos', 'cantidad', 'precio', 'subtotal', 'total'];

    public function producto() {
        return $this->belongsTo(Productos::class, 'id_productos', 'id_producto');
    }
}
