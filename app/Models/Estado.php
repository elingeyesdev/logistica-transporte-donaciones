<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Estado
 *
 * @property int $id_estado
 * @property string $nombre_estado
 * @property string|null $descripcion
 * @property string $tipo
 * @property string|null $color
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 */
class Estado extends Model
{
    use HasFactory;

    protected $table = 'estado';
    protected $primaryKey = 'id_estado';

    protected $fillable = [
        'nombre_estado',
    ];
    protected $perPage = 20;
}
