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

    public function index(Request $request)
    {
        $vehiculo = Vehiculo::with(['marcaVehiculo', 'tipoVehiculo'])->paginate();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $vehiculo
            ]);
        }

        return view('vehiculo.index', compact('vehiculo'))
            ->with('i', ($request->input('page', 1) - 1) * $vehiculo->perPage());
    }
    public function create(): View
    {
        $vehiculo = new Vehiculo();
        $tipos  = \App\Models\TipoVehiculo::orderBy('nombre_tipo_vehiculo')->get();
        $marcas = \App\Models\Marca::orderBy('nombre_marca')->get();

        return view('vehiculo.create', compact('vehiculo', 'tipos', 'marcas'));
    }
    public function store(VehiculoRequest $request)
    {
        $vehiculo = Vehiculo::create($request->validated());

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Vehículo creado exitosamente.',
                'data' => $vehiculo
            ], 201);
        }

        return Redirect::route('vehiculo.index')
            ->with('success', 'Vehículo creado exitosamente.');
    }
    public function show(Request $request, $id)
    {
        $vehiculo = Vehiculo::with([
            'marcaVehiculo',
            'tipoVehiculo',
            'paquetes.solicitud.destino',
            'paquetes.estado',
            'paquetes.conductor',
        ])->findOrFail($id);

        $paquetesEnCamino = $vehiculo->paquetes->filter(function ($p) {
            return optional($p->estado)->nombre_estado === 'En Camino';
        })->sortByDesc('fecha_aprobacion');

        $paquetesOtros = $vehiculo->paquetes->reject(function ($p) {
            return optional($p->estado)->nombre_estado === 'En Camino';
        })->sortByDesc('fecha_aprobacion');


        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $vehiculo,
                //ESTOS SON POR SI QUEREMOS QUE ESTEN EN MOVIL
              //  'paquetes_en_camino' => $paquetesEnCamino->values(),
              //  'paquetes_otros'     => $paquetesOtros->values(),
            ]);
        }

        return view('vehiculo.show', compact('vehiculo', 'paquetesEnCamino', 'paquetesOtros'));
    }
    public function edit($id): View
    {
        $vehiculo = Vehiculo::find($id);

        $tipos  = \App\Models\TipoVehiculo::orderBy('nombre_tipo_vehiculo')->get();
        $marcas = \App\Models\Marca::orderBy('nombre_marca')->get();

        return view('vehiculo.edit', compact('vehiculo', 'tipos', 'marcas'));
    }
    public function update(VehiculoRequest $request, Vehiculo $vehiculo)
    {
        $vehiculo->update($request->validated());

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Vehículo actualizado exitosamente.',
                'data' => $vehiculo
            ]);
        }

        return Redirect::route('vehiculo.index')
            ->with('success', 'Vehículo actualizado exitosamente.');
    }
    public function destroy(Request $request, $id)
    {
        Vehiculo::findOrFail($id)->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return Redirect::route('vehiculo.index')
            ->with('success', 'Vehículo eliminado exitosamente.');
    }
}
