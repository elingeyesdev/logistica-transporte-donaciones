@extends('adminlte::page')

@section('title', 'Editar Paquete')

@section('content_header')
    <h1>Editar Paquete</h1>
@stop

@section('content')
<section class="content container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-edit"></i> Actualizar Paquete</h5>
                </div>

                <div class="card-body bg-white">
                    <form method="POST" action="{{ route('paquete.update', $paquete->id_paquete) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @include('paquete.form')

                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@stop
