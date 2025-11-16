<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TipoEmergencia
 *
 * @property $id_emergencia
 * @property $emergencia
 * @property $prioridad
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class TipoEmergencia extends Model
{
    
     protected $table = 'tipo_emergencia';
    protected $primaryKey = 'id_emergencia';
    public $timestamps = false;

    protected $fillable = [
        'emergencia',
        'prioridad',
    ];

}
