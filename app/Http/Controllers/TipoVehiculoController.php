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
  
    public function index(Request $request)
    {
        $tipoVehiculos = TipoVehiculo::paginate();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $tipoVehiculos
            ]);
        }

        return view('tipo-vehiculo.index', compact('tipoVehiculos'))
            ->with('i', ($request->input('page', 1) - 1) * $tipoVehiculos->perPage());
    }
    public function create(): View
    {
        $tipoVehiculo = new TipoVehiculo();

        return view('tipo-vehiculo.create', compact('tipoVehiculo'));
    }
    public function store(TipoVehiculoRequest $request)
    {
        $tipo = TipoVehiculo::create($request->validated());

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tipo de vehículo creado.',
                'data' => $tipo
            ], 201);
        }

        return Redirect::route('tipo-vehiculo.index')
            ->with('success', 'TipoVehiculo creado exitosamente.');
    }
    public function show(Request $request, $id)
    {
        $tipoVehiculo = TipoVehiculo::findOrFail($id);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $tipoVehiculo
            ]);
        }

        return view('tipo-vehiculo.show', compact('tipoVehiculo'));
    }
    public function edit($id): View
    {
        $tipoVehiculo = TipoVehiculo::find($id);

        return view('tipo-vehiculo.edit', compact('tipoVehiculo'));
    }
    public function update(TipoVehiculoRequest $request, TipoVehiculo $tipoVehiculo)
    {
        $tipoVehiculo->update($request->validated());

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tipo de vehículo actualizado.',
                'data' => $tipoVehiculo
            ]);
        }

        return Redirect::route('tipo-vehiculo.index')
            ->with('success', 'TipoVehiculo actualizado exitosamente');
    }
    public function destroy(Request $request, $id)
    {
        $tipo = TipoVehiculo::findOrFail($id);
        $tipo->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }
        return Redirect::route('tipo-vehiculo.index')
            ->with('success', 'TipoVehiculo eliminado exitosamente');
    }
}
