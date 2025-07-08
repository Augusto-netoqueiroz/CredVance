<?php


 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SistemaLog extends Model
{
    protected $table = 'sistema_logs';

    protected $fillable = [
        'modulo',
        'referencia_id',
        'acao',
        'mensagem',
        'resultado',
    ];

    public $timestamps = true;
}
