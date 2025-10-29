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
                            <span id="card_title">
                                {{ __('Solicitudes') }}
                            </span>

                            <div class="float-right">
                                <a href="{{ route('solicitud.create') }}" class="btn btn-primary btn-sm float-right">
                                    <i class="fa fa-plus"></i> {{ __('Crear nueva') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
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
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $solicitud->nombre }}</td>
                                            <td>{{ $solicitud->apellido }}</td>
                                            <td>{{ $solicitud->carnet_identidad }}</td>
                                            <td>{{ $solicitud->correo_electronico }}</td>
                                            <td>{{ $solicitud->comunidad_solicitante }}</td>
                                            <td>{{ $solicitud->provincia }}</td>
                                            <td>{{ $solicitud->ubicacion }}</td>
                                            <td>{{ $solicitud->nro_celular }}</td>
                                            <td>{{ $solicitud->tipo_emergencia }}</td>
                                            <td>{{ $solicitud->codigo_seguimiento }}</td>

                                            <td class="text-center">
                                               @php($key = method_exists($solicitud, 'getRouteKey') ? $solicitud->getRouteKey() : ($solicitud->id_solicitud ?? $solicitud->id ?? null))
                                               @if($key)
                                               <form action="{{ route('solicitud.destroy', ['solicitud' => $key]) }}" method="POST">
                                                <a class="btn btn-sm btn-primary" href="{{ route('solicitud.show', ['solicitud' => $key]) }}">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a class="btn btn-sm btn-success" href="{{ route('solicitud.edit', ['solicitud' => $key]) }}">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar esta solicitud?')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                               @else
                                                <button class="btn btn-sm btn-secondary" disabled><i class="fa fa-eye"></i></button>
                                                <button class="btn btn-sm btn-secondary" disabled><i class="fa fa-edit"></i></button>
                                                <button class="btn btn-sm btn-secondary" disabled><i class="fa fa-trash"></i></button>
                                               @endif

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
