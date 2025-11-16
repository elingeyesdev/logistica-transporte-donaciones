@extends('adminlte::page')

@section('template_title')
    Reporte
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Reporte') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('reporte.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Id Reporte</th>
									<th >Direccion Archivo</th>
									<th >Fecha Reporte</th>
									<th >Gestion</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reportes as $reporte)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $reporte->id_reporte }}</td>
										<td >{{ $reporte->direccion_archivo }}</td>
										<td >{{ $reporte->fecha_reporte }}</td>
										<td >{{ $reporte->gestion }}</td>

                                            <td>
                                                <form action="{{ route('reporte.destroy', $reporte->id_reporte) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('reporte.show', $reporte->id_reporte) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Mostrar') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('reporte.edit', $reporte->id_reporte) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}</a>
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
                {!! $reportes->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
