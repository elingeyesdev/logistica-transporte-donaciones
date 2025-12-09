<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\Paquete;
use App\Models\HistorialSeguimientoDonacione;
use Illuminate\Http\Request;

class TrazabilidadController extends Controller
{
    public function porVoluntario(string $ci)
    {
        $solicitudes = Solicitud::where('ci_voluntario', $ci)
            ->with('solicitante')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($s) {
                return [
                    'id_solicitud'       => $s->id_solicitud,
                    'codigo_seguimiento' => $s->codigo_seguimiento,
                    'accion'             => $s->aprobada ? 'aprobada' : 'negada',
                    'fecha'              => $s->updated_at->format('d-m-Y'),
                    'justificacion'      => $s->justificacion,
                ];
            });

        $paquetes = Paquete::where('id_encargado', $ci)
            ->with(['solicitud', 'estado'])
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($p) {
                $historial = HistorialSeguimientoDonacione::where('id_paquete', $p->id_paquete)
                    ->with('ubicacion')
                    ->orderBy('fecha_actualizacion', 'asc')
                    ->get()
                    ->map(function ($h) {
                        return [
                            'fecha'     => $h->fecha_actualizacion,
                            'estado'    => $h->estado,
                            'ubicacion' => $h->ubicacion->zona ?? null,
                            'lat'       => optional($h->ubicacion)->latitud,
                            'lng'       => optional($h->ubicacion)->longitud,
                            'conductor' => $h->conductor_nombre,
                            'vehiculo'  => $h->vehiculo_placa,
                            'imagen'    => $h->imagen_evidencia ? asset('storage/' . $h->imagen_evidencia) : null,
                        ];
                    });

                return [
                    'id_paquete'       => $p->id_paquete,
                    'estado_actual'    => optional($p->estado)->nombre_estado,
                    'codigo'           => optional($p->solicitud)->codigo_seguimiento,
                    'creado'           => $p->created_at->format('d/m/Y'),
                    'historial'        => $historial,
                ];
            });

        return response()->json([
            'success'       => true,
            'ci_voluntario' => $ci,
            'solicitudes'   => $solicitudes,
            'paquetes'      => $paquetes,
        ]);
    }
}
