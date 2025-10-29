<?php

namespace App\Http\Controllers;

use App\Models\Donacion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\DonacionRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class DonacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $donacions = Donacion::paginate();

        return view('donacion.index', compact('donacions'))
            ->with('i', ($request->input('page', 1) - 1) * $donacions->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $donacion = new Donacion();

        return view('donacion.create', compact('donacion'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DonacionRequest $request): RedirectResponse
    {
        Donacion::create($request->validated());

        return Redirect::route('donacion.index')
            ->with('success', 'Donacion created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $donacion = Donacion::find($id);

        return view('donacion.show', compact('donacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $donacion = Donacion::find($id);

        return view('donacion.edit', compact('donacion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DonacionRequest $request, Donacion $donacion): RedirectResponse
    {
        $donacion->update($request->validated());

        return Redirect::route('donacion.index')
            ->with('success', 'Donacion updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Donacion::find($id)->delete();

        return Redirect::route('donacion.index')
            ->with('success', 'Donacion deleted successfully');
    }
}
