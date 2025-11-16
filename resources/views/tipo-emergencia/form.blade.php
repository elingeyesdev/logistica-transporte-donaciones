<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="emergencia" class="form-label">{{ __('Nombre de la Emergencia') }}</label>
            <input type="text" name="emergencia" class="form-control @error('emergencia') is-invalid @enderror" value="{{ old('emergencia', $tipoEmergencia?->emergencia) }}" id="emergencia" placeholder="Emergencia">
            {!! $errors->first('emergencia', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="prioridad" class="form-label">{{ __('Prioridad') }}</label>
            <input type="number" name="prioridad" class="form-control @error('prioridad') is-invalid @enderror" value="{{ old('prioridad', $tipoEmergencia?->prioridad) }}" id="prioridad" placeholder="Prioridad">
            {!! $errors->first('prioridad', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
    </div>
</div>