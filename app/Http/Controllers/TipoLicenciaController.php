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
  
    public function index(Request $request)
    {
        $tipoLicencia = TipoLicencia::paginate();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $tipoLicencia
            ]);
        }

        return view('tipo-licencia.index', compact('tipoLicencia'))
            ->with('i', ($request->input('page', 1) - 1) * $tipoLicencia->perPage());
    }

    public function create(): View
    {
        $tipoLicencia = new TipoLicencia();

        return view('tipo-licencia.create', compact('tipoLicencia'));
    }

    public function store(TipoLicenciaRequest $request)
    {
        $tipoLicencia = TipoLicencia::create($request->validated());

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tipo de licencia creado con éxito.',
                'data' => $tipoLicencia
            ], 201);
        }

        return Redirect::route('tipo-licencia.index')
            ->with('success', 'Tipo de licencia creado con éxito.');
    }

    public function show(Request $request, $id)
    {
        $tipoLicencia = TipoLicencia::findOrFail($id);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $tipoLicencia
            ]);
        }

        return view('tipo-licencia.show', compact('tipoLicencia'));
    }

    public function edit($id_licencia): View
    {
        $tipoLicencia = TipoLicencia::findOrFail($id_licencia);

        return view('tipo-licencia.edit', compact('tipoLicencia'));
    }
    public function update(TipoLicenciaRequest $request, TipoLicencia $tipoLicencia)
    {
        $tipoLicencia->update($request->validated());

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tipo de licencia actualizado.',
                'data' => $tipoLicencia
            ]);
        }

        return Redirect::route('tipo-licencia.index')
            ->with('success', 'Tipo de licencia actualizado exitosamente.');
    }

    public function destroy(Request $request, $id)
    {
        TipoLicencia::findOrFail($id)->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return Redirect::route('tipo-licencia.index')
            ->with('success', 'Tipo de licencia eliminado exitosamente.');
    }
}
