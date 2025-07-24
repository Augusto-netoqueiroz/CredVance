<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWhatsappTextJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $token = 'wZmkXG+oiDJZiPsH0au3OufdoNvctT7d79Glxo0Hcr2v';
    protected string $number;
    protected string $message;

    public function __construct(string $number, string $message)
    {
        $this->number = $number;
        $this->message = $message;
    }

    public function handle()
    {
        Log::info('Enviando mensagem de texto');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Content-Type' => 'application/json',
        ])->post('https://chat.fttelecom.cloud:443/backend/api/messages/send', [
            'number' => $this->number,
            'body' => $this->message,
            'saveOnTicket' => true,
            'linkPreview' => true,
        ]);

        if ($response->successful()) {
            Log::info('Mensagem de texto enviada com sucesso', ['response' => $response->json()]);
        } else {
            Log::error('Erro ao enviar mensagem de texto', ['status' => $response->status(), 'response' => $response->body()]);
        }
    }
}
