@extends('adminlte::page')

@section('template_title')
    {{ __('Crear') }} Conductor
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Crear') }} Conductores</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('conductor.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('conductor.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
