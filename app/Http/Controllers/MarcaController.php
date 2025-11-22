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
    
    public function index(Request $request): View
    {
        $marcas = Marca::paginate();

        return view('marca.index', compact('marcas'))
            ->with('i', ($request->input('page', 1) - 1) * $marcas->perPage());
    }

    public function create(): View
    {
        $marca = new Marca();

        return view('marca.create', compact('marca'));
    }
    public function store(MarcaRequest $request): RedirectResponse
    {
        Marca::create($request->validated());

        return Redirect::route('marca.index')
            ->with('success', 'Marca creada exitosamente.');
    }

    public function show($id): View
    {
        $marca = Marca::find($id);

        return view('marca.show', compact('marca'));
    }


    public function edit($id): View
    {
        $marca = Marca::find($id);

        return view('marca.edit', compact('marca'));
    }


    public function update(MarcaRequest $request, Marca $marca): RedirectResponse
    {
        $marca->update($request->validated());

        return Redirect::route('marca.index')
            ->with('success', 'Marca actualizada exitosamente.');
    }

    public function destroy($id): RedirectResponse
    {
        Marca::find($id)->delete();

        return Redirect::route('marca.index')
            ->with('success', 'Marca eliminada exitosamente.');
    }
}
