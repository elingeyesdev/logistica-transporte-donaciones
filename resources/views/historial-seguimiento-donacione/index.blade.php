@extends('layouts.app')

@section('template_title')
    Historial Seguimiento Donaciones
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Historial Seguimiento Donaciones') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('historial-seguimiento-donaciones.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Create New') }}
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
                                        
									<th >Id Historial</th>
									<th >Ci Usuario</th>
									<th >Estado</th>
									<th >Fecha Actualizacion</th>
									<th >Imagen Evidencia</th>
									<th >Id Donacion</th>
									<th >Id Ubicacion</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($historialSeguimientoDonaciones as $historialSeguimientoDonacione)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $historialSeguimientoDonacione->id_historial }}</td>
										<td >{{ $historialSeguimientoDonacione->ci_usuario }}</td>
										<td >{{ $historialSeguimientoDonacione->estado }}</td>
										<td >{{ $historialSeguimientoDonacione->fecha_actualizacion }}</td>
										<td >{{ $historialSeguimientoDonacione->imagen_evidencia }}</td>
										<td >{{ $historialSeguimientoDonacione->id_donacion }}</td>
										<td >{{ $historialSeguimientoDonacione->id_ubicacion }}</td>

                                            <td>
                                                <form action="{{ route('historial-seguimiento-donaciones.destroy', $historialSeguimientoDonacione->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('historial-seguimiento-donaciones.show', $historialSeguimientoDonacione->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('historial-seguimiento-donaciones.edit', $historialSeguimientoDonacione->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $historialSeguimientoDonaciones->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
