@extends('adminlte::page')

@section('template_title')
    Solicitudes
@endsection

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span id="card_title">{{ __('Solicitudes') }}</span>
          <a href="{{ route('solicitud.create') }}" class="btn btn-primary btn-sm">
            <i class="fa fa-plus"></i> {{ __('Crear nueva') }}
          </a>
        </div>

        @if ($message = Session::get('success'))
          <div class="alert alert-success m-4"><p>{{ $message }}</p></div>
        @endif

        <div class="card-body bg-white">
          <div class="table-responsive">
            <table class="table table-striped table-hover">
              <thead class="thead-dark">
                <tr>
                  <th>#</th>
                  <th>Nombre</th>
                  <th>Apellido</th>
                  <th>CI</th>
                  <th>Correo</th>
                  <th>Comunidad</th>
                  <th>Provincia</th>
                  <th>Ubicación</th>
                  <th>Celular</th>
                  <th>Tipo de Emergencia</th>
                  <th>Código Seguimiento</th>
                  <th class="text-center">Acciones</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($solicituds as $solicitud)
                  @php
                    $pers = optional($solicitud->solicitante);
                    $dest = optional($solicitud->destino);
                    $key  = $solicitud->id_solicitud;
                  @endphp
                  <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $pers->nombre ?? '—' }}</td>
                    <td>{{ $pers->apellido ?? '—' }}</td>
                    <td>{{ $pers->ci ?? '—' }}</td>
                    <td>{{ $pers->email ?? '—' }}</td>
                    <td>{{ $dest->comunidad ?? '—' }}</td>
                    <td>{{ $dest->provincia ?? '—' }}</td>
                    <td>{{ $dest->direccion ?? '—' }}</td>
                    <td>{{ $pers->telefono ?? '—' }}</td>
                    <td>{{ $solicitud->tipo_emergencia ?? '—' }}</td>
                    <td>{{ $solicitud->codigo_seguimiento ?? '—' }}</td>

                    <td class="text-center">
                      <form action="{{ route('solicitud.destroy', $key) }}" method="POST">
                        <a class="btn btn-sm btn-primary" href="{{ route('solicitud.show', $key) }}">
                          <i class="fa fa-eye"></i>
                        </a>
                        <a class="btn btn-sm btn-success" href="{{ route('solicitud.edit', $key) }}">
                          <i class="fa fa-edit"></i>
                        </a>
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger"
                                onclick="return confirm('¿Seguro que deseas eliminar esta solicitud?')">
                          <i class="fa fa-trash"></i>
                        </button>
                      </form>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>

      {!! $solicituds->withQueryString()->links() !!}
    </div>
  </div>
</div>
@endsection
