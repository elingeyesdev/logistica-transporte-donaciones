@extends('adminlte::page')

@section('template_title')
    {{ $tipoVehiculo->name ?? __('Mostrar ') . " " . __('Tipo de Vehiculo') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Mostrar') }} Tipos de Vehiculo</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('tipo-vehiculo.index') }}"> {{ __('Volver') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Id:</strong>
                                    {{ $tipoVehiculo->id_tipovehiculo }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Tipo Vehiculo:</strong>
                                    {{ $tipoVehiculo->nombre_tipo_vehiculo }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
