@extends('adminlte::page')

@section('template_title')
    {{ $reporte->name ?? __('Mostrar') . " " . __('Reporte') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Mostrar') }} Reporte</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('reporte.index') }}"> {{ __('Volver') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Reporte:</strong>
                                    {{ $reporte->id_reporte }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Direccion Archivo:</strong>
                                    {{ $reporte->direccion_archivo }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Reporte:</strong>
                                    {{ $reporte->fecha_reporte }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Gestion:</strong>
                                    {{ $reporte->gestion }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
