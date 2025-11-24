<div class="row padding-1 p-1">
    <div class="col-md-12">

        {{-- Nombre --}}
        <div class="form-group mb-2 mb20">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre"
                   class="form-control @error('nombre') is-invalid @enderror"
                   value="{{ old('nombre', $user?->nombre) }}" id="nombre"
                   placeholder="Nombre">
            {!! $errors->first('nombre', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        {{-- Apellido --}}
        <div class="form-group mb-2 mb20">
            <label for="apellido" class="form-label">Apellido</label>
            <input type="text" name="apellido"
                   class="form-control @error('apellido') is-invalid @enderror"
                   value="{{ old('apellido', $user?->apellido) }}" id="apellido"
                   placeholder="Apellido">
            {!! $errors->first('apellido', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        {{-- Correo --}}
        <div class="form-group mb-2 mb20">
            <label for="correo_electronico" class="form-label">Correo Electrónico</label>
            <input type="email" name="correo_electronico"
                   class="form-control @error('correo_electronico') is-invalid @enderror"
                   value="{{ old('correo_electronico', $user?->correo_electronico) }}" id="correo_electronico"
                   placeholder="correo@ejemplo.com">
            {!! $errors->first('correo_electronico', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        {{-- CI --}}
        <div class="form-group mb-2 mb20">
            <label for="ci" class="form-label">Carnet de Identidad</label>
            <input type="text" name="ci"
                   class="form-control @error('ci') is-invalid @enderror"
                   value="{{ old('ci', $user?->ci) }}" id="ci"
                   placeholder="Nº de CI">
            {!! $errors->first('ci', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        {{-- Teléfono --}}
        <div class="form-group mb-2 mb20">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" name="telefono"
                   class="form-control @error('telefono') is-invalid @enderror"
                   value="{{ old('telefono', $user?->telefono) }}" id="telefono"
                   placeholder="Teléfono">
            {!! $errors->first('telefono', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   id="password" placeholder="Contraseña">
            {!! $errors->first('password', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        {{-- Rol --}}
        <div class="form-group mb-2 mb20">
            <label for="id_rol" class="form-label">Rol</label>
            <select name="id_rol" id="id_rol" class="form-select @error('id_rol') is-invalid @enderror">
                <option value="">Seleccione un rol</option>
                @foreach($roles as $rol)
                    <option value="{{ $rol->id_rol }}"
                        {{ old('id_rol', $user?->id_rol) == $rol->id_rol ? 'selected' : '' }}>
                        {{ $rol->titulo_rol }}
                    </option>
                @endforeach
            </select>

            {!! $errors->first('id_rol', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>


        {{-- Administrador --}}
        <div class="form-group mb-2 mb20">
            <label for="administrador" class="form-label">Administrador</label>
            <select name="administrador" id="administrador"
                    class="form-select @error('administrador') is-invalid @enderror">
                <option value="0" {{ old('administrador', $user?->administrador) ? '' : 'selected' }}>No</option>
                <option value="1" {{ old('administrador', $user?->administrador) ? 'selected' : '' }}>Sí</option>
            </select>
            {!! $errors->first('administrador', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        {{-- Activo --}}
        <div class="form-group mb-2 mb20">
            <label for="activo" class="form-label">Activo</label>
            <select name="activo" id="activo"
                    class="form-select @error('activo') is-invalid @enderror">
                <option value="0" {{ old('activo', $user?->activo) ? '' : 'selected' }}>No</option>
                <option value="1" {{ old('activo', $user?->activo) ? 'selected' : '' }}>Sí</option>
            </select>
            {!! $errors->first('activo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
    <input type="hidden" name="conductor_fecha_nacimiento" id="conductor_fecha_nacimiento">
    <input type="hidden" name="conductor_id_licencia" id="conductor_id_licencia">
</div>
