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
use Illuminate\Support\Facades\Auth;

class SolicitudController extends Controller
{
    public function index(Request $request)
    {
        $solicituds = Solicitud::with(['solicitante', 'destino'])
        ->orderByDesc('fecha_solicitud')
        ->paginate();

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

        DB::beginTransaction();

        try {

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

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

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

        DB::beginTransaction();

        try {

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
                'id_tipoemergencia'  => $data['id_tipoemergencia'],
                'insumos_necesarios' => $data['insumos_necesarios'],
                'codigo_seguimiento' => $data['codigo_seguimiento'] ?? $solicitud->codigo_seguimiento,
                'fecha_solicitud'    => $data['fecha_solicitud'] ?? $solicitud->fecha_solicitud,
                'aprobada'           => (bool)($data['aprobada'] ?? $solicitud->aprobada),
                'apoyoaceptado'      => (bool)($data['apoyoaceptado'] ?? $solicitud->apoyoaceptado),
                'justificacion'      => $data['justificacion'] ?? $solicitud->justificacion,
            ]);

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

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
        $solicitud = Solicitud::with(['solicitante','destino'])->findOrFail($id);

        if ($solicitud->estado !== 'pendiente') {
            return redirect()->back()->with('error', 'Esta solicitud ya fue respondida.');
        }

        $estadoPendiente = Estado::where('nombre_estado', 'Pendiente')->first();

        if (!$estadoPendiente) {
            return redirect()->back()->with('error', 'No se encontró el estado Pendiente.');
        }

        DB::beginTransaction();

        try {

            $paquete = Paquete::create([
                'id_solicitud'      => $solicitud->id_solicitud,
                'estado_id'         => $estadoPendiente->id_estado,
                'codigo'            => $solicitud->codigo_seguimiento,
                'id_encargado'      => Auth::user()->ci,
                'fecha_aprobacion'  => now()->toDateString(),
            ]);

            $solicitud->update([
                'aprobada' => true,
                'estado'   => 'aprobada',
            ]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

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

    //ACCIONES PUBLICAS
       public function publicShow(string $codigo)
        {
            $solicitud = Solicitud::with(['solicitante', 'destino', 'tipoEmergencia'])
                ->where('codigo_seguimiento', $codigo)
                ->firstOrFail();
            return view('solicitud.public-show', compact('solicitud'));
        }

        public function publicEdit(string $codigo)
        {
            $tipoEmergencia = \App\Models\TipoEmergencia::orderBy('prioridad', 'desc')->get();
            $solicitud = Solicitud::with(['solicitante', 'destino', 'tipoEmergencia'])
                ->where('codigo_seguimiento', $codigo)
                ->firstOrFail();

            $editable = ($solicitud->estado === 'pendiente');

            if (!$editable) {
                return redirect()
                    ->route('solicitud.public.show', $codigo)
                    ->with('error', 'Esta solicitud ya no puede ser editada.');
            }
            return view('solicitud.public-edit', compact('solicitud', 'tipoEmergencia'));
        }

        public function publicUpdate(Request $request, string $codigo)
        {
            $solicitud = Solicitud::with(['solicitante', 'destino', 'tipoEmergencia'])
                ->where('codigo_seguimiento', $codigo)
                ->firstOrFail();

            $editable = ($solicitud->estado === 'pendiente');

            if (!$editable) {
                return redirect()
                    ->route('solicitud.public.show', $codigo)
                    ->with('error', 'Esta solicitud ya no puede ser editada.');
            }
            $data = $request->validate([
                'nombre'               => 'required|string|max:255',
                'apellido'             => 'required|string|max:255',
                'carnet_identidad'     => 'required|string|max:50',
                'correo_electronico'   => 'required|email|max:255',
                'nro_celular'          => 'required|string|max:50',
                'comunidad_solicitante'=> 'required|string|max:255',
                'provincia'            => 'required|string|max:255',
                'ubicacion'            => 'required|string|max:255',
                'latitud'              => 'required|numeric',
                'longitud'             => 'required|numeric',
                'cantidad_personas'    => 'required|integer|min:1',
                'fecha_inicio'         => 'required|date',
                'id_tipoemergencia'    => 'required|exists:tipo_emergencia,id_emergencia',
                'insumos_necesarios'   => 'required|string',
            ]);
            DB::beginTransaction();

            try {

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
                    'id_tipoemergencia'  => $data['id_tipoemergencia'],
                    'tipo_emergencia'    => $tipo->emergencia ?? $solicitud->tipo_emergencia,
                    'insumos_necesarios' => $data['insumos_necesarios'],
                ]);

                DB::commit();

            } catch (\Throwable $e) {
                DB::rollBack();
                throw $e;
            }

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $solicitud,
                    'message' => 'Solicitud actualizada correctamente'
                ]);
            }
            return redirect()
                ->route('solicitud.public.show', $codigo)
                ->with('success', 'Tu solicitud fue actualizada correctamente.');
        }
}
