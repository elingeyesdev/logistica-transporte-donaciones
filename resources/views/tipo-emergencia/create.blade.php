@extends('adminlte::page')

@section('template_title')
    {{ __('Crear') }} Tipo de Emergencia
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Create') }} Tipo de Emergencia</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('tipo-emergencia.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('tipo-emergencia.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
