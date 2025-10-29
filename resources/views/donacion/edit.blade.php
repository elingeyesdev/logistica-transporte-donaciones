@extends('adminlte::page')

@section('title', 'Editar Donación')

@section('content_header')
    <h1>Editar Donación</h1>
@stop

@section('content')
<section class="content container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-edit"></i> Actualizar Donación</h5>
                </div>

                <div class="card-body bg-white">
                    <form method="POST" action="{{ route('donacion.update', $donacion->id_donacion) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @include('donacion.form')

                        <div class="mt-3 text-right">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Guardar Cambios
                            </button>
                            <a href="{{ route('donacion.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@stop
