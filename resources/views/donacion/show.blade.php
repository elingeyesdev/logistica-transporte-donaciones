@extends('adminlte::page')

@section('template_title')
    {{ $donacion->name ?? __('Show') . " " . __('Donacion') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Donacion</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('donacion.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Donacion:</strong>
                                    {{ $donacion->id_donacion }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Solicitud:</strong>
                                    {{ $donacion->id_solicitud }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Descripcion:</strong>
                                    {{ $donacion->descripcion }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Cantidad Total:</strong>
                                    {{ $donacion->cantidad_total }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Estado Entrega:</strong>
                                    {{ $donacion->estado_entrega }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Ubicacion Actual:</strong>
                                    {{ $donacion->ubicacion_actual }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Creacion:</strong>
                                    {{ $donacion->fecha_creacion }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Entrega:</strong>
                                    {{ $donacion->fecha_entrega }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
