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
          <span id="card_title">{{ __('Solicitudes') }}</span>
          <a href="{{ route('solicitud.create') }}" class="btn btn-primary btn-sm">
            <i class="fa fa-plus"></i> {{ __('Crear nueva') }}
          </a>
        </div>
       </div>

        @if ($message = Session::get('success'))
          <div class="alert alert-success m-4"><p>{{ $message }}</p></div>
        @endif

        <div class="card-body bg-white">
          <div class="row">
              @foreach ($solicituds as $solicitud)
                  @php
                      $pers = optional($solicitud->solicitante);
                      $dest = optional($solicitud->destino);
                      $key  = $solicitud->id_solicitud;

                      $esPendienteYNoRespondida = $solicitud->estado === 'pendiente' || $solicitud->estado === null;

                      $badgeClass = 'badge-secondary';
                      $estadoTexto = $solicitud->estado ?? 'pendiente';
                      if ($solicitud->aprobada === true && $solicitud->estado != 'pendiente' ) {
                          $badgeClass = 'badge-success';
                          $estadoTexto = 'aprobada';
                      } elseif ($solicitud->aprobada === false && $solicitud->estado != 'pendiente') {
                          $badgeClass = 'badge-danger';
                          $estadoTexto = 'negada';
                      }
                      else {
                        $badgeClass = 'badge-warning';
                        $estadoTexto = 'pendiente';
                      }
                  @endphp

                  <div class="col-md-3">
                      <div class="card mb-3 shadow-sm bg-light">
                          <div class="card-header d-flex justify-content-between align-items-center">
                              <div style="font-size: large;">
                                  <strong>Solicitud {{ $solicitud->codigo_seguimiento }}</strong><br>
                              </div>
                              <span class="badge {{ $badgeClass }} text-uppercase" style="font-weight:600; font-size: small;">
                                  {{ $estadoTexto }}
                              </span>
                          </div>

                          <div class="card-body">
                              <p class="mb-1"><strong>Nombre:</strong> {{ $pers->nombre ?? '—' }} {{ $pers->apellido ?? '' }}</p>
                              <p class="mb-1"><strong>CI:</strong> {{ $pers->ci ?? '—' }}</p>
                              <p class="mb-1"><strong>Correo:</strong> {{ $pers->email ?? '—' }}</p>
                              <p class="mb-1"><strong>Celular:</strong> {{ $pers->telefono ?? '—' }}</p>
                            <br>

                              <p class="mb-1"><strong>Comunidad:</strong> {{ $dest->comunidad ?? '—' }}</p>
                              <p class="mb-1"><strong>Provincia:</strong> {{ $dest->provincia ?? '—' }}</p>
                              <p class="mb-1"><strong>Ubicación:</strong> {{ $dest->direccion ?? '—' }}</p>
                            <br>

                              <p class="mb-1"><strong>Tipo de Emergencia:</strong> {{ $solicitud->tipo_emergencia ?? '—' }}</p>
                              <p class="mb-1"><strong>Personas afectadas:</strong> {{ $solicitud->cantidad_personas }}</p>
                              <p class="mb-1"><strong>Fecha inicio:</strong> {{ \Carbon\Carbon::parse($solicitud->fecha_inicio)->format('d/m/Y') }}</p>
                          </div>

                          <div class="card-footer d-flex justify-content-between">
                              <div >
                                  <a class="btn btn-sm btn-dark mr-2" href="{{ route('solicitud.show', $key) }}">
                                      <i class="fa fa-eye"></i>
                                  </a>
                                  <a class="btn btn-sm btn-info mr-2" href="{{ route('solicitud.edit', $key) }}">
                                      <i class="fa fa-edit"></i>
                                  </a>
                                
                              </div>

                              <div>
                                  @if($esPendienteYNoRespondida)
                                      <form action="{{ route('solicitud.aprobar', $key) }}" method="POST" class="d-inline">
                                          @csrf
                                            <button 
                                                type="button" class="btn btn-sm btn-dark btn-open-aprobar" data-id="{{ $key }}">
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
                form.action = `/solicitud/aprobar/${id}`;
                 let modal = new bootstrap.Modal(document.getElementById('aprobarConfirmModal'));
                modal.show();
            });
        });
    </script>

    @endpush

      </div>

      {!! $solicituds->withQueryString()->links() !!}
    </div>
  </div>
</div>
@endsection
