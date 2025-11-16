@extends('adminlte::page')

@section('template_title')
    {{ $tipoEmergencia->name ?? __('Mostrar') . " " . __('Tipo de Emergencia') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Mostrar') }} Tipo de Emergencia</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('tipo-emergencia.index') }}"> {{ __('Volver') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Emergencia:</strong>
                                    {{ $tipoEmergencia->id_emergencia }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Emergencia:</strong>
                                    {{ $tipoEmergencia->emergencia }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Prioridad:</strong>
                                    {{ $tipoEmergencia->prioridad }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
