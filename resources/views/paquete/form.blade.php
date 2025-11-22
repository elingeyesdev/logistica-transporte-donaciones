<div class="row padding-1 p-1">
  <div class="col-md-12">

    {{-- SOLICITUD--}}
    <div class="form-group mb-2 mb20">
      <label for="id_solicitud" class="form-label">Solicitud</label>
      <select name="id_solicitud" id="id_solicitud"
              class="form-control @error('id_solicitud') is-invalid @enderror" required>
        <option value="">-- Seleccione --</option>
        @foreach($solicitudes as $s)
          @php
            $soli = optional($s->solicitante);
            $dest = optional($s->destino);
            $label = sprintf('#%d · %s %s · %s · %s',
              $s->id_solicitud,
              $soli->nombre ?? '—',
              $soli->apellido ?? '',
              $dest->comunidad ?? '—',
              $s->codigo_seguimiento ?? '—'
            );
          @endphp
          <option value="{{ $s->id_solicitud }}"
            {{ (string)old('id_solicitud', $paquete->id_solicitud ?? '') === (string)$s->id_solicitud ? 'selected' : '' }}>
            {{ $label }}
          </option>
        @endforeach
      </select>
      {!! $errors->first('id_solicitud', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
    </div>

    {{-- ESTADO (dropdown by id) --}}
    <div class="form-group mb-2 mb20">
      <label for="estado_id" class="form-label">Estado</label>
      <select name="estado_id" id="estado_id"
              class="form-control @error('estado_id') is-invalid @enderror" required>
        <option value="">-- Seleccione --</option>
        @foreach($estados as $id => $nombre)
          <option value="{{ $id }}"
            {{ (string)old('estado_id', $paquete->estado_id ?? '') === (string)$id ? 'selected' : '' }}>
            {{ $nombre }}
          </option>
        @endforeach
      </select>
      {!! $errors->first('estado_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
    </div>

    {{-- IMAGEN (URL) --}}
    <div class="form-group mb-2 mb20">
      <label for="imagen" class="form-label">Imagen (URL)</label>
      <input type="url" name="imagen" id="imagen"
             class="form-control @error('imagen') is-invalid @enderror"
             value="{{ old('imagen', $paquete->imagen) }}" placeholder="https://...">
      {!! $errors->first('imagen', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
      @if(old('imagen', $paquete->imagen))
        <div class="mt-2">
          <img src="{{ old('imagen', $paquete->imagen) }}" alt="Imagen paquete" style="max-height:120px">
        </div>
      @endif
    </div>

    {{-- UBICACIÓN ACTUAL --}}
    <div class="card mt-3 mb-3">
      <div class="card-header">
        <strong>Ubicación Actual</strong>
      </div>
      <div class="card-body">
        {{-- Mapa interactivo --}}
        <div class="row mb-3">
          <div class="col-md-12">
            <div class="form-group mb-2 mb20">
              <label for="mapa-ubicacion-paquete">Seleccione la Ubicación en el Mapa</label>
              <div id="mapa-ubicacion-paquete" style="height: 400px; width: 100%; border: 1px solid #ddd; border-radius: 4px;"></div>
              <small class="form-text text-muted">Haga clic en el mapa para seleccionar la ubicación. La latitud y longitud se llenarán automáticamente.</small>
              {!! $errors->first('latitud', '<div class="invalid-feedback d-block"><strong>:message</strong></div>') !!}
              {!! $errors->first('longitud', '<div class="invalid-feedback d-block"><strong>:message</strong></div>') !!}
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4">
            <div class="form-group mb-2 mb20">
              <label for="zona" class="form-label">Zona o Comunidad</label>
              <input type="text" name="zona" id="zona"
                    class="form-control @error('zona') is-invalid @enderror"
                    value="{{ old('zona') }}"
                    placeholder="Ej. Zona Sur, Centro, Norte...">
              {!! $errors->first('zona', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group mb-2 mb20">
              <label for="latitud" class="form-label">Latitud</label>
              <input type="number" step="any" name="latitud" id="latitud" readonly
                    class="form-control @error('latitud') is-invalid @enderror"
                    value="{{ old('latitud') }}"
                    placeholder="-17.7833">
              {!! $errors->first('latitud', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group mb-2 mb20">
              <label for="longitud" class="form-label">Longitud</label>
              <input type="number" step="any" name="longitud" id="longitud" readonly
                    class="form-control @error('longitud') is-invalid @enderror"
                    value="{{ old('longitud') }}"
                    placeholder="-63.1821">
              {!! $errors->first('longitud', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
            </div>
          </div>
        </div>

        {{-- Campo generado automáticamente --}}
        <div class="form-group mb-2 mb20" hidden>
          <label for="ubicacion_actual" class="form-label">Ubicación (generada automáticamente)</label>
          <input type="text" name="ubicacion_actual" id="ubicacion_actual"
                class="form-control"
                value="{{ old('ubicacion_actual', $paquete->ubicacion_actual) }}"
                readonly
                placeholder="Se generará al guardar">
        </div>
      </div>
    </div>


    <div class="row">
      <div class="col-md-6">
        <div class="form-group mb-2 mb20">
          <label class="form-label">Código</label>
          <input type="text" class="form-control" value="{{ $paquete->codigo ?? 'Se generará al guardar' }}" disabled>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group mb-2 mb20">
          <label class="form-label">Fecha Aprobación</label>
          <input type="text" class="form-control" value="{{ $paquete->fecha_aprobacion ?? now()->format('Y-m-d') }}" disabled>
        </div>
      </div>
    </div>

    <div class="text-right mt-2">
      <button type="submit" class="btn btn-primary">Guardar</button>
      <a href="{{ route('paquete.index') }}" class="btn btn-secondary">Volver</a>
    </div>
  </div>
</div>

<script>
(function() {
  // Inicializar el mapa cuando el DOM y Leaflet estén listos
  function initMap() {
    // Verificar que Leaflet esté disponible
    if (typeof L === 'undefined') {
      console.warn('Leaflet no está cargado. Reintentando...');
      setTimeout(initMap, 100);
      return;
    }

    const mapContainer = document.getElementById('mapa-ubicacion-paquete');
    if (!mapContainer) return;

    // Coordenadas por defecto (Bolivia - Santa Cruz)
    @php
        $latValue = old('latitud');
        $lngValue = old('longitud');
        
        // Si no hay valores en old, intentar extraer de ubicacion_actual del paquete (si existe)
        if (!$latValue && isset($paquete->ubicacion_actual) && $paquete->ubicacion_actual) {
            // Si ubicacion_actual está en formato "lat,lng"
            if (strpos($paquete->ubicacion_actual, ',') !== false) {
                $parts = explode(',', $paquete->ubicacion_actual);
                $latValue = trim($parts[0] ?? null);
                $lngValue = trim($parts[1] ?? null);
            }
        }
        
        $defaultLat = (is_numeric($latValue) && $latValue) ? floatval($latValue) : -17.8146;
        $defaultLng = (is_numeric($lngValue) && $lngValue) ? floatval($lngValue) : -63.1561;
        $hasCoords = (is_numeric($latValue) && $latValue && is_numeric($lngValue) && $lngValue);
        $defaultZoom = $hasCoords ? 13 : 6;
    @endphp
    const defaultLat = {{ $defaultLat }};
    const defaultLng = {{ $defaultLng }};
    const defaultZoom = {{ $defaultZoom }};

    // Inicializar el mapa
    const map = L.map('mapa-ubicacion-paquete').setView([defaultLat, defaultLng], defaultZoom);

    // Agregar capa de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
      maxZoom: 19
    }).addTo(map);

    // Variables para el marcador y los campos del formulario
    let marker = null;
    const latInput = document.getElementById('latitud');
    const lngInput = document.getElementById('longitud');

    // Si hay valores iniciales, colocar el marcador
    if (latInput && lngInput && latInput.value && lngInput.value) {
      const lat = parseFloat(latInput.value);
      const lng = parseFloat(lngInput.value);
      if (!isNaN(lat) && !isNaN(lng)) {
        marker = L.marker([lat, lng]).addTo(map);
        map.setView([lat, lng], 13);
      }
    }

    // Manejar clic en el mapa
    map.on('click', function(e) {
      const lat = e.latlng.lat;
      const lng = e.latlng.lng;

      // Actualizar campos de latitud y longitud
      if (latInput) latInput.value = lat.toFixed(6);
      if (lngInput) lngInput.value = lng.toFixed(6);

      // Actualizar o crear marcador
      if (marker) {
        marker.setLatLng([lat, lng]);
      } else {
        marker = L.marker([lat, lng]).addTo(map);
      }
    });
  }

  // Inicializar cuando el DOM esté listo
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initMap);
  } else {
    initMap();
  }
})();
</script>
