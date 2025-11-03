@extends('adminlte::page')

@section('template_title')
    {{ $paquete->name ?? __('Show') . " " . __('Paquete') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Paquete</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('paquete.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Paquete:</strong>
                                    {{ $paquete->id_paquete }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Solicitud:</strong>
                                    {{ $paquete->id_solicitud }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Descripcion:</strong>
                                    {{ $paquete->descripcion }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Cantidad Total:</strong>
                                    {{ $paquete->cantidad_total }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Estado Entrega:</strong>
                                    {{ $paquete->estado_entrega }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Ubicacion Actual:</strong>
                                    {{ $paquete->ubicacion_actual }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Creacion:</strong>
                                    {{ $paquete->fecha_creacion }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Entrega:</strong>
                                    {{ $paquete->fecha_entrega }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
