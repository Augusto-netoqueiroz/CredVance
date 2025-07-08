<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BoletoReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $boleto;
    public $cliente;
    public $contrato;

    public function __construct($boleto)
    {
        $this->boleto = $boleto;

        // Criando os objetos para passar para a view
        $this->cliente = (object) ['name' => $boleto->cliente_nome ?? 'Cliente'];
        $this->contrato = (object) ['id' => $boleto->contrato_id ?? 'N/A'];
    }

   public function build()
{
    $path = storage_path('app/private/' . $this->boleto->boleto_path);

    $mail = $this->subject('Seu boleto está disponível para pagamento')
        ->view('emails.boleto_enviado')
        ->with([
            'boleto' => $this->boleto,
            'cliente' => $this->cliente,
            'contrato' => $this->contrato,
        ]);

    if ($this->boleto->boleto_path && file_exists($path)) {
        $mail->attach($path, [
            'as' => 'boleto-contrato-' . $this->boleto->contrato_id . '.pdf',
            'mime' => 'application/pdf',
        ]);
    } else {
        \Log::warning('Arquivo de boleto não encontrado: ' . $path);
    }

    return $mail;
}

}


