@extends('adminlte::page')

@section('template_title')
    solicitante
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Solicitantes') }}
                            </span>

                             
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
									<th >Nombre</th>
									<th >Apellido</th>
									<th >Ci</th>
									<th >Email</th>
									<th >Telefono</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($solicitante as $solicitante)
                                        <tr>

                                        <td >{{ $solicitante->nombre }}</td>
										<td >{{ $solicitante->apellido }}</td>
										<td >{{ $solicitante->ci }}</td>
										<td >{{ $solicitante->email }}</td>
										<td >{{ $solicitante->telefono }}</td>

                                            <td>
                                                <form action="{{ route('solicitante.destroy', $solicitante->id_solicitante) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('solicitante.show', $solicitante->id_solicitante) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Mostrar') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('solicitante.edit', $solicitante->id_solicitante) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}</a>
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
