@extends('adminlte::page')

@section('template_title')
    Seguimiento de Paquetes
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Historial Seguimiento de Paquetes') }}
                            </span>

                        </div>
                    </div>
                    <div class="card-body bg-white">
                        <style>
                            .seguimiento-uniform-row .col-md-3 { display: flex; }
                            .seguimiento-uniform-row .card {
                                display: flex;
                                flex-direction: column;
                                width: 100%;
                                border-radius: 12px;
                                border-top: 5px solid transparent;
                                transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
                                position: relative;
                                overflow: hidden;
                                background: #fff;
                            }
                            .seguimiento-uniform-row .card::before {
                                content: '';
                                position: absolute;
                                top: 0;
                                left: -100%;
                                width: 100%;
                                height: 100%;
                                background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.25) 50%, rgba(255,255,255,0) 100%);
                                transition: left 0.5s;
                                pointer-events: none;
                            }
                            .seguimiento-uniform-row .card:hover::before { left: 100%; }
                            .seguimiento-uniform-row .card:hover {
                                transform: translateY(-6px) scale(1.02);
                                box-shadow: 0 12px 28px rgba(0,0,0,0.18);
                            }
                            .seguimiento-uniform-row .card.badge-warning:hover { border-top-color: #ffc107; }
                            .seguimiento-uniform-row .card.badge-info:hover { border-top-color: #17a2b8; }
                            .seguimiento-uniform-row .card.badge-success:hover { border-top-color: #28a745; }
                            .seguimiento-uniform-row .card.badge-secondary:hover { border-top-color: #6c757d; }
                            .seguimiento-uniform-row .card-header {
                                border-radius: 12px 12px 0 0;
                                background: linear-gradient(135deg, rgba(0,0,0,0.02) 0%, rgba(0,0,0,0.05) 100%);
                                border-bottom: 2px solid #e9ecef;
                            }
                            .seguimiento-uniform-row .card-body {
                                flex: 1;
                                display: flex;
                                flex-direction: column;
                                min-height: 260px;
                                background: #fff;
                            }
                            .seguimiento-uniform-row .card-footer {
                                margin-top: auto;
                                border-radius: 0 0 12px 12px;
                                background: #f8f9fa;
                                border-top: 1px solid #e9ecef;
                            }
                            .seguimiento-uniform-row .badge {
                                box-shadow: 0 2px 4px rgba(0,0,0,0.12);
                                font-weight: 700;
                                font-size: 0.8rem;
                            }
                        </style>
                        <div class="row seguimiento-uniform-row">
                            @forelse ($historialSeguimientoDonaciones as $idPaquete => $registros)
                                @php
                                    $ultimo = $registros->sortByDesc('fecha_actualizacion')->first();

                                    $paquete   = optional($ultimo->paquete);
                                    $solicitud = optional($paquete->solicitud);
                                    $ubicacion = optional($ultimo->ubicacion);

                                    $codigoSeguimiento = $solicitud->codigo_seguimiento ?? '—';
                                    $estado            = $ultimo->estado ?? '—';

                                    $ubicacionActual = $ubicacion->direccion
                                        ?? trim(
                                            ($ubicacion->zona ?? '') . ' ' .
                                            ($ubicacion->latitud ?? '') . ' ' .
                                            ($ubicacion->longitud ?? '')
                                        );

                                    $badgeClass = 'badge-secondary';
                                    if (strcasecmp($estado, 'Pendiente') === 0) {
                                        $badgeClass = 'badge-warning';
                                    } elseif (strcasecmp($estado, 'En Camino') === 0 || strcasecmp($estado, 'En camino') === 0) {
                                        $badgeClass = 'badge-info';
                                    } elseif (strcasecmp($estado, 'Entregado') === 0 || strcasecmp($estado, 'Entregada') === 0) {
                                        $badgeClass = 'badge-success';
                                    }
                                @endphp

                                <div class="col-md-3">
                                    <div class="card mb-3 shadow-sm bg-white {{ $badgeClass }}">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>Paquete {{ $codigoSeguimiento  }}</strong><br>
                                            </div>
                                            <span class="badge {{ $badgeClass }} text-uppercase" style="font-weight:600; font-size: small;">
                                                {{ $estado }}
                                            </span>
                                        </div>

                                        <div class="card-body">
                                            <p class="mb-1">
                                                <strong>Estado actual:</strong> {{ $estado }}
                                            </p>
                                            <p class="mb-1">
                                                <strong>Ubicación actual:</strong>
                                                {{ $ubicacionActual ?: '—' }}
                                            </p>
                                            <p class="mb-1">
                                                <strong>Última actualización:</strong>
                                                {{ \Carbon\Carbon::parse($ultimo->fecha_actualizacion)->format('d/m/Y H:i') }}
                                            </p>
                                            <p class="mb-0 text-muted">
                                                Registros de seguimiento: {{ $registros->count() }}
                                            </p>
                                        </div>

                                        <div class="card-footer d-flex justify-content-between">
                                            <a class="btn btn-sm btn-dark"
                                               href="{{ route('seguimiento.tracking', $idPaquete) }}">
                                                Ver mapa
                                            </a>

                                            @auth
                                                @if(auth()->user()->administrador)
                                                    <a href="{{ route('paquete.show', $idPaquete) }}"
                                                       class="btn btn-sm btn-secondary">
                                                        Ver paquete
                                                    </a>
                                                @endif
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <p class="text-muted">No hay seguimientos registrados.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    </div>
            </div>
        </div>
    </div>
@endsection
