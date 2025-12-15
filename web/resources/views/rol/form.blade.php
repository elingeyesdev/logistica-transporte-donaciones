<div class="row padding-1 p-1">
    <div class="col-md-12">
        <div class="form-group mb-2 mb20">
            <label for="titulo_rol" class="form-label">{{ __('Titulo Rol') }}</label>
            <input type="text" name="titulo_rol" class="form-control @error('titulo_rol') is-invalid @enderror" value="{{ old('titulo_rol', $rol?->titulo_rol) }}" id="titulo_rol" placeholder="Titulo Rol">
            {!! $errors->first('titulo_rol', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
    </div>
</div>