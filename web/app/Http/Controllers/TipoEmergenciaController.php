<?php

namespace App\Http\Controllers;

use App\Models\TipoEmergencia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\TipoEmergenciaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class TipoEmergenciaController extends Controller
{
 
    public function index(Request $request): View
    {
        $tipoEmergencia = TipoEmergencia::paginate();

        return view('tipo-emergencia.index', compact('tipoEmergencia'))
            ->with('i', ($request->input('page', 1) - 1) * $tipoEmergencia->perPage());
    }

    public function create(): View
    {
        $tipoEmergencia = new TipoEmergencia();

        return view('tipo-emergencia.create', compact('tipoEmergencia'));
    }

    public function store(TipoEmergenciaRequest $request): RedirectResponse
    {
        TipoEmergencia::create($request->validated());

        return Redirect::route('tipo-emergencia.index')
            ->with('success', 'TipoEmergencia creado exitosamente');
    }
    public function show($id): View
    {
        $tipoEmergencia = TipoEmergencia::find($id);

        return view('tipo-emergencia.show', compact('tipoEmergencia'));
    }

    public function edit($id): View
    {
        $tipoEmergencia = TipoEmergencia::find($id);

        return view('tipo-emergencia.edit', compact('tipoEmergencia'));
    }
    public function update(TipoEmergenciaRequest $request, $id): RedirectResponse
    {
        $tipoEmergencia = TipoEmergencia::find($id);
        $tipoEmergencia->update($request->validated());

        return Redirect::route('tipo-emergencia.index')
            ->with('success', 'TipoEmergencia actualizado exitosamente');
    }

    public function destroy($id): RedirectResponse
    {
        TipoEmergencia::find($id)->delete();

        return Redirect::route('tipo-emergencia.index')
            ->with('success', 'TipoEmergencia eliminado exitosamente');
    }
}
