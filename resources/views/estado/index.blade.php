@extends('adminlte::page')

@section('title', 'Estados')

@section('content_header')
    <h1>Gestión de Estados</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Lista de Estados</h3>
                    <a href="{{ route('estado.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Nuevo Estado
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
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Tipo</th>
                                    <th>Color</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($estados as $estado)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $estado->id_estado }}</td>
                                        <td>{{ $estado->nombre_estado }}</td>
                                        <td>{{ $estado->descripcion }}</td>
                                        <td>{{ ucfirst($estado->tipo) }}</td>
                                        <td>
                                            @if($estado->color)
                                                <span class="badge" style="background-color: {{ $estado->color }};">
                                                    {{ $estado->color }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">N/A</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('estado.destroy', $estado->id_estado) }}" method="POST">
                                                <a class="btn btn-sm btn-primary" href="{{ route('estado.show', $estado->id_estado) }}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a class="btn btn-sm btn-success" href="{{ route('estado.edit', $estado->id_estado) }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este estado?')">
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
                    {!! $estados->withQueryString()->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@stop
