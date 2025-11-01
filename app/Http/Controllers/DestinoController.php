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
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $destino = Destino::paginate();

        return view('destino.index', compact('destino'))
            ->with('i', ($request->input('page', 1) - 1) * $destino->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $destino = new Destino();

        return view('destino.create', compact('destino'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DestinoRequest $request): RedirectResponse
    {
        Destino::create($request->validated());

        return Redirect::route('destino.index')
            ->with('success', 'Destino created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $destino = Destino::find($id);

        return view('destino.show', compact('destino'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $destino = Destino::find($id);

        return view('destino.edit', compact('destino'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DestinoRequest $request, Destino $destino): RedirectResponse
    {
        $destino->update($request->validated());

        return Redirect::route('destino.index')
            ->with('success', 'Destino updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Destino::find($id)->delete();

        return Redirect::route('destino.index')
            ->with('success', 'Destino deleted successfully');
    }
}
