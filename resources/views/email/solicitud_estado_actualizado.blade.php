<!DOCTYPE html>
<html>
<body>
    <p>Hola {{ optional($solicitud->solicitante)->nombre }} {{ optional($solicitud->solicitante)->apellido }},</p>

    <p>Tu solicitud con código <strong>{{ $solicitud->codigo_seguimiento }}</strong>
        ha cambiado de estado a <strong>{{ strtoupper($solicitud->estado) }}</strong>.
    </p>

    @if($solicitud->estado === 'negada' && $solicitud->justificacion)
        <p>Motivo de la negación:</p>
        <p>{{ $solicitud->justificacion }}</p>
    @endif

    <p>Puedes ver el detalle en:</p>
    <p>
        <a href="{{ route('solicitud.public.show', $solicitud->codigo_seguimiento) }}">
            Ver mi solicitud
        </a>
    </p>
</body>
</html>
