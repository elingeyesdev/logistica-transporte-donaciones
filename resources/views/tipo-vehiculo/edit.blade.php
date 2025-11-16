@extends('adminlte::page')

@section('template_title')
    {{ __('Editar') }} Tipo Vehiculo
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Actualizar') }} Tipos de Vehiculo</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('tipo-vehiculo.update', $tipoVehiculo->id_tipovehiculo) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('tipo-vehiculo.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
