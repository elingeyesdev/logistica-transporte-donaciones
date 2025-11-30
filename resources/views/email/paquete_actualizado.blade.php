<!DOCTYPE html>
<html>
<body>
    <p>Hola {{ optional(optional($paquete->solicitud)->solicitante)->nombre }}
       {{ optional(optional($paquete->solicitud)->solicitante)->apellido }},</p>

    <p>Tu paquete con código <strong>{{ $paquete->codigo }}</strong> ha sido actualizado.</p>

    <p>Estado actual: <strong>{{ optional($paquete->estado)->nombre_estado }}</strong></p>

    <p>Conductor:</p>
    <ul>
        <li>Nombre: {{ optional($paquete->conductor)->nombre }} {{ optional($paquete->conductor)->apellido }}</li>
        <li>CI: {{ optional($paquete->conductor)->ci }}</li>
        <li>Teléfono: {{ optional($paquete->conductor)->celular }}</li>
    </ul>

    <p>Vehículo:</p>
    <ul>
        <li>Placa: {{ optional($paquete->vehiculo)->placa }}</li>
        <li>Marca: {{ optional(optional($paquete->vehiculo)->marcaVehiculo)->nombre_marca }}</li>
        <li>Tipo: {{ optional(optional($paquete->vehiculo)->tipoVehiculo)->nombre_tipo_vehiculo }}</li>
    </ul>

    <p>Ubicación actual: {{ $paquete->ubicacion_actual }}</p>
</body>
</html>
