<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consorcio extends Model
{
    protected $fillable = [
        'plano',
        'prazo',
        'valor_total',
        'parcela_mensal',
        'juros',
        'valor_final',
    ];
}
