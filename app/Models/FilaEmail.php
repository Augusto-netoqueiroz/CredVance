<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FilaEmail extends Model
{
    protected $table = 'fila_emails';

    protected $fillable = [
        'tipo',
        'email_destino',
        'assunto',
        'corpo',
        'dados_extras',
        'agendado_em',
        'enviado_em',
        'ultima_tentativa',
        'erro',
        'tentativas',
        'status',
    ];

    protected $casts = [
        'dados_extras' => 'array',
        'agendado_em' => 'datetime',
        'enviado_em' => 'datetime',
        'ultima_tentativa' => 'datetime',
    ];
}
