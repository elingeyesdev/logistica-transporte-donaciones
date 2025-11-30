<?php

namespace App\Mail;

use App\Models\Paquete;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaqueteEntregado extends Mailable
{
    use Queueable, SerializesModels;

    public Paquete $paquete;

    public function __construct(Paquete $paquete)
    {
        $this->paquete = $paquete;
    }

    public function build()
    {
        return $this
            ->subject("Confirmación de entrega de tu paquete {$this->paquete->codigo}")
            ->view('email.paquete_entregado');
    }
}
