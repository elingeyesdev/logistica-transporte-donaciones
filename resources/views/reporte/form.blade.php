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
        @if(!empty($reporte?->ruta_pdf))
            @php
                $pdfUrl = $reporte->ruta_pdf ? asset('storage/'.$reporte->ruta_pdf) : null;
            @endphp
            <div class="alert alert-light border mb-2">
                <strong>Archivo actual:</strong>
                <a href="{{ $pdfUrl }}" target="_blank" rel="noopener">{{ $reporte->nombre_pdf ?? 'Ver PDF' }}</a>
            </div>
        @endif
        <div class="form-group mb-2 mb20">
            <label for="archivo_pdf" class="form-label">Archivo PDF (opcional)</label>
            <input type="file" name="archivo_pdf" accept="application/pdf" class="form-control @error('archivo_pdf') is-invalid @enderror" id="archivo_pdf">
            {!! $errors->first('archivo_pdf', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            <small class="form-text text-muted">Si adjuntas un archivo, se almacenará en el sistema y se ligará al paquete.</small>
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