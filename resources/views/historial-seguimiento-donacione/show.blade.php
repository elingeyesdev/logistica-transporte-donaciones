@extends('adminlte::page')

@section('template_title')
    {{ $historialSeguimientoDonacione->name ?? __('Show') . " " . __('Historial Seguimiento Donacione') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Seguimiento de Paquetes</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('historial-seguimiento-donaciones.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Historial:</strong>
                                    {{ $historialSeguimientoDonacione->id_historial }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Ci Usuario:</strong>
                                    {{ $historialSeguimientoDonacione->ci_usuario }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Estado:</strong>
                                    {{ $historialSeguimientoDonacione->estado }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Actualizacion:</strong>
                                    {{ $historialSeguimientoDonacione->fecha_actualizacion }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Imagen Evidencia:</strong>
                                    {{ $historialSeguimientoDonacione->imagen_evidencia }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Paquete:</strong>
                                    {{ $historialSeguimientoDonacione->id_paquete }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Ubicacion:</strong>
                                    {{ $historialSeguimientoDonacione->id_ubicacion }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
