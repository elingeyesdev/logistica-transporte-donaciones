@extends('adminlte::auth.login')

@section('auth_header', 'Iniciar sesión')

@section('auth_body')
<form action="{{ route('login') }}" method="POST">
  @csrf

  <div class="mb-3">
    <label for="correo_electronico" class="form-label">Correo Electronico</label>
    <input type="email" class="form-control @error('correo_electronico') is-invalid @enderror"
           id="correo_electronico" name="correo_electronico" value="{{ old('correo_electronico') }}" required autofocus>
    @error('correo_electronico') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="mb-3">
    <label for="password" class="form-label">Contraseña</label>
    <input type="password" class="form-control @error('password') is-invalid @enderror"
           id="password" name="password" required>
    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="remember" id="remember">
      <label class="form-check-label" for="remember">Recordarme</label>
    </div>
  </div>

  <button type="submit" class="btn btn-primary w-100">Entrar</button>
</form>
@endsection

@section('auth_footer')
  <p class="text-center mt-3">
    ¿No tienes cuenta?
    <a href="{{ route('register') }}">Registrarse</a>
  </p>
@endsection
