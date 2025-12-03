@extends('adminlte::page')

@section('template_title')
    Roles
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Roles') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('rol.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                       
									<th>Id Rol</th>
									<th>Titulo Rol</th>
                                        <th class="text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rol as $rol)
                                        <tr>                                            
									<td>{{ $rol->id_rol }}</td>
									<td>{{ $rol->titulo_rol }}</td>

                                            <td class="text-right">
                                                <form action="{{ route('rol.destroy', $rol->id_rol) }}" method="POST" class="d-inline">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('rol.show', $rol->id_rol) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Mostrar') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('rol.edit', $rol->id_rol) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Actualizar') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('Seguro que quieres actualizar?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i> {{ __('Eliminar') }}</button>
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
