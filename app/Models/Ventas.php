<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ventas extends Model
{
    protected $table = 'venta';
    protected $primaryKey = 'id_venta';
    public $timestamps = false; // Según tu SQL, no usas created_at/updated_at

    protected $fillable = [
        'id_vendedor', 
        'id_producto', 
        'fecha', 
        ];

    public function detalles() {
        return $this->hasMany(DetalleVenta::class, 'id_venta', 'id_venta');
    }
    public function vendedor() {
        return $this->belongsTo(Usuario::class, 'id_vendedor', 'id_empleado');
    }
}