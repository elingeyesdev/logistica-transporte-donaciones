<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

use App\Http\Requests\PaqueteRequest;
use App\Models\Paquete;
use App\Models\Estado;
use App\Models\HistorialSeguimientoDonacione;
use App\Models\Solicitud;
use App\Models\Ubicacion;
use App\Models\Conductor;
use App\Models\Vehiculo;
use App\Models\TipoLicencia;
use App\Models\Marca;
use App\Models\TipoVehiculo;

class PaqueteController extends Controller
{
    public function index(Request $request)
    {
        $paquetes = Paquete::with(['estado','solicitud.solicitante','solicitud.destino'])->paginate();
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $paquetes
            ]);
        }

        return view('paquete.index', compact('paquetes'))
            ->with('i', ($request->input('page', 1) - 1) * $paquetes->perPage());
    }

    public function create(): View
    {
        $paquete      = new Paquete();
        $estados      = Estado::orderBy('nombre_estado')->pluck('nombre_estado','id_estado');
        $solicitudes  = Solicitud::with(['solicitante', 'destino'])->get();
        $conductores  = Conductor::orderBy('nombre')->orderBy('apellido')->get();
        $vehiculos    = Vehiculo::with(['marcaVehiculo','tipoVehiculo'])->orderBy('placa')->get();
        $licencias    = TipoLicencia::orderBy('licencia')->get();
        $marcas       = Marca::orderBy('nombre_marca')->get();
        $tiposVehiculo = TipoVehiculo::orderBy('nombre_tipo_vehiculo')->get();

        return view('paquete.create', compact(
            'paquete',
            'estados',
            'solicitudes',
            'conductores',
            'vehiculos',
            'licencias',
            'marcas',
            'tiposVehiculo'
        ));
    }

    public function store(PaqueteRequest $request)  
    {
        $paq = DB::transaction(function () use ($request) {

            $data = $request->validated();

            $data['fecha_aprobacion'] = now()->toDateString();
            $data['id_encargado']     = optional(Auth::user())->ci;
            $data['codigo']           = $this->makeCodigoPaquete();

            if ($request->hasFile('imagen')) {
                $data['imagen'] = $request->file('imagen')->store('paquetes', 'public');
            }

            /** @var \App\Models\Paquete $paq */
            $paq = Paquete::create($data);

            $estadoNombre = optional($paq->estado)->nombre_estado ?? 'Pendiente';

            if (strcasecmp($estadoNombre, 'Entregada') === 0) {
                $paq->update(['fecha_entrega' => now()->toDateString()]);
            }

            $lat  = $request->input('latitud');
            $lng  = $request->input('longitud');
            $zona = $request->input('zona');

            $ubicacionId = null;
            if ($lat !== null && $lng !== null) {
                $ubic = Ubicacion::create([
                    'latitud'  => $lat,
                    'longitud' => $lng,
                    'zona'     => $zona,
                ]);
                $ubicacionId = $ubic->id_ubicacion;
            }

            $ubicacionString = $this->buildUbicacionString($zona, $lat, $lng);
            $paq->update(['ubicacion_actual' => $ubicacionString]);

            $conductorNombre = null;
            $conductorCi     = null;
            $vehiculoPlaca   = null;

            if ($paq->id_conductor) {
                $conductor = Conductor::find($paq->id_conductor);
                if ($conductor) {
                    $conductorNombre = trim(($conductor->nombre ?? '') . ' ' . ($conductor->apellido ?? ''));
                    $conductorCi     = $conductor->ci;
                }
            }

            if ($paq->id_vehiculo) {
                $vehiculo = Vehiculo::find($paq->id_vehiculo);
                if ($vehiculo) {
                    $vehiculoPlaca = $vehiculo->placa;
                }
            }

            HistorialSeguimientoDonacione::create([
                'ci_usuario'          => optional(Auth::user())->ci,
                'estado'              => $estadoNombre,
                'imagen_evidencia'    => $request->input('imagen_evidencia'),
                'id_paquete'          => $paq->id_paquete,
                'id_ubicacion'        => $ubicacionId,
                'fecha_actualizacion' => now(),
                'conductor_nombre'    => $conductorNombre,
                'conductor_ci'        => $conductorCi,
                'vehiculo_placa'      => $vehiculoPlaca,
            ]);

            return $paq->getKey();
        });

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $paq
            ], 201);
        }

        return Redirect::route('paquete.index')
            ->with('success', "Paquete creado (ID {$paq->id_paquete}).");
    }

    private function makeCodigoPaquete(): string
    {
        for ($i = 0; $i < 10; $i++) {
            $code = 'D-' . str_pad((string) random_int(0, 999), 3, '0', STR_PAD_LEFT);
            if (!Paquete::where('codigo', $code)->exists()) {
                return $code;
            }
        }
        return 'D-' . substr((string) time(), -3);
    }
    private function buildUbicacionString($zona, $lat, $lng): string
    {
        $parts = [];
        if ($zona) $parts[] = $zona;
        if ($lat && $lng) $parts[] = "($lat, $lng)";
        return implode(' - ', $parts);
    }


    public function show(Request $request, $id)
    {
        $paquete = Paquete::with(['estado','solicitud.solicitante','solicitud.destino', 'conductor', 'vehiculo.marcaVehiculo',])->findOrFail($id);
        
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $paquete
            ]);
        }

        return view('paquete.show', compact('paquete'));
    }

    public function edit(Paquete $paquete): View
    {
        $paquete->load(['estado','solicitud.solicitante','solicitud.destino']);
        $estados      = Estado::orderBy('nombre_estado')->pluck('nombre_estado','id_estado');
        $solicitudes  = Solicitud::with(['solicitante', 'destino'])->get();
        $conductores  = Conductor::orderBy('nombre')->orderBy('apellido')->get();
        $vehiculos    = Vehiculo::with(['marcaVehiculo','tipoVehiculo'])->orderBy('placa')->get();
        $licencias    = TipoLicencia::orderBy('licencia')->get();
        $marcas       = Marca::orderBy('nombre_marca')->get();
        $tiposVehiculo = TipoVehiculo::orderBy('nombre_tipo_vehiculo')->get();
        return view('paquete.edit', compact(
            'paquete',
            'estados',
            'solicitudes',
            'conductores',
            'vehiculos',
            'licencias',
            'marcas',
            'tiposVehiculo'
        ));
    }

    public function update(PaqueteRequest $request, Paquete $paquete)
{
    $paq = DB::transaction(function () use ($request, $paquete) {

        $oldEstadoId = $paquete->estado_id;

        $payload = $request->validated();
        $payload['id_encargado'] = optional(Auth::user())->ci;

        if ($request->hasFile('imagen')) {
            $payload['imagen'] = $request->file('imagen')->store('paquetes', 'public');
        }

        $paquete->update($payload);

        $estadoNombre = optional($paquete->estado)->nombre_estado ?? 'Pendiente';

        if (strcasecmp($estadoNombre, 'Entregada') === 0) {
            $paquete->update(['fecha_entrega' => now()->toDateString()]);
        }

        $lat  = $request->input('latitud');
        $lng  = $request->input('longitud');
        $zona = $request->input('zona');

        $ubicacionId = null;

        if ($lat !== null && $lng !== null) {
            $ubic = Ubicacion::create([
                'latitud'  => $lat,
                'longitud' => $lng,
                'zona'     => $zona,
            ]);
            $ubicacionId = $ubic->id_ubicacion;
        }

        $ubicacionString = $this->buildUbicacionString($zona, $lat, $lng);
        $paquete->update(['ubicacion_actual' => $ubicacionString]);

        $conductorNombre = null;
        $conductorCi     = null;
        $vehiculoPlaca   = null;

        if ($paquete->id_conductor) {
            $conductor = Conductor::find($paquete->id_conductor);
            if ($conductor) {
                $conductorNombre = trim(($conductor->nombre ?? '') . ' ' . ($conductor->apellido ?? ''));
                $conductorCi     = $conductor->ci;
            }
        }

        if ($paquete->id_vehiculo) {
            $vehiculo = Vehiculo::find($paquete->id_vehiculo);
            if ($vehiculo) {
                $vehiculoPlaca = $vehiculo->placa;
            }
        }

        $pathEvidencia = null;
        if ($request->hasFile('imagen_evidencia')) {
            $pathEvidencia = $request->file('imagen_evidencia')->store('evidencias', 'public');
        }

        HistorialSeguimientoDonacione::create([
            'ci_usuario'          => optional(Auth::user())->ci,
            'estado'              => $estadoNombre,
            'imagen_evidencia'    => $pathEvidencia,
            'id_paquete'          => $paquete->id_paquete,
            'id_ubicacion'        => $ubicacionId,
            'fecha_actualizacion' => now(),
            'conductor_nombre'    => $conductorNombre,
            'conductor_ci'        => $conductorCi,
            'vehiculo_placa'      => $vehiculoPlaca,
        ]);

    });

    if ($request->wantsJson()) {
        return response()->json(['success' => true]);
    }

    return Redirect::route('paquete.index')->with('success', 'Paquete actualizado y registrado en historial.');
}


    public function destroy(Request $request, $id)
    {
        $paquete = Paquete::find($id);

        if (!$paquete) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error'   => 'Paquete no encontrado'
                ], 404);
            }

            return Redirect::route('paquete.index')
                ->with('error', 'Paquete no encontrado');
        }

        if ($paquete->imagen && Storage::disk('public')->exists($paquete->imagen)) {
            Storage::disk('public')->delete($paquete->imagen);
        }

        $paquete->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Paquete eliminado correctamente'
            ]);
        }
        return Redirect::route('paquete.index')
            ->with('success', 'Paquete eliminado correctamente');
    }
}
