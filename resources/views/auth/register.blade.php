@extends('adminlte::auth.register')

@section('auth_header', 'Crear cuenta')

@section('auth_body')
<form action="{{ route('register') }}" method="POST">
  @csrf

  <div class="row">
    <div class="col-md-6 mb-3">
      <label for="nombre" class="form-label">Nombre(s)</label>
      <input type="text" class="form-control @error('nombre') is-invalid @enderror"
             id="nombre" name="nombre" value="{{ old('nombre') }}" required>
      @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
      <label for="apellido" class="form-label">Apellidos</label>
      <input type="text" class="form-control @error('apellido') is-invalid @enderror"
             id="apellido" name="apellido" value="{{ old('apellido') }}" required>
      @error('apellido') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
  </div>

  <div class="mb-3">
    <label for="correo_electronico" class="form-label">Correo Electronico</label>
    <input type="email" class="form-control @error('correo_electronico') is-invalid @enderror"
           id="correo_electronico" name="correo_electronico" value="{{ old('correo_electronico') }}" required>
    @error('correo_electronico') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

   <div class="mb-3">
    <label for="telefono" class="form-label">Número de Celular:</label>
    <input type="number" class="form-control @error('telefono') is-invalid @enderror"
           id="telefono" name="telefono" value="{{ old('telefono') }}" required>
    @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
    <div class="mb-3">
    <label for="ci" class="form-label">Carnet de Identidad:</label>
    <input type="text" class="form-control @error('ci') is-invalid @enderror"
           id="ci" name="ci" value="{{ old('ci') }}" required>
    @error('ci') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
  <div class="mb-3">
    <label for="password" class="form-label">Contraseña</label>
    <input type="password" class="form-control @error('password') is-invalid @enderror"
           id="password" name="password" required>
    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="mb-3">
    <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
  </div>

  <button type="submit" class="btn btn-primary w-100">Registrar</button>
</form>
@endsection
