<?php

namespace App\Http\Controllers;

use App\Models\Conductor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ConductorRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ConductorController extends Controller
{
    public function index(Request $request): View
    {
        $conductor = Conductor::paginate();

        return view('conductor.index', compact('conductor'))
            ->with('i', ($request->input('page', 1) - 1) * $conductor->perPage());
    }
    public function create(): View
    {
        $conductor = new Conductor();
        $licencia = \App\Models\TipoLicencia::all();

        return view('conductor.create', compact('conductor', 'licencia'));
    }
    
    public function store(ConductorRequest $request): RedirectResponse
    {
        Conductor::create($request->validated());

        return Redirect::route('conductor.index')
            ->with('success', 'Conductor creado exitosamente.');
    }
    public function show($id): View
    {
        $conductor = Conductor::find($id);

        return view('conductor.show', compact('conductor'));
    }
    public function edit($id): View
    {
        $conductor = Conductor::find($id);
        $licencia = \App\Models\TipoLicencia::all();

        return view('conductor.edit', compact('conductor', 'licencia'));
    }
    public function update(ConductorRequest $request, Conductor $conductor): RedirectResponse
    {
        $conductor->update($request->validated());

        return Redirect::route('conductor.index')
            ->with('success', 'Conductor actualizado exitosamente');
    }

    public function destroy($id_conductor): RedirectResponse
    {
        Conductor::find($id_conductor)->delete();

        return Redirect::route('conductor.index')
            ->with('success', 'Conductor eliminado exitosamente');
    }
}
