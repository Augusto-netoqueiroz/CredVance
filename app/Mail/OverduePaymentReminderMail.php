<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OverduePaymentReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $cobranca;

    public function __construct(array $cobranca)
    {
        $this->cobranca = $cobranca;
    }

public function build()
{
    return $this->subject('Aviso de Pagamento Vencido - CredVance')
                ->view('emails.overdue.custom_html')
                ->with(['cobranca' => $this->cobranca]);
}
}
