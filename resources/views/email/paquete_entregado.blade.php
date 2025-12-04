<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <title>Paquete entregado</title>
</head>
<body style="margin:0;padding:24px;background-color:#f2f5f9;font-family:'Segoe UI',Tahoma,Arial,sans-serif;color:#1f2933;">
   <div style="max-width:620px;margin:0 auto;">
      <div style="background:#ffffff;border-radius:14px;box-shadow:0 8px 24px rgba(15,35,95,0.10);border:1px solid #e5e9f2;overflow:hidden;">
         <div style="background:linear-gradient(120deg,#22c55e,#0f9d58);padding:22px 28px;color:#ffffff;">
            <h2 style="margin:0;font-size:20px;font-weight:700;">¡Tu paquete ha sido entregado!</h2>
            <p style="margin:6px 0 0;font-size:14px;opacity:0.9;">Código: <strong style="color:#ffffff;">{{ $paquete->codigo ?? '—' }}</strong></p>
         </div>

         <div style="padding:24px 28px 10px;">
            @php
               $solicitante = optional(optional($paquete->solicitud)->solicitante);
               $destino = optional(optional($paquete->solicitud)->destino);
               $fechaEntregaRaw = $paquete->fecha_entrega;
               if ($fechaEntregaRaw instanceof \Carbon\Carbon) {
                  $fechaEntregaFormatted = $fechaEntregaRaw->format('d/m/Y');
               } elseif (!empty($fechaEntregaRaw)) {
                  try {
                     $fechaEntregaFormatted = \Carbon\Carbon::parse($fechaEntregaRaw)->format('d/m/Y');
                  } catch (\Exception $e) {
                     $fechaEntregaFormatted = $fechaEntregaRaw;
                  }
               } else {
                  $fechaEntregaFormatted = '—';
               }
            @endphp

            <p style="margin-top:0;margin-bottom:16px;font-size:15px;">
               Hola {{ trim(($solicitante->nombre ?? '').' '.($solicitante->apellido ?? '')) ?: 'amigo/a' }}, queremos contarte que el equipo de Alas Chiquitanas completó la entrega de tu donación.
            </p>

            <div style="margin-bottom:22px;">
               <h3 style="margin:0 0 12px;font-size:16px;color:#0f172a;">Resumen de entrega</h3>
               <table role="presentation" cellspacing="0" cellpadding="0" style="width:100%;border-collapse:collapse;font-size:14px;">
                  <tr>
                            <td style="padding:8px 0;color:#64748b;width:45%;">Fecha de entrega</td>
                            <td style="padding:8px 0;font-weight:600;color:#0f172a;">{{ $fechaEntregaFormatted }}</td>
                  </tr>
                  <tr>
                     <td style="padding:8px 0;color:#64748b;">Estado final</td>
                     <td style="padding:8px 0;font-weight:600;color:#0f172a;">Entregado</td>
                  </tr>
                  <tr>
                     <td style="padding:8px 0;color:#64748b;">Comunidad beneficiada</td>
                     <td style="padding:8px 0;font-weight:600;color:#0f172a;">{{ $destino->comunidad ?? '—' }}</td>
                  </tr>
                  <tr>
                     <td style="padding:8px 0;color:#64748b;">Provincia</td>
                     <td style="padding:8px 0;font-weight:600;color:#0f172a;">{{ $destino->provincia ?? '—' }}</td>
                  </tr>
               </table>
            </div>

            <p style="margin:0 0 18px;font-size:15px;">Gracias por confiar en nosotros y permitirnos ayudar. ¡Tu apoyo marca la diferencia!</p>
         </div>

         <div style="padding:16px 24px;background:#f7fafc;border-top:1px solid #e5e9f2;font-size:12px;color:#61718a;">
            <p style="margin:0;">Si tienes preguntas, responde a este correo o contáctanos por nuestros canales habituales.</p>
         </div>
      </div>
   </div>
</body>
</html>
