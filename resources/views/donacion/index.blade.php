@extends('adminlte::page')

@section('title', 'Donaciones')

@section('content_header')
    <h1>Gestión de Donaciones</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Listado de Donaciones</h3>
                    <a href="{{ route('donacion.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Nueva Donación
                    </a>
                </div>

                @if ($message = Session::get('success'))
                    <div class="alert alert-success m-3">
                        {{ $message }}
                    </div>
                @endif

                <div class="card-body bg-white">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>ID Donación</th>
                                    <th>Solicitud</th>
                                    <th>Descripción</th>
                                    <th>Cantidad Total</th>
                                    <th>Estado de Entrega</th>
                                    <th>Ubicación Actual</th>
                                    <th>Fecha Creación</th>
                                    <th>Fecha Entrega</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($donacions as $donacion)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $donacion->id_donacion }}</td>
                                        <td>{{ $donacion->id_solicitud }}</td>
                                        <td>{{ $donacion->descripcion }}</td>
                                        <td>{{ $donacion->cantidad_total }}</td>
                                        <td>
                                            <span class="badge 
                                                @if($donacion->estado_entrega == 'En preparación') bg-warning 
                                                @elseif($donacion->estado_entrega == 'En camino') bg-info 
                                                @elseif($donacion->estado_entrega == 'Entregada') bg-success 
                                                @else bg-secondary @endif">
                                                {{ $donacion->estado_entrega }}
                                            </span>
                                        </td>
                                        <td>{{ $donacion->ubicacion_actual }}</td>
                                        <td>{{ $donacion->fecha_creacion }}</td>
                                        <td>{{ $donacion->fecha_entrega }}</td>
                                        <td class="text-center">
                                            <form action="{{ route('donacion.destroy', $donacion->id_donacion) }}" method="POST">
                                                <a class="btn btn-sm btn-primary" href="{{ route('donacion.show', $donacion->id_donacion) }}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a class="btn btn-sm btn-success" href="{{ route('donacion.edit', $donacion->id_donacion) }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('¿Estás segura/o de eliminar esta donación?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer">
                    {!! $donacions->withQueryString()->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@stop
