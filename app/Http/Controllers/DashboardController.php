<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\Paquete;
use App\Models\User;
use App\Models\Conductor;
use App\Models\Estado;
use App\Models\Rol;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $total = Solicitud::count();

        $estadoAceptado = ['Aprobada','Aceptada','aprobada','aceptada'];
        $estadoRechazado = ['Rechazada','Negada','rechazada','negada'];

        $aceptadas = Solicitud::whereIn('estado', $estadoAceptado)->count();

        $rechazadas = Solicitud::whereIn('estado', $estadoRechazado)->count();

        $tasa = ($aceptadas + $rechazadas) > 0
            ? round(($aceptadas / ($aceptadas + $rechazadas)) * 100, 1)
            : 0;

        $productosMasPedidos = Solicitud::whereNotNull('insumos_necesarios')
            ->where('insumos_necesarios', '!=', '')
            ->get()
            ->pluck('insumos_necesarios')
            ->flatMap(function($insumos) {
                return preg_split('/[,;\n\r]+/', $insumos, -1, PREG_SPLIT_NO_EMPTY);
            })
            ->map(function($item) {
                return trim(strtolower($item));
            })
            ->filter()
            ->countBy()
            ->sortDesc()
            ->take(5);

        $idsEntregado = Estado::whereIn('nombre_estado', ['Entregado', 'entregado'])
            ->pluck('id_estado');

        $paquetes = Paquete::whereIn('estado_id', $idsEntregado)
            ->selectRaw('
                id_paquete,
                COALESCE(fecha_creacion::date, created_at::date) as fecha_creacion,
                COALESCE(fecha_entrega::date, updated_at::date) as fecha_entrega,
                (COALESCE(fecha_entrega::date, updated_at::date) - COALESCE(fecha_creacion::date, created_at::date)) as dias_entrega
            ')
            ->orderByDesc(DB::raw('(COALESCE(fecha_entrega::date, updated_at::date) - COALESCE(fecha_creacion::date, created_at::date))'))
            ->limit(10)
            ->get();

        $promedioEntrega = Paquete::whereIn('estado_id', $idsEntregado)
            ->selectRaw('AVG(COALESCE(fecha_entrega::date, updated_at::date) - COALESCE(fecha_creacion::date, created_at::date)) as promedio')
            ->value('promedio');

        $promedioEntrega = $promedioEntrega ? round($promedioEntrega, 1) : 0;

        $totalPaquetes = Paquete::count();
        $paquetesEntregados = Paquete::whereIn('estado_id', $idsEntregado)->count();

        $idsEnCamino = Estado::whereIn('nombre_estado', ['En camino','En Camino','En tránsito','En transito','En ruta','En Ruta'])
            ->pluck('id_estado');

        $totalVoluntarios = User::where('activo', true)->count();

        $voluntariosConductores = Conductor::count();

        $solicitudesAceptadas = Solicitud::with(['solicitante','destino'])
            ->whereIn('estado', $estadoAceptado)
            ->orderByDesc('fecha_solicitud')
            ->limit(10)
            ->get()
            ->map(function($solicitud){
                $fechaCarbon = $solicitud->fecha_solicitud ? Carbon::parse($solicitud->fecha_solicitud) : null;
                $fecha = $fechaCarbon ? $fechaCarbon->format('d/m/Y') : 'Sin fecha';
                $solicitante = trim(($solicitud->solicitante->nombre ?? '') . ' ' . ($solicitud->solicitante->apellido ?? ''));
                return [
                    'id' => $solicitud->id_solicitud,
                    'codigo' => $solicitud->codigo_seguimiento ?? 'SIN-CODIGO',
                    'solicitante' => $solicitante !== '' ? $solicitante : 'Sin solicitante',
                    'comunidad' => $solicitud->destino->comunidad ?? 'Sin comunidad',
                    'fecha' => $fecha,
                    'fecha_iso' => $fechaCarbon ? $fechaCarbon->toDateString() : null,
                ];
            })
            ->values();

        $solicitudesNegadas = Solicitud::with(['solicitante','destino'])
            ->whereIn('estado', $estadoRechazado)
            ->orderByDesc('fecha_solicitud')
            ->limit(10)
            ->get()
            ->map(function($solicitud){
                $fechaCarbon = $solicitud->fecha_solicitud ? Carbon::parse($solicitud->fecha_solicitud) : null;
                $fecha = $fechaCarbon ? $fechaCarbon->format('d/m/Y') : 'Sin fecha';
                $solicitante = trim(($solicitud->solicitante->nombre ?? '') . ' ' . ($solicitud->solicitante->apellido ?? ''));
                return [
                    'id' => $solicitud->id_solicitud,
                    'codigo' => $solicitud->codigo_seguimiento ?? 'SIN-CODIGO',
                    'solicitante' => $solicitante !== '' ? $solicitante : 'Sin solicitante',
                    'comunidad' => $solicitud->destino->comunidad ?? 'Sin comunidad',
                    'fecha' => $fecha,
                    'fecha_iso' => $fechaCarbon ? $fechaCarbon->toDateString() : null,
                ];
            })
            ->values();

        $paquetesEntregadosListado = Paquete::with(['solicitud.solicitante','conductor'])
            ->whereIn('estado_id', $idsEntregado)
            ->orderByDesc(DB::raw('COALESCE(fecha_entrega, updated_at)'))
            ->limit(50)
            ->get()
            ->map(function($paquete){
                $fechaCarbon = $paquete->fecha_entrega ? Carbon::parse($paquete->fecha_entrega) : ($paquete->updated_at ? Carbon::parse($paquete->updated_at) : null);
                $fecha = $fechaCarbon ? $fechaCarbon->format('d/m/Y') : 'Sin fecha';
                $solicitante = optional(optional($paquete->solicitud)->solicitante);
                $conductor = optional($paquete->conductor);
                return [
                    'id' => $paquete->id_paquete,
                    'codigo' => $paquete->codigo ?? ('PKG-'.$paquete->id_paquete),
                    'solicitante' => $solicitante ? trim(($solicitante->nombre ?? '').' '.($solicitante->apellido ?? '')) : 'Sin solicitante',
                    'conductor' => $conductor ? trim(($conductor->nombre ?? '').' '.($conductor->apellido ?? '')) : 'Sin conductor',
                    'fecha' => $fecha,
                    'fecha_iso' => $fechaCarbon ? $fechaCarbon->toDateString() : null,
                ];
            })
            ->values();

        $paquetesEnCaminoListado = Paquete::with(['solicitud.destino','conductor','vehiculo'])
            ->whereIn('estado_id', $idsEnCamino)
            ->orderByDesc(DB::raw('COALESCE(fecha_creacion, created_at)'))
            ->limit(50)
            ->get()
            ->map(function($paquete){
                $fechaCarbon = $paquete->fecha_creacion ? Carbon::parse($paquete->fecha_creacion) : ($paquete->created_at ? Carbon::parse($paquete->created_at) : null);
                $fecha = $fechaCarbon ? $fechaCarbon->format('d/m/Y') : 'Sin fecha';
                $destino = optional(optional($paquete->solicitud)->destino);
                $conductor = optional($paquete->conductor);
                $vehiculo = optional($paquete->vehiculo);
                return [
                    'id' => $paquete->id_paquete,
                    'codigo' => $paquete->codigo ?? ('PKG-'.$paquete->id_paquete),
                    'destino' => $destino ? ($destino->comunidad ?? 'Sin comunidad') : 'Sin comunidad',
                    'provincia' => $destino ? ($destino->provincia ?? null) : null,
                    'conductor' => $conductor ? trim(($conductor->nombre ?? '').' '.($conductor->apellido ?? '')) : 'Sin conductor',
                    'vehiculo' => $vehiculo ? ($vehiculo->placa ?? 'Sin placa') : 'Sin vehículo',
                    'fecha' => $fecha,
                    'fecha_iso' => $fechaCarbon ? $fechaCarbon->toDateString() : null,
                ];
            })
            ->values();

        $solicitudesPorComunidad = Solicitud::with('destino')
            ->whereHas('destino', function($q){
                $q->whereNotNull('comunidad')->where('comunidad', '!=', '');
            })
            ->orderByDesc('fecha_solicitud')
            ->limit(100)
            ->get()
            ->map(function($solicitud){
                $fechaCarbon = $solicitud->fecha_solicitud ? Carbon::parse($solicitud->fecha_solicitud) : null;
                $fecha = $fechaCarbon ? $fechaCarbon->format('d/m/Y') : 'Sin fecha';
                $destino = $solicitud->destino;
                return [
                    'id' => $solicitud->id_solicitud,
                    'codigo' => $solicitud->codigo_seguimiento ?? 'SIN-CODIGO',
                    'comunidad' => $destino->comunidad ?? 'Sin comunidad',
                    'provincia' => $destino->provincia ?? null,
                    'fecha' => $fecha,
                    'fecha_iso' => $fechaCarbon ? $fechaCarbon->toDateString() : null,
                ];
            })
            ->values();

        $topVoluntariosPaquetes = Paquete::selectRaw('id_encargado, COUNT(*) as total')
            ->whereNotNull('id_encargado')
            ->groupBy('id_encargado')
            ->orderByDesc('total')
            ->limit(3)
            ->get()
            ->map(function($row){
                $user = User::where('ci', $row->id_encargado)->first();
                return [
                    'ci' => $row->id_encargado,
                    'nombre' => $user ? trim($user->nombre.' '.$user->apellido) : 'Desconocido',
                    'total' => $row->total,
                ];
            });

        $rolVoluntarioId = Rol::where('titulo_rol', 'Voluntario')->value('id_rol');
        $voluntariosListado = User::with('rol')
            ->when($rolVoluntarioId, function($q) use ($rolVoluntarioId) {
                $q->where('id_rol', $rolVoluntarioId);
            }, function($q) {
                $q->whereHas('rol', function($sub){
                    $sub->where('titulo_rol', 'Voluntario');
                });
            })
            ->where('activo', true)
            ->orderBy('nombre')
            ->limit(50)
            ->get()
            ->map(function($user){
                return [
                    'id' => $user->id,
                    'nombre' => trim(($user->nombre ?? '').' '.($user->apellido ?? '')) ?: 'Sin nombre',
                    'correo' => $user->correo_electronico ?? '-',
                    'telefono' => $user->telefono ?? 'Sin teléfono',
                    'ci' => $user->ci ?? 'S/N',
                ];
            })
            ->values();

        $data = compact(
            'total',
            'aceptadas',
            'rechazadas',
            'tasa',
            'productosMasPedidos',
            'paquetes',
            'promedioEntrega',
            'totalPaquetes',
            'paquetesEntregados',
            'totalVoluntarios',
            'voluntariosConductores',
            'topVoluntariosPaquetes',
            'solicitudesAceptadas',
            'solicitudesNegadas',
            'solicitudesPorComunidad',
            'voluntariosListado',
            'paquetesEntregadosListado',
            'paquetesEnCaminoListado'
        );

        // Always return JSON for API requests
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json($data);
        }

        return view('dashboard', $data);
    }
}
