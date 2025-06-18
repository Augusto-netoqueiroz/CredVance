<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagamento extends Model
{
    use HasFactory;

    // Campos atribuíveis
    protected $fillable = [
        'contrato_id',
        'vencimento',
        'valor',
        'boleto',
        'comprovante',
        'status',
        // Novos campos:
        'codigo_solicitacao',
        'nosso_numero',
        'status_solicitacao',
        'data_emissao',
        'data_pagamento',
        'data_cancelamento',
        'linha_digitavel',
        'url_boleto',
        'boleto_path',
        'json_resposta',
        'tentativas',
        'webhook_recebido',
        'error_message',
    ];

    // Casts para tipos nativos
    protected $casts = [
        'vencimento' => 'date',
        'data_emissao' => 'datetime',
        'data_pagamento' => 'datetime',
        'data_cancelamento' => 'datetime',
        'webhook_recebido' => 'boolean',
        'tentativas' => 'integer',
    ];

    // Relação com Contrato
    public function contrato()
    {
        return $this->belongsTo(Contrato::class);
    }

    // Outros relacionamentos, escopos, etc.
}
