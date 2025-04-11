<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $verificationLink;

    /**
     * Cria uma nova instância da classe.
     *
     * @param string $verificationLink
     */
    public function __construct($verificationLink)
    {
        $this->verificationLink = $verificationLink;
    }

    /**
     * Monta o e-mail.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Verifique seu e-mail')
                    ->view('emails.verification');
    }
}
