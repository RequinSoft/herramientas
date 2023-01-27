<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPass extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = "Cambio de Contraseña";
    public $folio, $pass;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($folio, $pass)
    {
        //
        $this->folio = $folio;
        $this->pass = $pass;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.reset_pass');
    }
}
