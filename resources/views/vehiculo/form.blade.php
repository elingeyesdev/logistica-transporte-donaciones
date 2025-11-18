<div class="row padding-1 p-1">
    <div class="col-md-12">
 
        <div class="form-group mb-2 mb20">
            <label for="placa" class="form-label">Placa</label>
            <input 
                type="text" name="placa" id="placa" class="form-control @error('placa') is-invalid @enderror" value="{{ old('placa', $vehiculo?->placa) }}"
                placeholder="Ej: 1234ABC" pattern="^[0-9]{3,4}[A-Z]{3}$" style="text-transform:uppercase;" required >
            {!! $errors->first('placa', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="capacidad_aproximada" class="form-label">{{ __('Capacidad Aproximada (Kg.)') }}</label>
            <input type="number" name="capacidad_aproximada" class="form-control @error('capacidad_aproximada') is-invalid @enderror" value="{{ old('capacidad_aproximada', $vehiculo?->capacidad_aproximada) }}" id="capacidad_aproximada" placeholder="Capacidad Aproximada">
            {!! $errors->first('capacidad_aproximada', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="id_tipovehiculo" class="form-label">Tipo de Vehículo</label>
            <select name="id_tipovehiculo" id="id_tipovehiculo" class="form-select @error('id_tipovehiculo') is-invalid @enderror">
                <option value="">Seleccione un tipo</option>
                @foreach($tipos as $tipo)
                    <option value="{{ $tipo->id_tipovehiculo }}"
                        {{ old('id_tipovehiculo', $vehiculo?->id_tipovehiculo) == $tipo->id_tipovehiculo ? 'selected' : '' }}>
                        {{ $tipo->nombre_tipo_vehiculo }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('id_tipovehiculo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="modelo" class="form-label">{{ __('Modelo') }}</label>
            <input type="text" name="modelo" class="form-control @error('modelo') is-invalid @enderror" value="{{ old('modelo', $vehiculo?->modelo) }}" id="modelo" placeholder="Modelo">
            {!! $errors->first('modelo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="modelo_anio" class="form-label">{{ __('Año de Fabricación') }}</label>
            <input type="number" name="modelo_anio" class="form-control @error('modelo_anio') is-invalid @enderror" value="{{ old('modelo_anio', $vehiculo?->modelo_anio) }}" id="modelo_anio" placeholder="Ej: 2020" min="1975" max="{{ date('Y') + 1 }}">
            {!! $errors->first('modelo_anio', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="id_marca" class="form-label">Marca</label>
            <select name="id_marca" id="id_marca"
                    class="form-select @error('id_marca') is-invalid @enderror">
                <option value="">Seleccione una marca</option>
                @foreach($marcas as $marca)
                    <option value="{{ $marca->id_marca }}"
                        {{ old('id_marca', $vehiculo?->id_marca) == $marca->id_marca ? 'selected' : '' }}>
                        {{ $marca->nombre_marca }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('id_marca', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
    </div>
</div>