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
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $tipoLicencia = TipoLicencia::paginate();

        return view('tipo-licencia.index', compact('tipoLicencia'))
            ->with('i', ($request->input('page', 1) - 1) * $tipoLicencia->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $tipoLicencia = new TipoLicencia();

        return view('tipo-licencia.create', compact('tipoLicencia'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TipoLicenciaRequest $request): RedirectResponse
    {
        TipoLicencia::create($request->validated());

        return Redirect::route('tipo-licencia.index')
            ->with('success', 'Tipo de licencia creado con éxito');
    }

    /**
     * Display the specified resource.
     */
    public function show($id_licencia): View
    {
        $tipoLicencia = TipoLicencia::find($id_licencia);

        return view('tipo-licencia.show', compact('tipoLicencium'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id_licencia): View
    {
        $tipoLicencia = TipoLicencia::find($id_licencia);

        return view('tipo-licencia.edit', compact('tipoLicencium'));
    }

    /**
     * Update the specified resource in storage.
     */
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
