@extends('adminlte::page')

@section('template_title')
    Detalle de vehículo {{ $vehiculo->placa }}
@endsection

@section('content')
<section class="content container-fluid">
    <div class="">
        <div class="col-md-12" style="font-size:large;">
             <div class="card mb-3">
                <div class="card-header" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); color: white; display: flex; justify-content: space-between; align-items: center;">
                    <div class="float-left">
                        <span class="card-title"><i class="fas fa-car mr-2"></i>{{ __('Mostrar vehículo') }}</span>
                    </div>
                    <div class="float-right">
                        <a class="btn btn-primary btn-sm" href="{{ route('vehiculo.index') }}">{{ __('Volver') }}</a>
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
                    <h5 class="mb-2 text-dark d-flex align-items-center" style="font-weight: 700;">
                        Paquetes en ruta
                        <button id="pdf-paquetes-ruta" class="btn btn-danger btn-sm ms-2" title="Descargar PDF" style="font-size: 0.95em;">
                            <i class="fas fa-file-pdf"></i> PDF
                        </button>
                    </h5>
                    @section('js')
                    @parent
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const pdfBtn = document.getElementById('pdf-paquetes-ruta');
                        if (pdfBtn) {
                            pdfBtn.addEventListener('click', function() {
                                const paquetes = window.paquetesEnRutaForPdf || [];
                                if (!paquetes.length) return alert('No hay paquetes en ruta para exportar.');
                                const wrapper = document.createElement('div');
                                wrapper.innerHTML = '<h2 style="text-align:center;">Reporte de Paquetes en Ruta</h2>';
                                paquetes.forEach(function(p, idx) {
                                    const card = document.createElement('div');
                                    card.style = 'border:1px solid #ccc;padding:12px;margin-bottom:18px;border-radius:8px;';
                                    card.innerHTML =
                                        `<h3 style='color:#138496;'>Paquete #${idx+1} - Código: <b>${p.codigo}</b></h3>`+
                                        `<p><b>Destino:</b> ${p.destino}</p>`+
                                        `<p><b>Conductor:</b> ${p.conductor}</p>`+
                                        `<p><b>Estado:</b> ${p.estado}</p>`+
                                        `<p><b>Productos:</b> ${p.productos}</p>`+
                                        `<p><b>Destino completo:</b> ${p.destino_completo}</p>`+
                                        `<p><b>Fecha de aprobación:</b> ${p.fecha_aprobacion}</p>`+
                                        `<p><b>Solicitante:</b> ${p.solicitante}</p>`+
                                        `<p><b>CI Solicitante:</b> ${p.ci_solicitante}</p>`+
                                        `<p><b>Contacto Solicitante:</b> ${p.contacto_solicitante}</p>`+
                                        (p.referencia ? `<p><b>Referencia:</b> ${p.referencia}</p>` : '')+
                                        (p.contacto_referencia ? `<p><b>Contacto Referencia:</b> ${p.contacto_referencia}</p>` : '')+
                                        (p.imagen ? `<img src='${p.imagen}' alt='Evidencia' style='max-width:180px;max-height:180px;object-fit:cover;margin-top:8px;'>` : '<div style="color:#888;">Imagen no disponible</div>');
                                    wrapper.appendChild(card);
                                });
                                html2pdf().set({
                                    margin: 10,
                                    filename: 'paquetes_en_ruta.pdf',
                                    html2canvas: { scale: 2 },
                                    jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                                }).from(wrapper).save();
                            });
                        }
                    });
                    </script>
                    @php
                    // Prepara los datos de paquetes en ruta para el PDF en JS
                    $paquetesEnRutaForPdf = $paquetesEnCamino->map(function($paquete) {
                        $sol = $paquete->solicitud;
                        $dest = optional($sol?->destino);
                        $cond  = optional($paquete->conductor);
                        $estadoNombre = optional($paquete->estado)->nombre_estado ?? '—';
                        $codigo = $sol->codigo_seguimiento ?? $paquete->codigo ?? '—';
                        $conductorNombre = trim(($cond->nombre ?? '').' '.($cond->apellido ?? ''));
                        $productos = $sol->insumos_necesarios ?? '—';
                        $destinoCompleto = collect([
                            $dest->comunidad ?? null,
                            $dest->provincia ?? null,
                            $dest->direccion ?? null
                        ])->filter()->implode(', ');
                        $fechaAprobacion = $paquete->created_at ? \Carbon\Carbon::parse($paquete->created_at)->format('d/m/Y') : '—';
                        $solicitantePersona = optional($sol?->solicitante);
                        $nombreSolicitante = trim(($solicitantePersona->nombre ?? '').' '.($solicitantePersona->apellido ?? '')) ?: '—';
                        $ciSolicitante = $solicitantePersona->ci ?? '—';
                        $telefonoSolicitante = $solicitantePersona->telefono ?? '—';
                        $tieneReferencia = filled($sol->nombre_referencia) || filled($sol->celular_referencia);
                        $imageUrl = $paquete->imagen ? route('paquete.imagen', $paquete->id_paquete) : null;
                        return [
                            'codigo' => $codigo,
                            'destino' => ($dest->comunidad ?? '—').($dest->provincia ? ', '.$dest->provincia : ''),
                            'conductor' => $conductorNombre ?: '—',
                            'estado' => $estadoNombre,
                            'productos' => $productos,
                            'destino_completo' => $destinoCompleto ?: '—',
                            'fecha_aprobacion' => $fechaAprobacion,
                            'solicitante' => $nombreSolicitante,
                            'ci_solicitante' => $ciSolicitante,
                            'contacto_solicitante' => $telefonoSolicitante,
                            'referencia' => $sol->nombre_referencia ?? '',
                            'contacto_referencia' => $sol->celular_referencia ?? '',
                            'imagen' => $imageUrl,
                        ];
                    })->values();
                    @endphp
                    <script>
                    window.paquetesEnRutaForPdf = @json($paquetesEnRutaForPdf);
                    </script>
                    @endsection
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
                                            <strong>
                                                <span class="paquete-dot"
                                                    data-paquete-id="{{ $paquete->id_paquete }}"
                                                    title="Ruta en el mapa"
                                                    style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#6c757d;margin-right:6px;vertical-align:middle;box-shadow:0 1px 2px rgba(0,0,0,.25);">
                                                </span>
                                                {{ $codigo }}
                                            </strong>
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
                                            <strong>
                                                <span class="paquete-dot"
                                                    data-paquete-id="{{ $paquete->id_paquete }}"
                                                    title="Ruta en el mapa"
                                                    style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#6c757d;margin-right:6px;vertical-align:middle;box-shadow:0 1px 2px rgba(0,0,0,.25);">
                                                </span>
                                                {{ $codigo }}
                                            </strong>
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
        <div id="mapa-ruta-vehiculo" class="mt-3" style="height: 400px; display: none;"  data-paquete-url="{{ route('paquete.show', '__ID__') }}">
           
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
    var triggerTabList = [].slice.call(document.querySelectorAll('#vehiculoTabs button'));
    triggerTabList.forEach(function (triggerEl) {
        var tabTrigger = new bootstrap.Tab(triggerEl);
        triggerEl.addEventListener('click', function (event) {
            event.preventDefault();
            tabTrigger.show();
        });
    });

        
        const mapaDiv = document.getElementById('mapa-ruta-vehiculo');
        const rutaTab = document.getElementById('ruta-tab');
        const entregadosTab = document.getElementById('entregados-tab');
        function updateMapVisibility() {
            if (!mapaDiv) return;
            const rutaActive = rutaTab.classList.contains('active');
            mapaDiv.style.display = rutaActive ? 'block' : 'none';
        }
        updateMapVisibility();
        rutaTab && rutaTab.addEventListener('shown.bs.tab', updateMapVisibility);
        entregadosTab && entregadosTab.addEventListener('shown.bs.tab', updateMapVisibility);
    const formatDMY = (value) => {
        if (!value) return 'Sin fecha';

        const d = new Date(value);
        if (isNaN(d)) return 'Sin fecha';

        const day = String(d.getDate()).padStart(2, '0');
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const year = d.getFullYear();

        return `${day}/${month}/${year}`;
    };


    const paquetes = [
        @foreach($paquetesEnCamino as $p)
            {
                id_paquete: {{ $p->id_paquete }},
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
                id_paquete: {{ $p->id_paquete }},
                latitud: {{ $p->solicitud->destino->latitud ?? 'null' }},
                longitud: {{ $p->solicitud->destino->longitud ?? 'null' }},
                fecha_salida: '{{ $p->created_at }}',
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
    
    const parseDateSafe = (value) => {
        const d = new Date(value);
        return isNaN(d) ? null : d;
    };

    const palette = [
        '#1f77b4', '#ff7f0e', '#2ca02c', '#d62728',
        '#9467bd', '#8c564b', '#e377c2', '#7f7f7f',
        '#bcbd22', '#17becf'
    ];

    const paqueteColorMap = new Map();
    const getColorForPaquete = (id_paquete) => {
        if (!paqueteColorMap.has(id_paquete)) {
            const idx = paqueteColorMap.size % palette.length;
            paqueteColorMap.set(id_paquete, palette[idx]);
        }
        return paqueteColorMap.get(id_paquete);
    };

    const paintCodigoDots = () => {
        document.querySelectorAll('.paquete-dot[data-paquete-id]').forEach(el => {
            const id = parseInt(el.dataset.paqueteId, 10);
            if (!Number.isFinite(id)) return;
            el.style.background = getColorForPaquete(id);
        });
    };

    const dotIcon = (color) => L.divIcon({
        className: '',
        html: `<div style="
            width:16px;height:16px;border-radius:50%;
            background:${color};
            border:2px solid #fff;
            box-shadow:0 1px 3px rgba(0,0,0,.35);
        "></div>`,
        iconSize: [16, 16],
        iconAnchor: [8, 8],
    });

    let markers = [];
    let polyline = null;

    const clearMap = () => {
        markers.forEach(m => map.removeLayer(m));
        markers = [];
        if (polyline) {
            map.removeLayer(polyline);
            polyline = null;
        }
    };

    const drawAll = (joinedPoints) => {
        clearMap();

        const coords = joinedPoints.map(p => [p.latitud, p.longitud]);
        polyline = L.polyline(coords, { color: 'blue', weight: 5 }).addTo(map);
        map.fitBounds(polyline.getBounds());

        joinedPoints.forEach(p => {
            const color = getColorForPaquete(p.id_paquete);
            const marker = L.marker([p.latitud, p.longitud], { icon: dotIcon(color) }).addTo(map);

            const fecha = p.event_at ? formatDMY(p.event_at) : 'Sin fecha';
            const estadoHtml = p.estado ? `<br>Estado: ${p.estado}` : '';
            const zonaHtml = p.zona ? `<br>Zona: ${p.zona}` : '';
            const tipoHtml = p.source === 'historial' ? '<br><em>Historial</em>' : '<br><em>Destino</em>';

            marker.bindPopup(
                `<strong>${p.codigo}</strong><br>${p.comunidad}<br>${p.direccion}` +
                `<br>Fecha: ${fecha}` +
                estadoHtml +
                zonaHtml +
                tipoHtml
            );

            markers.push(marker);
        });
    };

    const basePoints = puntos
        .filter(p => p.id_paquete != null)
        .map(p => ({
            ...p,
            event_at: p.fecha_salida || p.fecha_llegada || null,
            source: 'base'
        }));
    paintCodigoDots();
    drawAll(basePoints);

    const paqueteUrlTemplate = mapaDiv.dataset.paqueteUrl; 

    const uniqueIds = [...new Set(basePoints.map(p => p.id_paquete))];

    Promise.all(uniqueIds.map(async (id) => {
        try {
            const url = paqueteUrlTemplate.replace('__ID__', id);
            const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
            if (!res.ok) return [];

            const json = await res.json();
            const historial = Array.isArray(json?.historial) ? json.historial : [];

            return historial.map(h => {
                const u = h.ubicacion || null;
                const lat = u && u.latitud != null ? parseFloat(u.latitud) : null;
                const lng = u && u.longitud != null ? parseFloat(u.longitud) : null;
                if (!Number.isFinite(lat) || !Number.isFinite(lng)) return null;

                const base = basePoints.find(bp => bp.id_paquete === id);

                return {
                    id_paquete: id,
                    latitud: lat,
                    longitud: lng,
                    codigo: base?.codigo ?? '',
                    comunidad: base?.comunidad ?? '',
                    direccion: base?.direccion ?? '',
                    estado: h.estado ?? null,
                    zona: u?.zona ?? null,
                    event_at: h.fecha_actualizacion || h.created_at || null,
                    source: 'historial'
                };
            }).filter(Boolean);
        } catch (e) {
            console.warn('Historial fetch failed for paquete:', id, e);
            return [];
        }
    })).then((lists) => {
        const historialPoints = lists.flat();

        const joined = [...historialPoints, ...basePoints]
            .filter(p => Number.isFinite(p.latitud) && Number.isFinite(p.longitud));

        joined.sort((a, b) => {
            const da = parseDateSafe(a.event_at);
            const db = parseDateSafe(b.event_at);
            if (!da && !db) return 0;
            if (!da) return 1;
            if (!db) return -1;
            return da - db;
        });
        paintCodigoDots();
        drawAll(joined);
    });



});
</script>
@endsection
