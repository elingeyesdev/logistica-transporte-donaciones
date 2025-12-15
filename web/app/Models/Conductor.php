<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Conductor
 *
 * @property $conductor_id
 * @property $nombre
 * @property $apellido
 * @property $fecha_nacimiento
 * @property $ci
 * @property $celular
 * @property $id_licencia
 *
 * @property TipoLicencia $tipoLicencia
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Conductor extends Model
{
    protected $table = 'conductor';
    protected $primaryKey = 'conductor_id'; 
    protected $perPage = 20;
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['nombre', 'apellido', 'fecha_nacimiento', 'ci', 'celular', 'id_licencia'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipoLicencium()
    {
        return $this->belongsTo(\App\Models\TipoLicencia::class, 'id_licencia', 'id_licencia');
    }
    public function getRouteKeyName()
    {
        return 'conductor_id';
    }

}
