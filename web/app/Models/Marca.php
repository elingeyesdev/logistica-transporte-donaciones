<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Marca
 *
 * @property $id_marca
 * @property $nombre_marca
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Marca extends Model
{
    protected $table = 'marca';
    protected $primaryKey = 'id_marca';
    protected $perPage = 20;
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['nombre_marca'];


}
