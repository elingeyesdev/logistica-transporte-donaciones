<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    protected $table = 'solicitud';

    protected $primaryKey = 'id_solicitud'; 
    public $incrementing = true;
    protected $keyType = 'int';
   protected $fillable = [
        'id_solicitante',
        'id_destino',
        'cantidad_personas',
        'fecha_inicio',
        'fecha_necesidad',
        'nombre_referencia',
        'celular_referencia',
        'tipo_emergencia',
        'id_tipoemergencia',
        'insumos_necesarios',
        'codigo_seguimiento',
        'estado',
        'fecha_solicitud',
        'aprobada',
        'apoyoaceptado',
        'justificacion',
        'ci_voluntario'
    ];


    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_necesidad' => 'date',
    ];
    public function solicitante()
    {
        return $this->belongsTo(\App\Models\Solicitante::class, 'id_solicitante', 'id_solicitante');
    }
    public function destino()
    {
        return $this->belongsTo(\App\Models\Destino::class, 'id_destino', 'id_destino');
    }
     public function tipoEmergencia()
    {
        return $this->belongsTo(\App\Models\TipoEmergencia::class, 'id_tipoemergencia', 'id_emergencia');
    }
}
