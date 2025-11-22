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
    public function index(Request $request): View
    {
        $estado = Estado::paginate();

        return view('estado.index', compact('estado'))
            ->with('i', ($request->input('page', 1) - 1) * $estado->perPage());
    }

    public function create(): View
    {
        $estado = new Estado();

        return view('estado.create', compact('estado'));
    }

    public function store(EstadoRequest $request): RedirectResponse
    {
        Estado::create($request->validated());

        return Redirect::route('estado.index')
            ->with('success', 'Estado creado exitosamente.');
    }

    public function show($id): View
    {
        $estado = Estado::find($id);

        return view('estado.show', compact('estado'));
    }

    public function edit($id): View
    {
        $estado = Estado::find($id);

        return view('estado.edit', compact('estado'));
    }

    public function update(EstadoRequest $request, Estado $estado): RedirectResponse
    {
        $estado->update($request->validated());

        return Redirect::route('estado.index')
            ->with('success', 'Estado actualizado correctamente');
    }

    public function destroy($id): RedirectResponse
    {
        Estado::find($id)->delete();

        return Redirect::route('estado.index')
            ->with('success', 'Estado eliminado');
    }
}
