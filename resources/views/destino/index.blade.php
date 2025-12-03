@extends('adminlte::page')

@section('template_title')
    destino
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); color: white;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title" style="font-size: 1.4rem; font-weight: 600;">
                                <i class="fas fa-map-pin mr-2"></i>{{ __('Destinos') }}
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
                                    <th>No</th>
                                    <th >Comunidad</th>
									<th >Direccion</th>
									<th >Latitud</th>
									<th >Longitud</th>
									<th >Provincia</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($destino as $destino)
                                        <tr>
                                            <td>{{ ++$i }}</td>
										<td >{{ $destino->comunidad }}</td>
										<td >{{ $destino->direccion }}</td>
										<td >{{ $destino->latitud }}</td>
										<td >{{ $destino->longitud }}</td>
										<td >{{ $destino->provincia }}</td>
                                            <td>
                                                <a class="btn btn-sm btn-primary " href="{{ route('destino.show', $destino->id_destino) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Mostrar') }}</a>
                                                @auth
                                                        @if(auth()->user()->administrador)
                                                            <form action="{{ route('destino.destroy', $destino->id_destino) }}"
                                                                method="POST"
                                                                style="display:inline-block">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                        class="btn btn-danger btn-sm"
                                                                        onclick="event.preventDefault(); confirm('¿Seguro que quieres eliminiar este registro?') ? this.closest('form').submit() : false;">
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

<style>
    .card-header {
        border-radius: 0.5rem 0.5rem 0 0 !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    }
    
    .table thead {
        border-bottom: 2px solid #17a2b8;
    }
    
    .table thead th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        color: #495057;
    }
    
    .table tbody tr {
        transition: all 0.2s ease;
    }
    
    .table tbody tr:hover {
        background-color: #f1f9fa !important;
        transform: scale(1.01);
        box-shadow: 0 2px 8px rgba(23, 162, 184, 0.15);
    }
</style>
