<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class QrCodeController extends Controller
{
    public function index()
    {
        $response = Http::get('http://23.97.106.141:3333/qrcode', [
            'sessionName' => 'EMBRACON_GERAL'
        ]);

        if ($response->failed()) {
            abort(500, 'Erro ao buscar QR Code da API externa.');
        }

        $data = $response->json();

        // Agora o qrcode está diretamente no campo 'qrcode'
        $qrcode = $data['qrcode'] ?? null;

        return view('qrcode', compact('qrcode'));
    }
}
