<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class paquete
 *
 * @property $id_paquete
 * @property $id_solicitud
 * @property $descripcion
 * @property $cantidad_total
 * @property $estado_id
 * @property $ubicacion_actual
 * @property $fecha_creacion
 * @property $fecha_entrega
 * @property $created_at
 * @property $updated_at
 *
 * @property Solicitud $solicitud
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class paquete extends Model
{
    
    protected $table = 'paquete';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'id_paquete';
    protected $fillable = ['id_paquete', 'id_solicitud', 'estado_id','cantidad_total', 'ubicacion_actual', 'fecha_creacion', 'fecha_entrega'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function solicitud()
    {
        return $this->belongsTo(\App\Models\Solicitud::class, 'id_solicitud', 'id_solicitud');
    }
       public function estado()
    {
        return $this->belongsTo(\App\Models\Estado::class, 'estado_id', 'id_estado');
    }
    public function encargado()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_encargado', 'ci');
    }

}
