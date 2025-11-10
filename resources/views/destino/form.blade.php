<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="id_destino" class="form-label">{{ __('Id Destino') }}</label>
            <input type="text" name="id_destino" class="form-control @error('id_destino') is-invalid @enderror" value="{{ old('id_destino', $destino?->id_destino) }}" id="id_destino" placeholder="Id Destino">
            {!! $errors->first('id_destino', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="comunidad" class="form-label">{{ __('Comunidad') }}</label>
            <input type="text" name="comunidad" class="form-control @error('comunidad') is-invalid @enderror" value="{{ old('comunidad', $destino?->comunidad) }}" id="comunidad" placeholder="Comunidad">
            {!! $errors->first('comunidad', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="direccion" class="form-label">{{ __('Direccion') }}</label>
            <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror" value="{{ old('direccion', $destino?->direccion) }}" id="direccion" placeholder="Direccion">
            {!! $errors->first('direccion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="latitud" class="form-label">{{ __('Latitud') }}</label>
            <input type="text" name="latitud" class="form-control @error('latitud') is-invalid @enderror" value="{{ old('latitud', $destino?->latitud) }}" id="latitud" placeholder="Latitud">
            {!! $errors->first('latitud', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="longitud" class="form-label">{{ __('Longitud') }}</label>
            <input type="text" name="longitud" class="form-control @error('longitud') is-invalid @enderror" value="{{ old('longitud', $destino?->longitud) }}" id="longitud" placeholder="Longitud">
            {!! $errors->first('longitud', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="provincia" class="form-label">{{ __('Provincia') }}</label>
            <input type="text" name="provincia" class="form-control @error('provincia') is-invalid @enderror" value="{{ old('provincia', $destino?->provincia) }}" id="provincia" placeholder="Provincia">
            {!! $errors->first('provincia', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
    </div>
</div>