<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\Paquete;
use App\Models\User;
use App\Models\Conductor;
use App\Models\Estado;
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

        $idsEntregado = Estado::whereIn('nombre_estado', ['Entregado', 'entregado'])
            ->pluck('id_estado');

        $paquetes = Paquete::whereIn('estado_id', $idsEntregado)
            ->selectRaw('
                id_paquete,
                COALESCE(fecha_creacion::date, created_at::date) as fecha_creacion,
                COALESCE(fecha_entrega::date, updated_at::date) as fecha_entrega,
                (COALESCE(fecha_entrega::date, updated_at::date) - COALESCE(fecha_creacion::date, created_at::date)) as dias_entrega
            ')
            ->orderByDesc(DB::raw('(COALESCE(fecha_entrega::date, updated_at::date) - COALESCE(fecha_creacion::date, created_at::date))'))
            ->limit(10)
            ->get();

        $promedioEntrega = Paquete::whereIn('estado_id', $idsEntregado)
            ->selectRaw('AVG(COALESCE(fecha_entrega::date, updated_at::date) - COALESCE(fecha_creacion::date, created_at::date)) as promedio')
            ->value('promedio');

        $promedioEntrega = $promedioEntrega ? round($promedioEntrega, 1) : 0;

        $totalPaquetes = Paquete::count();
        $paquetesEntregados = Paquete::whereIn('estado_id', $idsEntregado)->count();

        $totalVoluntarios = User::where('activo', true)->count();

        $voluntariosConductores = Conductor::count();

        $topVoluntariosPaquetes = Paquete::selectRaw('id_encargado, COUNT(*) as total')
            ->whereNotNull('id_encargado')
            ->groupBy('id_encargado')
            ->orderByDesc('total')
            ->limit(3)
            ->get()
            ->map(function($row){
                $user = User::where('ci', $row->id_encargado)->first();
                return [
                    'ci' => $row->id_encargado,
                    'nombre' => $user ? trim($user->nombre.' '.$user->apellido) : 'Desconocido',
                    'total' => $row->total,
                ];
            });

        $data = compact(
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
            'voluntariosConductores',
            'topVoluntariosPaquetes'
        );

        // Always return JSON for API requests
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json($data);
        }

        return view('dashboard', $data);
    }
}
