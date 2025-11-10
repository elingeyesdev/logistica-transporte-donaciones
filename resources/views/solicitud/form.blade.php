@php
    $pers = optional($solicitud->solicitante ?? null);
    $dest = optional($solicitud->destino ?? null);
@endphp

<div class="row">
    {{-- PERSONA (solicitante) --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="nombre">Nombre</label>
            <input required type="text" name="nombre" id="nombre"
                   class="form-control @error('nombre') is-invalid @enderror"
                   value="{{ old('nombre', $pers->nombre) }}"
                   placeholder="Ingrese el nombre del solicitante">
            @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="apellido">Apellido</label>
            <input required type="text" name="apellido" id="apellido"
                   class="form-control @error('apellido') is-invalid @enderror"
                   value="{{ old('apellido', $pers->apellido) }}"
                   placeholder="Ingrese el apellido del solicitante">
            @error('apellido') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="carnet_identidad">Carnet de Identidad</label>
            <input required type="text" name="carnet_identidad" id="carnet_identidad"
                   class="form-control @error('carnet_identidad') is-invalid @enderror"
                   value="{{ old('carnet_identidad', $pers->ci) }}"
                   placeholder="Ingrese el número de CI">
            @error('carnet_identidad') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="correo_electronico">Correo Electrónico</label>
            <input required type="email" name="correo_electronico" id="correo_electronico"
                   class="form-control @error('correo_electronico') is-invalid @enderror"
                   value="{{ old('correo_electronico', $pers->email) }}"
                   placeholder="correo@ejemplo.com">
            @error('correo_electronico') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="nro_celular">Número de Celular</label>
            <input required type="text" name="nro_celular" id="nro_celular"
                   class="form-control @error('nro_celular') is-invalid @enderror"
                   value="{{ old('nro_celular', $pers->telefono) }}"
                   placeholder="Ej. 70000000">
            @error('nro_celular') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    {{-- DESTINO (ubicación) --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="comunidad_solicitante">Comunidad Solicitante</label>
            <input required type="text" name="comunidad_solicitante" id="comunidad_solicitante"
                   class="form-control @error('comunidad_solicitante') is-invalid @enderror"
                   value="{{ old('comunidad_solicitante', $dest->comunidad) }}"
                   placeholder="Nombre de la comunidad o institución">
            @error('comunidad_solicitante') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="provincia">Provincia</label>
            <input required type="text" name="provincia" id="provincia"
                   class="form-control @error('provincia') is-invalid @enderror"
                   value="{{ old('provincia', $dest->provincia) }}"
                   placeholder="Ingrese la provincia">
            @error('provincia') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="ubicacion">Ubicación (dirección/zona)</label>
            <input required type="text" name="ubicacion" id="ubicacion"
                   class="form-control @error('ubicacion') is-invalid @enderror"
                   value="{{ old('ubicacion', $dest->direccion) }}"
                   placeholder="Dirección o zona">
            @error('ubicacion') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group mb-3">
            <label for="latitud">Latitud</label>
            <input required type="number" step="any" name="latitud" id="latitud"
                   class="form-control @error('latitud') is-invalid @enderror"
                   value="{{ old('latitud', $dest->latitud) }}" placeholder="-17.78">
            @error('latitud') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group mb-3">
            <label for="longitud">Longitud</label>
            <input required type="number" step="any" name="longitud" id="longitud"
                   class="form-control @error('longitud') is-invalid @enderror"
                   value="{{ old('longitud', $dest->longitud) }}" placeholder="-63.18">
            @error('longitud') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group mb-3">
            <label for="cantidad_personas">Cantidad de Personas</label>
            <input required type="number" min="1" name="cantidad_personas" id="cantidad_personas"
                   class="form-control @error('cantidad_personas') is-invalid @enderror"
                   value="{{ old('cantidad_personas', $solicitud->cantidad_personas) }}">
            @error('cantidad_personas') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group mb-3">
            <label for="fecha_inicio">Fecha de Inicio</label>
            <input required type="date" name="fecha_inicio" id="fecha_inicio"
                   class="form-control @error('fecha_inicio') is-invalid @enderror"
                   value="{{ old('fecha_inicio', optional($solicitud->fecha_inicio)->format('Y-m-d') ?? $solicitud->fecha_inicio) }}">
            @error('fecha_inicio') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group mb-3">
            <label for="tipo_emergencia">Tipo de Emergencia</label>
            <input required type="text" name="tipo_emergencia" id="tipo_emergencia"
                   class="form-control @error('tipo_emergencia') is-invalid @enderror"
                   value="{{ old('tipo_emergencia', $solicitud->tipo_emergencia) }}"
                   placeholder="Ej. Inundación, incendio...">
            @error('tipo_emergencia') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="insumos_necesarios">Insumos Necesarios</label>
            <textarea required name="insumos_necesarios" id="insumos_necesarios"
                      class="form-control @error('insumos_necesarios') is-invalid @enderror"
                      rows="3" placeholder="Describa los insumos que necesita">{{ old('insumos_necesarios', $solicitud->insumos_necesarios) }}</textarea>
            @error('insumos_necesarios') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="codigo_seguimiento">Código de Seguimiento</label>
            <input type="text" name="codigo_seguimiento" id="codigo_seguimiento"
                   class="form-control @error('codigo_seguimiento') is-invalid @enderror"
                   value="{{ old('codigo_seguimiento', $solicitud->codigo_seguimiento) }}"
                   placeholder="Ej. SOL-001" readonly>
            @error('codigo_seguimiento') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

<div class="text-right">
    <button type="submit" class="btn btn-success">
        <i class="fas fa-save"></i> Guardar Solicitud
    </button>
    <a href="{{ route('solicitud.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>
<script>
(function () {
  const $com = document.getElementById('comunidad_solicitante');
  const $code = document.getElementById('codigo_seguimiento');

  function initialsFrom(text) {
    if (!text) return '';
    const words = text.trim().toUpperCase().replace(/[^A-Z0-9\s]/g,'').split(/\s+/);
    return words.map(w => w[0]).join('').slice(0,4);
  }

  function genCode(base) {
    const n = Math.floor(100 + Math.random() * 900);
    return `${base || 'SOL'}-${n}`;
  }

  function updateCode() {
    const ini = initialsFrom($com.value) || 'SOL';
    if (!$code.value || /^[A-Z0-9]{1,4}-\d{3}$/.test($code.value)) {
      $code.value = genCode(ini);
    }
  }

  document.addEventListener('DOMContentLoaded', updateCode);
  $com?.addEventListener('input', updateCode);
  $com?.addEventListener('blur', updateCode);
})();
</script>

