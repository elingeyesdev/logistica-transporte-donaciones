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

    <div class="form-group mb-2 mb20">
      <label for="ubicacion_actual" class="form-label">Ubicación Actual</label>
      <input type="text" name="ubicacion_actual" id="ubicacion_actual"
             class="form-control @error('ubicacion_actual') is-invalid @enderror"
             value="{{ old('ubicacion_actual', $paquete->ubicacion_actual) }}" placeholder="Punto actual de tránsito">
      {!! $errors->first('ubicacion_actual', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
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
