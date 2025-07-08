<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DynamicEmail extends Mailable
{
    public $subject;
    public $bodyHtml;
    public $boletoPath;

    public function __construct($subject, $bodyHtml, $boletoPath = null)
    {
        $this->subject = $subject;
        $this->bodyHtml = $bodyHtml;
        $this->boletoPath = $boletoPath;
    }

   public function build()
{
    $email = $this->subject($this->subject)
                  ->html($this->bodyHtml);

    if ($this->boletoPath && file_exists($this->boletoPath)) {
        \Log::info('Anexando boleto: ' . $this->boletoPath);
        $email->attach($this->boletoPath, [
            'as' => 'boleto.pdf',
            'mime' => 'application/pdf',
        ]);
    } else {
        \Log::info('Boleto não anexado, arquivo não encontrado ou caminho inválido.');
    }

    return $email;
}

}

