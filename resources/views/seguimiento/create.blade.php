@extends('adminlte::page')

@section('template_title')
    {{ __('Crear') }} Historial Seguimiento de Paquetes
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Crear') }} Historial Seguimiento de Paquetes</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('seguimiento.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('seguimiento.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
