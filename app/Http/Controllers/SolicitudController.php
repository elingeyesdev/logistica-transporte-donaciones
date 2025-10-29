<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\SolicitudRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class SolicitudController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $solicituds = Solicitud::paginate();

        return view('solicitud.index', compact('solicituds'))
            ->with('i', ($request->input('page', 1) - 1) * $solicituds->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $solicitud = new Solicitud();

        return view('solicitud.create', compact('solicitud'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SolicitudRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['nombre_solicitante'] = trim(($data['nombre'] ?? '') . ' ' . ($data['apellido'] ?? '')) ?: ($data['nombre'] ?? null);
        $data['fecha_creacion'] = $data['fecha_inicio'] ?? now()->toDateString();
        $data['descripcion'] = $data['insumos_necesarios'] ?? ($data['tipo_emergencia'] ?? '');
        $data['estado'] = $data['estado'] ?? 'pendiente';

        Solicitud::create($data);

        return Redirect::route('solicitud.index')
            ->with('success', 'Solicitud created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $solicitud = Solicitud::find($id);

        return view('solicitud.show', compact('solicitud'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $solicitud = Solicitud::find($id);

        return view('solicitud.edit', compact('solicitud'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SolicitudRequest $request, Solicitud $solicitud): RedirectResponse
    {
        $data = $request->validated();
        $data['nombre_solicitante'] = trim(($data['nombre'] ?? '') . ' ' . ($data['apellido'] ?? '')) ?: ($data['nombre'] ?? $solicitud->nombre_solicitante);
        $data['fecha_creacion'] = $data['fecha_inicio'] ?? ($solicitud->fecha_creacion ?? now()->toDateString());
        $data['descripcion'] = $data['insumos_necesarios'] ?? ($data['tipo_emergencia'] ?? $solicitud->descripcion);
        $data['estado'] = $data['estado'] ?? ($solicitud->estado ?? 'pendiente');

        $solicitud->update($data);

        return Redirect::route('solicitud.index')
            ->with('success', 'Solicitud updated successfully');
    }

public function destroy($id_solicitud)
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
