@extends('adminlte::page')

@section('template_title')
    {{ __('Crear') }} 
@endsection

@push('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
      crossorigin=""/>
@endpush

@push('js')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
@endpush

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Crear') }} Paquete</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('paquete.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('paquete.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
