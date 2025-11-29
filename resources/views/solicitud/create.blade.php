@extends('adminlte::page')

@section('template_title')
    {{ __('Crear') }} Solicitud
@endsection
@if (!auth()->check())
    @section('layout_topnav', true)
@endif
@push('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
      crossorigin=""/>
@endpush

@push('js')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
@endpush

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; width: 100%;">
                        <span class="card-title">{{ __('Crear') }} Solicitud</span>
                        <button class="btn btn-outline-info btn-sm"
                                type="button"
                                data-toggle="collapse"
                                data-target="#buscarSolicitudCollapse"
                                aria-expanded="false"
                                aria-controls="buscarSolicitudCollapse">
                            Buscar mi solicitud
                        </button>
                    </div>
                </div>
                    <div class="collapse @if(!empty($solicitudEncontrada)) show @endif" id="buscarSolicitudCollapse">
                        <div class="card-body bg-light border-bottom">
                            <form method="GET" action="{{ route('solicitud.buscar') }}" class="row g-2 align-items-end">
                                <div class="col-md-4">
                                    <label for="codigo_seguimiento" class="form-label">Código de seguimiento</label>
                                    <input type="text"
                                           name="codigo_seguimiento"
                                           id="codigo_seguimiento"
                                           class="form-control"
                                           value="{{ $codigo ?? '' }}"
                                           placeholder="Ingresa tu código">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary">
                                        Buscar
                                    </button>
                                </div>
                            </form>

                            @if(!empty($solicitudEncontrada))
                                <hr>
                                <h5>Resultado de la búsqueda</h5>
                                @php
                                    $editable = ($solicitudEncontrada->estado === 'pendiente' || $solicitudEncontrada->estado === null)
                                        && is_null($solicitudEncontrada->aprobada);
                                @endphp

                                <p><strong>Código:</strong> {{ $solicitudEncontrada->codigo_seguimiento }}</p>
                                <p>
                                    <strong>Estado:</strong>
                                    @if($solicitudEncontrada->aprobada === true)
                                        Aprobada
                                    @elseif($solicitudEncontrada->aprobada === false)
                                        Negada
                                    @else
                                        {{ $solicitudEncontrada->estado ?? 'pendiente' }}
                                    @endif
                                </p>

                                <div class="d-flex gap-2">
                                    <a href="{{ route('solicitud.show', $solicitudEncontrada->id_solicitud) }}"
                                       class="btn btn-info btn-sm">
                                        Ver solicitud
                                    </a>

                                    @if($editable)
                                        <a href="{{ route('solicitud.edit', $solicitudEncontrada->id_solicitud) }}"
                                           class="btn btn-warning btn-sm">
                                            Editar mi solicitud
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('solicitud.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('solicitud.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
