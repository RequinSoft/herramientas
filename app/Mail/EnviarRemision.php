<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EnviarRemision extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = "Remisión Enviada";
    public $folio;
    public $referencia;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($folio, $referencia)
    {
        //
        $this->folio = $folio;
        $this->referencia = $referencia;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.enviarremision');
    }
}
