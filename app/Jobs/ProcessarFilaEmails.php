<?php

namespace App\Jobs;

use App\Models\FilaEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessarFilaEmails implements ShouldQueue
{
    public function handle()
    {
        // Agora só pega os emails pendentes do tipo boleto, com trim e lower para evitar espaços e maiúsculas
        $emails = FilaEmail::where('status', 'pendente')
            ->whereRaw('LOWER(TRIM(tipo)) = ?', ['boleto'])
            ->where(function($q) {
                $q->whereNull('agendado_em')->orWhere('agendado_em', '<=', now());
            })
            ->limit(10)
            ->get();

        Log::info('Emails pendentes do tipo boleto encontrados para envio:', $emails->pluck('id')->toArray());

        foreach ($emails as $email) {
            Log::info("Iniciando envio do email ID {$email->id} para {$email->email_destino}");

            try {
                $email->status = 'enviando';
                $email->ultima_tentativa = now();
                $email->tentativas++;
                $email->save();

                // Dados para a view e tracking pixel
                $dados = $email->dados_extras ?? [];
                $dados['emailId'] = $email->id;
                $dados['trackingPixelUrl'] = route('email.opened', ['emailId' => $email->id]);

                $this->enviarEmailBoleto($email, $dados);

                $email->status = 'enviado';
                $email->enviado_em = now();
                $email->erro = null;
                $email->save();

                Log::info("Email ID {$email->id} enviado com sucesso para {$email->email_destino}");
            } catch (\Exception $e) {
                Log::error("Erro ao enviar email ID {$email->id}: " . $e->getMessage());

                $email->status = 'erro';
                $email->erro = $e->getMessage();
                $email->save();
            }
        }
    }

    public function enviarEmailBoleto(FilaEmail $email, array $dados)
    {
        Mail::send('emails.boleto_enviado', $dados, function ($message) use ($email) {
            $message->to($email->email_destino)
                ->subject($email->assunto ?? 'Boleto disponível');

            if (!empty($email->dados_extras['pdf'])) {
                $pdfPath = storage_path('app/private/' . $email->dados_extras['pdf']);
                if (file_exists($pdfPath)) {
                    $message->attach($pdfPath);
                }
            }
        });
    }
}
