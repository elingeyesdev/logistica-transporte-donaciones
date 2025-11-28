@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Dashboard <small>Control panel</small></h1>
        <button class="btn btn-primary" id="btn-refresh-dashboard">
            <i class="fas fa-sync-alt"></i> Recargar
        </button>
    </div>
@stop

@section('content')
<div id="dashboard-content">
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3 id="total-solicitudes">{{ $total }}</h3>
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
                <h3 id="total-aceptadas">{{ $aceptadas }}</h3>
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
                <h3 id="total-rechazadas">{{ $rechazadas }}</h3>
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
                <h3 id="tasa-aprobacion">{{ $total > 0 ? round(($aceptadas / $total) * 100) : 0 }}%</h3>
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
    <div class="col-lg-6 col-6">
        <div class="small-box bg-purple">
            <div class="inner">
                <h3 id="total-voluntarios">{{ $totalVoluntarios }}</h3>
                <p>Total Voluntarios</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ url('usuario') }}" class="small-box-footer">Ver voluntarios <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-6 col-6">
        <div class="small-box bg-teal">
            <div class="inner">
                <h3 id="voluntarios-conductores">{{ $voluntariosConductores }}</h3>
                <p>Voluntarios Conductores</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-tie"></i>
            </div>
            <a href="{{ url('conductor') }}" class="small-box-footer">Ver conductores <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card mb-3">
            <div class="card-header border-0">
                <h3 class="card-title mb-0">Solicitudes (Distribución)</h3>
            </div>
            <div class="card-body pt-2">
                <canvas id="solicitudesChart" style="min-height: 250px; height: 250px; max-height: 250px; width:100%;"></canvas>
            </div>
        </div>
    </div>
</div>


<div class="row mt-3 align-items-stretch">
    
    <div class="col-md-6 mb-4 d-flex">
        <div class="card w-100 h-100">
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
                    <tbody id="productos-tbody">
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

    
    <div class="col-md-6 mb-4 d-flex">
        <div class="card w-100 h-100">
            <div class="card-header bg-warning">
                <h3 class="card-title">Estadísticas de Paquetes</h3>
            </div>
            <div class="card-body">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-info"><i class="fas fa-box"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Paquetes</span>
                        <span class="info-box-number" id="total-paquetes">{{ $totalPaquetes }}</span>
                    </div>
                </div>
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Paquetes Entregados</span>
                        <span class="info-box-number" id="paquetes-entregados">{{ $paquetesEntregados }}</span>
                    </div>
                </div>
                <div class="card card-outline card-info mb-0">
                    <div class="card-header py-2">
                        <h3 class="card-title" style="font-size: 1rem;">Top Voluntarios (Paquetes)</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Voluntario</th>
                                    <th class="text-right">Paquetes</th>
                                </tr>
                            </thead>
                            <tbody id="voluntarios-paquetes-tbody">
                                @forelse($topVoluntariosPaquetes as $v)
                                    <tr>
                                        <td>{{ $v['nombre'] }}<br><small class="text-muted">CI: {{ $v['ci'] }}</small></td>
                                        <td class="text-right"><span class="badge badge-primary">{{ $v['total'] }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="text-center text-muted">Sin datos</td></tr>
                                @endforelse
                            </tbody>
                        </table>
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
                    <tbody id="paquetes-tbody">
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
</div>
@stop

@section('js')
<script>
let solicitudesChart = null;

document.addEventListener('DOMContentLoaded', function() {
    if (typeof Chart === 'undefined') {
        console.warn('Chart.js no está cargado. Activa el plugin Chartjs en config/adminlte.php');
        return;
    }
    
    const ctx = document.getElementById('solicitudesChart');
    if (!ctx) return;
    
    solicitudesChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Aceptadas', 'Rechazadas'],
            datasets: [{
                data: [{{ $aceptadas }}, {{ $rechazadas }}],
                backgroundColor: ['#28a745', '#dc3545']
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

   
    document.getElementById('btn-refresh-dashboard').addEventListener('click', function() {
        const btn = this;
        const icon = btn.querySelector('i');
        
       
        icon.classList.add('fa-spin');
        btn.disabled = true;

        fetch('{{ route("dashboard") }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            
            document.getElementById('total-solicitudes').textContent = data.total;
            document.getElementById('total-aceptadas').textContent = data.aceptadas;
            document.getElementById('total-rechazadas').textContent = data.rechazadas;
            const tasaAprobacion = data.total > 0 ? Math.round((data.aceptadas / data.total) * 100) : 0;
            document.getElementById('tasa-aprobacion').textContent = tasaAprobacion + '%';
            document.getElementById('total-voluntarios').textContent = data.totalVoluntarios;
            document.getElementById('voluntarios-conductores').textContent = data.voluntariosConductores;
            document.getElementById('total-paquetes').textContent = data.totalPaquetes;
            document.getElementById('paquetes-entregados').textContent = data.paquetesEntregados;

          
            if (solicitudesChart) {
                solicitudesChart.data.datasets[0].data = [
                    data.aceptadas,
                    data.rechazadas
                ];
                solicitudesChart.update();
            }

           
            const productosHtml = Object.entries(data.productosMasPedidos).length > 0
                ? Object.entries(data.productosMasPedidos).map(([producto, cantidad]) => `
                    <tr>
                        <td>${producto.charAt(0).toUpperCase() + producto.slice(1)}</td>
                        <td class="text-right"><span class="badge badge-info">${cantidad}</span></td>
                    </tr>
                `).join('')
                : '<tr><td colspan="2" class="text-center text-muted">No hay datos de productos.</td></tr>';
            document.getElementById('productos-tbody').innerHTML = productosHtml;

            
            const paquetesHtml = data.paquetes.length > 0
                ? data.paquetes.map(paq => {
                    const badgeClass = paq.dias_entrega > 7 ? 'danger' : (paq.dias_entrega > 3 ? 'warning' : 'success');
                    return `
                        <tr>
                            <td><a href="/paquete/${paq.id_paquete}">#${paq.id_paquete}</a></td>
                            <td>${paq.fecha_creacion}</td>
                            <td>${paq.fecha_entrega}</td>
                            <td class="text-right">
                                <span class="badge badge-${badgeClass}">
                                    ${Math.round(paq.dias_entrega * 10) / 10} días
                                </span>
                            </td>
                        </tr>
                    `;
                }).join('')
                : '<tr><td colspan="4" class="text-center text-muted">No hay paquetes con fechas de entrega.</td></tr>';
            document.getElementById('paquetes-tbody').innerHTML = paquetesHtml;

            
            if (data.topVoluntariosPaquetes) {
                const voluntariosHtml = data.topVoluntariosPaquetes.length > 0
                    ? data.topVoluntariosPaquetes.map(v => `
                        <tr>
                            <td>${v.nombre}<br><small class="text-muted">CI: ${v.ci}</small></td>
                            <td class="text-right"><span class="badge badge-primary">${v.total}</span></td>
                        </tr>
                    `).join('')
                    : '<tr><td colspan="2" class="text-center text-muted">Sin datos</td></tr>';
                const voluntariosTbody = document.getElementById('voluntarios-paquetes-tbody');
                if (voluntariosTbody) voluntariosTbody.innerHTML = voluntariosHtml;
            }

          
            icon.classList.remove('fa-spin');
            btn.disabled = false;
        })
        .catch(error => {
            console.error('Error refreshing dashboard:', error);
            alert('Error al actualizar el dashboard');
            icon.classList.remove('fa-spin');
            btn.disabled = false;
        });
    });
});
</script>
@stop