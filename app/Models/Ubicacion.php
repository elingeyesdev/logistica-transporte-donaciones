<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Ubicacion
 *
 * @property $id_ubicacion
 * @property $latitud
 * @property $longitud
 * @property $zona
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Ubicacion extends Model
{
     protected $table = 'ubicacion'; 
    protected $primaryKey = 'id_ubicacion';
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['id_ubicacion', 'latitud', 'longitud', 'zona'];


}
