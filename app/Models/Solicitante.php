<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Solicitante
 *
 * @property $id_solicitante
 * @property $apellido
 * @property $ci
 * @property $email
 * @property $nombre
 * @property $telefono
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Solicitante extends Model
{
    protected $table = 'solicitante'; 
    protected $primaryKey = 'id_solicitante';
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['id_solicitante', 'apellido', 'ci', 'email', 'nombre', 'telefono'];


}
