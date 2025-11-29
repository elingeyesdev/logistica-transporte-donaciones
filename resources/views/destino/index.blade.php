@extends('adminlte::page')

@section('template_title')
    destino
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Destinos') }}
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
                                                                    <i class="fa fa-fw fa-trash"></i>
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
