<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\Paquete;
use App\Models\User;
use App\Models\Conductor;
use App\Models\Estado;
use App\Models\Rol;
use App\Models\Vehiculo;
use App\Models\Reporte;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DashboardReportExport;
class DashboardController extends Controller
{
    public function index()
    {
        $total = Solicitud::count();

        $estadoAceptado = ['Aprobada','Aceptada','aprobada','aceptada'];
        $estadoRechazado = ['Rechazada','Negada','rechazada','negada'];

        $estadoCatalogo = Estado::select('id_estado','nombre_estado')->get();
        $normalizeEstado = fn($nombre) => strtolower(trim($nombre ?? ''));

        $estadosEntregadosObjetivo = ['entregado','entregada'];
        $estadosArmadosObjetivo = ['armado','armada'];

        $estadosEnCaminoObjetivo = [
            'en camino','en tránsito','en transito','en ruta','en viaje','en transporte'
        ];

        $estadosPendienteObjetivo = [
            'pendiente','en preparación','en preparacion','sin asignar'
        ];

        $idsEntregado = $estadoCatalogo
            ->filter(fn($estado) => in_array($normalizeEstado($estado->nombre_estado), $estadosEntregadosObjetivo, true))
            ->pluck('id_estado');

        $idsEnCamino = $estadoCatalogo
            ->filter(fn($estado) => in_array($normalizeEstado($estado->nombre_estado), $estadosEnCaminoObjetivo, true))
            ->pluck('id_estado');
         $idsArmados = $estadoCatalogo
            ->filter(fn($estado) => in_array($normalizeEstado($estado->nombre_estado), $estadosArmadosObjetivo, true))
            ->pluck('id_estado');

        $idsPendiente = $estadoCatalogo
            ->filter(fn($estado) => in_array($normalizeEstado($estado->nombre_estado), $estadosPendienteObjetivo, true))
            ->pluck('id_estado');


        $aceptadas = Solicitud::whereIn('estado', $estadoAceptado)->count();

        $rechazadas = Solicitud::whereIn('estado', $estadoRechazado)->count();

        $tasa = ($aceptadas + $rechazadas) > 0
            ? round(($aceptadas / ($aceptadas + $rechazadas)) * 100, 1)
            : 0;

        $productosMasPedidos = $this->obtenerTopProductosDesdeInventario();

        if ($productosMasPedidos->isEmpty()) {
            $productosMasPedidos = $this->calcularTopProductosDesdeSolicitudes();
        }

        $paquetes = Paquete::with(['solicitud' => function ($q) {
            $q->select('id_solicitud', 'codigo_seguimiento');
        }])
        ->whereIn('estado_id', $idsEntregado)
        ->selectRaw('
            paquete.id_paquete,
            paquete.id_solicitud,
            paquete.created_at::date as fecha_create,
            COALESCE(paquete.fecha_entrega::date, paquete.updated_at::date) as fecha_entrega,
            (COALESCE(paquete.fecha_entrega::date, paquete.updated_at::date)
            - COALESCE(paquete.created_at::date)) as dias_entrega
        ')
        ->orderBy(DB::raw('
            (COALESCE(paquete.fecha_entrega::date, paquete.updated_at::date)
            - COALESCE(paquete.fecha_creacion::date, paquete.created_at::date))
        '))
        ->limit(20)
        ->get();

        $promedioEntrega = Paquete::whereIn('estado_id', $idsEntregado)
            ->selectRaw('AVG(COALESCE(created_at::date, updated_at::date) - COALESCE(created_at::date)) as promedio')
            ->value('promedio');

        $promedioEntrega = $promedioEntrega ? round($promedioEntrega, 2) : 0;

        $totalPaquetes = Paquete::count();
        $paquetesEntregados = Paquete::whereIn('estado_id', $idsEntregado)->count();
        $paquetesEnCamino = Paquete::whereIn('estado_id', $idsEnCamino)->count();
        $paquetesPendientes = $idsPendiente->isNotEmpty()
            ? Paquete::whereIn('estado_id', $idsPendiente)->count()
            : Paquete::whereNotIn('estado_id', $idsEntregado->merge($idsEnCamino))->count();
        $paquetesArmados = Paquete::whereIn('estado_id', $idsArmados)->count();
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
                $fechaInicioCarbon = $solicitud->fecha_inicio ? Carbon::parse($solicitud->fecha_inicio) : null;
                $solicitanteModel = optional($solicitud->solicitante);
                $destino = optional($solicitud->destino);
                $solicitante = trim(($solicitanteModel->nombre ?? '') . ' ' . ($solicitanteModel->apellido ?? ''));
                return [
                    'id' => $solicitud->id_solicitud,
                    'codigo' => $solicitud->codigo_seguimiento ?? 'SIN-CODIGO',
                    'solicitante' => $solicitante !== '' ? $solicitante : 'Sin solicitante',
                    'comunidad' => $destino->comunidad ?? 'Sin comunidad',
                    'fecha' => $fecha,
                    'fecha_iso' => $fechaCarbon ? $fechaCarbon->toDateString() : null,
                    'estado' => $solicitud->estado ?? 'Sin estado',
                    'tipo_emergencia' => $solicitud->tipo_emergencia ?? 'Sin tipo',
                    'cantidad_personas' => $solicitud->cantidad_personas,
                    'insumos' => $solicitud->insumos_necesarios,
                    'fecha_inicio' => $fechaInicioCarbon ? $fechaInicioCarbon->format('d/m/Y') : 'Sin fecha',
                    'solicitante_ci' => $solicitanteModel->ci ?? '—',
                    'solicitante_correo' => $solicitanteModel->email ?? '—',
                    'solicitante_telefono' => $solicitanteModel->telefono ?? '—',
                    'provincia' => $destino->provincia ?? '—',
                    'direccion' => $destino->direccion ?? '—',
                    'latitud' => $destino->latitud,
                    'longitud' => $destino->longitud,
                    'justificacion' => $solicitud->justificacion,
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
                $fechaInicioCarbon = $solicitud->fecha_inicio ? Carbon::parse($solicitud->fecha_inicio) : null;
                $solicitanteModel = optional($solicitud->solicitante);
                $destino = optional($solicitud->destino);
                $solicitante = trim(($solicitanteModel->nombre ?? '') . ' ' . ($solicitanteModel->apellido ?? ''));
                return [
                    'id' => $solicitud->id_solicitud,
                    'codigo' => $solicitud->codigo_seguimiento ?? 'SIN-CODIGO',
                    'solicitante' => $solicitante !== '' ? $solicitante : 'Sin solicitante',
                    'comunidad' => $destino->comunidad ?? 'Sin comunidad',
                    'fecha' => $fecha,
                    'fecha_iso' => $fechaCarbon ? $fechaCarbon->toDateString() : null,
                    'estado' => $solicitud->estado ?? 'Sin estado',
                    'tipo_emergencia' => $solicitud->tipo_emergencia ?? 'Sin tipo',
                    'cantidad_personas' => $solicitud->cantidad_personas,
                    'insumos' => $solicitud->insumos_necesarios,
                    'fecha_inicio' => $fechaInicioCarbon ? $fechaInicioCarbon->format('d/m/Y') : 'Sin fecha',
                    'solicitante_ci' => $solicitanteModel->ci ?? '—',
                    'solicitante_correo' => $solicitanteModel->email ?? '—',
                    'solicitante_telefono' => $solicitanteModel->telefono ?? '—',
                    'provincia' => $destino->provincia ?? '—',
                    'direccion' => $destino->direccion ?? '—',
                    'latitud' => $destino->latitud,
                    'longitud' => $destino->longitud,
                ];
            })
            ->values();

        $paquetesEntregadosListado = Paquete::with([
                'solicitud.solicitante',
                'solicitud.destino',
                'conductor',
                'vehiculo',
                'estado'
            ])
            ->whereIn('estado_id', $idsEntregado)
            ->orderByDesc(DB::raw('COALESCE(fecha_entrega, updated_at)'))
            ->limit(50)
            ->get()
            ->map(function($paquete){
                $fechaEntregaCarbon = $paquete->fecha_entrega ? Carbon::parse($paquete->fecha_entrega) : ($paquete->updated_at ? Carbon::parse($paquete->updated_at) : null);
                $fechaEntrega = $fechaEntregaCarbon ? $fechaEntregaCarbon->format('d/m/Y') : 'Sin fecha';
                $fechaCreacionCarbon = Carbon::parse($paquete->created_at);
                $fechaAprobacionCarbon = $paquete->fecha_aprobacion ? Carbon::parse($paquete->fecha_aprobacion) : null;
                $solicitud = optional($paquete->solicitud);
                $solicitante = optional($solicitud->solicitante);
                $destino = optional($solicitud->destino);
                $conductor = optional($paquete->conductor);
                $vehiculo = optional($paquete->vehiculo);
                $estado = optional($paquete->estado);
                $solicitanteNombre = trim(($solicitante->nombre ?? '').' '.($solicitante->apellido ?? '')) ?: 'Sin solicitante';
                $conductorNombre = trim(($conductor->nombre ?? '').' '.($conductor->apellido ?? '')) ?: 'Sin conductor';
                return [
                    'id' => $paquete->id_paquete,
                    'codigo' => $paquete->codigo ?? ('PKG-'.$paquete->id_paquete),
                    'solicitud_codigo' => $solicitud->codigo_seguimiento ?? 'SIN-CODIGO',
                    'estado' => $estado->nombre_estado ?? 'Sin estado',
                    'descripcion' => $paquete->descripcion ?? 'Sin descripción',
                    'cantidad_total' => $paquete->cantidad_total,
                    'ubicacion_actual' => $paquete->ubicacion_actual ?? '-',
                    'fecha_creacion' => $fechaCreacionCarbon ? $fechaCreacionCarbon->format('d/m/Y') : 'Sin fecha',
                    'fecha_creacion_iso' => $fechaCreacionCarbon ? $fechaCreacionCarbon->toDateString() : null,
                    'fecha_aprobacion' => $fechaAprobacionCarbon ? $fechaAprobacionCarbon->format('d/m/Y') : 'Sin fecha',
                    'fecha_entrega' => $fechaEntrega,
                    'fecha' => $fechaEntrega,
                    'fecha_iso' => $fechaEntregaCarbon ? $fechaEntregaCarbon->toDateString() : null,
                    'solicitante' => $solicitanteNombre,
                    'solicitante_ci' => $solicitante->ci ?? '—',
                    'solicitante_correo' => $solicitante->email ?? '—',
                    'solicitante_telefono' => $solicitante->telefono ?? '—',
                    'destino_comunidad' => $destino->comunidad ?? 'Sin comunidad',
                    'destino_provincia' => $destino->provincia ?? '—',
                    'destino_direccion' => $destino->direccion ?? '—',
                    'conductor' => $conductorNombre,
                    'conductor_ci' => $conductor->ci ?? '—',
                    'conductor_telefono' => $conductor->celular ?? '—',
                    'vehiculo' => $vehiculo ? ($vehiculo->marca ?? 'Sin marca') : 'Sin vehículo',
                    'vehiculo_placa' => $vehiculo->placa ?? '—',
                ];
            })
            ->values();

        $paquetesEnCaminoListado = Paquete::with([
                'solicitud.destino',
                'solicitud.solicitante',
                'conductor',
                'vehiculo.marcaVehiculo',
                'estado',
                'encargado'
            ])
            ->whereIn('estado_id', $idsEnCamino)
            ->orderByDesc(DB::raw('COALESCE(fecha_creacion, created_at)'))
            ->limit(50)
            ->get()
            ->map(function($paquete){
                $fechaCarbon = $paquete->created_at ? Carbon::parse($paquete->created_at) : ($paquete->created_at ? Carbon::parse($paquete->created_at) : null);
                $fecha = $fechaCarbon ? $fechaCarbon->format('d/m/Y') : 'Sin fecha';
                $solicitud = optional($paquete->solicitud);
                $destino = optional($solicitud->destino);
                $solicitante = optional($solicitud->solicitante);
                $conductor = optional($paquete->conductor);
                $vehiculo = optional($paquete->vehiculo);
                $vehiculoMarca = optional($vehiculo->marcaVehiculo);
                $estado = optional($paquete->estado);
                $encargado = optional($paquete->encargado);
                $solicitanteNombre = trim(($solicitante->nombre ?? '').' '.($solicitante->apellido ?? '')) ?: 'Sin solicitante';
                $conductorNombre = trim(($conductor->nombre ?? '').' '.($conductor->apellido ?? '')) ?: 'Sin conductor';
                $encargadoNombre = trim(($encargado->nombre ?? '').' '.($encargado->apellido ?? '')) ?: '—';
                $solicitudFechaCarbon = $solicitud && ($solicitud->fecha_creacion || $solicitud->created_at)
                    ? Carbon::parse($solicitud->fecha_creacion ?? $solicitud->created_at)
                    : null;
                return [
                    'id' => $paquete->id_paquete,
                    'codigo' => $paquete->codigo ?? ('PKG-'.$paquete->id_paquete),
                    'solicitud_codigo' => $solicitud->codigo_seguimiento ?? 'SIN-CODIGO',
                    'destino' => $destino ? ($destino->comunidad ?? 'Sin comunidad') : 'Sin comunidad',
                    'provincia' => $destino ? ($destino->provincia ?? null) : null,
                    'destino_comunidad' => $destino->comunidad ?? 'Sin comunidad',
                    'destino_provincia' => $destino->provincia ?? '—',
                    'destino_direccion' => $destino->direccion ?? '—',
                    'tipo_emergencia' => $solicitud->tipo_emergencia ?? '—',
                    'solicitante' => $solicitanteNombre,
                    'solicitante_ci' => $solicitante->ci ?? '—',
                    'solicitante_correo' => $solicitante->email ?? '—',
                    'solicitante_telefono' => $solicitante->telefono ?? '—',
                    'conductor' => $conductorNombre,
                    'conductor_ci' => $conductor->ci ?? '—',
                    'conductor_telefono' => $conductor->celular ?? '—',
                    'vehiculo' => $vehiculo ? ($vehiculo->placa ?? 'Sin placa') : 'Sin vehículo',
                    'vehiculo_marca' => $vehiculoMarca->nombre_marca ?? $vehiculoMarca->nombre ?? 'Sin marca',
                    'vehiculo_modelo' => $vehiculo->modelo ?? '—',
                    'vehiculo_color' => $vehiculo->color ?? '—',
                    'estado' => $estado->nombre_estado ?? 'Sin estado',
                    'ubicacion_actual' => $paquete->ubicacion_actual ?? '—',
                    'voluntario' => $encargadoNombre,
                    'voluntario_ci' => $paquete->id_encargado ?? '—',
                    'descripcion' => $paquete->descripcion ?? 'Sin descripción',
                    'cantidad_total' => $paquete->cantidad_total,
                    'fecha_solicitud_creacion' => $solicitudFechaCarbon ? $solicitudFechaCarbon->format('d/m/Y') : '—',
                    'fecha' => $fecha,
                    'fecha_iso' => $fechaCarbon ? $fechaCarbon->toDateString() : null,
                    'fecha_creacion' => $fecha,
                ];
            })
            ->values();

        $solicitudesPorComunidad = Solicitud::with(['destino','solicitante'])
            ->whereHas('destino', function($q){
                $q->whereNotNull('comunidad')->where('comunidad', '!=', '');
            })
            ->orderByDesc('fecha_solicitud')
            ->limit(100)
            ->get()
            ->map(function($solicitud){
                $fechaCarbon = $solicitud->fecha_solicitud ? Carbon::parse($solicitud->fecha_solicitud) : null;
                $fecha = $fechaCarbon ? $fechaCarbon->format('d/m/Y') : 'Sin fecha';
                $fechaInicioCarbon = $solicitud->fecha_inicio ? Carbon::parse($solicitud->fecha_inicio) : null;
                $destino = $solicitud->destino;
                $solicitanteModel = optional($solicitud->solicitante);
                $solicitanteNombre = trim(($solicitanteModel->nombre ?? '').' '.($solicitanteModel->apellido ?? '')) ?: 'Sin solicitante';
                return [
                    'id' => $solicitud->id_solicitud,
                    'codigo' => $solicitud->codigo_seguimiento ?? 'SIN-CODIGO',
                    'comunidad' => $destino->comunidad ?? 'Sin comunidad',
                    'provincia' => $destino->provincia ?? null,
                    'fecha' => $fecha,
                    'fecha_iso' => $fechaCarbon ? $fechaCarbon->toDateString() : null,
                    'fecha_inicio' => $fechaInicioCarbon ? $fechaInicioCarbon->format('d/m/Y') : 'Sin fecha',
                    'tipo_emergencia' => $solicitud->tipo_emergencia ?? 'Sin tipo',
                    'solicitante' => $solicitanteNombre,
                    'solicitante_ci' => $solicitanteModel->ci ?? '—',
                    'solicitante_correo' => $solicitanteModel->email ?? '—',
                    'solicitante_telefono' => $solicitanteModel->telefono ?? '—',
                    'insumos' => $solicitud->insumos_necesarios ?? '',
                    'direccion' => $destino->direccion ?? '—',
                    'estado' => $solicitud->estado ?? 'Sin estado',
                    'cantidad_personas' => $solicitud->cantidad_personas,
                    'latitud' => $destino->latitud,
                    'longitud' => $destino->longitud,

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
        $voluntariosListadoCollection = User::with('rol')
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
            ->get();

        $voluntariosListado = $voluntariosListadoCollection
            ->map(function($user){
                return [
                    'id' => $user->id,
                    'nombre' => trim(($user->nombre ?? '').' '.($user->apellido ?? '')) ?: 'Sin nombre',
                    'correo' => $user->correo_electronico ?? '-',
                    'telefono' => $user->telefono ?? 'Sin teléfono',
                    'ci' => $user->ci ?? 'S/N',
                ];
            });

        $voluntariosCi = $voluntariosListado->pluck('ci')->filter()->unique();
        $paquetesPorVoluntario = [];

        if ($voluntariosCi->isNotEmpty()) {
            $paquetesPorVoluntario = Paquete::with(['estado','solicitud'])
                ->whereIn('id_encargado', $voluntariosCi)
                ->orderByDesc(DB::raw('COALESCE(fecha_creacion, created_at)'))
                ->get()
                ->groupBy('id_encargado')
                ->map(function($items) {
                    return $items->take(3)->map(function($paquete) {
                        $solicitud = optional($paquete->solicitud);
                        $codigoSolicitud = $solicitud->codigo_seguimiento ?? 'SIN-CODIGO';
                        $fechaBase = $paquete->fecha_creacion ?? $paquete->created_at;
                        $fechaCarbon = $fechaBase ? Carbon::parse($fechaBase) : null;
                        return [
                            'id' => $paquete->id_paquete,
                            'solicitud_codigo' => $codigoSolicitud,
                            'estado' => optional($paquete->estado)->nombre_estado ?? 'Sin estado',
                            'fecha' => $fechaCarbon ? $fechaCarbon->format('d/m/Y') : 'Sin fecha',
                            'fecha_iso' => $fechaCarbon ? $fechaCarbon->toDateString() : null,
                            'created_at' => $paquete->created_at ? Carbon::parse($paquete->created_at)->toIso8601String() : null,
                        ];
                    })->values();
                })
                ->toArray();
        }

        $voluntariosListado = $voluntariosListado->map(function($voluntario) use ($paquetesPorVoluntario) {
            $ci = $voluntario['ci'] ?? null;
            $voluntario['paquetes'] = $ci && isset($paquetesPorVoluntario[$ci])
                ? $paquetesPorVoluntario[$ci]
                : [];
            return $voluntario;
        })->values();

        $vehiculosCollection = Vehiculo::with(['marcaVehiculo','tipoVehiculo'])
            ->orderBy('placa')
            ->limit(50)
            ->get();

        $vehiculosListado = $vehiculosCollection->map(function($vehiculo) {
            $marca = optional($vehiculo->marcaVehiculo);
            $tipo = optional($vehiculo->tipoVehiculo);
            return [
                'id' => $vehiculo->id_vehiculo,
                'placa' => $vehiculo->placa ?? 'Sin placa',
                'marca' => $marca->nombre_marca ?? $marca->nombre ?? 'Sin marca',
                'modelo' => $vehiculo->modelo ?? '-',
                'color' => $vehiculo->color ?? '-',
                'tipo' => $tipo->nombre_tipo_vehiculo ?? 'Sin tipo',
            ];
        });

        $vehiculosIds = $vehiculosCollection->pluck('id_vehiculo')->filter()->unique();
        $paquetesPorVehiculo = [];

        if ($vehiculosIds->isNotEmpty()) {
            $paquetesPorVehiculo = Paquete::with(['estado','solicitud'])
                ->whereIn('id_vehiculo', $vehiculosIds)
                ->orderByDesc(DB::raw('COALESCE(fecha_creacion, created_at)'))
                ->get()
                ->groupBy('id_vehiculo')
                ->map(function($items) {
                    return $items->take(3)->map(function($paquete) {
                        $solicitud = optional($paquete->solicitud);
                        $fechaCarbon = $paquete->fecha_creacion
                            ? Carbon::parse($paquete->fecha_creacion)
                            : ($paquete->created_at ? Carbon::parse($paquete->created_at) : null);
                        return [
                            'id' => $paquete->id_paquete,
                            'codigo_paquete' => $paquete->codigo ?? ('PKG-'.$paquete->id_paquete),
                            'solicitud_codigo' => $solicitud->codigo_seguimiento ?? 'SIN-CODIGO',
                            'estado' => optional($paquete->estado)->nombre_estado ?? 'Sin estado',
                            'fecha' => $fechaCarbon ? $fechaCarbon->format('d/m/Y') : 'Sin fecha',
                            'fecha_iso' => $fechaCarbon ? $fechaCarbon->toDateString() : null,
                            'created_at' => $paquete->created_at ? Carbon::parse($paquete->created_at)->toIso8601String() : null,
                        ];
                    })->values();
                })
                ->toArray();
        }

        $vehiculosListado = $vehiculosListado->map(function($vehiculo) use ($paquetesPorVehiculo) {
            $id = $vehiculo['id'] ?? null;
            $vehiculo['paquetes'] = $id && isset($paquetesPorVehiculo[$id])
                ? $paquetesPorVehiculo[$id]
                : [];
            return $vehiculo;
        })->values();

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
            'paquetesEnCamino',
            'paquetesPendientes',
            'paquetesArmados',
            'totalVoluntarios',
            'voluntariosConductores',
            'topVoluntariosPaquetes',
            'solicitudesAceptadas',
            'solicitudesNegadas',
            'solicitudesPorComunidad',
            'voluntariosListado',
            'paquetesEntregadosListado',
            'paquetesEnCaminoListado',
            'vehiculosListado'
        );

        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json($data);
        }

        return view('dashboard', $data);
    }

    private function obtenerTopProductosDesdeInventario()
    {
        $baseUrl = rtrim(config('services.inventario.base_url'), '/');
        if (!$baseUrl) {
            return collect();
        }

        try {
            $response = Http::timeout(8)
                ->acceptJson()
                ->get($baseUrl.'/api/inventario/por-producto');

            if (!$response->successful()) {
                return collect();
            }

            $payload = $response->json();
            if (!is_array($payload)) {
                return collect();
            }

            return collect($payload)
                ->map(function ($item) {
                    $nombre = trim((string)($item['nombre'] ?? '')); 
                    $cantidad = (int)($item['stock_total'] ?? $item['cantidad'] ?? $item['stock'] ?? 0);
                    return $nombre !== '' ? ['nombre' => $nombre, 'cantidad' => max($cantidad, 0)] : null;
                })
                ->filter()
                ->groupBy('nombre')
                ->map(fn($items) => (int)$items->sum('cantidad'))
                ->sortDesc()
                ->take(5);
        } catch (\Throwable $exception) {
            report($exception);
            return collect();
        }
    }

    private function calcularTopProductosDesdeSolicitudes()
    {
        return Solicitud::whereNotNull('insumos_necesarios')
            ->where('insumos_necesarios', '!=', '')
            ->get()
            ->pluck('insumos_necesarios')
            ->flatMap(function ($insumos) {
                return preg_split('/[,;\n\r]+/', $insumos, -1, PREG_SPLIT_NO_EMPTY);
            })
            ->map(function ($item) {
                return trim(strtolower($item));
            })
            ->filter()
            ->countBy()
            ->sortDesc()
            ->take(5);
    }

    public function exportExcel(Request $request)
    {
        $validated = $request->validate([
            'group' => ['required', 'string', 'max:50'],
            'type' => ['required', 'string', 'max:50'],
            'headings' => ['required', 'array', 'min:1'],
            'headings.*' => ['required', 'string'],
            'rows' => ['required', 'array', 'min:1'],
            'rows.*' => ['array'],
            'fecha_reporte' => ['nullable', 'date'],
            'gestion' => ['nullable', 'string', 'max:255'],
        ]);

        $columnCount = count($validated['headings']);
        $rows = collect($validated['rows'])->map(function ($row) use ($columnCount) {
            $normalized = array_map(function ($value) {
                if (is_array($value) || is_object($value)) {
                    return json_encode($value, JSON_UNESCAPED_UNICODE);
                }
                return $value ?? '';
            }, $row);

            $normalized = array_values($normalized);
            if (count($normalized) < $columnCount) {
                $normalized = array_pad($normalized, $columnCount, '');
            } elseif (count($normalized) > $columnCount) {
                $normalized = array_slice($normalized, 0, $columnCount);
            }

            return $normalized;
        })->toArray();

        $filename = Str::slug($validated['group'].'_'.$validated['type'].'_'.now()->format('Ymd_His')).'.xlsx';
        $export = new DashboardReportExport($rows, $validated['headings']);
        $excelBinary = Excel::raw($export, \Maatwebsite\Excel\Excel::XLSX);

        $relativePath = 'reportes/'.$filename;
        $stored = Storage::disk('public')->put($relativePath, $excelBinary);

        if (!$stored) {
            abort(500, 'No se pudo guardar el reporte en el almacenamiento.');
        }

        $fechaReporte = $validated['fecha_reporte'] ?? now()->toDateString();
        $gestion = $validated['gestion'] ?? now()->format('Y');

        Reporte::create([
            'nombre_pdf' => $filename,
            'ruta_pdf' => $relativePath,
            'fecha_reporte' => $fechaReporte,
            'gestion' => $gestion,
            'id_paquete' => null,
        ]);

        return response($excelBinary, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Content-Length' => strlen($excelBinary),
        ]);
    }
}
