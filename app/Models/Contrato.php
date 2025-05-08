<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contrato extends Model
{
    protected $fillable = [
        'cliente_id',
        'consorcio_id',
        'status',
        'quantidade_cotas',
        'aceito_em',
        'ip',
        'navegador_info',
        'resolucao',
        'latitude',
        'longitude',
        'pdf_contrato',
    ];
    

    public function cliente(): BelongsTo
    {
        // relaciona com o model User
        return $this->belongsTo(\App\Models\User::class, 'cliente_id');
    }

    public function consorcio(): BelongsTo
    {
        return $this->belongsTo(Consorcio::class);
    }

    public function pagamentos(): HasMany
    {
        return $this->hasMany(Pagamento::class);
    }
}
