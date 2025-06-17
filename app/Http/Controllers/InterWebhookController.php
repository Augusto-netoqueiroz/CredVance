<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Cobranca; // seu modelo para armazenar
use RuntimeException;

class InterWebhookController extends Controller
{
    public function handleCobranca(Request $request)
    {
        $payload = $request->json()->all();
        Log::info('Webhook Cobrança recebido', ['payload' => $payload]);

        // Exemplo: supondo que você tenha uma tabela "cobrancas" com coluna codigoSolicitacao etc.
        if (!isset($payload['codigoSolicitacao'])) {
            Log::warning('Webhook sem codigoSolicitacao', ['payload'=>$payload]);
            return response()->json(['error'=>'codigoSolicitacao ausente'], 400);
        }
        $codigo = $payload['codigoSolicitacao'];

        // Salve ou atualize
        Cobranca::updateOrCreate(
            ['codigoSolicitacao' => $codigo],
            [
                'linhaDigitavel' => $payload['linhaDigitavel'] ?? null,
                'codigoBarras'   => $payload['codigoBarras'] ?? null,
                'dataVencimento' => $payload['dataVencimento'] ?? null,
                'valorNominal'   => $payload['valorNominal'] ?? null,
                'status'         => $payload['status'] ?? null,
                // se vier links:
                'linkBoleto'     => data_get($payload, 'links.0.href'),
                // outros campos conforme payload
            ]
        );

        return response()->json(['received'=>true], 200);
    }
}
