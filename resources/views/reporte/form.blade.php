<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="id_reporte" class="form-label">{{ __('Id Reporte') }}</label>
            <input type="text" name="id_reporte" class="form-control @error('id_reporte') is-invalid @enderror" value="{{ old('id_reporte', $reporte?->id_reporte) }}" id="id_reporte" placeholder="Id Reporte">
            {!! $errors->first('id_reporte', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="direccion_archivo" class="form-label">{{ __('Direccion Archivo') }}</label>
            <input type="text" name="direccion_archivo" class="form-control @error('direccion_archivo') is-invalid @enderror" value="{{ old('direccion_archivo', $reporte?->direccion_archivo) }}" id="direccion_archivo" placeholder="Direccion Archivo">
            {!! $errors->first('direccion_archivo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
    <label for="fecha_reporte" class="form-label">Fecha del reporte</label>
           <input type="date" name="fecha_reporte" class="form-control @error('fecha_reporte') is-invalid @enderror" id="fecha_reporte" value="{{ old('fecha_reporte', $reporte?->fecha_reporte ?? now()->format('Y-m-d')) }}">
             {!! $errors->first('fecha_reporte', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="gestion" class="form-label">{{ __('Gestion') }}</label>
            <input type="text" name="gestion" class="form-control @error('gestion') is-invalid @enderror" value="{{ old('gestion', $reporte?->gestion) }}" id="gestion" placeholder="Gestion">
            {!! $errors->first('gestion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
    </div>
</div>