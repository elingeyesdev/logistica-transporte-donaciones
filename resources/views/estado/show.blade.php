@extends('adminlte::page')

@section('template_title')
    {{ $estado->name ?? __('Mostrar') . " " . __('Estado') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); color: white; display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title"><i class="fas fa-flag mr-2"></i>{{ __('Mostrar') }} Estado</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('estado.index') }}"> {{ __('Volver') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Estado:</strong>
                                    {{ $estado->id_estado }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Nombre Estado:</strong>
                                    {{ $estado->nombre_estado }}
                                </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
