<?php

namespace App\Mail;

use App\Models\Solicitud;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SolicitudRecibida extends Mailable
{
    use Queueable, SerializesModels;

    public Solicitud $solicitud;

    public function __construct(Solicitud $solicitud)
    {
        $this->solicitud = $solicitud;
    }

    public function build()
    {
        return $this
            ->subject('Confirmación de recepción de tu solicitud')
            ->view('email.solicitud_recibida');
    }
}
