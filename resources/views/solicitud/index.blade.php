@extends('adminlte::page')

@section('template_title')
    Solicituds
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Solicitud') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('solicitud.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Nombre Solicitante</th>
									<th >Fecha Creacion</th>
									<th >Descripcion</th>
									<th >Ubicacion</th>
									<th >Estado</th>
									<th >Codigo Seguimiento</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($solicituds as $solicitud)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $solicitud->nombre_solicitante }}</td>
										<td >{{ $solicitud->fecha_creacion }}</td>
										<td >{{ $solicitud->descripcion }}</td>
										<td >{{ $solicitud->ubicacion }}</td>
										<td >{{ $solicitud->estado }}</td>
										<td >{{ $solicitud->codigo_seguimiento }}</td>

                                            <td>
                                                <form action="{{ route('solicitud.destroy', $solicitud->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('solicitud.show', $solicitud->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('solicitud.edit', $solicitud->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $solicituds->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
