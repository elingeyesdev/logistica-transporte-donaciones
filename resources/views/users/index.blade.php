@extends('adminlte::page')

@section('title', 'Usuarios')

@section('content_header')
    <h1>Voluntarios Registrados</h1>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Listado de Voluntarios</h3>
  </div>
  <div class="card-body table-responsive">
    <table class="table table-bordered table-striped">
      <thead class="table-light">
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Apellido</th>
          <th>Correo</th>
          <th>Teléfono</th>
          <th>CI</th>
          <th>Rol</th>
          <th>Administrador</th>
          <th>Activo</th>
        </tr>
      </thead>
      <tbody>
        @foreach($users as $user)
        <tr>
          <td>{{ $user->id }}</td>
          <td>{{ $user->nombre ?? '—' }}</td>
          <td>{{ $user->apellido ?? '—' }}</td>
          <td>{{ $user->correo_electronico ?? $user->email }}</td>
          <td>{{ $user->telefono ?? '—' }}</td>
          <td>{{ $user->ci ?? '—' }}</td>
          <td>
            <select class="form-select form-select-sm role-select"
                    data-id="{{ $user->id }}">
                <option value="">Sin rol</option>
                @foreach($roles as $rol)
                  @if(stripos($rol->titulo_rol, 'admin') === false)
                    <option value="{{ $rol->id_rol }}"
                      {{ $user->id_rol == $rol->id_rol ? 'selected' : '' }}>
                      {{ $rol->titulo_rol }}
                    </option>
                  @endif
                @endforeach
            </select>
          </td>

          <td>
            <input type="checkbox" class="toggle-admin"
                   data-id="{{ $user->id }}"
                   {{ $user->administrador ? 'checked' : '' }}>
          </td>
          <td>
            <input type="checkbox" class="toggle-activo"
                   data-id="{{ $user->id }}"
                   {{ $user->activo ? 'checked' : '' }}>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection

<div class="modal fade" id="conductorModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Datos del conductor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="conductor_user_id">
        <input type="hidden" id="conductor_rol_id">

        <div class="form-group">
          <label for="conductor_fecha_nacimiento">Fecha de nacimiento</label>
          <input type="date" class="form-control" id="conductor_fecha_nacimiento">
        </div>

        <div class="form-group">
          <label for="conductor_id_licencia">Tipo de licencia</label>
          <select class="form-control" id="conductor_id_licencia">
            <option value="">Seleccione...</option>
            @foreach($licencias as $lic)
              <option value="{{ $lic->id_licencia}}">
                {{'Licencia '.$lic->licencia }}
              </option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="conductorModalGuardar">Guardar</button>
      </div>
    </div>
  </div>
</div>

@section('js')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const baseUrl = "{{ url('/usuario') }}/";

  function toggle(type, id) {
    const endpoint = type === 'admin'
      ? `${baseUrl}${id}/toggle-admin`
      : `${baseUrl}${id}/toggle-activo`;

    fetch(endpoint, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json'
      }
    })
    .then(res => res.json())
    .then(() => location.reload());
  }

  document.querySelectorAll('.toggle-admin').forEach(el => {
    el.addEventListener('change', () => toggle('admin', el.dataset.id));
  });

  document.querySelectorAll('.toggle-activo').forEach(el => {
    el.addEventListener('change', () => toggle('activo', el.dataset.id));
  });


  function sendRoleChange(id, rolId, extra = {}) {
    const params = new URLSearchParams({ id_rol: rolId, ...extra });

    return fetch(`${baseUrl}${id}/cambiar-rol`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json',
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: params.toString()
    })
    .then(async res => {
      const data = await res.json().catch(() => ({}));

      if (!res.ok || data.success === false) {
        alert(data.message || 'Error al guardar el rol / conductor.');
        console.error('Error cambiar-rol:', data);
        return;
      }
      location.reload();
    });
  }

  document.querySelectorAll('.role-select').forEach(sel => {
    sel.addEventListener('change', () => {
      const id = sel.dataset.id;
      const rolId = sel.value;
      if (!rolId) return;

      const rolText = sel.options[sel.selectedIndex].text.toLowerCase();

      if (rolText.includes('conductor')) {
        document.getElementById('conductor_user_id').value = id;
        document.getElementById('conductor_rol_id').value = rolId;
        document.getElementById('conductor_fecha_nacimiento').value = '';
        document.getElementById('conductor_id_licencia').value = '';

        $('#conductorModal').modal('show');
      } else {
        sendRoleChange(id, rolId);
      }
    });
  });

  document.getElementById('conductorModalGuardar').addEventListener('click', () => {
    const id    = document.getElementById('conductor_user_id').value;
    const rolId = document.getElementById('conductor_rol_id').value;
    const fecha = document.getElementById('conductor_fecha_nacimiento').value;
    const lic   = document.getElementById('conductor_id_licencia').value;

    if (!fecha || !lic) {
      alert('Debe completar la fecha de nacimiento y el tipo de licencia.');
      return;
    }

    $('#conductorModal').modal('hide');

    sendRoleChange(id, rolId, {
      fecha_nacimiento: fecha,
      id_licencia: lic
    });
  });

});
</script>
@endsection
