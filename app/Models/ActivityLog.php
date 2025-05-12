<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'nome',
        'data',
        'ip',
        'modulo',
        'descricao',
    ];

    protected $casts = [
        'data' => 'datetime', // ✅ Isso converte automaticamente em objeto Carbon
    ];
}
