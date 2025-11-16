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
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $tipoEmergencia = TipoEmergencia::paginate();

        return view('tipo-emergencia.index', compact('tipoEmergencia'))
            ->with('i', ($request->input('page', 1) - 1) * $tipoEmergencia->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $tipoEmergencia = new TipoEmergencia();

        return view('tipo-emergencia.create', compact('tipoEmergencia'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TipoEmergenciaRequest $request): RedirectResponse
    {
        TipoEmergencia::create($request->validated());

        return Redirect::route('tipo-emergencia.index')
            ->with('success', 'TipoEmergencia creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $tipoEmergencia = TipoEmergencia::find($id);

        return view('tipo-emergencia.show', compact('tipoEmergencia'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $tipoEmergencia = TipoEmergencia::find($id);

        return view('tipo-emergencia.edit', compact('tipoEmergencia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TipoEmergenciaRequest $request, TipoEmergencia $tipoEmergencia): RedirectResponse
    {
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
