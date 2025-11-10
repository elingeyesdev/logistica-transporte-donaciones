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

class SolicitudController extends Controller
{
    public function index(Request $request): View
    {
        $solicituds = Solicitud::with(['solicitante','destino'])->paginate();
        return view('solicitud.index', compact('solicituds'))
            ->with('i', ($request->input('page', 1) - 1) * $solicituds->perPage());
    }

    public function create(): View
    {
        $solicitud = new Solicitud();
        return view('solicitud.create', compact('solicitud'));
    }

    public function store(SolicitudRequest $request): RedirectResponse
    {
        $data = $request->validated();

        return DB::transaction(function () use ($data) {

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

            $solicitud = Solicitud::create([
                'id_solicitante'     => $solicitante->id_solicitante,
                'id_destino'         => $destino->id_destino,
                'cantidad_personas'  => $data['cantidad_personas'],
                'fecha_inicio'       => $data['fecha_inicio'],
                'tipo_emergencia'    => $data['tipo_emergencia'],
                'insumos_necesarios' => $data['insumos_necesarios'],
                'codigo_seguimiento' => $data['codigo_seguimiento'] ?? null,
                'estado'             => 'pendiente',
                'fecha_solicitud'    => $data['fecha_solicitud'] ?? now()->toDateString(),
                'aprobada'           => (bool)($data['aprobada'] ?? false),
                'apoyoaceptado'      => (bool)($data['apoyoaceptado'] ?? false),
                'justificacion'      => $data['justificacion'] ?? null,
            ]);

            return redirect()
                ->route('solicitud.show', $solicitud->id_solicitud)
                ->with('success', 'Solicitud creada correctamente.');
        });
    }

    public function show($id): View
    {
        $solicitud = Solicitud::with(['solicitante','destino'])->findOrFail($id);
    return view('solicitud.show', compact('solicitud'));
    }

    public function edit($id): View
    {
        $solicitud = Solicitud::with(['solicitante','destino'])->findOrFail($id);
        return view('solicitud.edit', compact('solicitud'));
    }

    public function update(SolicitudRequest $request, Solicitud $solicitud): RedirectResponse
    {
        $data = $request->validated();

         return DB::transaction(function () use ($data, $solicitud) {

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
                'tipo_emergencia'    => $data['tipo_emergencia'],
                'insumos_necesarios' => $data['insumos_necesarios'],
                'codigo_seguimiento' => $data['codigo_seguimiento'] ?? $solicitud->codigo_seguimiento,
                'fecha_solicitud'    => $data['fecha_solicitud'] ?? $solicitud->fecha_solicitud,
                'aprobada'           => (bool)($data['aprobada'] ?? $solicitud->aprobada),
                'apoyoaceptado'      => (bool)($data['apoyoaceptado'] ?? $solicitud->apoyoaceptado),
                'justificacion'      => $data['justificacion'] ?? $solicitud->justificacion,
            ]);

            return redirect()
                ->route('solicitud.show', $solicitud->id_solicitud)
                ->with('success', 'Solicitud actualizada correctamente.');
        });

    }

    public function destroy($id_solicitud): RedirectResponse
    {
        $solicitud = Solicitud::find($id_solicitud);
        if (!$solicitud) {
            return redirect()->route('solicitud.index')
                ->with('error', 'La solicitud no existe o ya fue eliminada.');
        }
        $solicitud->delete();
        return redirect()->route('solicitud.index')
            ->with('success', 'Solicitud eliminada correctamente.');
    }
}
