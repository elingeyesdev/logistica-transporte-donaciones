@php
    $pers = optional($solicitud->solicitante ?? null);
    $dest = optional($solicitud->destino ?? null);
@endphp
@php
    $pers = optional($solicitud->solicitante ?? null);
    $dest = optional($solicitud->destino ?? null);

    $latValue = old('latitud', $dest->latitud ?? null);
    $lngValue = old('longitud', $dest->longitud ?? null);
    $defaultLat = $latValue ?: -17.8146;
    $defaultLng = $lngValue ?: -63.1561;
    $defaultZoom = $latValue ? 13 : 6;
@endphp


<div class="row">
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

    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="mapa-ubicacion">Seleccione la Ubicación en el Mapa</label>
        <div id="mapa-ubicacion"
            data-lat="{{ $defaultLat }}"
            data-lng="{{ $defaultLng }}"
            data-zoom="{{ $defaultZoom }}"
            style="height: 400px; width: 100%; border: 1px solid #ddd; border-radius: 4px;">
        </div>
            <small class="form-text text-muted">Haga clic en el mapa para seleccionar la ubicación. La provincia, latitud y longitud se llenarán automáticamente.</small>
            @error('ubicacion') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            @error('latitud') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            @error('longitud') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="ubicacion">Ubicación (dirección/zona)</label>
            <input required type="text" name="ubicacion" id="ubicacion" readonly
                   class="form-control @error('ubicacion') is-invalid @enderror"
                   value="{{ old('ubicacion', $dest->direccion) }}"
                   placeholder="Se seleccionará automáticamente del mapa">
            @error('ubicacion') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group mb-3">
            <label for="latitud">Latitud</label>
            <input required type="number" step="any" name="latitud" id="latitud" readonly
                   class="form-control @error('latitud') is-invalid @enderror"
                   value="{{ old('latitud', $dest->latitud) }}" placeholder="-17.78">
            @error('latitud') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group mb-3">
            <label for="longitud">Longitud</label>
            <input required type="number" step="any" name="longitud" id="longitud" readonly
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
            <label for="id_tipoemergencia">Tipo de Emergencia</label>
            <select required name="id_tipoemergencia" id="id_tipoemergencia"
                    class="form-select @error('id_tipoemergencia') is-invalid @enderror">
                <option value="">Seleccione un tipo</option>
                @foreach($tipoEmergencia as $tipo)
                    <option value="{{ $tipo->id_emergencia }}"
                        {{ old('id_tipoemergencia', $solicitud->id_tipoemergencia) == $tipo->id_emergencia ? 'selected' : '' }}>
                        {{ $tipo->emergencia }} (Prioridad: {{ $tipo->prioridad }})
                    </option>
                @endforeach
            </select>
            @error('id_tipoemergencia') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <input type="hidden" name="tipo_emergencia"
            value="{{ old('tipo_emergencia', optional($solicitud->tipoEmergencia)->emergencia) }}">
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
    @php
        $codigoSeguimiento = old('codigo_seguimiento', $solicitud->codigo_seguimiento);
        if (empty($codigoSeguimiento)) {
            $codigoSeguimiento =
                \Illuminate\Support\Str::upper(\Illuminate\Support\Str::random(3))
                . '-' .
                rand(1000, 9999);
        }
    @endphp

    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="codigo_seguimiento">Código de Seguimiento</label>
            <input type="text"
                  name="codigo_seguimiento"
                  id="codigo_seguimiento"
                  class="form-control @error('codigo_seguimiento') is-invalid @enderror"
                  value="{{ $codigoSeguimiento }}"
                  placeholder="Ej. SOL-001"
                  readonly>
            @error('codigo_seguimiento')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
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
(function() {
  function initMap() {
    if (typeof L === 'undefined') {
      console.warn('Leaflet no está cargado. Reintentando...');
      setTimeout(initMap, 100);
      return;
    }

    const mapContainer = document.getElementById('mapa-ubicacion');
    if (!mapContainer) return;

    let defaultLat = parseFloat(mapContainer.dataset.lat || "-17.8146");
    let defaultLng = parseFloat(mapContainer.dataset.lng || "-63.1561");
    let defaultZoom = parseInt(mapContainer.dataset.zoom || "6", 10);

    const latInput = document.getElementById('latitud');
    const lngInput = document.getElementById('longitud');
    const ubicacionInput = document.getElementById('ubicacion');
    const provinciaInput = document.getElementById('provincia');

    const map = L.map('mapa-ubicacion').setView([defaultLat, defaultLng], defaultZoom);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap',
      maxZoom: 19
    }).addTo(map);

    let marker = null;

    function setMarkerAndReverseGeocode(lat, lng) {
      latInput.value = lat.toFixed(6);
      lngInput.value = lng.toFixed(6);

      if (marker) {
        marker.setLatLng([lat, lng]);
      } else {
        marker = L.marker([lat, lng]).addTo(map);
      }

      reverseGeocode(lat, lng);
    }

    function reverseGeocode(lat, lng) {
      ubicacionInput.value = 'Cargando...';
      provinciaInput.value = 'Cargando...';

      fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
        .then(r => r.json())
        .then(data => {
          if (data?.address) {
            const a = data.address;

            let dir = a.road ?? '';
            if (a.house_number) dir += ' ' + a.house_number;
            if (a.neighbourhood) dir += ', ' + a.neighbourhood;
            if (a.suburb) dir += ', ' + a.suburb;
            if (a.city || a.town) dir += ', ' + (a.city || a.town);

            ubicacionInput.value = dir || data.display_name || `${lat}, ${lng}`;

            provinciaInput.value =
              a.state ??
              a.region ??
              a.county ??
              '';
          } else {
            ubicacionInput.value = `${lat}, ${lng}`;
          }
        })
        .catch(() => {
          ubicacionInput.value = `${lat}, ${lng}`;
          provinciaInput.value = '';
        });
    }

    map.on('click', function(e) {
      setMarkerAndReverseGeocode(e.latlng.lat, e.latlng.lng);
    });

    if (latInput.value && lngInput.value) {
      const lat = parseFloat(latInput.value);
      const lng = parseFloat(lngInput.value);
      map.setView([lat, lng], 13);
      setMarkerAndReverseGeocode(lat, lng);
      return; 
    }

    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        function(pos) {
          const lat = pos.coords.latitude;
          const lng = pos.coords.longitude;

          map.setView([lat, lng], 18);
          setMarkerAndReverseGeocode(lat, lng);
        },
        function(err) {
          console.warn("Geolocalización rechazada o falló:", err);
        },
        { enableHighAccuracy: true, timeout: 8000 }
      );
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initMap);
  } else {
    initMap();
  }
})();
</script>
