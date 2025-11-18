<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\VehiculoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class VehiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $vehiculo = Vehiculo::paginate();

        return view('vehiculo.index', compact('vehiculo'))
            ->with('i', ($request->input('page', 1) - 1) * $vehiculo->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $vehiculo = new Vehiculo();
        $tipos  = \App\Models\TipoVehiculo::orderBy('nombre_tipo_vehiculo')->get();
        $marcas = \App\Models\Marca::orderBy('nombre_marca')->get();

        return view('vehiculo.create', compact('vehiculo', 'tipos', 'marcas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VehiculoRequest $request): RedirectResponse
    {
        Vehiculo::create($request->validated());
        
        return Redirect::route('vehiculo.index')
            ->with('success', 'Vehiculo creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $vehiculo = Vehiculo::find($id);

        return view('vehiculo.show', compact('vehiculo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $vehiculo = Vehiculo::find($id);

        $tipos  = \App\Models\TipoVehiculo::orderBy('nombre_tipo_vehiculo')->get();
        $marcas = \App\Models\Marca::orderBy('nombre_marca')->get();

        return view('vehiculo.edit', compact('vehiculo', 'tipos', 'marcas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VehiculoRequest $request, Vehiculo $vehiculo): RedirectResponse
    {
        $vehiculo->update($request->validated());
        
        return Redirect::route('vehiculo.index')
            ->with('success', 'Vehiculo actualizado exitosamente');
    }

    public function destroy($id): RedirectResponse
    {
        Vehiculo::find($id)->delete();

        return Redirect::route('vehiculo.index')
            ->with('success', 'Vehiculo eliminado exitosamente');
    }
}
