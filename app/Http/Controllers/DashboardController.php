<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\Paquete;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // === SOLICITUDES ===
        $total = Solicitud::count();

        $aceptadas = Solicitud::where(function($q){
            $q->where('aprobada', true)->orWhere('estado', 'Aprobada');
        })->count();

        $rechazadas = Solicitud::where(function($q){
            $q->where('aprobada', false)->orWhereIn('estado', ['Rechazada','Negada']);
        })->count();

        $tasa = ($aceptadas + $rechazadas) > 0
            ? round(($aceptadas / ($aceptadas + $rechazadas)) * 100, 1)
            : 0;

        // === PRODUCTOS MÁS PEDIDOS ===
        // Supone que el campo insumos_necesarios contiene texto con productos separados por comas o líneas
        $productosMasPedidos = Solicitud::whereNotNull('insumos_necesarios')
            ->where('insumos_necesarios', '!=', '')
            ->get()
            ->pluck('insumos_necesarios')
            ->flatMap(function($insumos) {
                // Divide por comas, saltos de línea o punto y coma
                return preg_split('/[,;\n\r]+/', $insumos, -1, PREG_SPLIT_NO_EMPTY);
            })
            ->map(function($item) {
                return trim(strtolower($item));
            })
            ->filter()
            ->countBy()
            ->sortDesc()
            ->take(5); // Top 5 productos

        // === PAQUETES: Tiempo de entrega ===
        $paquetes = Paquete::whereNotNull('fecha_creacion')
            ->whereNotNull('fecha_entrega')
            ->selectRaw('
                id_paquete,
                fecha_creacion,
                fecha_entrega,
                (fecha_entrega::date - fecha_creacion::date) as dias_entrega
            ')
            ->orderByDesc(DB::raw('(fecha_entrega::date - fecha_creacion::date)'))
            ->limit(10)
            ->get();

        // Promedio de días de entrega
        $promedioEntrega = Paquete::whereNotNull('fecha_creacion')
            ->whereNotNull('fecha_entrega')
            ->selectRaw('AVG(fecha_entrega::date - fecha_creacion::date) as promedio')
            ->value('promedio');
        
        $promedioEntrega = $promedioEntrega ? round($promedioEntrega, 1) : 0;

        // Total paquetes
        $totalPaquetes = Paquete::count();
        $paquetesEntregados = Paquete::whereNotNull('fecha_entrega')->count();

        return view('dashboard', compact(
            'total',
            'aceptadas',
            'rechazadas',
            'tasa',
            'productosMasPedidos',
            'paquetes',
            'promedioEntrega',
            'totalPaquetes',
            'paquetesEntregados'
        ));
    }
}
