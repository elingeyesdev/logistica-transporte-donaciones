<div class="row">

    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $solicitud->nombre) }}" placeholder="Ingrese el nombre del solicitante">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="apellido">Apellido</label>
            <input type="text" name="apellido" class="form-control" value="{{ old('apellido', $solicitud->apellido) }}" placeholder="Ingrese el apellido del solicitante">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="carnet_identidad">Carnet de Identidad</label>
            <input type="text" name="carnet_identidad" class="form-control" value="{{ old('carnet_identidad', $solicitud->carnet_identidad) }}" placeholder="Ingrese el número de CI">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="correo_electronico">Correo Electrónico</label>
            <input type="email" name="correo_electronico" class="form-control" value="{{ old('correo_electronico', $solicitud->correo_electronico) }}" placeholder="correo@ejemplo.com">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="comunidad_solicitante">Comunidad Solicitante</label>
            <input type="text" name="comunidad_solicitante" class="form-control" value="{{ old('comunidad_solicitante', $solicitud->comunidad_solicitante) }}" placeholder="Nombre de la comunidad o institución">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="ubicacion">Ubicación</label>
            <input type="text" name="ubicacion" class="form-control" value="{{ old('ubicacion', $solicitud->ubicacion) }}" placeholder="Dirección o zona">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="provincia">Provincia</label>
            <input type="text" name="provincia" class="form-control" value="{{ old('provincia', $solicitud->provincia) }}" placeholder="Ingrese la provincia">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="nro_celular">Número de Celular</label>
            <input type="text" name="nro_celular" class="form-control" value="{{ old('nro_celular', $solicitud->nro_celular) }}" placeholder="Ej. 70000000">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group mb-3">
            <label for="cantidad_personas">Cantidad de Personas</label>
            <input type="number" name="cantidad_personas" class="form-control" value="{{ old('cantidad_personas', $solicitud->cantidad_personas) }}" min="1">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group mb-3">
            <label for="fecha_inicio">Fecha de Inicio</label>
            <input type="date" name="fecha_inicio" class="form-control" value="{{ old('fecha_inicio', $solicitud->fecha_inicio) }}">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group mb-3">
            <label for="tipo_emergencia">Tipo de Emergencia</label>
            <input type="text" name="tipo_emergencia" class="form-control" value="{{ old('tipo_emergencia', $solicitud->tipo_emergencia) }}" placeholder="Ej. Inundación, incendio...">
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="insumos_necesarios">Insumos Necesarios</label>
            <textarea name="insumos_necesarios" class="form-control" rows="3" placeholder="Describa los insumos que necesita">{{ old('insumos_necesarios', $solicitud->insumos_necesarios) }}</textarea>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="codigo_seguimiento">Código de Seguimiento</label>
            <input type="text" name="codigo_seguimiento" class="form-control" value="{{ old('codigo_seguimiento', $solicitud->codigo_seguimiento) }}" placeholder="Ej. SOL-001">
        </div>
    </div>

</div>

<div class="text-right">
    <button type="submit" class="btn btn-success">
        <i class="fas fa-save"></i> Guardar Solicitud
    </button>
    <a href="{{ route('solicitud.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>
