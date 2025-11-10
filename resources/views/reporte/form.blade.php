<div class="row padding-1 p-1">
    <div class="col-md-12">
        <div class="form-group mb-2 mb20">
            <label for="id_paquete" class="form-label">Paquete:</label>
            <select name="id_paquete" id="id_paquete" 
                    class="form-control @error('id_paquete') is-invalid @enderror" required>
                <option value="">-- Seleccione un paquete --</option>
                @foreach($paquetes as $p)
                @php
                    $sol = optional($p->solicitud->solicitante);
                    $label = sprintf('#%d · %s %s · %s',
                        $p->id_paquete,
                        $sol->nombre ?? '—',
                        $sol->apellido ?? '',
                        $p->codigo ?? '');
                @endphp
                <option value="{{ $p->id_paquete }}"
                    {{ (string) old('id_paquete', $reporte->id_paquete ?? '') === (string) $p->id_paquete ? 'selected' : '' }}>
                    {{ $label }}
                </option>
                @endforeach
            </select>
            {!! $errors->first('id_paquete', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
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