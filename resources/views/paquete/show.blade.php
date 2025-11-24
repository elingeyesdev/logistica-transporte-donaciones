@extends('adminlte::page')

@section('template_title')
    {{ $paquete->name ?? __('Mostrar') . " " . __('Paquete') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Mostrar') }} Paquete</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('paquete.index') }}"> {{ __('Volver') }}</a>
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
                            <strong>Estado:</strong>
                            {{ optional($paquete->estado)->nombre_estado ?? '—' }}
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

                        @php
                            $conductor = optional($paquete->conductor);
                        @endphp
                        <div class="form-group mb-2 mb20">
                            <strong>Conductor:</strong>
                            @if($conductor->conductor_id)
                                {{ trim(($conductor->nombre ?? '').' '.($conductor->apellido ?? '')) ?: 'Sin nombre' }}
                                @if($conductor->ci)
                                    (CI {{ $conductor->ci }})
                                @endif
                            @else
                                —
                            @endif
                        </div>
                        @php
                            $vehiculo = optional($paquete->vehiculo);
                            $marca    = optional($vehiculo->marcaVehiculo);
                        @endphp
                        <div class="form-group mb-2 mb20">
                            <strong>Vehículo:</strong>
                            @if($vehiculo->id_vehiculo)
                                {{ $vehiculo->placa ?? 'Sin placa' }}
                                 @if($marca->id_marca || !empty($vehiculo->modelo)) 
                                    — {{ $marca->nombre_marca ?? $marca->nombre ?? 'Sin marca' }}
                                    {{ $vehiculo->modelo ?? '' }}
                                @endif
                            @else
                                —
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
