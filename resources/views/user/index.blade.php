@extends('adminlte::page')
@section('template_title')
    Usuarios
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Usuarios') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('user.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Id Usuario</th>
									<th >Active</th>
									<th >Admin</th>
									<th >Apellido</th>
									<th >Ci</th>
									<th >Contrasena</th>
									<th >Correo Electronico</th>
									<th >Nombre</th>
									<th >Telefono</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $user->id_usuario }}</td>
										<td >{{ $user->active }}</td>
										<td >{{ $user->admin }}</td>
										<td >{{ $user->apellido }}</td>
										<td >{{ $user->ci }}</td>
										<td >{{ $user->contrasena }}</td>
										<td >{{ $user->correo_electronico }}</td>
										<td >{{ $user->nombre }}</td>
										<td >{{ $user->telefono }}</td>

                                            <td>
                                                <form action="{{ route('user.destroy', $user->id_usuario) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('user.show', $user->id_usuario) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Mostrar') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('user.edit', $user->id_usuario) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}</a>
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
                {!! $usuarios->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
