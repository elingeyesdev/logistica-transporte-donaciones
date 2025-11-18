@extends('adminlte::page')

@section('template_title')
    {{ __('Actualizar') }} Marca
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Actualizar') }} Marca</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('marca.update', $marca->id_marca) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('marca.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
