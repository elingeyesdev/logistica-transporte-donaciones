<?php

namespace App\Http\Controllers;

use App\Models\Estado;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\EstadoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class EstadoController extends Controller
{
  public function index(Request $request)
    {
        $estados = Estado::paginate();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data'    => $estados
            ]);
        }

        return view('estado.index', compact('estados'))
            ->with('i', ($request->input('page', 1) - 1) * $estados->perPage());
    }
    public function create(): View
    {
        $estado = new Estado();

        return view('estado.create', compact('estado'));
    }

    public function store(EstadoRequest $request)
    {
        $estado = Estado::create($request->validated());

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Estado creado exitosamente.',
                'data'    => $estado
            ], 201);
        }

        return Redirect::route('estado.index')
            ->with('success', 'Estado creado exitosamente.');
    }

    public function show(Request $request, $id)
    {
        $estado = Estado::find($id);

        if (!$estado) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error'   => 'Estado no encontrado'
                ], 404);
            }

            return Redirect::route('estado.index')
                ->with('error', 'Estado no encontrado.');
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data'    => $estado
            ]);
        }

        return view('estado.show', compact('estado'));
    }


    public function edit($id): View
    {
        $estado = Estado::findOrFail($id);
        return view('estado.edit', compact('estado'));
    }

    public function update(EstadoRequest $request, Estado $estado)
    {
        $estado->update($request->validated());

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Estado actualizado correctamente.',
                'data'    => $estado
            ]);
        }

        return Redirect::route('estado.index')
            ->with('success', 'Estado actualizado correctamente.');
    }
    
    public function destroy(Request $request, $id)
    {
        $estado = Estado::find($id);

        if (!$estado) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error'   => 'Estado no encontrado'
                ], 404);
            }

            return Redirect::route('estado.index')
                ->with('error', 'Estado no encontrado.');
        }

        $estado->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Estado eliminado correctamente.'
            ]);
        }

        return Redirect::route('estado.index')
            ->with('success', 'Estado eliminado correctamente.');
    }

}
