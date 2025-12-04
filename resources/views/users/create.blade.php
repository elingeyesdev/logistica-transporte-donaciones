@extends('adminlte::page')

@section('template_title')
    {{ __('Crear') }} Usuario
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Crear') }} Voluntarios</span>
                    </div>
                    <div class="card-body bg-white">
                        <form  id="user-form" method="POST" action="{{ route('user.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('user.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

{{-- Modal para datos de Conductor --}}
@section('modals')
<div class="modal fade" id="conductorModal" tabindex="-1" role="dialog" aria-labelledby="conductorModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="conductorModalLabel">Datos de Conductor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div class="form-group mb-3">
          <label for="conductor_modal_fecha_nacimiento">Fecha de nacimiento</label>
          <input type="date" id="conductor_modal_fecha_nacimiento" class="form-control">
        </div>

        <div class="form-group mb-3">
          <label for="conductor_modal_id_licencia">Tipo de licencia</label>
          <select id="conductor_modal_id_licencia" class="form-select form-control">
            <option value="">Seleccione una licencia</option>
            @foreach($licencias as $lic)
              <option value="{{ $lic->id_licencia }}">
                {{ $lic->tipo_licencia ?? $lic->nombre ?? ('Licencia '.$lic->id_licencia) }}
              </option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" id="conductorModalSave" class="btn btn-primary">Guardar datos de conductor</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script>
(function() {
  var form = document.getElementById('user-form');
  if (!form) return;

  var roleSelect = document.getElementById('id_rol');
  var pendingSubmit = false;

  function rolEsConductor() {
    if (!roleSelect) return false;
    var opt = roleSelect.options[roleSelect.selectedIndex];
    if (!opt) return false;
    var title = (opt.text || '').toLowerCase();
    return title.indexOf('conductor') !== -1;
  }

  form.addEventListener('submit', function(e) {
    if (pendingSubmit) {
      return;
    }

    if (rolEsConductor()) {
      e.preventDefault();
      $('#conductorModal').modal('show');
    }
  });

  var saveBtn = document.getElementById('conductorModalSave');
  if (saveBtn) {
    saveBtn.addEventListener('click', function() {
      var dob = document.getElementById('conductor_modal_fecha_nacimiento').value;
      var lic = document.getElementById('conductor_modal_id_licencia').value;

      if (!dob || !lic) { return; }

      document.getElementById('conductor_fecha_nacimiento').value = dob;
      document.getElementById('conductor_id_licencia').value = lic;

      pendingSubmit = true;
      $('#conductorModal').modal('hide');
      form.submit();
    });
  }
})();
</script>
@endsection
