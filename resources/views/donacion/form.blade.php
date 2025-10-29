<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="id_donacion" class="form-label">{{ __('Id Donacion') }}</label>
            <input type="text" name="id_donacion" class="form-control @error('id_donacion') is-invalid @enderror" value="{{ old('id_donacion', $donacion?->id_donacion) }}" id="id_donacion" placeholder="Id Donacion">
            {!! $errors->first('id_donacion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="id_solicitud" class="form-label">{{ __('Id Solicitud') }}</label>
            <input type="text" name="id_solicitud" class="form-control @error('id_solicitud') is-invalid @enderror" value="{{ old('id_solicitud', $donacion?->id_solicitud) }}" id="id_solicitud" placeholder="Id Solicitud">
            {!! $errors->first('id_solicitud', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="descripcion" class="form-label">{{ __('Descripcion') }}</label>
            <input type="text" name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" value="{{ old('descripcion', $donacion?->descripcion) }}" id="descripcion" placeholder="Descripcion">
            {!! $errors->first('descripcion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="cantidad_total" class="form-label">{{ __('Cantidad Total') }}</label>
            <input type="text" name="cantidad_total" class="form-control @error('cantidad_total') is-invalid @enderror" value="{{ old('cantidad_total', $donacion?->cantidad_total) }}" id="cantidad_total" placeholder="Cantidad Total">
            {!! $errors->first('cantidad_total', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="estado_entrega" class="form-label">{{ __('Estado Entrega') }}</label>
            <input type="text" name="estado_entrega" class="form-control @error('estado_entrega') is-invalid @enderror" value="{{ old('estado_entrega', $donacion?->estado_entrega) }}" id="estado_entrega" placeholder="Estado Entrega">
            {!! $errors->first('estado_entrega', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="ubicacion_actual" class="form-label">{{ __('Ubicacion Actual') }}</label>
            <input type="text" name="ubicacion_actual" class="form-control @error('ubicacion_actual') is-invalid @enderror" value="{{ old('ubicacion_actual', $donacion?->ubicacion_actual) }}" id="ubicacion_actual" placeholder="Ubicacion Actual">
            {!! $errors->first('ubicacion_actual', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fecha_creacion" class="form-label">{{ __('Fecha Creacion') }}</label>
            <input type="text" name="fecha_creacion" class="form-control @error('fecha_creacion') is-invalid @enderror" value="{{ old('fecha_creacion', $donacion?->fecha_creacion) }}" id="fecha_creacion" placeholder="Fecha Creacion">
            {!! $errors->first('fecha_creacion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fecha_entrega" class="form-label">{{ __('Fecha Entrega') }}</label>
            <input type="text" name="fecha_entrega" class="form-control @error('fecha_entrega') is-invalid @enderror" value="{{ old('fecha_entrega', $donacion?->fecha_entrega) }}" id="fecha_entrega" placeholder="Fecha Entrega">
            {!! $errors->first('fecha_entrega', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>