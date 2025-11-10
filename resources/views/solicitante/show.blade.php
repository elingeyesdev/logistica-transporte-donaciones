@extends('adminlte::page')

@section('template_title')
    {{ $solicitante->name ?? __('Mostrar') . " " . __('Solicitante') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Mostrar') }} Solicitante</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('solicitante.index') }}"> {{ __('Volver') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Solicitante:</strong>
                                    {{ $solicitante->id_solicitante }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Apellido:</strong>
                                    {{ $solicitante->apellido }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Ci:</strong>
                                    {{ $solicitante->ci }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Email:</strong>
                                    {{ $solicitante->email }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Nombre:</strong>
                                    {{ $solicitante->nombre }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Telefono:</strong>
                                    {{ $solicitante->telefono }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
