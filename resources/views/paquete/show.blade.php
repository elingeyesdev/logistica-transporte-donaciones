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
                                                    /* Estilo profesional del reporte */
                                                    #reporte-pdf { font-family: Arial, Helvetica, sans-serif; color: #222; }
                                                    #reporte-pdf .header {
                                                        display: flex; justify-content: space-between; align-items: center;
                                                        border-bottom: 2px solid #444; padding-bottom: 8px; margin-bottom: 12px;
                                                    }
                                                    #reporte-pdf .brand { font-size: 18px; font-weight: 700; letter-spacing: .5px; }
                                                    #reporte-pdf .title { font-size: 16px; font-weight: 600; }
                                                    #reporte-pdf .subtle { color: #555; font-size: 12px; }
                                                    #reporte-pdf .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px 16px; margin: 10px 0 14px; }
                                                    #reporte-pdf .grid .item { padding: 6px 8px; background: #f7f7f7; border: 1px solid #e3e3e3; border-radius: 4px; }
                                                    #reporte-pdf .section-title { font-size: 14px; font-weight: 600; margin: 10px 0 6px; }
                                                    #reporte-pdf table { width: 100%; border-collapse: collapse; }
                                                    #reporte-pdf thead th { background: #efefef; border: 1px solid #ddd; padding: 6px; font-size: 12px; text-align: left; }
                                                    #reporte-pdf tbody td { border: 1px solid #ddd; padding: 6px; font-size: 12px; }
                                                    #reporte-pdf .footer { margin-top: 12px; border-top: 1px dashed #bbb; padding-top: 6px; font-size: 11px; color: #666; }
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
                                                    <div class="header">
                                                        <div class="brand">DAS - Alas Chiquitanas</div>
                                                        <div class="subtle">Generado: {{ now()->format('Y-m-d H:i') }}</div>
                                                    </div>
                                                    <div class="title">Reporte de Paquete N°{{ $paquete->id_paquete }}</div>

                                                    @php
                                                        $solicitud = optional($paquete->solicitud);
                                                        $solicitante = optional($solicitud->solicitante);
                                                        $destino = optional($solicitud->destino);
                                                    @endphp

                                                    <div class="section-title">Solicitud</div>
                                                    <table style="margin-bottom:14px;">
                                                        <thead>
                                                            <tr>
                                                                <th style="width:180px;">Campo</th>
                                                                <th>Valor</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>ID Solicitud</td>
                                                                <td>{{ $solicitud->id_solicitud ?? '—' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Tipo Emergencia</td>
                                                                <td>{{ $solicitud->tipo_emergencia ?? '—' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Solicitante CI</td>
                                                                <td>{{ $solicitante->ci ?? '—' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Solicitante Nombre</td>
                                                                <td>{{ trim(($solicitante->nombre ?? '').' '.($solicitante->apellido ?? '')) ?: '—' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Comunidad Destino</td>
                                                                <td>{{ $destino->comunidad ?? '—' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Fecha Creación Solicitud</td>
                                                                <td>{{ $solicitud->fecha_creacion ?? ($solicitud->created_at ?? '—') }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                    <div class="section-title">Seguimiento</div>
                                                    <table style="margin-bottom:12px;">
                                                        <thead>
                                                            <tr>
                                                                <th style="width:180px;">Campo</th>
                                                                <th>Valor</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>Estado</td>
                                                                <td>{{ $paquete->estado->nombre_estado ?? '—' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Vehículo</td>
                                                                <td>{{ $paquete->vehiculo->placa ?? '—' }}@if(optional($paquete->vehiculo->marcaVehiculo)->nombre_marca) - {{ optional($paquete->vehiculo->marcaVehiculo)->nombre_marca }}@endif</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Conductor</td>
                                                                <td>{{ trim(($paquete->conductor->nombre ?? '').' '.($paquete->conductor->apellido ?? '')) ?: '—' }}@if($paquete->conductor->ci ?? false) (CI {{ $paquete->conductor->ci }})@endif</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Ubicación Actual</td>
                                                                <td>{{ $paquete->ubicacion_actual ?? '—' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Fecha de reporte</td>
                                                                <td>{{ now()->format('Y-m-d H:i') }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- Tabla detallada de puntos de seguimiento eliminada a solicitud del usuario -->
                                                    <div class="footer">Documento generado automáticamente desde el sistema DAS.</div>
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
                            {{ \Carbon\Carbon::parse($paquete->fecha_creacion)->format('d/m/Y') }}
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
                                  - CI {{ $conductor->ci }}
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
