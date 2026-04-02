<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuario';
    protected $primaryKey = 'id_empleado';
    public $timestamps = false;

    protected $fillable = [
        'id_empleado',
        'nombre',
        'apellidoP',
        'apellidoM',
        'telefono',
        'turno',
        'sueldo_base',
        'comision',
        'sueldo_neto',
        'correo',
        'password',
        'rol',
        'activo'
    ];
    
    protected $hidden = [
        'password',
    ];

    
    public function empleado()
    {
        return $this->hasOne(Empleado::class, 'id_usuario');
    }
    public function ventas()
    {
        return $this->hasMany(Venta::class, 'id_vendedor');
    }
}