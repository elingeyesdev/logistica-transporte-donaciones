<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\MarcaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class MarcaController extends Controller
{
    
    public function index(Request $request)
    {
        $marcas = Marca::paginate();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $marcas
            ]);
        }

        return view('marca.index', compact('marcas'))
            ->with('i', ($request->input('page', 1) - 1) * $marcas->perPage());
    }

    public function create(): View
    {
        $marca = new Marca();

        return view('marca.create', compact('marca'));
    }
    public function store(MarcaRequest $request)
    {
        $marca = Marca::create($request->validated());

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Marca creada correctamente.',
                'data' => $marca
            ], 201);
        }

        return Redirect::route('marca.index')
            ->with('success', 'Marca creada exitosamente.');
    }

    public function show(Request $request, $id)
    {
        $marca = Marca::findOrFail($id);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $marca
            ]);
        }

        return view('marca.show', compact('marca'));
    }


    public function edit($id): View
    {
        $marca = Marca::find($id);

        return view('marca.edit', compact('marca'));
    }

    public function update(MarcaRequest $request, Marca $marca)
    {
        $marca->update($request->validated());

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Marca actualizada correctamente.',
                'data' => $marca
            ]);
        }

        return Redirect::route('marca.index')
            ->with('success', 'Marca actualizada exitosamente.');
    }

    public function destroy(Request $request, $id)
    {
        $marca = Marca::findOrFail($id);
        $marca->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return Redirect::route('marca.index')
            ->with('success', 'Marca eliminada exitosamente.');
    }
}
