<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Destino
 *
 * @property $created_at
 * @property $updated_at
 * @property $id_destino
 * @property $comunidad
 * @property $direccion
 * @property $latitud
 * @property $longitud
 * @property $provincia
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Destino extends Model
{
    protected $table = 'destino';
    protected $primaryKey = 'id_destino';
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['id_destino', 'comunidad', 'direccion', 'latitud', 'longitud', 'provincia'];


}
