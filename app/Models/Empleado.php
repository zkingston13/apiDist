<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'empleado';
    protected $primaryKey = 'id_empleado';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'nombre',
        'apellidoP',
        'apellidoM',
        'telefono',
        'turno'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'id_vendedor');
    }
}
