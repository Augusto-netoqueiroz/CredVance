<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $table = 'email_templates';

    protected $fillable = [
        'nome',
        'tipo',
        'assunto_padrao',
        'corpo_html',
        'ativo',
    ];

    public $timestamps = false; // Ajuste se usar timestamps automáticos

    public function emails()
    {
        return $this->hasMany(Email::class, 'email_template_id');
    }
}
