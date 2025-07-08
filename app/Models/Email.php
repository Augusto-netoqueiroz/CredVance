<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $table = 'emails';

    protected $fillable = [
        'email_template_id',
        'email_destino',
        'assunto',
        'corpo_customizado',
        'dados_json',
        'status',
        'agendado_em',
        'enviado_em',
        'erro',
        'tentativas',
        'visualizado_em',
    ];

    // Define colunas personalizadas para timestamps
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'atualizado_em';

    public $timestamps = true; // ativa timestamps

    protected $casts = [
        'dados_json' => 'array',
        'agendado_em' => 'datetime',
        'enviado_em' => 'datetime',
        'visualizado_em' => 'datetime',
        'criado_em' => 'datetime',
        'atualizado_em' => 'datetime',
    ];

    public function template()
    {
        return $this->belongsTo(EmailTemplate::class, 'email_template_id');
    }

    public function trackings()
    {
        return $this->hasMany(EmailTracking::class, 'email_id');
    }

    public function cliente()
{
    return $this->belongsTo(User::class, 'cliente_id');
}

public function contrato()
{
    return $this->belongsTo(Contrato::class, 'contrato_id');
}

 public function pagamentos()
{
    return $this->hasMany(Pagamento::class, 'contrato_id');
}
}
