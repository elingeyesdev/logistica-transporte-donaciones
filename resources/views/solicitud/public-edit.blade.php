@extends('adminlte::page')

@section('template_title', 'Editar mi solicitud')

@if (!auth()->check())
    @section('layout_topnav', true)
@endif

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
                    <span class="card-title">Editar mi solicitud ({{ $solicitud->codigo_seguimiento }})</span>
                </div>
                <div class="card-body bg-white">
                    <form method="POST"
                          action="{{ route('solicitud.public.update', $solicitud->codigo_seguimiento) }}"
                          role="form"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @include('solicitud.form')
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection
