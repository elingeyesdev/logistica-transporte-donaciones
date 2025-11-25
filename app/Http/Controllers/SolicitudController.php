<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\Solicitante;
use App\Models\Destino;
use App\Http\Requests\SolicitudRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Paquete;
use App\Models\Estado;

class SolicitudController extends Controller
{
    public function index(Request $request)
    {
        $solicituds = Solicitud::with(['solicitante','destino'])->paginate();
        
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $solicituds
            ]);
        }
        return view('solicitud.index', compact('solicituds'))
            ->with('i', ($request->input('page', 1) - 1) * $solicituds->perPage());
    }

    public function create(): View
    {
        $solicitud = new Solicitud();
        $tipoEmergencia = \App\Models\TipoEmergencia::orderBy('prioridad', 'desc')->get();
        return view('solicitud.create', compact('solicitud', 'tipoEmergencia'));
    }

   public function store(SolicitudRequest $request)
{
    $data = $request->validated();
    $solicitud = DB::transaction(function () use ($data) {

        $solicitante = Solicitante::firstOrCreate(
            ['ci' => $data['carnet_identidad']],
            [
                'nombre'   => $data['nombre'],
                'apellido' => $data['apellido'],
                'email'    => $data['correo_electronico'] ?? null,
                'telefono' => $data['nro_celular'] ?? null,
            ]
        );

        $destino = Destino::create([
            'comunidad' => $data['comunidad_solicitante'] ?? null,
            'provincia' => $data['provincia'] ?? null,
            'direccion' => $data['ubicacion'] ?? null,
            'latitud'   => $data['latitud'] ?? null,
            'longitud'  => $data['longitud'] ?? null,
        ]);

        $tipoEmergencia = \App\Models\TipoEmergencia::find($data['id_tipoemergencia']);
        
        $solicitud = Solicitud::create([
            'id_solicitante'     => $solicitante->id_solicitante,
            'id_destino'         => $destino->id_destino,
            'cantidad_personas'  => $data['cantidad_personas'],
            'fecha_inicio'       => $data['fecha_inicio'],
            'id_tipoemergencia'  => $data['id_tipoemergencia'],
            'tipo_emergencia'    => $tipoEmergencia->emergencia ?? null,
            'insumos_necesarios' => $data['insumos_necesarios'],
            'codigo_seguimiento' => $data['codigo_seguimiento'] ?? null,
            'estado'             => 'pendiente',
            'fecha_solicitud'    => $data['fecha_solicitud'] ?? now()->toDateString(),
            'aprobada'           => (bool)($data['aprobada'] ?? false),
            'apoyoaceptado'      => (bool)($data['apoyoaceptado'] ?? false),
            'justificacion'      => $data['justificacion'] ?? null,
        ]);

        return $solicitud;
    });

    // JSON TEST PARA EL GATEWAY
    if ($request->is('api/*')) {
        return response()->json([
            'success' => true,
            'data'    => $solicitud,
            'message' => 'Solicitud creada correctamente.',
        ], 201);
    }

     return redirect()
        ->route('solicitud.show', $solicitud->id_solicitud)
        ->with('success', 'Solicitud creada correctamente.');
}
    public function show($id): View
    {
        $solicitud = Solicitud::with(['solicitante','destino'])->findOrFail($id);
    return view('solicitud.show', compact('solicitud'));
    }

    public function edit($id): View
    {
        $solicitud = Solicitud::with(['solicitante','destino'])->findOrFail($id);
        $tipoEmergencia = \App\Models\TipoEmergencia::orderBy('prioridad', 'desc')->get();
        return view('solicitud.edit', compact('solicitud', 'tipoEmergencia'));
    }

    public function update(SolicitudRequest $request, Solicitud $solicitud)
    {
        $data = $request->validated();

        $solicitud = DB::transaction(function () use ($data, $solicitud) {

            $solicitud->solicitante?->update([
                'nombre'  => $data['nombre'],
                'apellido'=> $data['apellido'],
                'ci'      => $data['carnet_identidad'] ?? null,
                'email'   => $data['correo_electronico'] ?? null,
                'telefono'=> $data['nro_celular'] ?? null,
            ]);

            $solicitud->destino?->update([
                'comunidad' => $data['comunidad_solicitante'] ?? null,
                'provincia' => $data['provincia'] ?? null,
                'direccion' => $data['ubicacion'] ?? null,
                'latitud'   => $data['latitud'] ?? null,
                'longitud'  => $data['longitud'] ?? null,
            ]);

            $solicitud->update([
                'cantidad_personas'  => $data['cantidad_personas'],
                'fecha_inicio'       => $data['fecha_inicio'],
                'id_tipoemergencia'    => $data['id_tipoemergencia'],
                'insumos_necesarios' => $data['insumos_necesarios'],
                'codigo_seguimiento' => $data['codigo_seguimiento'] ?? $solicitud->codigo_seguimiento,
                'fecha_solicitud'    => $data['fecha_solicitud'] ?? $solicitud->fecha_solicitud,
                'aprobada'           => (bool)($data['aprobada'] ?? $solicitud->aprobada),
                'apoyoaceptado'      => (bool)($data['apoyoaceptado'] ?? $solicitud->apoyoaceptado),
                'justificacion'      => $data['justificacion'] ?? $solicitud->justificacion,
            ]);

            return $solicitud;

        });
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $solicitud,
                'message' => 'Solicitud actualizada correctamente'
            ]);
        }

        return redirect()
            ->route('solicitud.show', $solicitud->id_solicitud)
            ->with('success', 'Solicitud actualizada correctamente.');
    }

    public function destroy(Request $request, $id)
    {
        $solicitud = Solicitud::find($id);

        if (!$solicitud) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Solicitud no encontrada'
                ], 404);
            }

            return redirect()->route('solicitud.index')
                ->with('error', 'Solicitud no existe.');
        }

        $solicitud->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()
            ->route('solicitud.index')
            ->with('success', 'Solicitud eliminada correctamente.');
    }
    public function aprobar(Request $request, int $id)
    {
        $solicitud = Solicitud::with(['solicitante', 'destino'])->findOrFail($id);

        if ($solicitud->estado !== 'pendiente') {            
             return redirect()->back()->with('error', 'Esta solicitud ya fue respondida.');
        }

        $estadoPendiente = Estado::where('nombre_estado', 'Pendiente')->first();

        if (!$estadoPendiente) {
            return redirect()->back()->with('error', 'No se encontró el estado Pendiente para el paquete.');
        }

        $paquete = null;

       $paquete = DB::transaction(function () use ($solicitud, $estadoPendiente) {
            $paq = Paquete::create([
                'id_solicitud'      => $solicitud->id_solicitud,
                'estado_id'         => $estadoPendiente->id_estado,
                'imagen'            => 'SIN_IMAGEN',
                'ubicacion_actual'  => null,
                'latitud'           => null,
                'longitud'          => null,
                'zona'              => null,
                'id_conductor'      => null,
                'id_vehiculo'       => null,
            ]);

            $solicitud->update([
                'aprobada' => true,
                'estado'   => 'aprobada',
            ]);

            return $paq;
        });

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Solicitud aprobada correctamente',
                'paquete' => $paquete
            ]);
        }

         return redirect()
            ->route('paquete.show', $paquete->id_paquete)
            ->with('success', 'Solicitud aprobada y paquete creado correctamente.');
    }

    public function negar(Request $request, int $id)
    {
        $request->validate([
            'justificacion' => ['required', 'string', 'max:255'],
        ]);

        $solicitud = Solicitud::findOrFail($id);

        if ($solicitud->estado !== 'pendiente') {
            return redirect()->back()->with('error', 'Esta solicitud ya fue respondida.');
        }

        $solicitud->update([
            'aprobada'      => false,
            'estado'        => 'negada',
            'justificacion' => $request->justificacion,
        ]);

          if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Solicitud negada correctamente'
            ]);
        }

        return redirect()->back()
            ->with('success', 'Solicitud negada correctamente.');
    }

    public function buscarPorCodigo(Request $request): View
    {
        $codigo = $request->get('codigo_seguimiento');

        $solicitudEncontrada = null;

        if ($codigo) {
            $solicitudEncontrada = Solicitud::with(['solicitante', 'destino'])
                ->where('codigo_seguimiento', $codigo)
                ->first();
        }

        $solicitud = new Solicitud();
        $tipoEmergencia = \App\Models\TipoEmergencia::orderBy('prioridad', 'desc')->get();

        return view('solicitud.create', compact(
            'solicitud',
            'tipoEmergencia',
            'solicitudEncontrada',
            'codigo'
        ));
    }

}
