@extends('adminlte::page')

@section('template_title')
    {{ $vehiculo->name ?? __('Mostrar') . " " . __('Vehiculos') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Mostrar') }}Vehiculos</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('vehiculo.index') }}"> {{ __('Volver') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Vehiculo:</strong>
                                    {{ $vehiculo->id_vehiculo }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Placa:</strong>
                                    {{ $vehiculo->placa }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Capacidad Aproximada:</strong>
                                    {{ $vehiculo->capacidad_aproximada }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Tipo de Vehiculo:</strong>
                                    {{ $vehiculo->id_tipovehiculo }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Modelo Anio:</strong>
                                    {{ $vehiculo->modelo_anio }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Modelo:</strong>
                                    {{ $vehiculo->modelo }}
                                </div>
                                  <div class="form-group mb-2 mb20">
                                    <strong>Marca:</strong>
                                    {{ $vehiculo->marca }}
                                </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
