<?php

namespace App\Mail;

use App\Models\Paquete;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
class CodigoEntregaPaquete extends Mailable
{
    use Queueable, SerializesModels;

    public Paquete $paquete;
    public string $codigo;

    public function __construct(Paquete $paquete, string $codigo)
    {
        $this->paquete = $paquete;
        $this->codigo  = $codigo;
    }

    public function build()
    {
        return $this
            ->subject("Código de validación para entrega de tu paquete {$this->paquete->solicitud->codigo_seguimiento}")
            ->view('email.codigo_entrega_paquete');
    }
}
