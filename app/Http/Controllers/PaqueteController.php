<?php

namespace App\Http\Controllers;

use App\Models\paquete;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\paqueteRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class paqueteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $paquetes = paquete::paginate();

        return view('paquete.index', compact('paquetes'))
            ->with('i', ($request->input('page', 1) - 1) * $paquetes->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $paquete = new paquete();

        return view('paquete.create', compact('paquete'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(paqueteRequest $request): RedirectResponse
    {
        paquete::create($request->validated());

        return Redirect::route('paquete.index')
            ->with('success', 'paquete created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $paquete = paquete::find($id);

        return view('paquete.show', compact('paquete'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $paquete = paquete::find($id);

        return view('paquete.edit', compact('paquete'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(paqueteRequest $request, paquete $paquete): RedirectResponse
    {
        $paquete->update($request->validated());

        return Redirect::route('paquete.index')
            ->with('success', 'paquete updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        paquete::find($id)->delete();

        return Redirect::route('paquete.index')
            ->with('success', 'paquete deleted successfully');
    }
}
