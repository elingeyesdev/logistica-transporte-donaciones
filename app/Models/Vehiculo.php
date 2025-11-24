<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Vehiculo
 *
 * @property $id_vehiculo
 * @property $placa
 * @property $capacidad_aproximada
 * @property $id_tipovehiculo
 * @property $modelo_anio
 * @property $modelo
 *
 * @property TipoVehiculo $tipoVehiculo
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Vehiculo extends Model
{
    protected $table = 'vehiculo';
    protected $primaryKey = 'id_vehiculo';
    public $timestamps = false;

    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['placa', 'capacidad_aproximada', 'id_tipovehiculo', 'modelo_anio', 'modelo', 'marca', 'id_marca',];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipoVehiculo()
    {
        return $this->belongsTo(\App\Models\TipoVehiculo::class, 'id_tipovehiculo', 'id_tipovehiculo');
    }
        public function marcaVehiculo()
    {
        return $this->belongsTo(\App\Models\Marca::class, 'id_marca', 'id_marca');
    }
}
