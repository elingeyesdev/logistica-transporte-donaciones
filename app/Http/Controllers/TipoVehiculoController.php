<?php

namespace App\Http\Controllers;

use App\Models\TipoVehiculo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\TipoVehiculoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class TipoVehiculoController extends Controller
{
  
    public function index(Request $request): View
    {
        $tipoVehiculos = TipoVehiculo::paginate();

        return view('tipo-vehiculo.index', compact('tipoVehiculos'))
            ->with('i', ($request->input('page', 1) - 1) * $tipoVehiculos->perPage());
    }
    public function create(): View
    {
        $tipoVehiculo = new TipoVehiculo();

        return view('tipo-vehiculo.create', compact('tipoVehiculo'));
    }
    public function store(TipoVehiculoRequest $request): RedirectResponse
    {
        TipoVehiculo::create($request->validated());

        return Redirect::route('tipo-vehiculo.index')
            ->with('success', 'TipoVehiculo creado exitosamente.');
    }
    public function show($id): View
    {
        $tipoVehiculo = TipoVehiculo::find($id);

        return view('tipo-vehiculo.show', compact('tipoVehiculo'));
    }
    public function edit($id): View
    {
        $tipoVehiculo = TipoVehiculo::find($id);

        return view('tipo-vehiculo.edit', compact('tipoVehiculo'));
    }
    public function update(TipoVehiculoRequest $request, TipoVehiculo $tipoVehiculo): RedirectResponse
    {
        $tipoVehiculo->update($request->validated());

        return Redirect::route('tipo-vehiculo.index')
            ->with('success', 'TipoVehiculo actualizado exitosamente');
    }
    public function destroy($id): RedirectResponse
    {
        TipoVehiculo::find($id)->delete();

        return Redirect::route('tipo-vehiculo.index')
            ->with('success', 'TipoVehiculo eliminado exitosamente');
    }
}
