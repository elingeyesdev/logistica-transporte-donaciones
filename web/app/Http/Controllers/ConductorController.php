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
    public function index(Request $request)
    {
        $conductores = Conductor::paginate();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $conductores
            ]);
        }
        return view('conductor.index', compact('conductores'))
            ->with('i', ($request->input('page', 1) - 1) * $conductores->perPage());
    }
    public function create(): View
    {
        $conductor = new Conductor();
        $licencia = \App\Models\TipoLicencia::all();

        return view('conductor.create', compact('conductor', 'licencia'));
    }
    
    public function store(ConductorRequest $request)
    {
        $conductor = Conductor::create($request->validated());

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Conductor creado correctamente.',
                'data' => $conductor
            ], 201);
        }

        return Redirect::route('conductor.index')
            ->with('success', 'Conductor creado exitosamente.');
    }
    public function show(Request $request, $id)
    {
        $conductor = Conductor::findOrFail($id);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $conductor
            ]);
        }

        return view('conductor.show', compact('conductor'));
    }
    public function edit($id): View
    {
        $conductor = Conductor::find($id);
        $licencia = \App\Models\TipoLicencia::all();

        return view('conductor.edit', compact('conductor', 'licencia'));
    }
    public function update(ConductorRequest $request, Conductor $conductor)
    {
        $conductor->update($request->validated());

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Conductor actualizado correctamente.',
                'data' => $conductor
            ]);
        }

        return Redirect::route('conductor.index')
            ->with('success', 'Conductor actualizado exitosamente');
    }

    public function destroy(Request $request, $id_conductor)
    {
        $conductor = Conductor::findOrFail($id_conductor);
        $conductor->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return Redirect::route('conductor.index')
            ->with('success', 'Conductor eliminado exitosamente');
    }
}
