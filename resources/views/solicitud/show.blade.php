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

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span><i class="fas fa-file-alt mr-2"></i>Detalle de Solicitud</span>
    <span class="badge {{ $badgeClass }}">
      {{ ucfirst($estado) }}
    </span>
  </div>

  <div class="card-body bg-white">

    @if($isNegada && $solicitud->justificacion)
      <div class="alert alert-danger">
        <strong><i class="fas fa-exclamation-triangle mr-2"></i>Justificación de la negación:</strong>
        <div class="mt-2">{{ $solicitud->justificacion }}</div>
      </div>
    @endif

    <div class="form-group mb-3">
      <strong>Código de seguimiento:</strong>
      {{ $solicitud->codigo_seguimiento ?? '—' }}
    </div>
    <div class="form-group mb-3">
      <strong>Fecha de inicio:</strong>
      {{ $solicitud->fecha_inicio ? (\Illuminate\Support\Carbon::parse($solicitud->fecha_inicio)->format('d/m/Y')) : '—' }}
    </div>
    <div class="form-group mb-3">
      <strong>Tipo de emergencia:</strong>
      {{ $solicitud->tipo_emergencia ?? '—' }}
    </div>

    <hr>

    <h5 class="mb-3"><i class="fas fa-user mr-2"></i>Solicitante</h5>
    <div class="form-group mb-3">
      <strong>Nombre:</strong>
      {{ trim(($pers->nombre ?? '').' '.($pers->apellido ?? '')) ?: '—' }}
    </div>
    <div class="form-group mb-3">
      <strong>CI:</strong>
      {{ $pers->ci ?? '—' }}
    </div>
    <div class="form-group mb-3">
      <strong>Correo:</strong>
      {{ $pers->email ?? '—' }}
    </div>
    <div class="form-group mb-3">
      <strong>Celular:</strong>
      {{ $pers->telefono ?? '—' }}
    </div>
    <hr>

    <h5 class="mb-3"><i class="fas fa-user mr-2"></i>Contacto de Referencia Adicional</h5>
    <div class="form-group mb-3">
      <strong>Nombre Completo:</strong>
      {{ trim(($solicitud->nombre_referencia) ?: '—') }}
    </div>
    <div class="form-group mb-3">
      <strong>Celular:</strong>
      {{ $solicitud->celular_referencia ?? '—' }}
    </div>

    <hr>

    <h5 class="mb-3"><i class="fas fa-map-marker-alt mr-2"></i>Destino</h5>
    <div class="form-group mb-3">
      <strong>Comunidad:</strong>
      {{ $dest->comunidad ?? '—' }}
    </div>
    <div class="form-group mb-3">
      <strong>Provincia:</strong>
      {{ $dest->provincia ?? '—' }}
    </div>
    <div class="form-group mb-3">
      <strong>Ubicación:</strong>
      {{ $dest->direccion ?? '—' }}
    </div>

    <hr>

    <h5 class="mb-3"><i class="fas fa-box-open mr-2"></i>Necesidades</h5>
    <div class="form-group mb-3">
      <strong>Cantidad de personas:</strong>
      {{ $solicitud->cantidad_personas ?? '—' }}
    </div>
    <div class="form-group mb-3">
      <strong>Insumos necesarios:</strong>
      <div style="white-space: pre-wrap">{{ $solicitud->insumos_necesarios ?? '—' }}</div>
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
