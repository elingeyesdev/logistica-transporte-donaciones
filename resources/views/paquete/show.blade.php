@extends('adminlte::page')

@section('template_title')
    {{ $paquete->name ?? __('Mostrar') . " " . __('Paquete') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Mostrar') }} Paquete</span>
                        </div>
                        <div class="float-right">
                                                        <a class="btn btn-secondary btn-sm" id="btn-imprimir-reporte">
                                                                <i class="fas fa-file-pdf"></i> Reporte PDF
                                                        </a>
                                                        <a class="btn btn-primary btn-sm" href="{{ route('paquete.index') }}"> {{ __('Volver') }}</a>
                        </div>
                    </div>

                                        <div class="card-body bg-white">
                                                <style>
                                                    /* Ocultar el contenido del reporte en pantalla */
                                                    @media screen {
                                                        #reporte-pdf { display: none; }
                                                    }
                                                    /* Mostrar solo el contenido del reporte al imprimir */
                                                    @media print {
                                                        body * { visibility: hidden; }
                                                        #reporte-pdf, #reporte-pdf * { visibility: visible; }
                                                        #reporte-pdf { position: absolute; left: 0; top: 0; width: 100%; }
                                                    }
                                                    #reporte-pdf table { width: 100%; border-collapse: collapse; }
                                                    #reporte-pdf th, #reporte-pdf td { border: 1px solid #ddd; padding: 6px; font-size: 12px; }
                                                    #reporte-pdf h2 { margin-bottom: 8px; }
                                                    #reporte-pdf .meta { margin: 6px 0 12px; font-size: 13px; }
                                                </style>
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        const btn = document.getElementById('btn-imprimir-reporte');
                                                        if (btn) {
                                                            btn.addEventListener('click', function() {
                                                                window.print();
                                                            });
                                                        }
                                                    });
                                                </script>

                                                <div id="reporte-pdf">
                                                    <h2>Reporte de Paquete #{{ $paquete->id_paquete }}</h2>

                                                    @php
                                                        $solicitud = optional($paquete->solicitud);
                                                        $solicitante = optional($solicitud->solicitante);
                                                        $destino = optional($solicitud->destino);
                                                    @endphp

                                                    <h3>Solicitud</h3>
                                                    <table style="margin-bottom:14px;">
                                                        <tbody>
                                                            <tr>
                                                                <th style="width:180px;">ID Solicitud</th>
                                                                <td>{{ $solicitud->id_solicitud ?? '—' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Tipo Emergencia</th>
                                                                <td>{{ $solicitud->tipo_emergencia ?? '—' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Solicitante CI</th>
                                                                <td>{{ $solicitante->ci ?? '—' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Solicitante Nombre</th>
                                                                <td>{{ trim(($solicitante->nombre ?? '').' '.($solicitante->apellido ?? '')) ?: '—' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Comunidad Destino</th>
                                                                <td>{{ $destino->comunidad ?? '—' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Fecha Creación Solicitud</th>
                                                                <td>{{ $solicitud->fecha_creacion ?? ($solicitud->created_at ?? '—') }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                    <h3>Seguimiento</h3>
                                                    <div class="meta" style="margin-bottom:10px;">
                                                        <div><strong>Estado:</strong> {{ $paquete->estado->nombre_estado ?? '—' }}</div>
                                                        <div><strong>Vehículo:</strong> {{ $paquete->vehiculo->placa ?? '—' }}@if(optional($paquete->vehiculo->marcaVehiculo)->nombre_marca) - {{ optional($paquete->vehiculo->marcaVehiculo)->nombre_marca }}@endif</div>
                                                        <div><strong>Conductor:</strong> {{ trim(($paquete->conductor->nombre ?? '').' '.($paquete->conductor->apellido ?? '')) ?: '—' }}@if($paquete->conductor->ci ?? false) (CI {{ $paquete->conductor->ci }})@endif</div>
                                                        <div><strong>Fecha de reporte:</strong> {{ now()->format('Y-m-d H:i') }}</div>
                                                    </div>
                                                    <table>
                                                        <thead>
                                                            <tr>
                                                                <th>Fecha/Hora</th>
                                                                <th>Ubicación</th>
                                                                <th>Latitud</th>
                                                                <th>Longitud</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $seguimientos = method_exists($paquete, 'historialSeguimientoDonaciones')
                                                                    ? ($paquete->historialSeguimientoDonaciones ?? collect())
                                                                    : (property_exists($paquete, 'seguimientos') ? ($paquete->seguimientos ?? collect()) : collect());
                                                            @endphp
                                                            @forelse($seguimientos as $s)
                                                                <tr>
                                                                    <td>{{ $s->fecha_seguimiento ?? $s->created_at }}</td>
                                                                    <td>{{ $s->ubicacion ?? $s->ubicacion_actual ?? '—' }}</td>
                                                                    <td>{{ $s->latitud ?? '—' }}</td>
                                                                    <td>{{ $s->longitud ?? '—' }}</td>
                                                                </tr>
                                                            @empty
                                                                <tr><td colspan="4">Sin registros de seguimiento.</td></tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Id Paquete:</strong>
                            {{ $paquete->id_paquete }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Id Solicitud:</strong>
                            {{ $paquete->id_solicitud }}
                        </div>
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Estado:</strong>
                            {{ optional($paquete->estado)->nombre_estado ?? '—' }}
                        </div>

                        <div class="form-group mb-2 mb20">
                            <strong>Ubicacion Actual:</strong>
                            {{ $paquete->ubicacion_actual }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Fecha Creacion:</strong>
                            {{ $paquete->fecha_creacion }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Fecha Entrega:</strong>
                            {{ $paquete->fecha_entrega }}
                        </div>

                        @php
                            $conductor = optional($paquete->conductor);
                        @endphp
                        <div class="form-group mb-2 mb20">
                            <strong>Conductor:</strong>
                            @if($conductor->conductor_id)
                                {{ trim(($conductor->nombre ?? '').' '.($conductor->apellido ?? '')) ?: 'Sin nombre' }}
                                @if($conductor->ci)
                                    (CI {{ $conductor->ci }})
                                @endif
                            @else
                                —
                            @endif
                        </div>
                        @php
                            $vehiculo = optional($paquete->vehiculo);
                            $marca    = optional($vehiculo->marcaVehiculo);
                        @endphp
                        <div class="form-group mb-2 mb20">
                            <strong>Vehículo:</strong>
                            @if($vehiculo->id_vehiculo)
                                {{ $vehiculo->placa ?? 'Sin placa' }}
                                 @if($marca->id_marca || !empty($vehiculo->modelo)) 
                                    — {{ $marca->nombre_marca ?? $marca->nombre ?? 'Sin marca' }}
                                    {{ $vehiculo->modelo ?? '' }}
                                @endif
                            @else
                                —
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
