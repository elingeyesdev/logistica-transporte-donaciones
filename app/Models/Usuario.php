<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuario'; // 👈 Nombre real de la tabla en singular
    protected $primaryKey = 'id_usuario'; // 👈 Nombre real de la columna ID
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true; // si tu tabla tiene created_at y updated_at

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
