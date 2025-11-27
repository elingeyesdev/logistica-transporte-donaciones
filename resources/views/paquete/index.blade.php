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
                                    <th>Estado de Entrega</th>
                                    <th>Ubicación Actual</th>
                                    <th>Fecha Creación</th>
                                    <th>Fecha Entrega</th>
                                     <th class="text-center"></th>

                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($paquetes as $paquete)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $paquete->id_paquete }}</td>
                                        @php
                                            $sol  = optional($paquete->solicitud);
                                            $pers = optional($sol->solicitante);
                                            $dest = optional($sol->destino);
                                        @endphp
                                        <td>
                                            <div><strong>CI Sol.:</strong> {{ $pers->ci ?? '—' }}</div>
                                            <div><strong>Solicitante:</strong> {{ $pers->nombre ?? '—' }} {{ $pers->apellido ?? '—' }}</div>
                                            <div><strong>Comunidad:</strong> {{ $dest->comunidad ?? '—' }}</div>
                                            <div><strong>Emergencia:</strong> {{ $sol->tipo_emergencia ?? '—' }}</div>
                                        </td>
    
                                       @php $nombre = optional($paquete->estado)->nombre_estado; @endphp
                                        <td>
                                        <span class="badge
                                            @if($nombre === 'Pendiente') bg-warning
                                            @elseif($nombre === 'En camino') bg-info
                                            @elseif($nombre === 'Entregada') bg-success
                                            @elseif($nombre === 'Esperando Aprobacion') bg-secondary
                                            @else bg-secondary @endif">
                                            {{ $nombre ?? '—' }}
                                        </span>
                                        </td>
                                       <td>{{ trim(\Illuminate\Support\Str::before($paquete->ubicacion_actual, '-')) }}</td>
                                        <td> {{ \Carbon\Carbon::parse( $paquete->fecha_creacion)->format('d/m/Y') }}</td>
                                        <td>{{ $paquete->fecha_entrega }}</td>
                                        <td class="text-center">
                                                <a class="btn btn-sm btn-primary" href="{{ route('seguimiento.tracking', $paquete->id_paquete) }}">
                                                    Seguimiento
                                                </a>
                                        </td>
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
