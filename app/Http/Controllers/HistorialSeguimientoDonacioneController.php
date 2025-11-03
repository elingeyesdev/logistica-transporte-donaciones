<?php

namespace App\Http\Controllers;

use App\Models\HistorialSeguimientoDonacione;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\HistorialSeguimientoDonacioneRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class HistorialSeguimientoDonacioneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $historialSeguimientoDonaciones = HistorialSeguimientoDonacione::paginate();

        return view('historial-seguimiento-donacione.index', compact('historialSeguimientoDonaciones'))
            ->with('i', ($request->input('page', 1) - 1) * $historialSeguimientoDonaciones->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $historialSeguimientoDonacione = new HistorialSeguimientoDonacione();

        return view('historial-seguimiento-donacione.create', compact('historialSeguimientoDonacione'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HistorialSeguimientoDonacioneRequest $request): RedirectResponse
    {
        HistorialSeguimientoDonacione::create($request->validated());

        return Redirect::route('historial-seguimiento-donaciones.index')
            ->with('success', 'HistorialSeguimientoDonacione created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $historialSeguimientoDonacione = HistorialSeguimientoDonacione::find($id);

        return view('historial-seguimiento-donacione.show', compact('historialSeguimientoDonacione'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $historialSeguimientoDonacione = HistorialSeguimientoDonacione::find($id);

        return view('historial-seguimiento-donacione.edit', compact('historialSeguimientoDonacione'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HistorialSeguimientoDonacioneRequest $request, HistorialSeguimientoDonacione $historialSeguimientoDonacione): RedirectResponse
    {
        $historialSeguimientoDonacione->update($request->validated());

        return Redirect::route('historial-seguimiento-donaciones.index')
            ->with('success', 'HistorialSeguimientoDonacione updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        HistorialSeguimientoDonacione::find($id)->delete();

        return Redirect::route('historial-seguimiento-donaciones.index')
            ->with('success', 'HistorialSeguimientoDonacione deleted successfully');
    }
}
