@extends('adminlte::page')

@section('template_title')
    Vehiculos
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Vehiculos') }}
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
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                     
									<th >Id Vehiculo</th>
									<th >Placa</th>
									<th >Capacidad Aproximada</th>
									<th >Tipo</th>
									<th >Modelo Anio</th>
									<th >Modelo</th>
                                    <th >Marca</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($vehiculo as $veh)
                                        <tr>                                            
										<td >{{ $veh->id_vehiculo }}</td>
										<td >{{ $veh->placa }}</td>
										<td >{{ $veh->capacidad_aproximada }}</td>
										<td >{{ $veh->tipoVehiculo?->nombre_tipo_vehiculo ?? 'Sin tipo asignado' }}</td>
										<td >{{ $veh->modelo_anio }}</td>
										<td >{{ $veh->modelo }}</td>
                                        <td >{{ $veh->marca }}</td>


                                            <td>
                                                <form action="{{ route('vehiculo.destroy', $veh->id_vehiculo) }}" method="POST">
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
