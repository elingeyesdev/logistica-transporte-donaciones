<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\Paquete;
use App\Models\User;
use App\Models\Conductor;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        
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

        
        $productosMasPedidos = Solicitud::whereNotNull('insumos_necesarios')
            ->where('insumos_necesarios', '!=', '')
            ->get()
            ->pluck('insumos_necesarios')
            ->flatMap(function($insumos) {
                
                return preg_split('/[,;\n\r]+/', $insumos, -1, PREG_SPLIT_NO_EMPTY);
            })
            ->map(function($item) {
                return trim(strtolower($item));
            })
            ->filter()
            ->countBy()
            ->sortDesc()
            ->take(5); 

        
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

        
        $promedioEntrega = Paquete::whereNotNull('fecha_creacion')
            ->whereNotNull('fecha_entrega')
            ->selectRaw('AVG(fecha_entrega::date - fecha_creacion::date) as promedio')
            ->value('promedio');
        
        $promedioEntrega = $promedioEntrega ? round($promedioEntrega, 1) : 0;

        
        $totalPaquetes = Paquete::count();
        $paquetesEntregados = Paquete::whereNotNull('fecha_entrega')->count();

        
        $totalVoluntarios = User::where('activo', true)->count();
        
        
        $voluntariosConductores = Conductor::count();

        return view('dashboard', compact(
            'total',
            'aceptadas',
            'rechazadas',
            'tasa',
            'productosMasPedidos',
            'paquetes',
            'promedioEntrega',
            'totalPaquetes',
            'paquetesEntregados',
            'totalVoluntarios',
            'voluntariosConductores'
        ));
    }
}
