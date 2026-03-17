<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DetalleCompra;

class Compra extends Model
{
   protected $table = 'compra';
    protected $primaryKey = 'id_compra';
    public $timestamps = false;

    protected $fillable = [
        'id_proveedor',
        'fecha',
        ];

    public function detalles() {
        return $this->hasMany(DetalleCompra::class, 'id_compra', 'id_compra');
    }
    public function proveedor(){
        return $this->belongsTo(Proveedor::class, 'id_provedor');
    }
}
