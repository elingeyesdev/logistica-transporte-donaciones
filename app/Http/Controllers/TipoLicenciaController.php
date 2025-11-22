<?php

namespace App\Http\Controllers;

use App\Http\Requests\TipoLicenciaRequest;
use App\Models\TipoLicencia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class TipoLicenciaController extends Controller
{
  
    public function index(Request $request): View
    {
        $tipoLicencia = TipoLicencia::paginate();

        return view('tipo-licencia.index', compact('tipoLicencia'))
            ->with('i', ($request->input('page', 1) - 1) * $tipoLicencia->perPage());
    }

    public function create(): View
    {
        $tipoLicencia = new TipoLicencia();

        return view('tipo-licencia.create', compact('tipoLicencia'));
    }

    public function store(TipoLicenciaRequest $request): RedirectResponse
    {
        TipoLicencia::create($request->validated());

        return Redirect::route('tipo-licencia.index')
            ->with('success', 'Tipo de licencia creado con éxito');
    }
    public function show($id_licencia): View
    {
        $tipoLicencia = TipoLicencia::find($id_licencia);

        return view('tipo-licencia.show', compact('tipoLicencium'));
    }
    public function edit($id_licencia): View
    {
        $tipoLicencia = TipoLicencia::find($id_licencia);

        return view('tipo-licencia.edit', compact('tipoLicencium'));
    }
    public function update(TipoLicenciaRequest $request, TipoLicencia $tipoLicencia): RedirectResponse
    {
        $tipoLicencia->update($request->validated());

        return Redirect::route('tipo-licencia.index')
            ->with('success', 'Tipo de Licencia actualizado exitosamente.');
    }

    public function destroy($id): RedirectResponse
    {
        TipoLicencia::find($id)->delete();

        return Redirect::route('tipo-licencia.index')
            ->with('success', 'Tipo de Licencia eliminado exitosamente');
    }
}
