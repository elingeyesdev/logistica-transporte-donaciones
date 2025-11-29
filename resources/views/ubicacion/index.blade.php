@extends('adminlte::page')

@section('template_title')
    Ubicacion
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Ubicacion') }}
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
                                        
									<th >Id Ubicacion</th>
									<th >Latitud</th>
									<th >Longitud</th>
									<th >Zona</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ubicacion as $ubicacion)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $ubicacion->id_ubicacion }}</td>
										<td >{{ $ubicacion->latitud }}</td>
										<td >{{ $ubicacion->longitud }}</td>
										<td >{{ $ubicacion->zona }}</td>

                                            <td>
                                                <a class="btn btn-sm btn-primary " href="{{ route('ubicacion.show', $ubicacion->id_ubicacion) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Mostrar') }}</a>
                                                @auth
                                                        @if(auth()->user()->administrador)
                                                            <form action="{{ route('ubicacion.destroy', $ubicacion->id_ubicacion) }}"
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
