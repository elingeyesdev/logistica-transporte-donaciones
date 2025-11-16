<?php

namespace App\Http\Controllers;

use App\Models\Solicitante;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\SolicitanteRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class SolicitanteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $solicitante = Solicitante::paginate();

        return view('solicitante.index', compact('solicitante'))
            ->with('i', ($request->input('page', 1) - 1) * $solicitante->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $solicitante = new Solicitante();

        return view('solicitante.create', compact('solicitante'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SolicitanteRequest $request): RedirectResponse
    {
        Solicitante::create($request->validated());

        return Redirect::route('solicitante.index')
            ->with('success', 'Solicitante creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $solicitante = Solicitante::find($id);

        return view('solicitante.show', compact('solicitante'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $solicitante = Solicitante::find($id);

        return view('solicitante.edit', compact('solicitante'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SolicitanteRequest $request, Solicitante $solicitante): RedirectResponse
    {
        $solicitante->update($request->validated());

        return Redirect::route('solicitante.index')
            ->with('success', 'Solicitante actualizado correctamente');
    }

    public function destroy($id): RedirectResponse
    {
        Solicitante::find($id)->delete();

        return Redirect::route('solicitante.index')
            ->with('success', 'Solicitante eliminado');
    }
}
