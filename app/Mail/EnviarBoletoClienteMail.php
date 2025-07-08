<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Pagamento;

class EnviarBoletoClienteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pagamento;

    /**
     * Create a new message instance.
     */
    public function __construct(Pagamento $pagamento)
    {
        $this->pagamento = $pagamento;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Seu boleto está disponível para pagamento')
                    ->markdown('emails.boletos.enviar');
    }
}
