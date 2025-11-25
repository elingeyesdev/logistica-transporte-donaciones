@extends('adminlte::page')

@section('template_title')
    Conductores
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Conductores') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('conductor.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                       
									<th >Id</th>
									<th >Nombre</th>
									<th >Apellido</th>
									<th >Fecha Nacimiento</th>
									<th >Ci</th>
									<th >Celular</th>
									<th >Tipo de Licencia</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($conductores as $conductor)
                                        <tr>
                                            
										<td >{{ $conductor->conductor_id }}</td>
										<td >{{ $conductor->nombre }}</td>
										<td >{{ $conductor->apellido }}</td>
										<td >{{ $conductor->fecha_nacimiento }}</td>
										<td >{{ $conductor->ci }}</td>
										<td >{{ $conductor->celular }}</td>
										<td >{{ $conductor->tipoLicencium?->licencia??'Sin Tipo' }}</td>
                                            <td>
                                                <form action="{{ route('conductor.destroy', $conductor) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary" href="{{ route('conductor.show', $conductor) }}">
                                                        <i class="fa fa-fw fa-eye"></i> {{ __('Mostrar') }}
                                                    </a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('conductor.edit', $conductor) }}">
                                                        <i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}
                                                    </a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="event.preventDefault(); confirm('¿Seguro que quieres eliminar este registro?') ? this.closest('form').submit() : false;">
                                                        <i class="fa fa-fw fa-trash"></i> {{ __('Eliminar') }}
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
            </div>
        </div>
    </div>
@endsection
