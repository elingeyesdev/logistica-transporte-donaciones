@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Historial Seguimiento Donacione
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Create') }} Historial Seguimiento Donacione</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('historial-seguimiento-donaciones.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('historial-seguimiento-donacione.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
