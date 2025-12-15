<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Solicitante extends Model
{
    protected $table = 'solicitante';
    protected $primaryKey = 'id_solicitante';

    protected $fillable = [
        'nombre',
        'apellido',
        'ci',
        'email',
        'telefono',
    ];
     public function solicitudes()
    {
        return $this->hasMany(Solicitud::class, 'id_solicitante', 'id_solicitante');
    }
}
