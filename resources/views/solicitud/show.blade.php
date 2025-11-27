@extends('adminlte::page')

@section('title', 'Detalle de Solicitud')

@section('content_header')
  <h1 class="mb-0">Solicitud N°{{ $solicitud->id_solicitud }}</h1>
@endsection

@section('content')
@php
  $pers = optional($solicitud->solicitante);
  $dest = optional($solicitud->destino);

  $estado = $solicitud->estado ?? 'pendiente';
  $isNegada = $estado === 'negada';
  $respondida = $estado !== 'pendiente';

  $badgeClass = 'badge-secondary';
  if ($estado === 'pendiente') {
      $badgeClass = 'badge-warning';   
  } elseif ($estado === 'aprobada') {
      $badgeClass = 'badge-success'; 
  } elseif ($estado === 'negada') {
      $badgeClass = 'badge-danger';
  }
@endphp

<style>
  .kv .label{font-size:.85rem;color:#6c757d;margin-bottom:.25rem;display:block}
  .kv .value{font-weight:600}
  .divider{border-top:1px solid rgba(0,0,0,.08); margin: .75rem 0 1rem}
</style>

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span><i class="fas fa-file-alt mr-2"></i>Detalle</span>
    <span class="badge {{ $badgeClass }}">
      {{ ucfirst($estado) }}
    </span>
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

  <div class="card-footer d-flex justify-content-between">
    <a href="{{ route('solicitud.index') }}" class="btn btn-secondary">
      <i class="fas fa-arrow-left"></i> Volver
    </a>
    <div>
      @if($respondida)
        <button class="btn btn-primary" disabled
                title="No se puede editar una solicitud {{ $estado }}">
          <i class="fa fa-edit"></i> Editar
        </button>
        <button class="btn btn-danger" disabled
                title="No se puede eliminar una solicitud {{ $estado }}">
          <i class="fa fa-trash"></i> Eliminar
        </button>
      @else
        <a href="{{ route('solicitud.edit', $solicitud->id_solicitud) }}" class="btn btn-primary">
          <i class="fa fa-edit"></i> Editar
        </a>
        <form action="{{ route('solicitud.destroy', $solicitud->id_solicitud) }}" method="POST" class="d-inline"
              onsubmit="return confirm('¿Eliminar esta solicitud?')">
          @csrf @method('DELETE')
          <button class="btn btn-danger"><i class="fa fa-trash"></i> Eliminar</button>
        </form>
      @endif
    </div>
  </div>
</div>
@endsection
