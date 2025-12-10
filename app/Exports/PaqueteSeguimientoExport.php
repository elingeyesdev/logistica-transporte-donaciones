<?php

namespace App\Exports;

use App\Models\Paquete;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PaqueteSeguimientoExport implements FromView
{
    protected Paquete $paquete;

    public function __construct(Paquete $paquete)
    {
        $this->paquete = $paquete;
    }

    public function view(): View
    {
        $paquete = $this->paquete->loadMissing([
            'estado',
            'solicitud.solicitante',
            'solicitud.destino',
            'conductor',
            'vehiculo.marcaVehiculo',
            'vehiculo.tipoVehiculo',
            'encargado',
        ]);

        return view('exports.paquete-seguimiento', [
            'paquete' => $paquete,
        ]);
    }
}
