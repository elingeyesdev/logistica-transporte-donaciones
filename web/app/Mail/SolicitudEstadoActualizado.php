<?php

namespace App\Mail;

use App\Models\Solicitud;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SolicitudEstadoActualizado extends Mailable
{
    use Queueable, SerializesModels;

    public Solicitud $solicitud;

    public function __construct(Solicitud $solicitud)
    {
        $this->solicitud = $solicitud;
    }

    public function build()
    {
        $estado = $this->solicitud->estado;
        $codigo_seguimiento = $this->solicitud->codigo_seguimiento;
        $subject = match ($estado) {
            'aprobada' => "Tu solicitud ha sido aprobada",
            'negada'   => 'Tu solicitud ha sido negada',
            default    => "Actualizacion de tu solicitud ({$estado})",
        };

        return $this
            ->subject($subject)
            ->view('email.solicitud_estado_actualizado');
    }
}
