<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Compra;

class Proveedor extends Model
{
    protected $table = 'proveedor';
    protected $primaryKey = 'id_proveedor';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'rfc',
        'direccion',
        'telEfono'
    ];

    public function compras()
    {
        return $this->hasMany(Compra::class, 'id_proveedor');
    }
}
