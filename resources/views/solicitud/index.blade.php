@extends('adminlte::page')

@section('template_title')
    Solicitudes
@endsection

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      <div class="card">
          <div class="card-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
              <span id="card_title" style="font-size: larger; font-weight: bolder;">{{ __('Solicitudes') }}</span>
              <a href="{{ route('solicitud.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> {{ __('Crear nueva') }}
              </a>
            </div>
          </div>
        <div class="row mb-3 mt-3">
          <div class="col-md-4 ml-4">
            <label class="form-label mb-1 d-block">Estado</label>
            <div class="btn-group btn-group-sm" role="group" aria-label="Filtro por estado">
                <button type="button"
                        class="btn btn-outline-secondary btn-filtro-estado active"
                        data-value="todos">
                    Todas
                </button>
                <button type="button"
                        class="btn btn-outline-secondary btn-filtro-estado"
                        data-value="aprobada">
                    Aprobadas
                </button>
                <button type="button"
                        class="btn btn-outline-secondary btn-filtro-estado"
                        data-value="negada">
                    Negadas
                </button>
                <button type="button"
                        class="btn btn-outline-secondary btn-filtro-estado"
                        data-value="pendiente">
                    Pendientes
                </button>
            </div>
        </div>

        <div class="col-md-4">
            <label class="form-label mb-1 d-block">Orden</label>
            <div class="btn-group btn-group-sm" role="group" aria-label="Orden de solicitudes">
                <button type="button"
                        class="btn btn-outline-secondary btn-filtro-orden active"
                        data-value="recientes">
                    Recientes primero
                </button>
                <button type="button"
                        class="btn btn-outline-secondary btn-filtro-orden"
                        data-value="antiguas">
                    Antiguas primero
                </button>
            </div>
        </div>
        </div>


 

        @if ($message = Session::get('success'))
          <div class="alert alert-success m-4"><p>{{ $message }}</p></div>
        @endif

        <div class="card-body bg-white">
          <style>
            .solicitud-uniform-row .col-md-3 {display:flex;}
            .solicitud-uniform-row .card {
              display:flex; 
              flex-direction:column; 
              width:100%;
              border-radius: 12px;
              border-top: 5px solid transparent;
              transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
              position: relative;
              overflow: hidden;
            }
            .solicitud-uniform-row .card::before {
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
            .solicitud-uniform-row .card:hover::before {
              left: 100%;
            }
            .solicitud-uniform-row .card:hover {
              transform: translateY(-6px) scale(1.02);
              box-shadow: 0 12px 28px rgba(0,0,0,0.18);
            }
            .solicitud-uniform-row .card.badge-success:hover {border-top-color: #28a745;}
            .solicitud-uniform-row .card.badge-danger:hover {border-top-color: #dc3545;}
            .solicitud-uniform-row .card.badge-warning:hover {border-top-color: #ffc107;}
            .solicitud-uniform-row .card-header {
              border-radius: 12px 12px 0 0;
              background: linear-gradient(135deg, rgba(0,0,0,0.02) 0%, rgba(0,0,0,0.05) 100%);
              border-bottom: 2px solid #e9ecef;
            }
            .solicitud-uniform-row .card-footer {
              margin-top:auto;
              border-radius: 0 0 12px 12px;
              background: #f8f9fa;
              border-top: 1px solid #e9ecef;
            }
            .solicitud-uniform-row .card-body {
              flex:1; 
              min-height:340px; 
              display:flex; 
              flex-direction:column;
              background: #fff;
            }
            .solicitud-uniform-row .card-body .mt-auto p {margin-bottom:4px;}
            .solicitud-uniform-row .badge {
              box-shadow: 0 2px 4px rgba(0,0,0,0.1);
              font-weight: 700;
              font-size: 0.8rem;
            }
          </style>
          <div class="row solicitud-uniform-row">
              @foreach ($solicituds as $solicitud)
                @php
                    $pers = optional($solicitud->solicitante);
                    $dest = optional($solicitud->destino);
                    $key  = $solicitud->id_solicitud;

                    $esPendienteYNoRespondida = $solicitud->estado === 'pendiente' || $solicitud->estado === null;
                    $badgeClass   = 'badge-secondary';
                    $estadoTexto  = $solicitud->estado ?? 'pendiente';
                    $estadoFiltro = 'pendiente';

                    if ($solicitud->aprobada === true && $solicitud->estado != 'pendiente') {
                        $badgeClass   = 'badge-success';
                        $estadoTexto  = 'aprobada';
                        $estadoFiltro = 'aprobada';
                    } elseif ($solicitud->aprobada === false && $solicitud->estado != 'pendiente') {
                        $badgeClass   = 'badge-danger';
                        $estadoTexto  = 'negada';
                        $estadoFiltro = 'negada';
                    } else {
                        $badgeClass   = 'badge-warning';
                        $estadoTexto  = 'pendiente';
                        $estadoFiltro = 'pendiente';
                    }

                    $fechaFiltro = $solicitud->created_at;
                @endphp

                <div class="col-md-3 solicitud-card"
                    data-estado="{{ $estadoFiltro }}"
                    data-fecha="{{ optional($fechaFiltro)->format('Y-m-d H:i:s') }}"
                    data-fecha-ts="{{ optional($fechaFiltro)->timestamp ?? '' }}"
                    data-id="{{ $key }}">
                    <div class="card mb-3 shadow-sm bg-white {{ $badgeClass }}">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div style="font-size: large;">
                                <strong>Solicitud {{ $solicitud->codigo_seguimiento }}</strong><br>
                            </div>
                            <span class="badge {{ $badgeClass }} text-uppercase">
                                {{ $estadoTexto }}
                            </span>
                        </div>

                        <div class="card-body">
                            <p class="mb-1"><strong>Nombre:</strong> {{ $pers->nombre ?? '—' }} {{ $pers->apellido ?? '' }}</p>
                            <p class="mb-1"><strong>CI:</strong> {{ $pers->ci ?? '—' }}</p>
                            <p class="mb-1"><strong>Correo:</strong> {{ $pers->email ?? '—' }}</p>
                            <p class="mb-1"><strong>Celular:</strong> {{ $pers->telefono ?? '—' }}</p>

                            <p class="mb-1"><strong>Comunidad:</strong> {{ $dest->comunidad ?? '—' }}</p>
                            <p class="mb-1"><strong>Provincia:</strong> {{ $dest->provincia ?? '—' }}</p>
                            <p class="mb-1"><strong>Ubicación:</strong>
                                <span title="{{ $dest->direccion }}">
                                    {{ $dest->direccion ? \Illuminate\Support\Str::limit($dest->direccion, 60) : '—' }}
                                </span>
                            </p>

                            <div class="mt-auto">
                                <p class="mb-1"><strong>Tipo de Emergencia:</strong> {{ $solicitud->tipo_emergencia ?? '—' }}</p>
                                <p class="mb-1"><strong>Personas afectadas:</strong> {{ $solicitud->cantidad_personas }}</p>
                                <p class="mb-1">
                                    <strong>Fecha inicio:</strong>
                                    {{ \Carbon\Carbon::parse($solicitud->fecha_inicio)->format('d/m/Y') }}
                                </p>
                            </div>
                        </div>

                        <div class="card-footer d-flex justify-content-between">
                            <div>
                                <a class="btn btn-sm btn-dark mr-2" href="{{ route('solicitud.show', $key) }}">
                                    <i class="fa fa-eye"></i>
                                </a>
                                @if ($solicitud->estado === 'pendiente')
                                    <a class="btn btn-sm btn-info mr-2" href="{{ route('solicitud.edit', $key) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                @endif
                            </div>

                            <div>
                                @if($esPendienteYNoRespondida)
                                    <form action="{{ route('solicitud.aprobar', $key) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-dark btn-open-aprobar"
                                            data-id="{{ $key }}">
                                            Aprobar
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-outline-dark btn-open-negar" data-id="{{ $key }}">
                                        Negar
                                    </button>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach

          </div>
      </div>

      <div class="modal fade" id="negarModal" tabindex="-1" aria-labelledby="negarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <form method="POST" id="formNegarSolicitud">
              @csrf
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="negarModalLabel">Negar solicitud</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                  <div class="mb-3">
                      <label for="justificacion" class="form-label">Ingresa un justificativo para la negación</label>
                      <textarea name="justificacion" id="justificacion" class="form-control" rows="3" required></textarea>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                  <button type="submit" class="btn btn-danger">Confirmar negación</button>
                </div>
              </div>
          </form>
        </div>
      </div>

<div class="modal fade" id="aprobarConfirmModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">

      <div class="modal-header py-2">
        <h6 class="modal-title">Confirmar Aprobación</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body py-3">
        <p class="mb-0">¿Aprobar esta solicitud y crear un paquete?</p>
      </div>

      <div class="modal-footer py-2">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancelar</button>
        <form id="aprobarForm" method="POST">
          @csrf
          <button type="submit" class="btn btn-success btn-sm">Aprobar</button>
        </form>
      </div>

    </div>
  </div>
</div>


     @push('js')
    <script>
        function openNegarModal(id) {
            const form = document.getElementById('formNegarSolicitud');
            form.action = "{{ url('solicitud') }}/" + id + "/negar";
            const modalEl = document.getElementById('negarModal');
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.btn-open-negar').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const id = this.dataset.id;
                    openNegarModal(id);
                });
            });
        });
    </script>
    @endpush
    @push('js')
    <script>
        document.querySelectorAll('.btn-open-aprobar').forEach(btn => {
            btn.addEventListener('click', function () {
                let id = this.getAttribute('data-id');
                const form = document.getElementById('aprobarForm');
                form.action = `/solicitud/${id}/aprobar`;
                 let modal = new bootstrap.Modal(document.getElementById('aprobarConfirmModal'));
                modal.show();
            });
        });
    </script>

    @endpush
    @push('js')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const estadoButtons = document.querySelectorAll('.btn-filtro-estado');
        const ordenButtons  = document.querySelectorAll('.btn-filtro-orden');
        const container     = document.querySelector('.solicitud-uniform-row');

        if (!container) return;

        const allCards = Array.from(container.querySelectorAll('.solicitud-card'));

        let estado = 'todos';
        let orden  = 'recientes';

        function aplicarFiltros() {
            let cards = allCards.slice();
            if (estado !== 'todos') {
                cards = cards.filter(c => c.dataset.estado === estado);
            }

            cards.sort((a, b) => {
                const ta = parseInt(a.dataset.fechaTs || '0', 10);
                const tb = parseInt(b.dataset.fechaTs || '0', 10);

                return (orden === 'recientes') ? (tb - ta) : (ta - tb);
            });

            allCards.forEach(c => c.style.display = 'none');

            cards.forEach(c => {
                c.style.display = '';
                container.appendChild(c);
            });
        }

        estadoButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                estadoButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                estado = this.dataset.value;
                aplicarFiltros();
            });
        });

        ordenButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                ordenButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                orden = this.dataset.value;
                aplicarFiltros();
            });
        });
        aplicarFiltros();
    });
    </script>
    @endpush

      </div>
    @if ($solicituds->hasPages())
        <nav aria-label="Paginación de solicitudes" class="mt-3 mb-3">
            <ul class="pagination justify-content-center mb-0">
                @if ($solicituds->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">Primera</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $solicituds->url(1) }}">Primera</a>
                    </li>
                @endif
                @if ($solicituds->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">Anterior</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $solicituds->previousPageUrl() }}" rel="prev">Anterior</a>
                    </li>
                @endif
                @php
                    $current = $solicituds->currentPage();
                    $last    = $solicituds->lastPage();
                    $start   = max(1, $current - 2);
                    $end     = min($last, $current + 2);
                @endphp

                @if ($start > 1)
                    <li class="page-item">
                        <a class="page-link" href="{{ $solicituds->url(1) }}">1</a>
                    </li>
                    @if ($start > 2)
                        <li class="page-item disabled">
                            <span class="page-link">…</span>
                        </li>
                    @endif
                @endif

                @for ($page = $start; $page <= $end; $page++)
                    @if ($page == $current)
                        <li class="page-item active" aria-current="page">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $solicituds->url($page) }}">{{ $page }}</a>
                        </li>
                    @endif
                @endfor

                @if ($end < $last)
                    @if ($end < $last - 1)
                        <li class="page-item disabled">
                            <span class="page-link">…</span>
                        </li>
                    @endif
                    <li class="page-item">
                        <a class="page-link" href="{{ $solicituds->url($last) }}">{{ $last }}</a>
                    </li>
                @endif

                @if ($solicituds->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $solicituds->nextPageUrl() }}" rel="next">Siguiente</a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">Siguiente</span>
                    </li>
                @endif

                @if ($solicituds->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $solicituds->url($last) }}">Última</a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">Última</span>
                    </li>
                @endif

            </ul>
        </nav>
    @endif

    </div>
  </div>
</div>
@endsection
