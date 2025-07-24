<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWhatsappMediaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $token = 'wZmkXG+oiDJZiPsH0au3OufdoNvctT7d79Glxo0Hcr2v';
    protected string $number;
    protected string $mediaPath;

    public function __construct(string $number, string $mediaPath)
    {
        $this->number = $number;
        $this->mediaPath = $mediaPath;
    }

    public function handle()
    {
        Log::info('Enviando mídia');

        if (!file_exists($this->mediaPath) || !is_readable($this->mediaPath)) {
            Log::error('Arquivo de mídia não existe ou não é legível', ['mediaPath' => $this->mediaPath]);
            return;
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->attach(
            'medias', file_get_contents($this->mediaPath), basename($this->mediaPath)
        )->post('https://chat.fttelecom.cloud:443/backend/api/messages/send', [
            'number' => $this->number,
            'saveOnTicket' => 'true',
        ]);

        if ($response->successful()) {
            Log::info('Mídia enviada com sucesso', ['response' => $response->json()]);
        } else {
            Log::error('Erro ao enviar mídia', ['status' => $response->status(), 'response' => $response->body()]);
        }
    }
}
