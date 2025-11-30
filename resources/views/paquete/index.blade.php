@extends('adminlte::page')

@section('template_title')
    Paquetes
@endsection

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
          <div style="display: flex; justify-content: space-between; align-items: center;">
            <span id="card_title" style="font-size: larger; font-weight: bolder;">{{ __('Paquetes') }}</span>
          </div>
        </div>
        @if ($message = Session::get('success'))
          <div class="alert alert-success m-4"><p>{{ $message }}</p></div>
        @endif

        <div class="card-body bg-white">
          <style>
            .paquete-uniform-row .col-md-3 {display:flex;}
            .paquete-uniform-row .card {display:flex; flex-direction:column; width:100%;}
            .paquete-uniform-row .card-body {flex:1; display:flex; flex-direction:column; min-height:320px;}
            .paquete-uniform-row .card-footer {margin-top:auto;}
          </style>
          <div class="row paquete-uniform-row">

            @foreach ($paquetes as $paquete)

              @php
                $sol  = optional($paquete->solicitud);
                $pers = optional($sol->solicitante);
                $dest = optional($sol->destino);

                $estado = optional($paquete->estado)->nombre_estado;

                $badgeClass = match($estado) {
                    'Pendiente' => 'badge-warning',
                    'En Camino', 'En camino' => 'badge-info',
                    'Entregado' => 'badge-success',
                    'Esperando Aprobacion' => 'badge-secondary',
                    default => 'badge-secondary'
                };
              @endphp

              <div class="col-md-3">
                <div class="card mb-3 shadow-sm bg-light">

                  <div class="card-header d-flex justify-content-between align-items-center">
                    <div style="font-size: large;">
                      <strong>Paquete {{ $paquete->codigo ?? $sol->codigo_seguimiento }}</strong><br>
                    </div>

                    <span class="badge {{ $badgeClass }} text-uppercase"
                          style="font-weight:600; font-size: small;">
                        {{ $estado ?? '—' }}
                    </span>
                  </div>

                  <div class="card-body gap-2 justify-content-center">

                    <p class="mb-3"><strong>Solicitante:</strong> 
                      {{ $pers->nombre ?? '—' }} {{ $pers->apellido ?? '' }}
                    </p>

                    <p class="mb-3"><strong>CI:</strong> {{ $pers->ci ?? '—' }}</p>

                    <p class="mb-3"><strong>Comunidad:</strong> {{ $dest->comunidad ?? '—' }}</p>

                    <p class="mb-3"><strong>Emergencia:</strong> {{ $sol->tipo_emergencia ?? '—' }}</p>

                    <div class="">
                      <p class="mb-3"><strong>Ubicación:</strong>
                        <span title="{{ $paquete->ubicacion_actual }}">{{ ($paquete->ubicacion_actual ? \Illuminate\Support\Str::limit(trim(\Illuminate\Support\Str::before($paquete->ubicacion_actual, '-')), 55) : '—') }}</span>
                      </p>

                      <p class="mb-3"><strong>Fecha Creación:</strong> 
                        {{ \Carbon\Carbon::parse($paquete->created_at)->format('d/m/Y') }}
                      </p>

                      <p class="mb-3"><strong>Fecha Entrega:</strong> 
                        @if($paquete->fecha_entrega)
                            {{ \Carbon\Carbon::parse($paquete->fecha_entrega)->format('d/m/Y') }}
                        @else
                            —
                        @endif
                      </p>

                    </div>

                  </div>

                  <div class="card-footer d-flex justify-content-between mt-0">

                    <div>
                      <a class="btn btn-sm btn-dark mr-2" href="{{ route('paquete.show', $paquete->id_paquete) }}">
                        <i class="fa fa-eye"></i>
                      </a>
                      @php
                       $estadoNombre = $estado ?? optional($paquete->estado)->nombre_estado;
                      @endphp

                      @if( ! $estadoNombre || !in_array(strtolower($estadoNombre), ['entregado', 'entregada']) )
                          <a class="btn btn-sm btn-info mr-2" href="{{ route('paquete.edit', $paquete->id_paquete) }}">
                              <i class="fa fa-edit"></i>
                          </a>
                      @endif

                      <form action="{{ route('paquete.destroy', $paquete->id_paquete) }}"
                            method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger"
                                onclick="return confirm('¿Estás seguro de eliminar este paquete?')">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </form>
                    </div>

                    <div>
                      @if($estado === 'En Camino' || $estado === 'En camino')
                        <a class="btn btn-sm btn-primary" 
                           href="{{ route('seguimiento.tracking', $paquete->id_paquete) }}">
                          Seguimiento
                        </a>
                      @endif

                    </div>

                  </div>

                </div>
              </div>

            @endforeach

          </div>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection
