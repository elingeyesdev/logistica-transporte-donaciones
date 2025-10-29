<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\SolicitudRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class SolicitudController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $solicituds = Solicitud::paginate();

        return view('solicitud.index', compact('solicituds'))
            ->with('i', ($request->input('page', 1) - 1) * $solicituds->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $solicitud = new Solicitud();

        return view('solicitud.create', compact('solicitud'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SolicitudRequest $request): RedirectResponse
    {
        Solicitud::create($request->validated());

        return Redirect::route('solicitud.index')
            ->with('success', 'Solicitud created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $solicitud = Solicitud::find($id);

        return view('solicitud.show', compact('solicitud'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $solicitud = Solicitud::find($id);

        return view('solicitud.edit', compact('solicitud'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SolicitudRequest $request, Solicitud $solicitud): RedirectResponse
    {
        $solicitud->update($request->validated());

        return Redirect::route('solicitud.index')
            ->with('success', 'Solicitud updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Solicitud::find($id)->delete();

        return Redirect::route('solicitud.index')
            ->with('success', 'Solicitud deleted successfully');
    }
}
