<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualización de paquete</title>
</head>
<body style="margin:0;padding:24px;background-color:#f2f5f9;font-family:'Segoe UI',Tahoma,Arial,sans-serif;color:#1f2933;">
    <div style="max-width:620px;margin:0 auto;">
        <div style="background:#ffffff;border-radius:14px;box-shadow:0 8px 24px rgba(15,35,95,0.10);border:1px solid #e5e9f2;overflow:hidden;">
            <div style="background:linear-gradient(120deg,#00a4c7,#00749b);padding:22px 28px;color:#ffffff;">
                <h2 style="margin:0;font-size:20px;font-weight:700;">Actualización de tu paquete</h2>
            </div>

            <div style="padding:24px 28px 10px;">
                @php
                    $solicitante = optional(optional($paquete->solicitud)->solicitante);
                    $conductor = optional($paquete->conductor);
                    $vehiculo = optional($paquete->vehiculo);
                    $codigoSeguimiento = $paquete->codigo ?: optional($paquete->solicitud)->codigo_seguimiento;
                @endphp

                <p style="margin-top:0;margin-bottom:16px;font-size:15px;">Hola {{ trim(($solicitante->nombre ?? '').' '.($solicitante->apellido ?? '')) ?: 'amigo/a' }},<br>te informamos que tu paquete ha sido actualizado recientemente.</p>

                <div style="margin-bottom:22px;">
                    <h3 style="margin:0 0 12px;font-size:16px;color:#0f172a;">Estado del paquete</h3>
                    <table role="presentation" cellspacing="0" cellpadding="0" style="width:100%;border-collapse:collapse;font-size:14px;">
                        <tr>
                            <td style="padding:8px 0;color:#64748b;width:45%;">Estado actual</td>
                            <td style="padding:8px 0;font-weight:600;color:#0f172a;">{{ optional($paquete->estado)->nombre_estado ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0;color:#64748b;">Ubicación registrada</td>
                            <td style="padding:8px 0;font-weight:600;color:#0f172a;">{{ $paquete->ubicacion_actual ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0;color:#64748b;">Última actualización</td>
                            <td style="padding:8px 0;font-weight:600;color:#0f172a;">{{ optional($paquete->updated_at)->format('d/m/Y H:i') ?? now()->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>

                <div style="margin-bottom:22px;">
                    <h3 style="margin:0 0 12px;font-size:16px;color:#0f172a;">Conductor asignado</h3>
                    <table role="presentation" cellspacing="0" cellpadding="0" style="width:100%;border-collapse:collapse;font-size:14px;">
                        <tr>
                            <td style="padding:8px 0;color:#64748b;width:45%;">Nombre</td>
                            <td style="padding:8px 0;font-weight:600;color:#0f172a;">{{ trim(($conductor->nombre ?? '').' '.($conductor->apellido ?? '')) ?: 'No asignado' }}</td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0;color:#64748b;">CI</td>
                            <td style="padding:8px 0;font-weight:600;color:#0f172a;">{{ $conductor->ci ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0;color:#64748b;">Teléfono</td>
                            <td style="padding:8px 0;font-weight:600;color:#0f172a;">{{ $conductor->celular ?? '—' }}</td>
                        </tr>
                    </table>
                </div>

                <div style="margin-bottom:22px;">
                    <h3 style="margin:0 0 12px;font-size:16px;color:#0f172a;">Vehículo</h3>
                    @php
                        $marca = optional($vehiculo->marcaVehiculo);
                        $tipo = optional($vehiculo->tipoVehiculo);
                    @endphp
                    <table role="presentation" cellspacing="0" cellpadding="0" style="width:100%;border-collapse:collapse;font-size:14px;">
                        <tr>
                            <td style="padding:8px 0;color:#64748b;width:45%;">Placa</td>
                            <td style="padding:8px 0;font-weight:600;color:#0f172a;">{{ $vehiculo->placa ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0;color:#64748b;">Marca</td>
                            <td style="padding:8px 0;font-weight:600;color:#0f172a;">{{ $marca->nombre_marca ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0;color:#64748b;">Tipo</td>
                            <td style="padding:8px 0;font-weight:600;color:#0f172a;">{{ $tipo->nombre_tipo_vehiculo ?? '—' }}</td>
                        </tr>
                    </table>
                </div>

                @isset($paquete->id_paquete)
                    <div style="text-align:center;margin:28px 0 10px;">
                        <a href="{{ route('seguimiento.tracking', $paquete->id_paquete) }}"
                           style="display:inline-block;padding:12px 22px;background-color:#00a4c7;color:#ffffff;text-decoration:none;border-radius:8px;font-weight:600;font-size:14px;">
                            Ver seguimiento del paquete
                        </a>
                    </div>
                @endisset
            </div>

            <div style="padding:16px 24px;background:#f7fafc;border-top:1px solid #e5e9f2;font-size:12px;color:#61718a;">
                <p style="margin:0;">Este mensaje fue enviado por DAS - Alas Chiquitanas. Ante cualquier duda responde a este correo.</p>
            </div>
        </div>
    </div>
</body>
</html>
