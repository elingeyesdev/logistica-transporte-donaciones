<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

use App\Http\Requests\PaqueteRequest;
use App\Models\Paquete;
use App\Models\Estado;
use App\Models\HistorialSeguimientoDonacione;
use App\Models\Solicitud;
use App\Models\Ubicacion;

class PaqueteController extends Controller
{
    public function index(Request $request): View
    {
        $paquetes = Paquete::with(['estado','solicitud.solicitante','solicitud.destino'])->paginate();

        return view('paquete.index', compact('paquetes'))
            ->with('i', ($request->input('page', 1) - 1) * $paquetes->perPage());
    }

    public function create(): View
    {
        $paquete = new Paquete();
        $estados  = Estado::orderBy('nombre_estado')->pluck('nombre_estado','id_estado');
        $solicitudes = Solicitud::with(['solicitante', 'destino'])->get();

        return view('paquete.create', compact('paquete','estados', 'solicitudes'));
    }

    public function store(PaqueteRequest $request): RedirectResponse
    {
        $id = DB::transaction(function () use ($request) {

            $data = $request->validated();

            $data['fecha_aprobacion'] = now()->toDateString();
            $data['id_encargado']     = optional(Auth::user())->ci;
            $data['codigo']           = $this->makeCodigoPaquete();

            /** @var \App\Models\Paquete $paq */
            $paq = Paquete::create($data);

            $estadoNombre = optional($paq->estado)->nombre_estado ?? 'Pendiente';

            if (strcasecmp($estadoNombre, 'Entregada') === 0) {
                $paq->update(['fecha_entrega' => now()->toDateString()]);
            }

            $lat = $request->input('latitud');
            $lng = $request->input('longitud');
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
            $data['ubicacion_actual'] = $ubicacionString;

            $paq = Paquete::create($data);

            HistorialSeguimientoDonacione::create([
                'ci_usuario'          => optional(Auth::user())->ci,
                'estado'              => optional($paq->estado)->nombre_estado ?? 'Pendiente',
                'imagen_evidencia'    => $request->input('imagen_evidencia'),
                'id_paquete'          => $paq->id_paquete,
                'id_ubicacion'        => $ubicacionId,
                'fecha_actualizacion' => now(),
            ]);

            return $paq->getKey();
        });

        return Redirect::route('paquete.index')
            ->with('success', "Paquete creado (ID {$id}).");
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


    public function show($id): View
    {
        $paquete = Paquete::with(['estado','solicitud.solicitante','solicitud.destino'])->findOrFail($id);
        return view('paquete.show', compact('paquete'));
    }

    public function edit(Paquete $paquete): View
    {
        $paquete->load(['estado','solicitud.solicitante','solicitud.destino']);
        $estados = Estado::orderBy('nombre_estado')->pluck('nombre_estado','id_estado');
            $solicitudes = Solicitud::with(['solicitante', 'destino'])->get();
        return view('paquete.edit', compact('paquete','estados', 'solicitudes'));
    }

    public function update(PaqueteRequest $request, Paquete $paquete): RedirectResponse
    {
        DB::transaction(function () use ($request, $paquete) {

            $oldEstadoId = $paquete->estado_id;

            $payload = $request->validated();
            $payload['id_encargado'] = optional(Auth::user())->ci;

            $paquete->update($payload);

            $newNombre = optional($paquete->estado)->nombre_estado ?? 'Pendiente';

            if ($paquete->estado_id != $oldEstadoId) {

                if (strcasecmp($newNombre, 'Entregada') === 0) {
                    $paquete->update(['fecha_entrega' => now()->toDateString()]);
                }

                $lat = $request->input('latitud');
                $lng = $request->input('longitud');
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

                HistorialSeguimientoDonacione::create([
                    'ci_usuario'          => optional(Auth::user())->ci,
                    'estado'              => $newNombre,
                    'imagen_evidencia'    => $request->input('imagen_evidencia'),
                    'id_paquete'          => $paquete->id_paquete,
                    'id_ubicacion'        => $ubicacionId,
                    'fecha_actualizacion' => now(),
                ]);

            }
        });

        return Redirect::route('paquete.index')
            ->with('success', 'Paquete actualizado correctamente');
    }

    public function destroy($id): RedirectResponse
    {
        Paquete::find($id)?->delete();

        return Redirect::route('paquete.index')
            ->with('success', 'Paquete eliminado correctamente');
    }
}
