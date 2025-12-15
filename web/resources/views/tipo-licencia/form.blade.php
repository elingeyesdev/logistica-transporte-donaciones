<div class="row padding-1 p-1">
    <div class="col-md-12">

        <div class="form-group mb-2 mb20">
            <label for="licencia" class="form-label">{{ __('Licencia') }}</label>
            <input type="text" name="licencia" class="form-control @error('licencia') is-invalid @enderror" value="{{ old('licencia', $tipoLicencia?->licencia) }}" id="licencia" placeholder="Licencia">
            {!! $errors->first('licencia', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
    </div>
</div>