<!DOCTYPE html>
<html>
<body>
    <p>Hola {{ optional(optional($paquete->solicitud)->solicitante)->nombre }}
       {{ optional(optional($paquete->solicitud)->solicitante)->apellido }},</p>

    <p>Te confirmamos que tu paquete con código <strong>{{ $paquete->codigo }}</strong> ha sido
       <strong>ENTREGADO</strong>.</p>

    <p>Fecha de entrega: {{ $paquete->fecha_entrega }}</p>
    <p>Gracias por confiar en nosotros.</p>
</body>
</html>
