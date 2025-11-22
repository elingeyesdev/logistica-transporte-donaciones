<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\RolRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class RolController extends Controller
{
  
    public function index(Request $request): View
    {
        $rol = Rol::paginate();

        return view('rol.index', compact('rol'))
            ->with('i', ($request->input('page', 1) - 1) * $rol->perPage());
    }

    public function create(): View
    {
        $rol = new Rol();

        return view('rol.create', compact('rol'));
    }

    public function store(RolRequest $request): RedirectResponse
    {
        Rol::create($request->validated());

        return Redirect::route('rol.index')
            ->with('success', 'Rol creado exitosamente.');
    }
    public function show($id): View
    {
        $rol = Rol::find($id);

        return view('rol.show', compact('rol'));
    }

    public function edit($id): View
    {
        $rol = Rol::find($id);

        return view('rol.edit', compact('rol'));
    }

    public function update(RolRequest $request, Rol $rol): RedirectResponse
    {
        $rol->update($request->validated());

        return Redirect::route('rol.index')
            ->with('success', 'Rol actualizado exitosamente');
    }

    public function destroy($id): RedirectResponse
    {
        Rol::find($id)->delete();

        return Redirect::route('rol.index')
            ->with('success', 'Rol eliminado exitosamente');
    }
}
