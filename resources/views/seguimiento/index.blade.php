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
                        <div class="row">
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
                                    <div class="card mb-3 shadow-sm bg-light">
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
