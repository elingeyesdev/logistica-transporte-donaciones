@extends('adminlte::page')

@section('template_title')
    {{ $solicitud->name ?? __('Show') . " " . __('Solicitud') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Solicitud</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('solicitud.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Nombre Solicitante:</strong>
                                    {{ $solicitud->nombre_solicitante }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Creacion:</strong>
                                    {{ $solicitud->fecha_creacion }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Descripcion:</strong>
                                    {{ $solicitud->descripcion }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Ubicacion:</strong>
                                    {{ $solicitud->ubicacion }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Estado:</strong>
                                    {{ $solicitud->estado }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Codigo Seguimiento:</strong>
                                    {{ $solicitud->codigo_seguimiento }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
