@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard <small>Control panel</small></h1>
@stop

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $total }}</h3>
                <p>Solicitudes Totales</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <a href="{{ url('solicitud') }}" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $aceptadas }}</h3>
                <p>Aceptadas</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <a href="{{ url('solicitud') }}?estado=Aprobada" class="small-box-footer">Ver aceptadas <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $rechazadas }}</h3>
                <p>Rechazadas</p>
            </div>
            <div class="icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <a href="{{ url('solicitud') }}?estado=Rechazada" class="small-box-footer">Ver rechazadas <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $total > 0 ? round(($aceptadas / $total) * 100) : 0 }}%</h3>
                <p>Tasa de Aprobación</p>
            </div>
            <div class="icon">
                <i class="fas fa-chart-pie"></i>
            </div>
            <a href="#" class="small-box-footer">Info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                    <h3 class="card-title">Solicitudes</h3>
                </div>
            </div>
            <div class="card-body">
                <canvas id="solicitudesChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-file-alt"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Solicitudes</span>
                <span class="info-box-number">{{ $total }}</span>
            </div>
        </div>

        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Aceptadas</span>
                <span class="info-box-number">{{ $aceptadas }}</span>
            </div>
        </div>

        <div class="info-box">
            <span class="info-box-icon bg-danger"><i class="fas fa-times"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Rechazadas</span>
                <span class="info-box-number">{{ $rechazadas }}</span>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Progreso General</h3>
            </div>
            <div class="card-body">
                <div class="progress-group">
                    Aceptadas
                    <span class="float-right"><b>{{ $aceptadas }}</b>/{{ $total }}</span>
                    <div class="progress progress-sm">
                        <div class="progress-bar bg-success" style="width: {{ $total > 0 ? ($aceptadas / $total) * 100 : 0 }}%"></div>
                    </div>
                </div>
                <div class="progress-group">
                    Rechazadas
                    <span class="float-right"><b>{{ $rechazadas }}</b>/{{ $total }}</span>
                    <div class="progress progress-sm">
                        <div class="progress-bar bg-danger" style="width: {{ $total > 0 ? ($rechazadas / $total) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- NUEVAS SECCIONES --}}
<div class="row mt-3">
    {{-- Productos más pedidos --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title text-white">Productos Más Pedidos (Top 5)</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th class="text-right">Veces pedido</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productosMasPedidos as $producto => $cantidad)
                            <tr>
                                <td>{{ ucfirst($producto) }}</td>
                                <td class="text-right"><span class="badge badge-info">{{ $cantidad }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-center text-muted">No hay datos de productos.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Info Paquetes --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-warning">
                <h3 class="card-title">Estadísticas de Paquetes</h3>
            </div>
            <div class="card-body">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-info"><i class="fas fa-box"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Paquetes</span>
                        <span class="info-box-number">{{ $totalPaquetes }}</span>
                    </div>
                </div>
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Paquetes Entregados</span>
                        <span class="info-box-number">{{ $paquetesEntregados }}</span>
                    </div>
                </div>
                <div class="info-box">
                    <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Promedio Entrega (días)</span>
                        <span class="info-box-number">{{ $promedioEntrega }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-secondary">
                <h3 class="card-title text-white">Paquetes: Tiempo de Entrega (últimos 10)</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th>ID Paquete</th>
                            <th>Fecha Creación</th>
                            <th>Fecha Entrega</th>
                            <th class="text-right">Días de Entrega</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paquetes as $paq)
                            <tr>
                                <td><a href="{{ route('paquete.show', $paq->id_paquete) }}">#{{ $paq->id_paquete }}</a></td>
                                <td>{{ $paq->fecha_creacion }}</td>
                                <td>{{ $paq->fecha_entrega }}</td>
                                <td class="text-right">
                                    <span class="badge badge-{{ $paq->dias_entrega > 7 ? 'danger' : ($paq->dias_entrega > 3 ? 'warning' : 'success') }}">
                                        {{ round($paq->dias_entrega, 1) }} días
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">No hay paquetes con fechas de entrega.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer text-muted">
                <small>Paquetes ordenados por tiempo de entrega descendente.</small>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Chart === 'undefined') {
        console.warn('Chart.js no está cargado. Activa el plugin Chartjs en config/adminlte.php');
        return;
    }
    
    const ctx = document.getElementById('solicitudesChart');
    if (!ctx) return;
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Aceptadas', 'Rechazadas', 'Pendientes'],
            datasets: [{
                data: [{{ $aceptadas }}, {{ $rechazadas }}, {{ $total - $aceptadas - $rechazadas }}],
                backgroundColor: ['#28a745', '#dc3545', '#ffc107']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                display: true,
                position: 'bottom'
            }
        }
    });
});
</script>
@stop