<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estado de solicitud actualizado</title>
</head>
<body style="margin:0;padding:24px;background-color:#f2f5f9;font-family:'Segoe UI',Tahoma,Arial,sans-serif;color:#1f2933;">
    <div style="max-width:620px;margin:0 auto;">
        <div style="background:#ffffff;border-radius:14px;box-shadow:0 8px 24px rgba(15,35,95,0.10);border:1px solid #e5e9f2;overflow:hidden;">
            @php
                $estadoUpper = strtoupper($solicitud->estado ?? '');
                $isNegada = strtolower($solicitud->estado ?? '') === 'negada';
                $isAprobada = strtolower($solicitud->estado ?? '') === 'aprobada';
                $gradient = $isAprobada ? 'linear-gradient(120deg,#22c55e,#15803d)' : ($isNegada ? 'linear-gradient(120deg,#ef4444,#b91c1c)' : 'linear-gradient(120deg,#f59e0b,#c2410c)');
            @endphp
            <div style="background:{{ $gradient }}; padding:22px 28px;color:#ffffff;">
                <h2 style="margin:0;font-size:20px;font-weight:700;">Actualizamos tu solicitud</h2>
                <p style="margin:6px 0 0;font-size:14px;opacity:0.9;">Código: <strong style="color:#ffffff;">{{ $solicitud->codigo_seguimiento ?? '—' }}</strong></p>
            </div>

            <div style="padding:24px 28px 12px;">
                @php
                    $solicitante = optional($solicitud->solicitante);
                @endphp

                <p style="margin-top:0;margin-bottom:16px;font-size:15px;">Hola {{ trim(($solicitante->nombre ?? '').' '.($solicitante->apellido ?? '')) ?: 'amigo/a' }}, te informamos que tu solicitud cambió de estado.</p>

                <div style="margin-bottom:22px;">
                    <h3 style="margin:0 0 12px;font-size:16px;color:#0f172a;">Nuevo estado</h3>
                    <table role="presentation" cellspacing="0" cellpadding="0" style="width:100%;border-collapse:collapse;font-size:14px;">
                        <tr>
                            <td style="padding:8px 0;color:#64748b;width:45%;">Estado actual</td>
                            <td style="padding:8px 0;font-weight:700;color:#0f172a;">{{ $estadoUpper ?: '—' }}</td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0;color:#64748b;">Fecha de actualización</td>
                            <td style="padding:8px 0;font-weight:600;color:#0f172a;">{{ optional($solicitud->updated_at)->format('d/m/Y') ?? now()->format('d/m/Y') }}</td>
                        </tr>
                    </table>
                </div>

                @if($isNegada && $solicitud->justificacion)
                    <div style="margin-bottom:22px;">
                        <h3 style="margin:0 0 12px;font-size:16px;color:#b91c1c;">Motivo de la decisión</h3>
                        <div style="padding:16px 18px;background:#fef2f2;border-radius:10px;border:1px solid #fecaca;font-size:14px;color:#7f1d1d;">
                            {{ $solicitud->justificacion }}
                        </div>
                    </div>
                @endif

                <p style="margin:0 0 18px;font-size:14px;color:#475569;">Puedes revisar el detalle completo y realizar seguimiento a través del siguiente enlace:</p>

                <div style="text-align:center;margin-bottom:18px;">
                    <a href="{{ route('solicitud.public.show', $solicitud->codigo_seguimiento) }}"
                       style="display:inline-block;padding:12px 22px;background-color:#0ea5e9;color:#ffffff;text-decoration:none;border-radius:8px;font-weight:600;font-size:14px;">
                        Ver mi solicitud
                    </a>
                </div>
            </div>

            <div style="padding:16px 24px;background:#f7fafc;border-top:1px solid #e5e9f2;font-size:12px;color:#61718a;">
                <p style="margin:0;">Gracias por confiar en DAS - Alas Chiquitanas. Seguimos atentos a cualquier consulta.</p>
            </div>
        </div>
    </div>
</body>
</html>
