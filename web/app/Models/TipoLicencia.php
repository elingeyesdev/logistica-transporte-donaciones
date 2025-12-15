<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TipoLicencia
 *
 * @property $id_licencia
 * @property $licencia
 *
 * @property Conductor[] $conductor
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class TipoLicencia extends Model
{
    protected $table = 'tipo_licencia';
    protected $primaryKey = 'id_licencia';
    public $timestamps = false;

    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['licencia'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function conductor()
    {
        return $this->hasMany(\App\Models\Conductor::class, 'id_licencia', 'id_licencia');
    }
    
}
