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
    /**
     * Muestra la lista de estados
     */
    public function index(Request $request): View
    {
        $estados = Estado::paginate(10);

        return view('estado.index', compact('estados'))
            ->with('i', ($request->input('page', 1) - 1) * $estados->perPage());
    }

    /**
     * Muestra el formulario para crear un nuevo estado
     */
    public function create(): View
    {
        $estado = new Estado();
        return view('estado.create', compact('estado'));
    }

    /**
     * Guarda un nuevo estado en la base de datos
     */
    public function store(EstadoRequest $request): RedirectResponse
    {
        Estado::create($request->validated());

        return Redirect::route('estado.index')
            ->with('success', 'Estado creado correctamente.');
    }

    /**
     * Muestra un estado específico
     */
    public function show($id_estado): View
    {
        $estado = Estado::find($id_estado);

        if (!$estado) {
            return Redirect::route('estado.index')
                ->with('error', 'El estado no existe.');
        }

        return view('estado.show', compact('estado'));
    }

    /**
     * Muestra el formulario de edición de un estado
     */
    public function edit($id_estado): View
    {
        $estado = Estado::find($id_estado);

        if (!$estado) {
            return Redirect::route('estado.index')
                ->with('error', 'El estado no existe.');
        }

        return view('estado.edit', compact('estado'));
    }

    /**
     * Actualiza un estado existente
     */
    public function update(EstadoRequest $request, $id_estado): RedirectResponse
    {
        $estado = Estado::find($id_estado);

        if (!$estado) {
            return Redirect::route('estado.index')
                ->with('error', 'El estado no existe.');
        }

        $estado->update($request->validated());

        return Redirect::route('estado.index')
            ->with('success', 'Estado actualizado correctamente.');
    }

    /**
     * Elimina un estado
     */
    public function destroy($id_estado): RedirectResponse
    {
        $estado = Estado::find($id_estado);

        if (!$estado) {
            return Redirect::route('estado.index')
                ->with('error', 'El estado no existe o ya fue eliminado.');
        }

        $estado->delete();

        return Redirect::route('estado.index')
            ->with('success', 'Estado eliminado correctamente.');
    }
}
