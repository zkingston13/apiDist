<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productos extends Model
{
    use HasFactory;
     protected $table = 'productos';
    protected $primaryKey = 'id_producto';
    public $timestamps = false;

    protected $fillable = [
        'id_producto',
        'nombre_producto',
        'precio',
        'existencia',
        'categoria_id',
        'activo',
        'img',
        'img1',
        'img2'
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'activo' => 'integer'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id', 'id_categoria');
    }

   public function detalle_compra()
    {
        return $this->hasMany(DetalleCompra::class, 'id_producto', 'id_producto');
    }
    public function ventas()
    {
        return $this->hasMany(Ventas::class, 'id_producto', 'id_producto');
    }
}