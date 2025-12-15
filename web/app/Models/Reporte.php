<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
    protected $table = 'reporte';

    protected $primaryKey = 'id_reporte';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = true;

    protected $fillable = [
        'nombre_pdf',
        'ruta_pdf',
        'fecha_reporte',
        'gestion',
        'imagen_evidencia',
        'id_paquete'
    ];

    protected $perPage = 20;
}
