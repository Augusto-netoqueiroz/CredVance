<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FinalizeRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $finishLink;

    public function __construct($finishLink)
    {
        $this->finishLink = $finishLink;
    }

    public function build()
        {
            return $this->subject('Finalize seu cadastro')
                        ->view('emails.finish-registration');
        }

}
