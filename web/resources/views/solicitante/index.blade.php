@extends('adminlte::page')

@section('template_title')
    solicitante
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card shadow-sm">
                    <div class="card-header" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); border:none;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title" style="color: #fff; font-weight: 600; font-size: 1.1rem;">
                                <i class="fas fa-users mr-2"></i>{{ __('Solicitantes') }}
                            </span>

                             
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body bg-white">
                        <style>
                            .table thead th {
                                background-color: #f8f9fa;
                                color: #495057;
                                font-weight: 600;
                                border-bottom: 2px solid #17a2b8;
                                padding: 12px;
                                font-size: 0.9rem;
                            }
                            .table tbody tr {
                                transition: all 0.2s;
                            }
                            .table tbody tr:hover {
                                background-color: #f1f9fa;
                                transform: scale(1.01);
                                box-shadow: 0 2px 4px rgba(23,162,184,0.1);
                            }
                            .table tbody td {
                                vertical-align: middle;
                                padding: 12px;
                            }
                        </style>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>                                        
									<th>Nombre</th>
									<th>Apellido</th>
									<th>CI</th>
									<th>Email</th>
									<th>Teléfono</th>

                                        <th class="text-right">Acciones</th>
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

                                            <td class="text-right">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('solicitante.show', $solicitante->id_solicitante) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Mostrar') }}</a>
                                              {{--  <a class="btn btn-sm btn-success" href="{{ route('solicitante.edit', $solicitante->id_solicitante) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}</a>--}}
                                                   @auth
                                                        @if(auth()->user()->administrador)
                                                            <form action="{{ route('solicitante.destroy', $solicitante->id_solicitante) }}"
                                                                method="POST"
                                                                style="display:inline-block">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                        class="btn btn-danger btn-sm"
                                                                        onclick="event.preventDefault(); confirm('Seguro que quieres eliminiar este registro?') ? this.closest('form').submit() : false;">
                                                                    <i class="fa fa-fw fa-trash"></i> {{ __('Eliminar') }}
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endauth

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
