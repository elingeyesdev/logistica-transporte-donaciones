<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destino extends Model
{
    protected $table = 'destino';
    protected $primaryKey = 'id_destino';

    protected $fillable = [
        'comunidad',
        'provincia',
        'direccion',
        'latitud',
        'longitud',
    ];
     public function solicitudes()
    {
        return $this->hasMany(Solicitud::class, 'id_destino', 'id_destino');
    }
}
