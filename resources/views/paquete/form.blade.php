@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row padding-1 p-1">
  <div class="col-md-12">

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

    <div class="card mt-3 mb-3">
      <div class="card-header">
        <strong>Datos de Transporte</strong>
      </div>
      <div class="card-body">

        <div class="form-group mb-2 mb20">
          <label for="id_conductor" class="form-label">Conductor asignado</label>
          <select name="id_conductor" id="id_conductor"
                  class="form-control @error('id_conductor') is-invalid @enderror">
            <option value="">-- Sin asignar / Seleccionar --</option>
            @foreach($conductores as $c)
              @php
                  $nombreConductor = trim(($c->nombre ?? '').' '.($c->apellido ?? ''));
              @endphp
              <option value="{{ $c->conductor_id }}"
                {{ (string) old('id_conductor', $paquete->id_conductor ?? '') === (string) $c->conductor_id ? 'selected' : '' }}>
                {{ $nombreConductor ?: 'Sin nombre' }} (CI {{ $c->ci ?? '—' }})
              </option>
            @endforeach
          </select>
          {!! $errors->first('id_conductor', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
          <small class="form-text text-muted">
            ¿No existe el conductor? 
            <a href="#" data-toggle="modal" data-target="#modalConductor">
                Crear nuevo conductor
            </a>
          </small>
        </div>

        <div class="form-group mb-2 mb20">
          <label for="id_vehiculo" class="form-label">Vehículo asignado</label>
          <select name="id_vehiculo" id="id_vehiculo"
                  class="form-control @error('id_vehiculo') is-invalid @enderror">
            <option value="">-- Sin asignar / Seleccionar --</option>
            @foreach($vehiculos as $v)
              <option value="{{ $v->id_vehiculo }}"
                {{ (string) old('id_vehiculo', $paquete->id_vehiculo ?? '') === (string) $v->id_vehiculo ? 'selected' : '' }}>
                {{ $v->placa ?? 'Sin placa' }}
              </option>
            @endforeach
          </select>
          {!! $errors->first('id_vehiculo', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
          <small class="form-text text-muted">
            ¿No existe el vehículo? 
            <a href="#" data-toggle="modal" data-target="#modalVehiculo">
                Crear nuevo vehículo
            </a>
          </small>
        </div>

      </div>
    </div>


    <div class="form-group mb-2 mb20">
        <label for="imagen" class="form-label">Imagen del paquete</label>
        <input type="file" name="imagen" id="imagen"
              accept="image/*"
              class="form-control @error('imagen') is-invalid @enderror">

        {!! $errors->first('imagen', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}

        @if($paquete->imagen)
            <div class="mt-2">
                <img src="{{ asset('storage/' . $paquete->imagen) }}"
                    alt="Imagen paquete"
                    style="max-height:120px">
            </div>
        @endif
    </div>


    <div id="geo-alert" class="alert alert-warning d-none" role="alert"></div>
    <div class="card mt-3 mb-3">
      <div class="card-header">
        <strong>Ubicación Actual</strong>
      </div>
      <div class="card-body">
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
    </div>

    <div class="text-right mt-2">
      <button type="submit" class="btn btn-primary">Guardar</button>
      <a href="{{ route('paquete.index') }}" class="btn btn-secondary">Volver</a>
    </div>
  </div>
</div>



@php
    $latValue = old('latitud');
    $lngValue = old('longitud');

    if (!$latValue && isset($paquete->ubicacion_actual) && $paquete->ubicacion_actual) {
        if (strpos($paquete->ubicacion_actual, ',') !== false) {
            $parts = explode(',', $paquete->ubicacion_actual);
            $latValue = trim($parts[0] ?? null);
            $lngValue = trim($parts[1] ?? null);
        }
    }

    $defaultLat = (is_numeric($latValue) && $latValue) ? (float) $latValue : -17.8146;
    $defaultLng = (is_numeric($lngValue) && $lngValue) ? (float) $lngValue : -63.1561;
    $hasCoords  = (is_numeric($latValue) && $latValue && is_numeric($lngValue) && $lngValue);
    $defaultZoom = $hasCoords ? 13 : 6;
@endphp

<script>
(function() {

  function initMap() {
    if (typeof L === 'undefined') {
      console.warn("Leaflet no está cargado. Reintentando...");
      setTimeout(initMap, 100);
      return;
    }

    const mapContainer = document.getElementById('mapa-ubicacion-paquete');
    if (!mapContainer) return;

    const defaultLat  = Number("{{ $defaultLat }}");
    const defaultLng  = Number("{{ $defaultLng }}");
    const defaultZoom = Number("{{ $defaultZoom }}");

    const map = L.map('mapa-ubicacion-paquete', {
      zoomControl: true,
      dragging: false, 
      scrollWheelZoom: false,
      doubleClickZoom: false,
      boxZoom: false,
      touchZoom: false,
    }).setView([defaultLat, defaultLng], defaultZoom);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors',
      maxZoom: 19,
    }).addTo(map);

    let marker = null;

    const latInput = document.getElementById('latitud');
    const lngInput = document.getElementById('longitud');

    function setMarker(lat, lng) {
      latInput.value = lat.toFixed(6);
      lngInput.value = lng.toFixed(6);

      if (!marker) {
        marker = L.marker([lat, lng], {
          draggable: false
        }).addTo(map);
      } else {
        marker.setLatLng([lat, lng]);
      }

      map.setView([lat, lng], 15);
    }

    if (latInput.value && lngInput.value) {
      const lat = parseFloat(latInput.value);
      const lng = parseFloat(lngInput.value);
      if (!isNaN(lat) && !isNaN(lng)) {
        setMarker(lat, lng);
        return;
      }
    }

    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        function(position) {
          const lat = position.coords.latitude;
          const lng = position.coords.longitude;

          setMarker(lat, lng);
        },
        function(error) {
          console.warn("Error o permiso denegado en geolocalización:", error);

          latInput.value = "";
          lngInput.value = "";

          const geoAlert = document.getElementById("geo-alert");
          geoAlert.classList.remove("d-none");
          geoAlert.innerHTML = `
              <strong>Permiso de ubicación requerido:</strong><br>
              Debe habilitar la ubicación en su dispositivo para actualizar la posición del paquete.
          `;
        },
        { enableHighAccuracy: true, timeout: 8000 }
      );
    }

    map.on("click", function() {
      alert("No puedes modificar la ubicación manualmente. Se usa tu ubicación real por seguridad.");
    });

  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initMap);
  } else {
    initMap();
  }

})();
</script>
