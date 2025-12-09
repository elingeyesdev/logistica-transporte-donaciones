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

        <div class="row mb-3 mt-2 px-4">
          <div class="col-md-4 mb-2 mb-md-0">
            <label class="form-label mb-1 d-block">Estado</label>
            <div class="btn-group btn-group-sm" role="group" aria-label="Filtro por estado">
              <button type="button"
                      class="btn btn-outline-secondary btn-paquete-estado active"
                      data-value="todos">
                Todos
              </button>
              <button type="button"
                      class="btn btn-outline-secondary btn-paquete-estado"
                      data-value="en_camino">
                En camino
              </button>
              <button type="button"
                      class="btn btn-outline-secondary btn-paquete-estado"
                      data-value="entregado">
                Entregados
              </button>
              <button type="button"
                      class="btn btn-outline-secondary btn-paquete-estado"
                      data-value="pendiente">
                Pendientes
              </button>
              <button type="button"
                      class="btn btn-outline-secondary btn-paquete-estado"
                      data-value="armado">
                Armados
              </button>
            </div>
          </div>

          <div class="col-md-4">
            <label class="form-label mb-1 d-block">Orden</label>
            <div class="btn-group btn-group-sm" role="group" aria-label="Orden de paquetes">
              <button type="button"
                      class="btn btn-outline-secondary btn-paquete-orden active"
                      data-value="recientes">
                Recientes primero
              </button>
              <button type="button"
                      class="btn btn-outline-secondary btn-paquete-orden"
                      data-value="antiguas">
                Antiguas primero
              </button>
            </div>
          </div>
        </div>

        <div class="card-body bg-white">
          <style>
            .paquete-uniform-row .col-md-3 {display:flex;}
            .paquete-uniform-row .card {
              display:flex; 
              flex-direction:column; 
              width:100%;
              border-radius: 12px;
              border-top: 5px solid transparent;
              transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
              position: relative;
              overflow: hidden;
            }
            .paquete-uniform-row .card::before {
              content: '';
              position: absolute;
              top: 0;
              left: -100%;
              width: 100%;
              height: 100%;
              background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.2) 50%, rgba(255,255,255,0) 100%);
              transition: left 0.5s;
              pointer-events: none;
            }
            .paquete-uniform-row .card:hover::before {
              left: 100%;
            }
            .paquete-uniform-row .card:hover {
              transform: translateY(-6px) scale(1.02);
              box-shadow: 0 12px 28px rgba(0,0,0,0.18);
            }
            .paquete-uniform-row .card.badge-warning:hover {border-top-color: #ffc107;}
            .paquete-uniform-row .card.badge-info:hover {border-top-color: #17a2b8;}
            .paquete-uniform-row .card.badge-success:hover {border-top-color: #28a745;}
            .paquete-uniform-row .card.badge-secondary:hover {border-top-color: #6c757d;}
            .paquete-uniform-row .card-header {
              border-radius: 12px 12px 0 0;
              background: linear-gradient(135deg, rgba(0,0,0,0.02) 0%, rgba(0,0,0,0.05) 100%);
              border-bottom: 2px solid #e9ecef;
            }
            .paquete-uniform-row .card-footer {
              margin-top:auto;
              border-radius: 0 0 12px 12px;
              background: #f8f9fa;
              border-top: 1px solid #e9ecef;
            }
            .paquete-uniform-row .card-body {
              flex:1; 
              display:flex; 
              flex-direction:column; 
              min-height:320px;
              background: #fff;
            }
            .paquete-uniform-row .badge {
              box-shadow: 0 2px 4px rgba(0,0,0,0.1);
              font-weight: 700;
              font-size: 0.8rem;
            }
          </style>
          <div class="row paquete-uniform-row">

            @foreach ($paquetes as $paquete)

              @php
                $sol  = optional($paquete->solicitud);
                $pers = optional($sol->solicitante);
                $dest = optional($sol->destino);

                $estado = optional($paquete->estado)->nombre_estado;
                $estadoLower = $estado ? strtolower($estado) : null;

                $fechaCreacionDisplay = $paquete->fecha_creacion ?? $paquete->created_at;
                $fechaCreacionFormatted = $fechaCreacionDisplay
                  ? ($fechaCreacionDisplay instanceof \Carbon\Carbon
                    ? $fechaCreacionDisplay->format('d/m/Y')
                    : \Carbon\Carbon::parse($fechaCreacionDisplay)->format('d/m/Y'))
                  : '—';

                $fechaEntregaSource = $paquete->fecha_entrega;
                if (!$fechaEntregaSource && $estadoLower && \Illuminate\Support\Str::contains($estadoLower, 'entreg')) {
                  $fechaEntregaSource = $paquete->updated_at;
                }
                $fechaEntregaFormatted = $fechaEntregaSource
                  ? ($fechaEntregaSource instanceof \Carbon\Carbon
                    ? $fechaEntregaSource->format('d/m/Y')
                    : \Carbon\Carbon::parse($fechaEntregaSource)->format('d/m/Y'))
                  : null;

                $badgeClass = match($estado) {
                    'Pendiente' => 'badge-warning',
                    'Armado' => 'badge-primary',
                    'En Camino', 'En camino' => 'badge-info',
                    'Entregado' => 'badge-success',
                    'Esperando Aprobacion' => 'badge-secondary',
                    default => 'badge-secondary'
                };
              @endphp

              @php
                  $estadoFiltro = 'pendiente';
                  if ($estadoLower && \Illuminate\Support\Str::contains($estadoLower, 'camino')) {
                    $estadoFiltro = 'en_camino';
                  } elseif ($estadoLower && \Illuminate\Support\Str::contains($estadoLower, 'entreg')) {
                    $estadoFiltro = 'entregado';
                  } elseif ($estadoLower && \Illuminate\Support\Str::contains($estadoLower, 'armad')) {
                    $estadoFiltro = 'armado';
                  }

                $fechaReferencia = $paquete->updated_at ?? $paquete->created_at;
                $fechaTimestamp  = optional($fechaReferencia)->timestamp ?? '';
              @endphp

              <div class="col-md-3 paquete-card"
                   data-estado="{{ $estadoFiltro }}"
                   data-fecha-ts="{{ $fechaTimestamp }}">
                <div class="card mb-3 shadow-sm bg-white {{ $badgeClass }}">

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
                        {{ $fechaCreacionFormatted }}
                      </p>

                      @if($estadoLower && \Illuminate\Support\Str::contains($estadoLower, 'entreg') && $fechaEntregaFormatted)
                      <p class="mb-3"><strong>Fecha Entrega:</strong> 
                        {{ $fechaEntregaFormatted }}
                      </p>
                      @endif

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

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const estadoButtons = document.querySelectorAll('.btn-paquete-estado');
  const ordenButtons  = document.querySelectorAll('.btn-paquete-orden');
  const container     = document.querySelector('.paquete-uniform-row');

  if (!container) return;

  const cards = Array.from(container.querySelectorAll('.paquete-card'));

  let estadoFiltro = 'todos';
  let ordenFiltro  = 'recientes';

  function aplicarFiltros() {
    let visibles = cards.slice();

    if (estadoFiltro !== 'todos') {
      visibles = visibles.filter(card => card.dataset.estado === estadoFiltro);
    }

    visibles.sort((a, b) => {
      const ta = parseInt(a.dataset.fechaTs || '0', 10);
      const tb = parseInt(b.dataset.fechaTs || '0', 10);
      return ordenFiltro === 'recientes' ? (tb - ta) : (ta - tb);
    });

    cards.forEach(card => card.style.display = 'none');

    visibles.forEach(card => {
      card.style.display = '';
      container.appendChild(card);
    });
  }

  estadoButtons.forEach(btn => {
    btn.addEventListener('click', function () {
      estadoButtons.forEach(b => b.classList.remove('active'));
      this.classList.add('active');
      estadoFiltro = this.dataset.value;
      aplicarFiltros();
    });
  });

  ordenButtons.forEach(btn => {
    btn.addEventListener('click', function () {
      ordenButtons.forEach(b => b.classList.remove('active'));
      this.classList.add('active');
      ordenFiltro = this.dataset.value;
      aplicarFiltros();
    });
  });

  aplicarFiltros();
});
</script>
@endpush
@endsection
