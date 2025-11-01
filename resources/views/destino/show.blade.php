@extends('adminlte::page')

@section('template_title')
    {{ $destino->name ?? __('Show') . " " . __('Destino') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Destino</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('destino.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Destino:</strong>
                                    {{ $destino->id_destino }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Comunidad:</strong>
                                    {{ $destino->comunidad }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Direccion:</strong>
                                    {{ $destino->direccion }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Latitud:</strong>
                                    {{ $destino->latitud }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Longitud:</strong>
                                    {{ $destino->longitud }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Provincia:</strong>
                                    {{ $destino->provincia }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
