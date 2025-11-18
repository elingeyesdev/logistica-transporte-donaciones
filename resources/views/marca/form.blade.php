<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="nombre_marca" class="form-label">{{ __('Nombre de la Marca') }}</label>
            <input type="text" name="nombre_marca" class="form-control @error('nombre_marca') is-invalid @enderror" value="{{ old('nombre_marca', $marca?->nombre_marca) }}" id="nombre_marca" placeholder="Nombre Marca">
            {!! $errors->first('nombre_marca', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
    </div>
</div>