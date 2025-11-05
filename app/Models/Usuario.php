<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuario'; 
    protected $primaryKey = 'id_usuario'; 
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'active',
        'admin',
        'apellido',
        'ci',
        'contrasena',
        'correo_electronico',
        'nombre',
        'telefono',
    ];
}
