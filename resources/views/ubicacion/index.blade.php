@extends('adminlte::page')

@section('template_title')
    Ubicacion
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Ubicacion') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('ubicacion.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        <th>No</th>
                                        
									<th >Id Ubicacion</th>
									<th >Latitud</th>
									<th >Longitud</th>
									<th >Zona</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ubicacion as $ubicacion)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $ubicacion->id_ubicacion }}</td>
										<td >{{ $ubicacion->latitud }}</td>
										<td >{{ $ubicacion->longitud }}</td>
										<td >{{ $ubicacion->zona }}</td>

                                            <td>
                                                <form action="{{ route('ubicacion.destroy', $ubicacion->id_ubicacion) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('ubicacion.show', $ubicacion->id_ubicacion) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Mostrar') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('ubicacion.edit', $ubicacion->id_ubicacion) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i> {{ __('Eliminar') }}</button>
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
