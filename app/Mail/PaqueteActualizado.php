<?php

namespace App\Mail;

use App\Models\Paquete;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaqueteActualizado extends Mailable
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
            ->subject("Actualizacion de tu paquete {$this->paquete->codigo}")
            ->view('email.paquete_actualizado');
    }
}
