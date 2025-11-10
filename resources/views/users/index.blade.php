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

@section('js')
<script>
document.addEventListener('DOMContentLoaded', () => {
   const baseAdminUrl = "{{ url('/usuarios') }}/";
  const baseActivoUrl = "{{ url('/usuarios') }}/";

    function toggle(type, id) {
    const endpoint = type === 'admin'
      ? `${baseAdminUrl}${id}/toggle-admin`
      : `${baseActivoUrl}${id}/toggle-activo`;

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
});
</script>
@endsection
