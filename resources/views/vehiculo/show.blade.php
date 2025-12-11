@extends('adminlte::page')

@section('template_title')
    Detalle de vehículo {{ $vehiculo->placa }}
@endsection

@section('content')
<section class="content container-fluid">
    <div class="">
        <div class="col-md-12" style="font-size:large;">
             <div class="card mb-3">
                <div class="card-header d-flex justify-content-between">
                    <div>
                        <h3 class="">{{ __('Mostrar vehículo') }}</h3>
                    </div>
                    <div>
                        <a class="btn btn-primary btn-sm" href="{{ route('vehiculo.index') }}">
                            {{ __('Volver') }}
                        </a>
                    </div>
                </div>

                <div class="card-body bg-white">
                    <div class="form-group mb-2">
                        <strong>Placa:</strong>
                        {{ $vehiculo->placa }}
                    </div>
                    <div class="form-group mb-2">
                        <strong>Capacidad aproximada:</strong>
                        {{ $vehiculo->capacidad_aproximada }}
                    </div>
                    <div class="form-group mb-2">
                        <strong>Tipo de vehículo:</strong>
                        {{ optional($vehiculo->tipoVehiculo)->nombre_tipo_vehiculo ?? $vehiculo->id_tipovehiculo }}
                    </div>
                    <div class="form-group mb-2">
                        <strong>Modelo año:</strong>
                        {{ $vehiculo->modelo_anio }}
                    </div>
                    <div class="form-group mb-2">
                        <strong>Modelo:</strong>
                        {{ $vehiculo->modelo }}
                    </div>
                    <div class="form-group mb-2">
                        <strong>Marca:</strong>
                        {{ optional($vehiculo->marcaVehiculo)->nombre_marca ?? $vehiculo->marca }}
                    </div>
                    <div class="form-group mb-2">
                        <strong>Color:</strong>
                        {{ $vehiculo->color }}
                    </div>
                </div>
            </div>

<div class="card">
    <div class="card-body bg-white">
        <ul class="nav nav-tabs mb-3" id="vehiculoTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="ruta-tab" data-bs-toggle="tab" data-bs-target="#ruta" type="button" role="tab" aria-controls="ruta" aria-selected="true">Paquetes en ruta</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="entregados-tab" data-bs-toggle="tab" data-bs-target="#entregados" type="button" role="tab" aria-controls="entregados" aria-selected="false">Paquetes entregados</button>
            </li>
        </ul>
        <div class="tab-content" id="vehiculoTabsContent">
            <div class="tab-pane fade show active" id="ruta" role="tabpanel" aria-labelledby="ruta-tab">
                @if($paquetesEnCamino->isNotEmpty())
                    <h5 class="mb-2 text-dark" style="font-weight: 700;">Paquetes en ruta</h5>
                    <div class="table-responsive mb-3">
                        <table class="table table-sm table-hover align-middle">
                            <thead>
                                <tr>
                                    <th style="width: 10%">Código</th>
                                    <th style="width: 40%">Destino</th>
                                    <th style="width: 30%">Conductor</th>
                                    <th style="width: 20%">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($paquetesEnCamino as $paquete)
                                    @php
                                        $sol = $paquete->solicitud;
                                        $dest = optional($sol?->destino);
                                        $cond  = optional($paquete->conductor);
                                        $estadoNombre = optional($paquete->estado)->nombre_estado ?? '—';
                                        $codigo = $sol->codigo_seguimiento ?? $paquete->codigo ?? '—';
                                        $conductorNombre = trim(($cond->nombre ?? '').' '.($cond->apellido ?? ''));
                                    @endphp

                                    <tr data-widget="expandable-table" aria-expanded="false">
                                        <td>
                                            <strong>{{ $codigo }}</strong>
                                        </td>
                                        <td>
                                            @if($dest->comunidad || $dest->provincia)
                                                {{ $dest->comunidad?? '—' }}, 
                                                @if($dest->provincia)
                                                        {{ $dest->provincia }}
                                                @endif
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>
                                            @if($conductorNombre || $cond->ci)
                                                {{ $conductorNombre ?: '—' }} -
                                                @if($cond->ci)
                                                        CI: {{ $cond->ci }}
                                                @endif
                                            @else
                                                <span class="text-muted">Sin conductor asignado</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info" style="font-size: medium;">
                                                {{ $estadoNombre }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr class="expandable-body d-none">
                                        <td colspan="12">
                                            <div class="p-3 bg-light">
                                                <div class="row">
                                                    <div class="col-md-5 mb-2">
                                                        <h6 class="mb-1" style="font-weight: 700;">Productos</h6>
                                                        <p class="mb-0">
                                                            {{ $sol->insumos_necesarios ?? '—' }}
                                                        </p>
                                                    </div>

                                                    <div class="col-md-5 mb-2">
                                                        <h6 class="mb-1" style="font-weight: 700;">Destino completo</h6>
                                                        @if($dest->comunidad || $dest->provincia || $dest->direccion)
                                                            <p class="mb-0">
                                                                {{ $dest->comunidad ?? '—' }},
                                                                @if($dest->provincia)
                                                                    {{ $dest->provincia}},
                                                                @endif
                                                                    {{ $dest->direccion ?? '' }}
                                                            </p>
                                                        @else
                                                            <p class="mb-0 text-muted">Sin información de destino</p>
                                                        @endif
                                                    </div>

                                                    <div class="col-md-2 mb-2">
                                                        <h6 class="mb-1" style="font-weight: 700;">Fecha de aprobación</h6>
                                                        @if($paquete->created_at)
                                                            <p class="mb-0">
                                                                {{ \Carbon\Carbon::parse($paquete->created_at)->format('d/m/Y') }}
                                                            </p>
                                                        @else
                                                            <p class="mb-0 text-muted">—</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    @php
                                                        $solicitantePersona = optional($sol?->solicitante);
                                                        $nombreSolicitante = trim(($solicitantePersona->nombre ?? '').' '.($solicitantePersona->apellido ?? '')) ?: '—';
                                                        $ciSolicitante = $solicitantePersona->ci ?? '—';
                                                        $telefonoSolicitante = $solicitantePersona->telefono ?? '—';
                                                        $tieneReferencia = filled($sol->nombre_referencia) || filled($sol->celular_referencia);
                                                    @endphp
                                                    <div class="col-md-4 mb-2">
                                                        <h6 class="mb-1" style="font-weight: 700;">Evidencia Reciente</h6>
                                                         @php
                                                            $imageUrl = $paquete->imagen
                                                                ? route('paquete.imagen', $paquete->id_paquete)
                                                                : null;
                                                        @endphp
                                                        @if($imageUrl)
                                                            <img src="{{ $imageUrl }}" class="card-img-top" alt="Foto de entrega" style="max-width: 200px; max-height: 200px; object-fit:cover;">
                                                        @else
                                                            <div class="text-muted">Imagen no disponible</div>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <h6 class="mb-1" style="font-weight: 700;">Solicitante</h6>
                                                        <p class="mb-1"><strong>Nombre:</strong> {{ $nombreSolicitante }}</p>
                                                        <p class="mb-1"><strong>CI:</strong> {{ $ciSolicitante }}</p>
                                                        <p class="mb-0"><strong>Contacto:</strong> {{ $telefonoSolicitante }}</p>
                                                    </div>
                                                    @if($tieneReferencia)
                                                        <div class="col-md-4 mb-2">
                                                            <h6 class="mb-1" style="font-weight: 700;">Contacto de referencia</h6>
                                                            <p class="mb-1"><strong>Nombre:</strong> {{ $sol->nombre_referencia ?? '—' }}</p>
                                                            <p class="mb-0"><strong>Contacto:</strong> {{ $sol->celular_referencia ?? '—' }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">No hay paquetes en ruta para este vehículo.</p>
                @endif
            </div>
            <div class="tab-pane fade" id="entregados" role="tabpanel" aria-labelledby="entregados-tab">
                @if($paquetesOtros->isNotEmpty())
                    <h5 class="mt-2 mb-2" style="font-weight: 700;">Paquetes entregados</h5>
                    <div class="table-responsive mb-3">
                        <table class="table table-sm table-hover align-middle">
                            <thead>
                                <tr>
                                    <th style="width: 10%">Código</th>
                                    <th style="width: 40%">Destino</th>
                                    <th style="width: 30%">Conductor</th>
                                    <th style="width: 20%">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($paquetesOtros as $paquete)
                                    @php
                                        $sol = $paquete->solicitud;
                                        $dest = optional($sol?->destino);
                                        $cond  = optional($paquete->conductor);
                                        $estadoNombre = optional($paquete->estado)->nombre_estado ?? '—';
                                        $codigo = $sol->codigo_seguimiento ?? $paquete->codigo ?? '—';
                                        $conductorNombre = trim(($cond->nombre ?? '').' '.($cond->apellido ?? ''));
                                    @endphp

                                    <tr data-widget="expandable-table" aria-expanded="false">
                                        <td>
                                            <strong>{{ $codigo }}</strong>
                                        </td>
                                        <td>
                                            @if($dest->comunidad || $dest->provincia)
                                                {{ $dest->comunidad?? '—' }}, 
                                                @if($dest->provincia)
                                                        {{ $dest->provincia }}
                                                @endif
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>
                                            @if($conductorNombre || $cond->ci)
                                                {{ $conductorNombre ?: '—' }} -
                                                @if($cond->ci)
                                                        CI: {{ $cond->ci }}
                                                @endif
                                            @else
                                                <span class="text-muted">Sin conductor asignado</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-success" style="font-size: medium;">
                                                {{ $estadoNombre }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr class="expandable-body d-none">
                                        <td colspan="12">
                                            <div class="p-3 bg-light">
                                                <div class="row">
                                                    <div class="col-md-5 mb-2">
                                                        <h6 class="mb-1" style="font-weight: 700;">Productos</h6>
                                                        <p class="mb-0">
                                                            {{ $sol->insumos_necesarios ?? '—' }}
                                                        </p>
                                                    </div>

                                                    <div class="col-md-5 mb-2">
                                                        <h6 class="mb-1" style="font-weight: 700;">Destino completo</h6>
                                                        @if($dest->comunidad || $dest->provincia || $dest->direccion)
                                                            <p class="mb-0">
                                                                {{ $dest->comunidad ?? '—' }},
                                                                @if($dest->provincia)
                                                                    {{ $dest->provincia}},
                                                                @endif
                                                                    {{ $dest->direccion ?? '' }}
                                                            </p>
                                                        @else
                                                            <p class="mb-0 text-muted">Sin información de destino</p>
                                                        @endif
                                                    </div>

                                                    <div class="col-md-2 mb-2">
                                                        <h6 class="mb-1" style="font-weight: 700;">Fecha de aprobación</h6>
                                                        @if($paquete->created_at)
                                                            <p class="mb-0">
                                                                {{ \Carbon\Carbon::parse($paquete->created_at)->format('d/m/Y') }}
                                                            </p>
                                                        @else
                                                            <p class="mb-0 text-muted">—</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    @php
                                                        $solicitantePersona = optional($sol?->solicitante);
                                                        $nombreSolicitante = trim(($solicitantePersona->nombre ?? '').' '.($solicitantePersona->apellido ?? '')) ?: '—';
                                                        $ciSolicitante = $solicitantePersona->ci ?? '—';
                                                        $telefonoSolicitante = $solicitantePersona->telefono ?? '—';
                                                        $tieneReferencia = filled($sol->nombre_referencia) || filled($sol->celular_referencia);
                                                    @endphp
                                                    <div class="col-md-4 mb-2">
                                                        <h6 class="mb-1" style="font-weight: 700;">Evidencia de Entrega</h6>
                                                         @php
                                                            $imageUrl = $paquete->imagen
                                                                ? route('paquete.imagen', $paquete->id_paquete)
                                                                : null;
                                                        @endphp
                                                        @if($imageUrl)
                                                            <img src="{{ $imageUrl }}" class="card-img-top" alt="Foto de entrega" style="max-width: 200px; max-height: 200px; object-fit:cover;">
                                                        @else
                                                            <div class="text-muted">Imagen no disponible</div>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <h6 class="mb-1" style="font-weight: 700;">Solicitante</h6>
                                                        <p class="mb-1"><strong>Nombre:</strong> {{ $nombreSolicitante }}</p>
                                                        <p class="mb-1"><strong>CI:</strong> {{ $ciSolicitante }}</p>
                                                        <p class="mb-0"><strong>Contacto:</strong> {{ $telefonoSolicitante }}</p>
                                                    </div>
                                                    @if($tieneReferencia)
                                                        <div class="col-md-4 mb-2">
                                                            <h6 class="mb-1" style="font-weight: 700;">Contacto de referencia</h6>
                                                            <p class="mb-1"><strong>Nombre:</strong> {{ $sol->nombre_referencia ?? '—' }}</p>
                                                            <p class="mb-0"><strong>Contacto:</strong> {{ $sol->celular_referencia ?? '—' }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">No hay paquetes entregados para este vehículo.</p>
                @endif
            </div>
        </div>
        <div id="mapa-ruta-vehiculo" class="mt-3" style="height: 400px; display: none;">
           
        </div>
    </div>
</div>
            </div>
            

        </div>
    </div>
</section>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script>
document.addEventListener('DOMContentLoaded', function() {
    // seccion unica con b5
    var triggerTabList = [].slice.call(document.querySelectorAll('#vehiculoTabs button'));
    triggerTabList.forEach(function (triggerEl) {
        var tabTrigger = new bootstrap.Tab(triggerEl);
        triggerEl.addEventListener('click', function (event) {
            event.preventDefault();
            tabTrigger.show();
        });
    });

    // Mapa
    const mapaDiv = document.getElementById('mapa-ruta-vehiculo');
    if (!mapaDiv) return;
    mapaDiv.style.display = 'block';

    const paquetes = [
        @foreach($paquetesEnCamino as $p)
            {
                latitud: {{ $p->solicitud->destino->latitud ?? 'null' }},
                longitud: {{ $p->solicitud->destino->longitud ?? 'null' }},
                fecha_salida: '{{ $p->fecha_creacion ?? $p->created_at }}',
                fecha_llegada: '{{ $p->fecha_entrega ?? $p->updated_at }}',
                comunidad: '{{ $p->solicitud->destino->comunidad ?? '' }}',
                direccion: '{{ $p->solicitud->destino->direccion ?? '' }}',
                codigo: '{{ $p->codigo ?? $p->solicitud->codigo_seguimiento ?? '' }}',
            },
        @endforeach
        @foreach($paquetesOtros as $p)
            {
                latitud: {{ $p->solicitud->destino->latitud ?? 'null' }},
                longitud: {{ $p->solicitud->destino->longitud ?? 'null' }},
                fecha_salida: '{{ $p->fecha_creacion ?? $p->created_at }}',
                fecha_llegada: '{{ $p->fecha_entrega ?? $p->updated_at }}',
                comunidad: '{{ $p->solicitud->destino->comunidad ?? '' }}',
                direccion: '{{ $p->solicitud->destino->direccion ?? '' }}',
                codigo: '{{ $p->codigo ?? $p->solicitud->codigo_seguimiento ?? '' }}',
            },
        @endforeach
    ];

    const puntos = paquetes.filter(p => p.latitud !== null && p.longitud !== null);
    puntos.sort((a, b) => {
        const fa = new Date(a.fecha_salida);
        const fb = new Date(b.fecha_salida);
        return fa - fb;
    });
    if (puntos.length === 0) return;
    const map = L.map('mapa-ruta-vehiculo').setView([puntos[0].latitud, puntos[0].longitud], 9);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '© OpenStreetMap'
    }).addTo(map);
    const polyCoords = puntos.map(p => [p.latitud, p.longitud]);
    const polyline = L.polyline(polyCoords, {color: 'blue', weight: 5}).addTo(map);
    map.fitBounds(polyline.getBounds());
    puntos.forEach((p, idx) => {
        const marker = L.marker([p.latitud, p.longitud]).addTo(map);
        marker.bindPopup(`<strong>${p.codigo}</strong><br>${p.comunidad}<br>${p.direccion}<br>Salida: ${p.fecha_salida}<br>Llegada: ${p.fecha_llegada}`);
    });
});
</script>
@endsection
