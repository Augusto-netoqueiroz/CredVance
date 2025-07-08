<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoletoLog extends Model
{
    protected $table = 'boleto_logs';

    protected $fillable = [
        'pagamento_id',
        'contrato_id',
        'cliente_id',
        'enviado',
        'enviado_em',
        'aberto',
        'aberto_em',
    ];

    protected $casts = [
        'enviado' => 'boolean',
        'aberto' => 'boolean',
        'enviado_em' => 'datetime',
        'aberto_em' => 'datetime',
    ];

public function contrato()
{
    return $this->belongsTo(Contrato::class, 'contrato_id');
}

public function cliente()
{
    return $this->belongsTo(User::class, 'cliente_id');
}

public function pagamento()
{
    return $this->belongsTo(Pagamento::class, 'pagamento_id');
}


}

