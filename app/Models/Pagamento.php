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
        'enviado_em', // NOVO
    ];

    // Casts para tipos nativos
    protected $casts = [
        'vencimento' => 'date',
        'data_emissao' => 'datetime',
        'data_pagamento' => 'datetime',
        'data_cancelamento' => 'datetime',
        'enviado_em' => 'datetime', // NOVO
        'webhook_recebido' => 'boolean',
        'tentativas' => 'integer',
    ];

    // Relação com Contrato
    public function contrato()
    {
        return $this->belongsTo(Contrato::class);
    }

    // Helper para acessar direto o cliente do pagamento
    public function cliente()
    {
        return $this->hasOneThrough(
            User::class,      // Model destino (users)
            Contrato::class,  // Model intermediário (contratos)
            'id',             // Chave primária de Contrato
            'id',             // Chave primária de User
            'contrato_id',    // Foreign key em Pagamento
            'cliente_id'      // Foreign key em Contrato (ajuste se o campo for diferente)
        );
    }
}
