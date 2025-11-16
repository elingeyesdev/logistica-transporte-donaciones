@extends('adminlte::page')

@section('template_title')
    {{ __('Crear') }} Tipo de Vehiculo
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Crear') }} Tipos de Vehiculo</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('tipo-vehiculo.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('tipo-vehiculo.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
