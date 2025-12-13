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
  <div class="mb-3">
      <label for="id_rol" class="form-label">Rol</label>
      <select name="id_rol" id="id_rol"
              class="form-control @error('id_rol') is-invalid @enderror">
          @foreach($roles as $rol)
              <option value="{{ $rol->id_rol }}"
                  {{ old('id_rol') == $rol->id_rol ? 'selected' : '' }}>
                  {{ $rol->titulo_rol }}
              </option>
          @endforeach
      </select>
      @error('id_rol') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
  <button type="submit" class="btn btn-primary w-100">Registrar</button>
</form>
@endsection
@section('auth_footer')
    @php
        $gatewayLookupUrl = rtrim(env('GATEWAY_REGISTRO_SIMPLE_URL', ''), '/');
    @endphp

    @if($gatewayLookupUrl)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const ciInput       = document.getElementById('ci');
                const nombreInput   = document.getElementById('nombre');
                const apellidoInput = document.getElementById('apellido');
                const telefonoInput = document.getElementById('telefono');

                const lookupBaseUrl = @json($gatewayLookupUrl);

                if (!ciInput || !lookupBaseUrl) {
                    return;
                }

                let lastLookupCi = null;
                let isFetching   = false;

                ciInput.addEventListener('blur', async function () {
                    const ci = (ciInput.value || '').trim();

                    if (ci.length < 5 || ci === lastLookupCi || isFetching) {
                        return;
                    }

                    lastLookupCi = ci;
                    isFetching   = true;

                    try {
                        const url = `${lookupBaseUrl}/${encodeURIComponent(ci)}`;

                        const response = await fetch(url, {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json',
                                'X-Client-System': 'logistica',
                            },
                        });

                        if (!response.ok) {
                            console.warn('Gateway lookup failed with status', response.status);
                            return;
                        }

                        const json = await response.json();

                        if (!json.success || !json.found || !json.data) {
                            return;
                        }

                        const data = json.data;

                        if (nombreInput && !nombreInput.value.trim() && data.nombre) {
                            nombreInput.value = data.nombre;
                        }
                        if (apellidoInput && !apellidoInput.value.trim() && data.apellido) {
                            apellidoInput.value = data.apellido;
                        }
                        if (telefonoInput && !telefonoInput.value.trim() && data.telefono) {
                            telefonoInput.value = data.telefono;
                        }

                        if (data.ci && ciInput.value.trim() !== data.ci) {
                            ciInput.value = data.ci;
                        }

                    } catch (error) {
                        console.error('Error llamando al gateway para autocompletar', error);
                    } finally {
                        isFetching = false;
                    }
                });
            });
        </script>
    @endif
@endsection
