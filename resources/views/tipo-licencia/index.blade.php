@extends('adminlte::page')

@section('template_title')
    Tipo Licencia
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card shadow-sm">
                    <div class="card-header" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); border: none;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title" style="color: #fff; font-weight: 600; font-size: 1.1rem;">
                                <i class="fas fa-id-card-alt mr-2"></i>{{ __('Tipo Licencia') }}
                            </span>

                                                         <div class="float-right">
                                                                <a href="{{ route('tipo-licencia.create') }}" class="btn btn-light btn-sm float-right" data-placement="left">
                                                                    <i class="fa fa-plus mr-1"></i>{{ __('Crear Nuevo') }}
                                                                </a>
                                                            </div>
                        </div>
                    </div>
                    @if ($message = Session::get('Éxito'))
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
                                transition: all 0.2s ease;
                            }

                            .table tbody tr:hover {
                                background-color: #f1f9fa;
                                transform: scale(1.01);
                                box-shadow: 0 2px 4px rgba(23, 162, 184, 0.1);
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
									<th>Id Licencia</th>
									<th>Licencia</th>
                                        <th class="text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tipoLicencia as $tipoLicencia)
                                        <tr>
                                            
										<td>{{ $tipoLicencia->id_licencia }}</td>
										<td>{{ $tipoLicencia->licencia }}</td>

                                            <td class="text-right">
                                                <form action="{{ route('tipo-licencia.destroy', $tipoLicencia->id_licencia) }}" method="POST" class="d-inline">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('tipo-licencia.show', $tipoLicencia->id_licencia) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Mostrar') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('tipo-licencia.edit', $tipoLicencia->id_licencia) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}</a>
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
