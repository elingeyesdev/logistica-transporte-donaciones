<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
    // 🔹 Nombre real de la tabla (Laravel intenta pluralizar si no lo pones)
    protected $table = 'reporte';

    // 🔹 Nombre de la columna ID real
    protected $primaryKey = 'id_reporte';

    // 🔹 Laravel usa incrementing true para claves numéricas
    public $incrementing = true;

    // 🔹 Tipo de dato de la clave primaria
    protected $keyType = 'int';

    // 🔹 Indica que la tabla tiene created_at y updated_at
    public $timestamps = true;

    // 🔹 Campos que se pueden llenar con create() o update()
    protected $fillable = [
        'direccion_archivo',
        'fecha_reporte',
        'gestion',
    ];

    // 🔹 Opcional: cantidad de filas por página (para paginación en index)
    protected $perPage = 20;
}
