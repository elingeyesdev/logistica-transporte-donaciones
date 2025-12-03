@extends('adminlte::page')

@section('template_title')
    Tipo de Emergencia
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Tipo de Emergencia') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('tipo-emergencia.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
									<th>Id Emergencia</th>
									<th>Emergencia</th>
									<th>Prioridad</th>
                                        <th class="text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tipoEmergencia as $tipoEmergencia)
                                        <tr>                                            
										<td>{{ $tipoEmergencia->id_emergencia }}</td>
										<td>{{ $tipoEmergencia->emergencia }}</td>
										<td>{{ $tipoEmergencia->prioridad }}</td>

                                            <td class="text-right">
                                                <form action="{{ route('tipo-emergencia.destroy', $tipoEmergencia->id_emergencia) }}" method="POST" class="d-inline">
                                                    <a class="btn btn-sm btn-primary" href="{{ route('tipo-emergencia.show', $tipoEmergencia->id_emergencia) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Mostrar') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('tipo-emergencia.edit', $tipoEmergencia->id_emergencia) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('Seguro que quieres eliminar?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i> {{ __('Eliminar') }}</button>
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
