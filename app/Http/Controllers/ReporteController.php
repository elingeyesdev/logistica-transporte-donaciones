<?php

namespace App\Http\Controllers;

use App\Models\Reporte;
use App\Models\Paquete;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ReporteRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\UploadedFile;

class ReporteController extends Controller
{
 
    public function index(Request $request): View
    {
        $reportes = Reporte::paginate();

        return view('reporte.index', compact('reportes'))
            ->with('i', ($request->input('page', 1) - 1) * $reportes->perPage());
    }

    public function create(): View
    {
        $reporte = new Reporte();
        $paquetes = Paquete::with('solicitud.solicitante')->get();

        return view('reporte.create', compact('reporte', 'paquetes'));
    }

    public function store(ReporteRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('archivo_pdf')) {
            [$nombrePdf, $rutaPdf] = $this->persistPdfFile($request->file('archivo_pdf'));
            $data['nombre_pdf'] = $nombrePdf;
            $data['ruta_pdf'] = $rutaPdf;
        }

        unset($data['archivo_pdf']);

        if (empty($data['gestion'])) {
            $data['gestion'] = now()->format('Y');
        }

        Reporte::create($data);

        return Redirect::route('reporte.index')
            ->with('success', 'Reporte creado exitosamente.');
    }

    public function show($id): View
    {
        $reporte = Reporte::find($id);

        return view('reporte.show', compact('reporte'));
    }
    public function edit($id): View
    {
        $reporte = Reporte::findOrFail($id);
        $paquetes = Paquete::with('solicitud.solicitante')->get();

        return view('reporte.edit', compact('reporte', 'paquetes'));
    }


    public function update(ReporteRequest $request, Reporte $reporte): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('archivo_pdf')) {
            [$nombrePdf, $rutaPdf] = $this->persistPdfFile($request->file('archivo_pdf'), $reporte);
            $data['nombre_pdf'] = $nombrePdf;
            $data['ruta_pdf'] = $rutaPdf;
        }

        unset($data['archivo_pdf']);

        if (empty($data['gestion'])) {
            $data['gestion'] = now()->format('Y');
        }

        $reporte->update($data);

        return Redirect::route('reporte.index')
            ->with('success', 'Reporte actualizado correctamente');
    }

    public function storeDashboardReport(Request $request)
    {
        $validated = $request->validate([
            'archivo' => ['required', 'file', 'mimes:pdf', 'max:20480'],
            'fecha_reporte' => ['nullable', 'date'],
            'gestion' => ['nullable', 'string', 'max:255'],
        ]);

        [$nombrePdf, $rutaPdf] = $this->persistPdfFile($request->file('archivo'));

        $fechaReporte = $validated['fecha_reporte'] ?? now()->toDateString();
        $gestion = $validated['gestion'] ?? now()->format('Y');

        $reporte = Reporte::create([
            'nombre_pdf' => $nombrePdf,
            'ruta_pdf' => $rutaPdf,
            'fecha_reporte' => $fechaReporte,
            'gestion' => $gestion,
            'id_paquete' => null,
        ]);

        return response()->json([
            'success' => true,
            'reporte_id' => $reporte->id_reporte,
            'url' => asset('storage/' . $rutaPdf),
        ]);
    }

    public function destroy($id): RedirectResponse
    {
        $reporte = Reporte::findOrFail($id);

        if ($reporte->ruta_pdf && Storage::disk('public')->exists($reporte->ruta_pdf)) {
            Storage::disk('public')->delete($reporte->ruta_pdf);
        }

        $reporte->delete();

        return Redirect::route('reporte.index')
            ->with('success', 'Reporte eliminado');
    }

    private function persistPdfFile(?UploadedFile $file, ?Reporte $existing = null): array
    {
        if (!$file) {
            return [$existing?->nombre_pdf, $existing?->ruta_pdf];
        }

        if ($existing && $existing->ruta_pdf && Storage::disk('public')->exists($existing->ruta_pdf)) {
            Storage::disk('public')->delete($existing->ruta_pdf);
        }

        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $baseName = Str::slug($originalName) ?: 'reporte-paquete';
        $filename = $baseName . '_' . now()->format('Ymd_His') . '.pdf';
        $path = $file->storeAs('reportes', $filename, 'public');

        return [$filename, $path];
    }
}
