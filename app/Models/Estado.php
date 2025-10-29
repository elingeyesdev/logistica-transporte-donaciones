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

    // 👇 Nombre real de la tabla (evita pluralización)
    protected $table = 'estado';

    // 👇 Clave primaria real
    protected $primaryKey = 'id_estado';

    // 👇 Campos que se pueden asignar masivamente
    protected $fillable = [
        'nombre_estado',
        'descripcion',
        'tipo',
        'color',
    ];

    // 👇 Paginación por defecto
    protected $perPage = 20;
}
