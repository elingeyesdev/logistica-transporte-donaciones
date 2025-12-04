<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitud recibida</title>
</head>
<body style="margin:0;padding:24px;background-color:#f2f5f9;font-family:'Segoe UI',Tahoma,Arial,sans-serif;color:#1f2933;">
    <div style="max-width:620px;margin:0 auto;">
        <div style="background:#ffffff;border-radius:14px;box-shadow:0 8px 24px rgba(15,35,95,0.10);border:1px solid #e5e9f2;overflow:hidden;">
            <div style="background:linear-gradient(120deg,#2563eb,#1d4ed8);padding:22px 28px;color:#ffffff;">
                <h2 style="margin:0;font-size:20px;font-weight:700;">¡Tu solicitud fue recibida!</h2>
                <p style="margin:6px 0 0;font-size:14px;opacity:0.9;">Código asignado: <strong style="color:#ffffff;">{{ $solicitud->codigo_seguimiento ?? '—' }}</strong></p>
            </div>

            <div style="padding:24px 28px 12px;">
                @php
                    $solicitante = optional($solicitud->solicitante);
                    $destino = optional($solicitud->destino);
                @endphp

                <p style="margin-top:0;margin-bottom:16px;font-size:15px;">Hola {{ trim(($solicitante->nombre ?? '').' '.($solicitante->apellido ?? '')) ?: 'amigo/a' }},<br>gracias por confiar en nosotros. Hemos registrado tu solicitud y pronto recibirás novedades.</p>

                <div style="margin-bottom:24px;">
                    <h3 style="margin:0 0 12px;font-size:16px;color:#0f172a;">Datos de la solicitud</h3>
                    <table role="presentation" cellspacing="0" cellpadding="0" style="width:100%;border-collapse:collapse;font-size:14px;">
                        <tr>
                            <td style="padding:8px 0;color:#64748b;width:45%;">Tipo de emergencia</td>
                            <td style="padding:8px 0;font-weight:600;color:#0f172a;">{{ $solicitud->tipo_emergencia ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0;color:#64748b;">Personas afectadas</td>
                            <td style="padding:8px 0;font-weight:600;color:#0f172a;">{{ $solicitud->cantidad_personas ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0;color:#64748b;">Insumos requeridos</td>
                            <td style="padding:8px 0;font-weight:600;color:#0f172a;">{{ $solicitud->insumos_necesarios ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0;color:#64748b;">Comunidad</td>
                            <td style="padding:8px 0;font-weight:600;color:#0f172a;">{{ $destino->comunidad ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0;color:#64748b;">Provincia</td>
                            <td style="padding:8px 0;font-weight:600;color:#0f172a;">{{ $destino->provincia ?? '—' }}</td>
                        </tr>
                    </table>
                </div>

                <div style="margin-bottom:24px;">
                    <h3 style="margin:0 0 12px;font-size:16px;color:#0f172a;">Datos del solicitante</h3>
                    <table role="presentation" cellspacing="0" cellpadding="0" style="width:100%;border-collapse:collapse;font-size:14px;">
                        <tr>
                            <td style="padding:8px 0;color:#64748b;width:45%;">Nombre completo</td>
                            <td style="padding:8px 0;font-weight:600;color:#0f172a;">{{ trim(($solicitud->nombre ?? '').' '.($solicitud->apellido ?? '')) ?: '—' }}</td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0;color:#64748b;">Correo electrónico</td>
                            <td style="padding:8px 0;font-weight:600;color:#0f172a;">{{ $solicitante->email ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0;color:#64748b;">Número de celular</td>
                            <td style="padding:8px 0;font-weight:600;color:#0f172a;">{{ $solicitante->telefono ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0;color:#64748b;">Carnet de identidad</td>
                            <td style="padding:8px 0;font-weight:600;color:#0f172a;">{{ $solicitante->ci ?? '—' }}</td>
                        </tr>
                    </table>
                </div>

                <p style="margin:0 0 20px;font-size:14px;color:#475569;">Si necesitas corregir algún dato, puedes ingresar nuevamente a tu solicitud mediante el siguiente enlace:</p>

                <div style="text-align:center;margin-bottom:18px;">
                    <a href="{{ route('solicitud.public.edit', $solicitud->codigo_seguimiento) }}"
                       style="display:inline-block;padding:12px 22px;background-color:#2563eb;color:#ffffff;text-decoration:none;border-radius:8px;font-weight:600;font-size:14px;">
                        Editar mi solicitud
                    </a>
                </div>
            </div>

            <div style="padding:16px 24px;background:#f7fafc;border-top:1px solid #e5e9f2;font-size:12px;color:#61718a;">
                <p style="margin:0;">Responderemos a la brevedad. Gracias por confiar en DAS - Alas Chiquitanas.</p>
            </div>
        </div>
    </div>
</body>
</html>
