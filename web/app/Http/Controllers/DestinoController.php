<?php

namespace App\Http\Controllers;

use App\Models\Destino;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\DestinoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class DestinoController extends Controller
{
   
    public function index(Request $request): View
    {
        $destino = Destino::paginate();

        return view('destino.index', compact('destino'))
            ->with('i', ($request->input('page', 1) - 1) * $destino->perPage());
    }
    public function create(): View
    {
        $destino = new Destino();

        return view('destino.create', compact('destino'));
    }

    
    public function store(DestinoRequest $request): RedirectResponse
    {
        Destino::create($request->validated());

        return Redirect::route('destino.index')
            ->with('success', 'Destino creado exitosamente.');
    }

    public function show($id): View
    {
        $destino = Destino::find($id);

        return view('destino.show', compact('destino'));
    }
    public function edit($id): View
    {
        $destino = Destino::find($id);

        return view('destino.edit', compact('destino'));
    }

    public function update(DestinoRequest $request, Destino $destino): RedirectResponse
    {
        $destino->update($request->validated());

        return Redirect::route('destino.index')
            ->with('success', 'Destino actualizado correctamente');
    }

    public function destroy($id): RedirectResponse
    {
        Destino::find($id)->delete();

        return Redirect::route('destino.index')
            ->with('success', 'Destino eliminado');
    }
}
