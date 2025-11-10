<?php

namespace App\Http\Controllers;

use App\Models\Reporte;
use App\Models\paquete;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ReporteRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ReporteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $reportes = Reporte::paginate();

        return view('reporte.index', compact('reportes'))
            ->with('i', ($request->input('page', 1) - 1) * $reportes->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $reporte = new Reporte();
        $paquetes = Paquete::with('solicitud.solicitante')->get();

        return view('reporte.create', compact('reporte', 'paquetes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReporteRequest $request): RedirectResponse
    {
        Reporte::create($request->validated());

        return Redirect::route('reporte.index')
            ->with('success', 'Reporte created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $reporte = Reporte::find($id);

        return view('reporte.show', compact('reporte'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $reporte = Reporte::findOrFail($id);
        $paquetes = Paquete::with('solicitud.solicitante')->get();

        return view('reporte.edit', compact('reporte', 'paquetes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReporteRequest $request, Reporte $reporte): RedirectResponse
    {
        $reporte->update($request->validated());

        return Redirect::route('reporte.index')
            ->with('success', 'Reporte actualizado correctamente');
    }

    public function destroy($id): RedirectResponse
    {
        Reporte::find($id)->delete();

        return Redirect::route('reporte.index')
            ->with('success', 'Reporte eliminado');
    }
}
