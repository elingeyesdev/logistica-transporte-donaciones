@extends('adminlte::page')

@section('layout_topnav', true)

@section('title', 'DAS | Inicio')

@section('content_header')
@stop

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-12 ">
            <div class="card shadow-lg border-0" style="min-height: 70vh;">
                <div class="card-body text-center p-5">
                    <style>
                        .landing-btn {
                            min-width: 240px;
                            border-width: 2px;
                            transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease, color 0.2s ease;
                        }

                        .landing-btn:hover {
                            transform: translateY(-4px);
                            box-shadow: 0 6px 16px rgba(0, 123, 255, 0.25);
                            background-color: #007bff;
                            border-color: #007bff;
                            color: #ffffff;
                        }
                    </style>
                    <img src="{{ asset('vendor/adminlte/dist/img/AdminLTELogo.png') }}" alt="Logo DAS" class="mb-4" style="max-width: 140px;">
                    <h1 class="mb-3">D.A.S</h1>
                    <h1 class="mb-3">Logística y Transporte de donaciones</h1>
                    <p class="lead text-muted mb-4">
                        Somos un equipo comprometido con la logística solidaria para que la ayuda llegue a cada comunidad que la necesita.
                        Nuestro trabajo coordina voluntarios, donaciones y transporte para brindar apoyo oportuno y transparente.
                    </p>
                    <div class="d-flex flex-column flex-md-row align-items-stretch justify-content-center mt-5 pt-3 gap-3">
                        <a href="{{ route('solicitud.create') }}" class="btn btn-outline-primary btn-lg mx-md-2 mb-3 mb-md-0 flex-grow-1 shadow-sm landing-btn">
                            <i class="fas fa-hands-helping mr-2"></i>
                            Solicitar ayuda
                        </a>
                        <a href="{{ route('galeria.index') }}" class="btn btn-outline-primary btn-lg mx-md-2 mb-3 mb-md-0 flex-grow-1 shadow-sm landing-btn">
                            <i class="fas fa-images mr-2"></i>
                            Galería de paquetes entregados
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg mx-md-2 flex-grow-1 shadow-sm landing-btn">
                            <i class="fas fa-users mr-2"></i>
                            ¿Ya eres parte?
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
