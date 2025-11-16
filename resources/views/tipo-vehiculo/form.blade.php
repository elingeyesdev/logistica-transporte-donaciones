<div class="row padding-1 p-1">
    <div class="col-md-12">
        <div class="form-group mb-2 mb20">
            <label for="nombre_tipo_vehiculo" class="form-label">{{ __('Tipo Vehiculo') }}</label>
            <input type="text" name="nombre_tipo_vehiculo" class="form-control @error('nombre_tipo_vehiculo') is-invalid @enderror" value="{{ old('nombre_tipo_vehiculo', $tipoVehiculo?->nombre_tipo_vehiculo) }}" id="nombre_tipo_vehiculo" placeholder="Ej. Camioneta doble cabina">
            {!! $errors->first('nombre_tipo_vehiculo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
    </div>
</div>