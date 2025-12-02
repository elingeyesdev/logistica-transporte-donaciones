@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Dashboard <small>Control panel</small></h1>
        <div class="btn-group">
            <button class="btn btn-outline-primary" data-toggle="modal" data-target="#dashboardFiltersModal">
                <i class="fas fa-filter"></i> Filtros
            </button>
            <button class="btn btn-primary" id="btn-refresh-dashboard">
                <i class="fas fa-sync-alt"></i> Recargar
            </button>
        </div>
    </div>
@stop

@section('content')

<div class="modal fade" id="dashboardFiltersModal" tabindex="-1" role="dialog" aria-labelledby="dashboardFiltersLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="dashboardFiltersLabel">Filtros del Dashboard</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card card-outline card-info mb-3">
                    <div class="card-header py-2">
                        <h3 class="card-title mb-0">Rango de Fechas</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="d-block">Selecciona el periodo</label>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                        </div>
                                        <input type="date" class="form-control" id="filter-date-from">
                                    </div>
                                    <small class="text-muted">Desde</small>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="far fa-calendar-check"></i></span>
                                        </div>
                                        <input type="date" class="form-control" id="filter-date-to">
                                    </div>
                                    <small class="text-muted">Hasta</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-outline card-primary">
                    <div class="card-header py-2">
                        <h3 class="card-title mb-0">Solicitudes</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-0">
                            <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
                                <label for="filter-solicitudes" class="mb-0">Mostrar por</label>
                                <div class="btn-group mt-2 mt-md-0" role="group" aria-label="Acciones filtro">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-toggle-list">
                                        <i class="fas fa-eye-slash"></i> Ocultar lista
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="btn-generar-reporte">
                                        <i class="fas fa-file-export"></i> Generar
                                    </button>
                                </div>
                            </div>
                            <select id="filter-solicitudes" class="form-control mb-3">
                                <option value="comunidad">Comunidad</option>
                                <option value="aceptadas">Aceptadas</option>
                                <option value="negadas">Negadas</option>
                            </select>
                            <ul id="filter-solicitudes-result" class="list-group">
                                <li class="list-group-item text-muted" id="filter-solicitudes-placeholder">
                                    Selecciona "Comunidad", "Aceptadas" o "Negadas" para ver la lista.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="card card-outline card-success mt-3">
                    <div class="card-header py-2">
                        <h3 class="card-title mb-0">Paquetes</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-0">
                            <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
                                <label for="filter-paquetes" class="mb-0">Mostrar por</label>
                                <div class="btn-group mt-2 mt-md-0" role="group" aria-label="Acciones paquetes">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-toggle-paquetes">
                                        <i class="fas fa-eye-slash"></i> Ocultar lista
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="btn-generar-paquetes">
                                        <i class="fas fa-file-export"></i> Generar
                                    </button>
                                </div>
                            </div>
                            <select id="filter-paquetes" class="form-control mb-3">
                                <option value="voluntarios">Voluntarios</option>
                                <option value="entregadas">Entregadas</option>
                                <option value="en_camino">En camino</option>
                                <option value="vehiculos">Vehículos</option>
                            </select>
                            <ul id="filter-paquetes-result" class="list-group">
                                <li class="list-group-item text-muted">
                                    Selecciona "Voluntarios", "Entregadas", "En camino" o "Vehículos" para ver la lista.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="apply-dashboard-filters">Aplicar</button>
            </div>
        </div>
    </div>
</div>

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
                                <td>{{ \Carbon\Carbon::parse($paq->fecha_entrega)->format('d/m/Y') }}</td>
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
const solicitudesData = {
    comunidad: @json($solicitudesPorComunidad),
    aceptadas: @json($solicitudesAceptadas),
    negadas: @json($solicitudesNegadas)
};
const paquetesData = {
    voluntarios: @json($voluntariosListado),
    entregadas: @json($paquetesEntregadosListado),
    en_camino: @json($paquetesEnCaminoListado),
    vehiculos: []
};

document.addEventListener('DOMContentLoaded', function() {
    if (typeof Chart === 'undefined') {
        console.warn('Chart.js no está cargado. Activa el plugin Chartjs en config/adminlte.php');
    } else {
        const ctx = document.getElementById('solicitudesChart');
        if (ctx) {
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
        }
    }

   
    const filterSelect = document.getElementById('filter-solicitudes');
    const resultList = document.getElementById('filter-solicitudes-result');
    const toggleListBtn = document.getElementById('btn-toggle-list');
    const paquetesSelect = document.getElementById('filter-paquetes');
    const paquetesResultList = document.getElementById('filter-paquetes-result');
    const togglePaquetesBtn = document.getElementById('btn-toggle-paquetes');
    const dateFromInput = document.getElementById('filter-date-from');
    const dateToInput = document.getElementById('filter-date-to');
    let listHidden = false;
    let paquetesListHidden = false;

    function passesDateFilter(item) {
        const fromVal = dateFromInput ? dateFromInput.value : '';
        const toVal = dateToInput ? dateToInput.value : '';
        if (!fromVal && !toVal) return true;
        if (!item.fecha_iso) return false;

        const itemDate = item.fecha_iso;
        if (fromVal && itemDate < fromVal) return false;
        if (toVal && itemDate > toVal) return false;
        return true;
    }

    function renderSolicitudesList(type) {
        if (!resultList) return;
        const requiresList = ['aceptadas', 'negadas', 'comunidad'].includes(type);
        if (!requiresList) {
            resultList.innerHTML = `
                <li class="list-group-item text-muted">
                    Selecciona "Comunidad", "Aceptadas" o "Negadas" para ver la lista.
                </li>
            `;
            return;
        }

        const dataset = solicitudesData[type] || [];
        const filtered = dataset.filter(passesDateFilter);

        if (!filtered.length) {
            const friendlyLabel = type === 'negadas' ? 'negadas' : (type === 'comunidad' ? 'por comunidad' : 'aceptadas');
            resultList.innerHTML = `
                <li class="list-group-item text-muted">
                    No hay solicitudes ${friendlyLabel} registradas para el rango seleccionado.
                </li>
            `;
            return;
        }

        if (type === 'comunidad') {
            const grouped = filtered.reduce((acc, item) => {
                const key = (item.comunidad || 'Sin comunidad').toLowerCase();
                if (!acc[key]) {
                    acc[key] = {
                        nombre: item.comunidad || 'Sin comunidad',
                        provincia: item.provincia,
                        total: 0,
                        ultimaFecha: item.fecha,
                        ultimaFechaIso: item.fecha_iso
                    };
                }
                acc[key].total += 1;
                if (item.fecha_iso && (!acc[key].ultimaFechaIso || item.fecha_iso > acc[key].ultimaFechaIso)) {
                    acc[key].ultimaFecha = item.fecha;
                    acc[key].ultimaFechaIso = item.fecha_iso;
                }
                return acc;
            }, {});

            const rows = Object.values(grouped)
                .sort((a, b) => b.total - a.total)
                .map(item => `
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${item.nombre}</strong>${item.provincia ? `<br><small class="text-muted">Provincia: ${item.provincia}</small>` : ''}
                            <br><small class="text-muted">Última solicitud: ${item.ultimaFecha}</small>
                        </div>
                        <span class="badge badge-primary badge-pill">${item.total}</span>
                    </li>
                `).join('');
            resultList.innerHTML = rows;
            return;
        }

        resultList.innerHTML = filtered.map(item => `
            <li class="list-group-item d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <div class="mb-2 mb-md-0">
                    <strong>${item.codigo}</strong> · ${item.solicitante}<br>
                    <small class="text-muted">${item.comunidad} · ${item.fecha}</small>
                </div>
                <a href="/solicitud/${item.id}" class="btn btn-sm btn-outline-secondary">Ver</a>
            </li>
        `).join('');
    }

    function attachDateListeners() {
        if (dateFromInput) {
            dateFromInput.addEventListener('change', () => {
                if (filterSelect) {
                    renderSolicitudesList(filterSelect.value);
                }
                if (paquetesSelect) {
                    renderPaquetesList(paquetesSelect.value);
                }
            });
        }
        if (dateToInput) {
            dateToInput.addEventListener('change', () => {
                if (filterSelect) {
                    renderSolicitudesList(filterSelect.value);
                }
                if (paquetesSelect) {
                    renderPaquetesList(paquetesSelect.value);
                }
            });
        }
    }

    if (filterSelect) {
        renderSolicitudesList(filterSelect.value);
        filterSelect.addEventListener('change', function() {
            renderSolicitudesList(this.value);
        });
        attachDateListeners();
    }

    function updateToggleButton() {
        if (!toggleListBtn) return;
        toggleListBtn.innerHTML = listHidden
            ? '<i class="fas fa-eye"></i> Mostrar lista'
            : '<i class="fas fa-eye-slash"></i> Ocultar lista';
    }

    if (toggleListBtn && resultList) {
        updateToggleButton();
        toggleListBtn.addEventListener('click', function() {
            listHidden = !listHidden;
            if (listHidden) {
                resultList.classList.add('d-none');
            } else {
                resultList.classList.remove('d-none');
            }
            updateToggleButton();
        });
    }

    function renderPaquetesList(type) {
        if (!paquetesResultList) return;

        if (type === 'voluntarios') {
            const dataset = paquetesData.voluntarios || [];
            if (!dataset.length) {
                paquetesResultList.innerHTML = `
                    <li class="list-group-item text-muted">
                        No se encontraron voluntarios activos.
                    </li>
                `;
                return;
            }

            paquetesResultList.innerHTML = dataset.map(item => `
                <li class="list-group-item d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <div class="mb-2 mb-md-0">
                        <strong>${item.nombre}</strong><br>
                        <small class="text-muted">CI: ${item.ci}</small>
                    </div>
                    <div class="text-md-right">
                        <div><i class="fas fa-envelope mr-1"></i>${item.correo}</div>
                        <div><i class="fas fa-phone mr-1"></i>${item.telefono}</div>
                    </div>
                </li>
            `).join('');
            return;
        }

        if (type === 'entregadas') {
            const dataset = (paquetesData.entregadas || []).filter(passesDateFilter);
            if (!dataset.length) {
                paquetesResultList.innerHTML = `
                    <li class="list-group-item text-muted">
                        No hay paquetes entregados para el rango seleccionado.
                    </li>
                `;
                return;
            }

            paquetesResultList.innerHTML = dataset.map(item => `
                <li class="list-group-item d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <div class="mb-2 mb-md-0">
                        <strong>${item.codigo}</strong> · ${item.solicitante}<br>
                        <small class="text-muted">Entrega: ${item.fecha}</small>
                    </div>
                    <div class="text-md-right">
                        <div><i class="fas fa-user mr-1"></i>${item.conductor}</div>
                        <a href="/paquete/${item.id}" class="btn btn-sm btn-outline-secondary mt-2 mt-md-0">Ver</a>
                    </div>
                </li>
            `).join('');
            return;
        }

        if (type === 'en_camino') {
            const dataset = (paquetesData.en_camino || []).filter(passesDateFilter);
            if (!dataset.length) {
                paquetesResultList.innerHTML = `
                    <li class="list-group-item text-muted">
                        No hay paquetes en camino para el rango seleccionado.
                    </li>
                `;
                return;
            }

            paquetesResultList.innerHTML = dataset.map(item => `
                <li class="list-group-item d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <div class="mb-2 mb-md-0">
                        <strong>${item.codigo}</strong> · ${item.destino}<br>
                        <small class="text-muted">Salida: ${item.fecha}${item.provincia ? ` · Prov.: ${item.provincia}` : ''}</small>
                    </div>
                    <div class="text-md-right">
                        <div><i class="fas fa-user mr-1"></i>${item.conductor}</div>
                        <div><i class="fas fa-truck mr-1"></i>${item.vehiculo}</div>
                        <a href="/paquete/${item.id}" class="btn btn-sm btn-outline-secondary mt-2">Ver</a>
                    </div>
                </li>
            `).join('');
            return;
        }

        paquetesResultList.innerHTML = `
            <li class="list-group-item text-muted">
                Selecciona "Voluntarios", "Entregadas" o "En camino" para ver la lista disponible (Vehículos próximamente).
            </li>
        `;
    }

    function updatePaquetesToggleButton() {
        if (!togglePaquetesBtn) return;
        togglePaquetesBtn.innerHTML = paquetesListHidden
            ? '<i class="fas fa-eye"></i> Mostrar lista'
            : '<i class="fas fa-eye-slash"></i> Ocultar lista';
    }

    if (paquetesSelect) {
        renderPaquetesList(paquetesSelect.value);
        paquetesSelect.addEventListener('change', function() {
            renderPaquetesList(this.value);
        });
    }

    if (togglePaquetesBtn && paquetesResultList) {
        updatePaquetesToggleButton();
        togglePaquetesBtn.addEventListener('click', function() {
            paquetesListHidden = !paquetesListHidden;
            if (paquetesListHidden) {
                paquetesResultList.classList.add('d-none');
            } else {
                paquetesResultList.classList.remove('d-none');
            }
            updatePaquetesToggleButton();
        });
    }

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

            if (data.solicitudesPorComunidad) solicitudesData.comunidad = data.solicitudesPorComunidad;
            if (data.solicitudesAceptadas) solicitudesData.aceptadas = data.solicitudesAceptadas;
            if (data.solicitudesNegadas) solicitudesData.negadas = data.solicitudesNegadas;
            if (data.voluntariosListado) paquetesData.voluntarios = data.voluntariosListado;
            if (data.paquetesEntregadosListado) paquetesData.entregadas = data.paquetesEntregadosListado;
            if (data.paquetesEnCaminoListado) paquetesData.en_camino = data.paquetesEnCaminoListado;
            if (filterSelect) renderSolicitudesList(filterSelect.value);
            if (paquetesSelect) renderPaquetesList(paquetesSelect.value);

           
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