@extends('adminlte::page')

@section('template_title')
    {{ $tipoLicencia->name ?? __('Mostrar') . " " . __('Tipo de Licencia') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Mostrar') }} Tipo Licencia</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('tipo-licencia.index') }}"> {{ __('Volver') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Licencia:</strong>
                                    {{ $tipoLicencia->id_licencia }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Licencia:</strong>
                                    {{ $tipoLicencia->licencia }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
