<?php

namespace App\Mail;

use App\Models\Contrato;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ContratoCriadoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $contrato;

    public function __construct(Contrato $contrato)
    {
        $this->contrato = $contrato;
    }

    public function build()
    {
        $mail = $this->subject('Seu contrato foi criado!')
                     ->view('emails.contrato_criado')
                     ->with(['contrato' => $this->contrato]);

        // Anexa o PDF se existir
        if ($this->contrato->pdf_contrato && Storage::exists($this->contrato->pdf_contrato)) {
            $mail->attach(Storage::path($this->contrato->pdf_contrato), [
                'as' => 'Contrato.pdf',
                'mime' => 'application/pdf',
            ]);
        }
        return $mail;
    }
}
