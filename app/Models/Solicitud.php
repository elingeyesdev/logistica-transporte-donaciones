<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    use HasFactory;

    protected $table = 'solicitud';
    protected $primaryKey = 'id_solicitud';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nombre',
        'apellido',
        'carnet_identidad',
        'correo_electronico',
        'comunidad_solicitante',
        'ubicacion',
        'provincia',
        'nro_celular',
        'cantidad_personas',
        'fecha_inicio',
        'tipo_emergencia',
        'insumos_necesarios',
        'codigo_seguimiento',
        // Campos requeridos por la tabla antigua
        'nombre_solicitante',
        'fecha_creacion',
        'descripcion',
        'estado',
    ];

    public function getRouteKeyName()
    {
        return 'id_solicitud';
    }
}
