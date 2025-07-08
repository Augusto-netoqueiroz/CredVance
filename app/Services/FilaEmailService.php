<?php

namespace App\Services;

use App\Models\FilaEmail;

class FilaEmailService
{
    public static function fila(
        $tipo,
        $email_destino,
        $assunto,
        $corpo = null,
        $dados_extras = [],
        $agendado_em = null
    ) {
        return FilaEmail::create([
            'tipo' => $tipo,
            'email_destino' => $email_destino,
            'assunto' => $assunto,
            'corpo' => $corpo,
            'dados_extras' => $dados_extras,
            'agendado_em' => $agendado_em,
            'status' => 'pendente',
        ]);
    }
}
