@extends('adminlte::page')

@section('title', 'Paquetes')

@section('content_header')
    <h1>Gestión de Paquetes</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Listado de Paquetes</h3>
                    <a href="{{ route('paquete.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Nuevo Paquete
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
                                    <th>ID Paquete</th>
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
                                @foreach ($paquetes as $paquete)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $paquete->id_paquete }}</td>
                                        <td>{{ $paquete->id_solicitud }}</td>
                                        <td>{{ $paquete->descripcion }}</td>
                                        <td>{{ $paquete->cantidad_total }}</td>
                                        <td>
                                            <span class="badge 
                                                @if($paquete->estado_entrega == 'En preparación') bg-warning 
                                                @elseif($paquete->estado_entrega == 'En camino') bg-info 
                                                @elseif($paquete->estado_entrega == 'Entregada') bg-success 
                                                @else bg-secondary @endif">
                                                {{ $paquete->estado_entrega }}
                                            </span>
                                        </td>
                                        <td>{{ $paquete->ubicacion_actual }}</td>
                                        <td>{{ $paquete->fecha_creacion }}</td>
                                        <td>{{ $paquete->fecha_entrega }}</td>
                                        <td class="text-center">
                                            <form action="{{ route('paquete.destroy', $paquete->id_paquete) }}" method="POST">
                                                <a class="btn btn-sm btn-primary" href="{{ route('paquete.show', $paquete->id_paquete) }}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a class="btn btn-sm btn-success" href="{{ route('paquete.edit', $paquete->id_paquete) }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('¿Estás segura/o de eliminar este paquete?')">
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
                    {!! $paquetes->withQueryString()->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@stop
