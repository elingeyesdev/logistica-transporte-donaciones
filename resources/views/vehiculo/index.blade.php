@extends('adminlte::page')

@section('template_title')
    Vehiculos
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card shadow-sm">
                    <div class="card-header" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); border: none;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title" style="color: #fff; font-weight: 600; font-size: 1.1rem;">
                                <i class="fas fa-truck-moving mr-2"></i>{{ __('Vehiculos') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('vehiculo.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Crear Nuevo') }}
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
                        <style>
                            .table thead th {
                                background-color: #f8f9fa;
                                color: #495057;
                                font-weight: 600;
                                border-bottom: 2px solid #17a2b8;
                                padding: 12px;
                                font-size: 0.9rem;
                            }

                            .table tbody tr {
                                transition: all 0.2s ease;
                            }

                            .table tbody tr:hover {
                                background-color: #f1f9fa;
                                transform: scale(1.01);
                                box-shadow: 0 2px 4px rgba(23, 162, 184, 0.1);
                            }

                            .table tbody td {
                                vertical-align: middle;
                                padding: 12px;
                            }
                        </style>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
									<th >Placa</th>
									<th >Capacidad Aproximada</th>
									<th >Tipo</th>
									<th >Modelo Anio</th>
									<th >Modelo</th>
                                    <th >Marca</th>
                                    <th >Color</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($vehiculo as $veh)
                                        <tr>                                            
                                        <td>{{ $veh->placa }}</td>
                                        <td>{{ $veh->capacidad_aproximada }}</td>
                                        <td>{{ $veh->tipoVehiculo?->nombre_tipo_vehiculo ?? 'Sin tipo asignado' }}</td>
                                        <td>{{ $veh->modelo_anio }}</td>
                                        <td>{{ $veh-> modelo }}</td>
                                        <td>
                                            {{ optional($veh->marcaVehiculo)->nombre_marca
                                                ?? optional($veh->marcaVehiculo)->nombre
                                                ?? 'Sin marca' }}
                                        </td>
                						<td>{{ $veh-> color }}</td>
                                                            <td class="text-right">
                                                                <form action="{{ route('vehiculo.destroy', $veh->id_vehiculo) }}" method="POST" class="d-inline">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('vehiculo.show', $veh->id_vehiculo) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Mostrar') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('vehiculo.edit', $veh->id_vehiculo) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('Seguro que quieres eliminiar este registro?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i> {{ __('Eliminar') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
