@php
    $solicitud = optional($paquete->solicitud);
    $solicitante = optional($solicitud->solicitante);
    $destino = optional($solicitud->destino);
    $conductor = optional($paquete->conductor);
    $vehiculo  = optional($paquete->vehiculo);
    $marca     = optional($vehiculo->marcaVehiculo);
    $encargado = optional($paquete->encargado);
    $estadoNombre = optional($paquete->estado)->nombre_estado;
    $fechaEntrega = $paquete->fecha_entrega
        ? \Carbon\Carbon::parse($paquete->fecha_entrega)->format('d/m/Y')
        : null;
@endphp
<table>
    <thead>
        <tr>
            <th colspan="2">Reporte de Seguimiento de Paquete #{{ $paquete->id_paquete }}</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><strong>Generado</strong></td>
            <td>{{ now()->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td><strong>Estado actual</strong></td>
            <td>{{ $estadoNombre ?? '—' }}</td>
        </tr>
        <tr>
            <td><strong>Ubicación actual</strong></td>
            <td>{{ $paquete->ubicacion_actual ?? '—' }}</td>
        </tr>
        <tr>
            <td><strong>Fecha de entrega</strong></td>
            <td>{{ $fechaEntrega ?? 'No registrada' }}</td>
        </tr>
    </tbody>
</table>

<table>
    <thead>
        <tr>
            <th colspan="2">Datos de la solicitud</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><strong>Código de solicitud</strong></td>
            <td>{{ $solicitud->codigo_seguimiento ?? '—' }}</td>
        </tr>
        <tr>
            <td><strong>Tipo de emergencia</strong></td>
            <td>{{ $solicitud->tipo_emergencia ?? '—' }}</td>
        </tr>
        <tr>
            <td><strong>Solicitante</strong></td>
            <td>{{ trim(($solicitante->nombre ?? '').' '.($solicitante->apellido ?? '')) ?: '—' }}</td>
        </tr>
        <tr>
            <td><strong>CI solicitante</strong></td>
            <td>{{ $solicitante->ci ?? '—' }}</td>
        </tr>
        <tr>
            <td><strong>Comunidad destino</strong></td>
            <td>{{ $destino->comunidad ?? '—' }}</td>
        </tr>
    </tbody>
</table>

<table>
    <thead>
        <tr>
            <th colspan="2">Logística</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><strong>Vehículo</strong></td>
            <td>
                {{ $vehiculo->placa ?? '—' }}
                @if($marca->nombre_marca ?? false)
                    - {{ $marca->nombre_marca }}
                @endif
            </td>
        </tr>
        <tr>
            <td><strong>Conductor</strong></td>
            <td>
                {{ trim(($conductor->nombre ?? '').' '.($conductor->apellido ?? '')) ?: '—' }}
                @if($conductor->ci)
                    (CI {{ $conductor->ci }})
                @endif
            </td>
        </tr>
        <tr>
            <td><strong>Voluntario encargado</strong></td>
            <td>
                {{ trim(($encargado->nombre ?? '').' '.($encargado->apellido ?? '')) ?: '—' }}
                @if($paquete->id_encargado)
                    (CI {{ $paquete->id_encargado }})
                @endif
            </td>
        </tr>
        <tr>
            <td><strong>Última actualización</strong></td>
            <td>{{ $paquete->updated_at ? $paquete->updated_at->format('d/m/Y H:i') : '—' }}</td>
        </tr>
    </tbody>
</table>
