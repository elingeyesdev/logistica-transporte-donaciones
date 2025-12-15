@extends('adminlte::page')

@section('title', 'Perfil pendiente')

@section('content_header')
    <h1>Perfil pendiente de activación</h1>
@endsection

@section('content')
    <div class="alert alert-warning">
        <h4>Tu cuenta aún no está activa</h4>
        <p>
            El administrador del sistema debe habilitar tu perfil para continuar.
            Una vez activado, podrás acceder al sistema normalmente.
        </p>
    </div>
@endsection
