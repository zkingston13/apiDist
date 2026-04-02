<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Compra;
use App\Models\Productos;

class DetalleCompra extends Model
{
   protected $table = 'detalle_compra';
    protected $primaryKey = 'id_detalle';
    public $timestamps = false;

    protected $fillable = [
        'id_compra', 
        'id_producto', 
        'cantidad', 
        'precio'
        ];

    public function compra(){
        return $this->belongsTo(Compra::class, 'id_compra');
    } 
   public function producto()
{
    return $this->belongsTo(Productos::class, 'id_producto', 'id_producto');
}
}
