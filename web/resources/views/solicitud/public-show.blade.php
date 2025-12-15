@extends('adminlte::page')

@section('template_title', 'Mi Solicitud')

@if (!auth()->check())
    @section('layout_topnav', true)
@endif

@section('content')
@php
$pers = optional($solicitud->solicitante);
$dest = optional($solicitud->destino);

$estado = $solicitud->estado ?? 'pendiente';
$isNegada = $estado === 'negada';
$respondida = $estado !== 'pendiente';
$editable = ($solicitud->estado === 'pendiente' || $solicitud->estado === null);

$badgeClass = 'badge-secondary';
if ($estado === 'pendiente') {
    $badgeClass = 'badge-warning';   
} elseif ($estado === 'aprobada') {
    $badgeClass = 'badge-success'; 
} elseif ($estado === 'negada') {
    $badgeClass = 'badge-danger';
}
@endphp

@if($editable)
    @push('css')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
              integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
              crossorigin=""/>
    @endpush

    @push('js')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
                integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
                crossorigin=""></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var btn = document.getElementById('btn-editar-solicitud');
                var section = document.getElementById('editar-solicitud-section');

                if (btn && section) {
                    btn.addEventListener('click', function (e) {
                        e.preventDefault();
                        section.classList.remove('d-none');
                        section.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    });
                }

                if ($errors)
                    if (section) {
                        section.classList.remove('d-none');
                        section.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        </script>
    @endpush
@endif

<style>
.kv .label{font-size:.85rem;color:#6c757d;margin-bottom:.25rem;display:block}
.kv .value{font-weight:600}
.divider{border-top:1px solid rgba(0,0,0,.08); margin: .75rem 0 1rem}
</style>

<div class="card">
    <div class="card-header justify-content-between d-flex">
        <h3 class="card-title col-md-8">
            Solicitud {{ $solicitud->codigo_seguimiento }}
        </h3>
         @if($editable)
            <button id="btn-editar-solicitud" class="btn btn-primary btn-sm">
                <i class="fas fa-edit mr-1"></i> Editar mi solicitud
            </button>
        @endif
        <a href="{{ route('solicitud.public.create') }}" class="btn btn-secondary btn-sm">
            Volver
        </a>
    </div>

    <div class="card-body">

        @if($isNegada && $solicitud->justificacion)
        <div class="alert alert-warning">
            <strong>Justificación de la negación:</strong>
            <div class="mt-1">{{ $solicitud->justificacion }}</div>
        </div>
        <div class="divider"></div>
        @endif

        <div class="row kv">
        <div class="col-md-4 mb-3">
            <span class="label">Código de seguimiento</span>
            <div class="value">{{ $solicitud->codigo_seguimiento ?? '—' }}</div>
        </div>
        <div class="col-md-4 mb-3">
            <span class="label">Fecha de inicio</span>
            <div class="value">
            {{ $solicitud->fecha_inicio ? (\Illuminate\Support\Carbon::parse($solicitud->fecha_inicio)->format('Y-m-d')) : '—' }}
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <span class="label">Tipo de emergencia</span>
            <div class="value">{{ $solicitud->tipo_emergencia ?? '—' }}</div>
        </div>
        </div>

        <div class="divider"></div>

        <h5 class="mb-3"><i class="fas fa-user mr-2"></i>Solicitante</h5>
        <div class="row kv">
        <div class="col-md-3 mb-3">
            <span class="label">Nombre</span>
            <div class="value">{{ trim(($pers->nombre ?? '').' '.($pers->apellido ?? '')) ?: '—' }}</div>
        </div>
        <div class="col-md-3 mb-3">
            <span class="label">CI</span>
            <div class="value">{{ $pers->ci ?? '—' }}</div>
        </div>
        <div class="col-md-3 mb-3">
            <span class="label">Correo</span>
            <div class="value">{{ $pers->email ?? '—' }}</div>
        </div>
        <div class="col-md-3 mb-3">
            <span class="label">Celular</span>
            <div class="value">{{ $pers->telefono ?? '—' }}</div>
        </div>
        </div>

        <div class="divider"></div>

        <h5 class="mb-3"><i class="fas fa-map-marker-alt mr-2"></i>Destino</h5>
        <div class="row kv">
        <div class="col-md-3 mb-3">
            <span class="label">Comunidad</span>
            <div class="value">{{ $dest->comunidad ?? '—' }}</div>
        </div>
        <div class="col-md-3 mb-3">
            <span class="label">Provincia</span>
            <div class="value">{{ $dest->provincia ?? '—' }}</div>
        </div>
        <div class="col-md-3 mb-3">
            <span class="label">Ubicación</span>
            <div class="value">{{ $dest->direccion ?? '—' }}</div>
        </div>
        <div class="col-md-3 mb-3">
            <span class="label">Coordenadas</span>
            <div class="value">
            @if(!is_null($dest->latitud) && !is_null($dest->longitud))
                {{ $dest->latitud }}, {{ $dest->longitud }}
            @else
                —
            @endif
            </div>
        </div>
        </div>

        <div class="divider"></div>
        <h5 class="mb-3"><i class="fas fa-box-open mr-2"></i>Necesidades</h5>
        <div class="row kv">
        <div class="col-md-3 mb-3">
            <span class="label">Cantidad de personas</span>
            <div class="value">{{ $solicitud->cantidad_personas ?? '—' }}</div>
        </div>
        <div class="col-md-9 mb-3">
            <span class="label">Insumos necesarios</span>
            <div class="value" style="white-space: pre-wrap">{{ $solicitud->insumos_necesarios ?? '—' }}</div>
        </div>
    </div>
</div>

@if($editable)
    <div id="editar-solicitud-section" class="card mt-4 {{ $errors->any() ? '' : 'd-none' }}" style="background-color: gainsboro;">
        <div class="card-header">
            <h3 class="card-title" style="font-weight: 700;">Actualizar mi solicitud</h3>
        </div>
        <div class="card-body bg-white">
            <form method="POST"
                  action="{{ route('solicitud.public.update', $solicitud->codigo_seguimiento) }}"
                  role="form"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @include('solicitud.form')
            </form>
        </div>
    </div>
@endif
@endsection
