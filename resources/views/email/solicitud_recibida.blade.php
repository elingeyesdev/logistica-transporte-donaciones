<!DOCTYPE html>
<html>
<body>
    <p>Hola {{ optional($solicitud->solicitante)->nombre }} {{ optional($solicitud->solicitante)->apellido }},</p>

    <p>Hemos recibido tu solicitud, tu código asignado es <strong>{{ $solicitud->codigo_seguimiento }}</strong>.</p>

    <p>Datos Registrados:</p>
    <ul>
        <li>Tipo de emergencia: {{ $solicitud->tipo_emergencia }}</li>
        <li>Cantidad de personas: {{ $solicitud->cantidad_personas }}</li>
        <li>Insumos necesarios: {{ $solicitud->insumos_necesarios }}</li>
        <li>Comunidad: {{ optional($solicitud->destino)->comunidad }}</li>
        <li>Provincia: {{ optional($solicitud->destino)->provincia }}</li>
    </ul>
    <p>Datos del Solicitante:</p>
    <ul>
        <li>Nombre: {{ $solicitud->nombre }} {{ $solicitud->apellido }}</li>
        <li>Correo Electronico: {{ optional($solicitud->solicitante)->email }}</li>
        <li>Número de Celular: {{ optional($solicitud->solicitante)->telefono }}</li>
        <li>Carnet de Identidad: {{ optional($solicitud->solicitante)->ci }}</li>        
    </ul>
    <p>Recibiras un mensaje por este medio con la respuesta.</p>
    <p>
        Puedes editar tu solicitud en el siguiente enlace:<br>
        <a href="{{ route('solicitud.public.edit', $solicitud->codigo_seguimiento) }}">
            Editar mi solicitud
        </a>
    </p>
</body>
</html>
