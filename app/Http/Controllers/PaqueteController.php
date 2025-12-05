<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaqueteActualizado;
use App\Mail\PaqueteEntregado;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

use App\Models\Reporte;
use Illuminate\Support\Str;
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
use Illuminate\Support\Facades\Cache;
use App\Mail\CodigoEntregaPaquete;

class PaqueteController extends Controller
{
    public function index(Request $request)
    {
        $paquetesQuery = Paquete::with(['estado','solicitud.solicitante','solicitud.destino'])
            ->orderByRaw(
                "CASE WHEN LOWER((SELECT nombre_estado FROM estado WHERE estado.id_estado = paquete.estado_id)) = 'en camino' THEN 0 ELSE 1 END"
            )
            ->orderByDesc('updated_at');

        $paquetes = $paquetesQuery->paginate();
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
        DB::beginTransaction();
        try{

            $data = $request->validated();

            $data['fecha_entrega']   = null;
            $data['id_encargado']     = Auth::user()->ci;
            $data['codigo']           = $this->makeCodigoPaquete();

            if ($request->hasFile('imagen')) {
                $data['imagen'] = $request->file('imagen')->store('paquetes', 'public');
            }

            /** @var \App\Models\Paquete $paq */
            $paq = Paquete::create($data);

            $estadoNombre = optional($paq->estado)->nombre_estado ?? 'Pendiente';

            if (strcasecmp($estadoNombre, 'Entregada') === 0 || strcasecmp($estadoNombre, 'Entregado') === 0) {
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

             DB::commit();
            $paqId = $paq->id_paquete;
        }catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

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
      DB::beginTransaction();

        try {

        $oldEstadoId = $paquete->estado_id;
        $newEstadoId = (int) $request->input('estado_id');

        $oldEstado = Estado::find($oldEstadoId);
        $newEstado = Estado::find($newEstadoId);

        $oldNombre = optional($oldEstado)->nombre_estado;
        $newNombre = optional($newEstado)->nombre_estado;

        if ($this->esEstadoEntregadoPorNombre($newNombre)) {
            $cacheKeyVerified = "paquete_entrega_verified_{$paquete->id_paquete}";
            $verified = Cache::get($cacheKeyVerified, false);

            if (!$verified) {
                DB::rollBack();

                $mensaje = 'Debes validar el código de entrega antes de marcar el paquete como "Entregado".';

                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'errors'  => ['codigo_entrega' => [$mensaje]],
                    ], 422);
                }

                return back()
                    ->withErrors(['codigo_entrega' => $mensaje])
                    ->withInput();
            }
            Cache::forget($cacheKeyVerified);
        }

        if ($oldNombre && $newNombre) {
            $oldIsPendiente = strcasecmp($oldNombre, 'Pendiente') === 0;
            $newIsPendiente = strcasecmp($newNombre, 'Pendiente') === 0;
            $newIsEnCamino  = strcasecmp($newNombre, 'En Camino') === 0
                        || strcasecmp($newNombre, 'En camino') === 0;

            if ($oldIsPendiente && !$newIsEnCamino) {
                DB::rollBack();

                $mensaje = 'Un paquete en estado "Pendiente" solo puede pasar a "En Camino".';

                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'errors'  => ['estado_id' => [$mensaje]],
                    ], 422);
                }

                return back()
                    ->withErrors(['estado_id' => $mensaje])
                    ->withInput();
            }

            if (!$oldIsPendiente && $newIsPendiente) {
                DB::rollBack();

                $mensaje = 'No puedes regresar el paquete al estado "Pendiente".';

                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'errors'  => ['estado_id' => [$mensaje]],
                    ], 422);
                }

                return back()
                    ->withErrors(['estado_id' => $mensaje])
                    ->withInput();
            }
        }

        $payload = $request->validated();
        $payload['id_encargado'] = optional(Auth::user())->ci ?? $paquete->id_encargado ?? null;

        if ($request->hasFile('imagen')) {
            $payload['imagen'] = $request->file('imagen')->store('paquetes', 'public');
            Log::info('Imagen actualizada:', [
                'ruta' => $payload['imagen'],
                'existe' => file_exists(public_path('storage/' . $payload['imagen']))
            ]);
        } else {
            unset($payload['imagen']);
            Log::info('No se proporcionó una nueva imagen.');
        }

        $paquete->update($payload);

        $estadoNombre = optional($paquete->estado)->nombre_estado ?? 'Pendiente';

        $entregado = $estadoNombre &&
            (strcasecmp($estadoNombre, 'Entregado') === 0 ||
            strcasecmp($estadoNombre, 'Entregada') === 0);
        if ($entregado) {
            if (is_null($paquete->fecha_entrega)) {
                $paquete->update(['fecha_entrega' => now()->toDateString()]);
            }
        } else {
            if (!is_null($paquete->fecha_entrega)) {
                $paquete->update(['fecha_entrega' => null]);
            }
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
        DB::commit();
        
        $paquete->load([
            'estado',
            'solicitud.solicitante',
            'conductor',
            'vehiculo.marcaVehiculo',
            'vehiculo.tipoVehiculo',
        ]);

        $destinatario = optional(optional($paquete->solicitud)->solicitante)->email;

        if ($destinatario) {
            Log::info('Enviando correo de paquete actualizado', [
                'paquete_id' => $paquete->id_paquete,
                'email' => optional($paquete->solicitud)->codigo_seguimiento,
            ]);
            Mail::to($destinatario)->send(
                new PaqueteActualizado($paquete)
            );

            $estadoNombre = optional($paquete->estado)->nombre_estado;

            if ($estadoNombre &&
                (strcasecmp($estadoNombre, 'Entregado') === 0 ||
                strcasecmp($estadoNombre, 'Entregada') === 0)) {

                Log::info('Enviando correo de paquete Entregado', [
                'paquete_id' => $paquete->id_paquete,
                'email' => optional($paquete->solicitud)->codigo_seguimiento,
                ]);
                Mail::to($destinatario)->send(
                    new PaqueteEntregado($paquete)
                );
            }
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

    } catch (\Throwable $e) {
        DB::rollBack();
        throw $e;
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

    public function storePdfReporte(Request $request, Paquete $paquete)
    {
        $validated = $request->validate([
            'archivo' => ['required', 'file', 'mimes:pdf', 'max:20480'],
            'fecha_reporte' => ['nullable', 'date'],
            'gestion' => ['nullable', 'string', 'max:255'],
        ]);

        $file = $request->file('archivo');
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeBase = Str::slug($originalName) ?: 'reporte-paquete-' . $paquete->id_paquete;
        $filename = $safeBase . '_' . now()->format('Ymd_His') . '.pdf';
        $storedPath = $file->storeAs('reportes', $filename, 'public');

        $reporte = Reporte::create([
            'id_paquete' => $paquete->id_paquete,
            'nombre_pdf' => $filename,
            'ruta_pdf' => $storedPath,
            'fecha_reporte' => $validated['fecha_reporte'] ?? now()->toDateString(),
            'gestion' => $validated['gestion'] ?? now()->format('Y'),
        ]);

        return response()->json([
            'success' => true,
            'reporte_id' => $reporte->id_reporte,
            'url' => asset('storage/' . $storedPath),
        ]);
    }

    public function galeria()
    {
        $paquetes = Paquete::with(['solicitud.solicitante', 'solicitud.destino']) ->whereHas('estado', function ($query) {
                $query->where('nombre_estado', 'Entregado');
            })
            ->get(); 
        Log::info('Paquetes entregados:', $paquetes->toArray());

        foreach ($paquetes as $paquete) {
            Log::info('Paquete:', [
                'id' => $paquete->id,
                'imagen' => $paquete->imagen,
                'ruta_completa' => public_path('storage/paquetes/' . $paquete->imagen),
                'existe' => file_exists(public_path('storage/paquetes/' . $paquete->imagen))
            ]);
        }
        return view('galeria.index', compact('paquetes'));
    }

    private function esEstadoEntregadoPorNombre(?string $nombre): bool
    {
        if (!$nombre) {
            return false;
        }

        $nombre = trim(mb_strtolower($nombre));
        return $nombre === 'entregado' || $nombre === 'entregada';
    }

    public function sendEntregaCode(Request $request, Paquete $paquete)
    {
        $estadoId = (int) $request->input('estado_id');
        $estado   = Estado::find($estadoId);
        $nombre   = optional($estado)->nombre_estado;

        if (!$this->esEstadoEntregadoPorNombre($nombre)) {
            return response()->json([
                'success' => false,
                'message' => 'El estado seleccionado no es "Entregado/Entregada".'
            ], 422);
        }

        $solicitante = optional($paquete->solicitud)->solicitante;
        $destinatario = optional($solicitante)->email;

        if (!$destinatario) {
            return response()->json([
                'success' => false,
                'message' => 'La solicitud asociada no tiene correo de contacto.'
            ], 422);
        }

        $codigo = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);

        $cacheKeyCode     = "paquete_entrega_code_{$paquete->id_paquete}";
        $cacheKeyVerified = "paquete_entrega_verified_{$paquete->id_paquete}";

        Cache::put($cacheKeyCode, $codigo, now()->addMinutes(15));
        Cache::forget($cacheKeyVerified); 

        Mail::to($destinatario)->send(new CodigoEntregaPaquete($paquete, $codigo));

        return response()->json([
            'success' => true,
            'message' => 'Se ha enviado un código de verificación al solicitante.'
        ]);
    }

    public function verifyEntregaCode(Request $request, Paquete $paquete)
    {
        $request->validate([
            'codigo' => ['required','string','size:4'],
        ]);

        $inputCode = $request->input('codigo');

        $cacheKeyCode     = "paquete_entrega_code_{$paquete->id_paquete}";
        $cacheKeyVerified = "paquete_entrega_verified_{$paquete->id_paquete}";

        $storedCode = Cache::get($cacheKeyCode);

        if (!$storedCode || $storedCode !== $inputCode) {
            return response()->json([
                'success' => false,
                'message' => 'Código incorrecto o vencido. El paquete no fue entregado.'
            ], 422);
        }

        Cache::put($cacheKeyVerified, true, now()->addMinutes(30));
        Cache::forget($cacheKeyCode);

        return response()->json([
            'success' => true,
            'message' => 'Código validado. Ahora puedes confirmar la entrega del paquete.'
        ]);
    }



}
