<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Solicitud
 *
 * @property $id
 * @property $nombre_solicitante
 * @property $fecha_creacion
 * @property $descripcion
 * @property $ubicacion
 * @property $estado
 * @property $codigo_seguimiento
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Solicitud extends Model
{
    
    protected $table = 'solicitud';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['nombre_solicitante', 'fecha_creacion', 'descripcion', 'ubicacion', 'estado', 'codigo_seguimiento'];


}
