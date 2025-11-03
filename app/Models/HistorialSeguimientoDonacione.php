<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class HistorialSeguimientopaquetee
 *
 * @property $id_historial
 * @property $ci_usuario
 * @property $estado
 * @property $fecha_actualizacion
 * @property $imagen_evidencia
 * @property $id_paquete
 * @property $id_ubicacion
 * @property $created_at
 * @property $updated_at
 *
 * @property paquete $paquete
 * @property Ubicacion $ubicacion
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class HistorialSeguimientoDonacione extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['id_historial', 'ci_usuario', 'estado', 'fecha_actualizacion', 'imagen_evidencia', 'id_paquete', 'id_ubicacion'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paquete()
    {
        return $this->belongsTo(\App\Models\paquete::class, 'id_paquete', 'id_paquete');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ubicacion()
    {
        return $this->belongsTo(\App\Models\Ubicacion::class, 'id_ubicacion', 'id_ubicacion');
    }
    
}
