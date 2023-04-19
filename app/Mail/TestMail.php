<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $objEnviarMail;
    public $listaProductosXProveedorMail;
    public function __construct($objEnviarMail, $listaProductosXProveedorMail)
    {
        $this->objEnviarMail = $objEnviarMail;
        $this->listaProductosXProveedorMail = $listaProductosXProveedorMail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'), env('APP_NAME'))
                    ->view('testmail')
                    ->subject('Solicitud de presupuestación')
                    ->with('objEnviarMail', $this->objEnviarMail)
                    ->with('listaProductosXProveedorMail', $this->listaProductosXProveedorMail);
    }
}
