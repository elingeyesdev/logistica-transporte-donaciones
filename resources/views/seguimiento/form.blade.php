<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="id_historial" class="form-label">{{ __('Id Historial') }}</label>
            <input type="text" name="id_historial" class="form-control @error('id_historial') is-invalid @enderror" value="{{ old('id_historial', $historialSeguimientoDonacione?->id_historial) }}" id="id_historial" placeholder="Id Historial">
            {!! $errors->first('id_historial', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="ci_usuario" class="form-label">{{ __('Ci Usuario') }}</label>
            <input type="text" name="ci_usuario" class="form-control @error('ci_usuario') is-invalid @enderror" value="{{ old('ci_usuario', $historialSeguimientoDonacione?->ci_usuario) }}" id="ci_usuario" placeholder="Ci Usuario">
            {!! $errors->first('ci_usuario', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="estado" class="form-label">{{ __('Estado') }}</label>
            <input type="text" name="estado" class="form-control @error('estado') is-invalid @enderror" value="{{ old('estado', $historialSeguimientoDonacione?->estado) }}" id="estado" placeholder="Estado">
            {!! $errors->first('estado', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fecha_actualizacion" class="form-label">{{ __('Fecha Actualizacion') }}</label>
            <input type="text" name="fecha_actualizacion" class="form-control @error('fecha_actualizacion') is-invalid @enderror" value="{{ old('fecha_actualizacion', $historialSeguimientoDonacione?->fecha_actualizacion) }}" id="fecha_actualizacion" placeholder="Fecha Actualizacion">
            {!! $errors->first('fecha_actualizacion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="imagen_evidencia" class="form-label">{{ __('Imagen Evidencia') }}</label>
            <input type="text" name="imagen_evidencia" class="form-control @error('imagen_evidencia') is-invalid @enderror" value="{{ old('imagen_evidencia', $historialSeguimientoDonacione?->imagen_evidencia) }}" id="imagen_evidencia" placeholder="Imagen Evidencia">
            {!! $errors->first('imagen_evidencia', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="id_paquete" class="form-label">{{ __('Id Paquete') }}</label>
            <input type="text" name="id_paquete" class="form-control @error('id_paquete') is-invalid @enderror" value="{{ old('id_paquete', $historialSeguimientoDonacione?->id_paquete) }}" id="id_paquete" placeholder="Id Paquete">
            {!! $errors->first('id_paquete', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="id_ubicacion" class="form-label">{{ __('Id Ubicacion') }}</label>
            <input type="text" name="id_ubicacion" class="form-control @error('id_ubicacion') is-invalid @enderror" value="{{ old('id_ubicacion', $historialSeguimientoDonacione?->id_ubicacion) }}" id="id_ubicacion" placeholder="Id Ubicacion">
            {!! $errors->first('id_ubicacion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>