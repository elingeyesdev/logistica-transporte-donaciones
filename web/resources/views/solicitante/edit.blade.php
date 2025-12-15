@extends('adminlte::page')

@section('template_title')
    {{ __('Editar') }} Solicitante
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Editar') }} Solicitante</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('solicitante.update', $solicitante->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('solicitante.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
