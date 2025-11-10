<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\HistorialSeguimientoDonacione;
use App\Models\Paquete;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\HistorialSeguimientoDonacioneRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class HistorialSeguimientoDonacioneController extends Controller
{
    public function index(Request $request): View
    {
        $historialSeguimientoDonaciones = HistorialSeguimientoDonacione::with(['paquete'])->paginate();
        return view('seguimiento.index', compact('historialSeguimientoDonaciones'))
            ->with('i', ($request->input('page', 1) - 1) * $historialSeguimientoDonaciones->perPage());
    }

    public function create(): View
    {
        
        $historialSeguimientoDonacione = new HistorialSeguimientoDonacione();
        return view('seguimiento.create', compact('historialSeguimientoDonacione'));
    }

    public function store(HistorialSeguimientoDonacioneRequest $request): RedirectResponse
    {
        $paq = Paquete::with('estado')->findOrFail($request->input('id_paquete'));
        $estadoNombre = optional($paq->estado)->nombre_estado ?? 'Pendiente';

        HistorialSeguimientoDonacione::create(array_merge(
            $request->validated(),
            [
                'estado'              => $estadoNombre,
                'ci_usuario'          => optional(Auth::user())->ci,
                'fecha_actualizacion' => now(),
            ]
        ));


        return Redirect::route('seguimiento.index')
            ->with('success', 'Seguimiento registrado.');
    }

    public function show($id): View
    {
        $historialSeguimientoDonaciones = HistorialSeguimientoDonacione::with(['paquete'])->paginate();
        return view('seguimiento.show', compact('historialSeguimientoDonacione'));
    }

    public function edit($id): View
    {
        $historialSeguimientoDonacione = HistorialSeguimientoDonacione::find($id);
        return view('seguimiento.edit', compact('historialSeguimientoDonacione'));
    }

    public function update(HistorialSeguimientoDonacioneRequest $request, HistorialSeguimientoDonacione $historialSeguimientoDonacione): RedirectResponse
    {
        $paq = Paquete::with('estado')->findOrFail($request->input('id_paquete', $historialSeguimientoDonacione->id_paquete));
        $estadoNombre = optional($paq->estado)->nombre_estado ?? 'Pendiente';

        $historialSeguimientoDonacione->update(array_merge(
            $request->validated(),
            [
                'estado'              => $estadoNombre,
                'ci_usuario'          => optional(Auth::user())->ci ?? $historialSeguimientoDonacione->ci_usuario,
                'fecha_actualizacion' => now(),
            ]
        ));

        return Redirect::route('seguimiento.index')
            ->with('success', 'Seguimiento actualizado.');
    }

    public function destroy($id): RedirectResponse
    {
        HistorialSeguimientoDonacione::find($id)?->delete();

        return Redirect::route('seguimiento.index')
            ->with('success', 'Seguimiento eliminado.');
    }
}
