@extends('adminlte::page')
@section('template_title')
    {{ $user->name ?? __('Mostrar') . " " . __('Usuario') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Mostrar') }} Usuario</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('user.index') }}"> {{ __('Volver') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Usuario:</strong>
                                    {{ $user->id_usuario }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Active:</strong>
                                    {{ $user->active }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Admin:</strong>
                                    {{ $user->admin }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Apellido:</strong>
                                    {{ $user->apellido }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Ci:</strong>
                                    {{ $user->ci }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Contrasena:</strong>
                                    {{ $user->contrasena }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Correo Electronico:</strong>
                                    {{ $user->correo_electronico }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Nombre:</strong>
                                    {{ $user->nombre }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Telefono:</strong>
                                    {{ $user->telefono }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
