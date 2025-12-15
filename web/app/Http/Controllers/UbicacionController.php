<?php

namespace App\Http\Controllers;

use App\Models\Ubicacion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\UbicacionRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class UbicacionController extends Controller
{   
    public function index(Request $request): View
    {
        $ubicacion = Ubicacion::paginate();

        return view('ubicacion.index', compact('ubicacion'))
            ->with('i', ($request->input('page', 1) - 1) * $ubicacion->perPage());
    }
    public function create(): View
    {
        $ubicacion = new Ubicacion();

        return view('ubicacion.create', compact('ubicacion'));
    }
    public function store(UbicacionRequest $request): RedirectResponse
    {
        Ubicacion::create($request->validated());

        return Redirect::route('ubicacion.index')
            ->with('success', 'Ubicacion creado exitosamente.');
    }
    public function show($id): View
    {
        $ubicacion = Ubicacion::find($id);

        return view('ubicacion.show', compact('ubicacion'));
    }
    public function edit($id): View
    {
        $ubicacion = Ubicacion::find($id);

        return view('ubicacion.edit', compact('ubicacion'));
    }
    public function update(UbicacionRequest $request, Ubicacion $ubicacion): RedirectResponse
    {
        $ubicacion->update($request->validated());

        return Redirect::route('ubicacion.index')
            ->with('success', 'Ubicacion actualizado correctamente');
    }

    public function destroy($id): RedirectResponse
    {
        Ubicacion::find($id)->delete();

        return Redirect::route('ubicacion.index')
            ->with('success', 'Ubicacion eliminado');
    }
}
