<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pagamento extends Model
{
    protected $fillable = [
        'contrato_id',
        'vencimento',
        'valor',
        'status',
    ];

    public function contrato()
    {
        return $this->belongsTo(Contrato::class);
    }
}
