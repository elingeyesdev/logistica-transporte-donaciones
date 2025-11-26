@extends('adminlte::page')

@section('title', 'Editar Paquete')

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

@section('content_header')
    <h1>Editar Paquete</h1>
@stop

@section('content')
<section class="content container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-edit"></i> Actualizar Paquete</h5>
                </div>

                <div class="card-body bg-white">
                    <form method="POST" action="{{ route('paquete.update', $paquete->id_paquete) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @include('paquete.form')

                        
                    </form>
                    {{-- Modal Crear Conductor --}}
                    <div class="modal fade" id="modalConductor" tabindex="-1" aria-labelledby="modalConductorLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <form id="formConductorModal" data-action="{{ route('conductor.store') }}">
                            @csrf
                            <div class="modal-header">
                            <h5 class="modal-title" id="modalConductorLabel">Crear nuevo conductor</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <div class="modal-body">

                            <div class="mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" name="nombre" class="form-control">
                                <div class="text-danger small" data-error="nombre"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Apellido</label>
                                <input type="text" name="apellido" class="form-control">
                                <div class="text-danger small" data-error="apellido"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Fecha de nacimiento</label>
                                <input type="date" name="fecha_nacimiento" class="form-control">
                                <div class="text-danger small" data-error="fecha_nacimiento"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">CI</label>
                                <input type="text" name="ci" class="form-control">
                                <div class="text-danger small" data-error="ci"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Celular</label>
                                <input type="text" name="celular" class="form-control">
                                <div class="text-danger small" data-error="celular"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tipo de licencia</label>
                                <select name="id_licencia" class="form-control">
                                <option value="">-- Seleccione --</option>
                                @foreach($licencias as $lic)
                                    <option value="{{ $lic->id_licencia }}">{{ $lic->licencia }}</option>
                                @endforeach
                                </select>
                                <div class="text-danger small" data-error="id_licencia"></div>
                            </div>

                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar conductor</button>
                            </div>
                        </form>
                        </div>
                    </div>
                    </div>

                    {{-- Modal Crear Vehículo --}}
                    <div class="modal fade" id="modalVehiculo" tabindex="-1" aria-labelledby="modalVehiculoLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <form id="formVehiculoModal" data-action="{{ route('vehiculo.store') }}">
                            @csrf
                            <div class="modal-header">
                            <h5 class="modal-title" id="modalVehiculoLabel">Crear nuevo vehículo</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <div class="modal-body">

                            <div class="mb-3">
                                <label class="form-label">Placa</label>
                                <input type="text" name="placa" class="form-control" placeholder="Ej. 8547DFG">
                                <div class="text-danger small" data-error="placa"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Capacidad aproximada</label>
                                <input type="text" name="capacidad_aproximada" class="form-control">
                                <div class="text-danger small" data-error="capacidad_aproximada"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Modelo (nombre)</label>
                                <input type="text" name="modelo" class="form-control">
                                <div class="text-danger small" data-error="modelo"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Año modelo</label>
                                <input type="number" name="modelo_anio" class="form-control" min="1975">
                                <div class="text-danger small" data-error="modelo_anio"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tipo de vehículo</label>
                                <select name="id_tipovehiculo" class="form-control">
                                <option value="">-- Seleccione --</option>
                                @foreach($tiposVehiculo as $t)
                                    <option value="{{ $t->id_tipovehiculo }}">{{ $t->nombre_tipo_vehiculo }}</option>
                                @endforeach
                                </select>
                                <div class="text-danger small" data-error="id_tipovehiculo"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Marca</label>
                                <select name="id_marca" class="form-control">
                                <option value="">-- Sin marca / Seleccione --</option>
                                @foreach($marcas as $m)
                                    <option value="{{ $m->id_marca }}">{{ $m->nombre_marca }}</option>
                                @endforeach
                                </select>
                                <div class="text-danger small" data-error="id_marca"></div>
                            </div>

                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar vehículo</button>
                            </div>
                        </form>
                        </div>
                    </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</section>
<script>
(function() {
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

  function clearErrors(form) {
    form.querySelectorAll('[data-error]').forEach(function(el) {
      el.textContent = '';
    });
  }

  function showErrors(form, errors) {
    Object.keys(errors).forEach(function(field) {
      const el = form.querySelector('[data-error="'+field+'"]');
      if (el) {
        el.textContent = errors[field][0] ?? '';
      }
    });
  }

  const formConductor = document.getElementById('formConductorModal');
  if (formConductor) {
    formConductor.addEventListener('submit', function(e) {
      e.preventDefault();
      clearErrors(formConductor);

      const url = formConductor.dataset.action;
      const formData = new FormData(formConductor);

      fetch(url, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        },
        body: formData
      })
      .then(async response => {
        if (response.ok) {
          const json = await response.json();
          const c = json.data;

          const select = document.getElementById('id_conductor');
          if (select) {
            const option = document.createElement('option');
            const nombre = (c.nombre ?? '') + ' ' + (c.apellido ?? '');
            option.value = c.conductor_id;
            option.textContent = (nombre.trim() || 'Sin nombre') + ' (CI ' + (c.ci ?? '—') + ')';
            select.appendChild(option);
            select.value = String(c.conductor_id);
          }

          formConductor.reset();
          $('#modalConductor').modal('hide');

        } else if (response.status === 422) {
          const json = await response.json();
          showErrors(formConductor, json.errors || {});
        } else {
          console.error('Error creando conductor', response);
        }
      })
      .catch(err => {
        console.error('Error de red creando conductor', err);
      });
    });
  }

  const formVehiculo = document.getElementById('formVehiculoModal');
  if (formVehiculo) {
    formVehiculo.addEventListener('submit', function(e) {
      e.preventDefault();
      clearErrors(formVehiculo);

      const url = formVehiculo.dataset.action;
      const formData = new FormData(formVehiculo);

      fetch(url, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        },
        body: formData
      })
      .then(async response => {
        if (response.ok) {
          const json = await response.json();
          const v = json.data;

          const select = document.getElementById('id_vehiculo');
          if (select) {
            const option = document.createElement('option');
            option.value = v.id_vehiculo;
            option.textContent = v.placa ?? 'Sin placa';
            select.appendChild(option);
            select.value = String(v.id_vehiculo);
          }

          formVehiculo.reset();
          $('#modalVehiculo').modal('hide');

        } else if (response.status === 422) {
          const json = await response.json();
          showErrors(formVehiculo, json.errors || {});
        } else {
          console.error('Error creando vehículo', response);
        }
      })
      .catch(err => {
        console.error('Error de red creando vehículo', err);
      });
    });
  }
})();
</script>

@stop
