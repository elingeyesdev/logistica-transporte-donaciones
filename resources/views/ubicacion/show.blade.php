@extends('adminlte::page')

@section('template_title')
    {{ $ubicacion->name ?? __('Mostrar') . " " . __('Ubicacion') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Mostrar') }} Ubicacion</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('ubicacion.index') }}"> {{ __('Volver') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Ubicacion:</strong>
                                    {{ $ubicacion->id_ubicacion }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Latitud:</strong>
                                    {{ $ubicacion->latitud }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Longitud:</strong>
                                    {{ $ubicacion->longitud }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Zona:</strong>
                                    {{ $ubicacion->zona }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
