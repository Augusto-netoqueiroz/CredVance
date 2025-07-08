<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParceiroAcesso extends Model
{
    use HasFactory;

    protected $fillable = ['parceiro_id', 'ip', 'user_agent', 'evento'];

    public function parceiro()
    {
        return $this->belongsTo(Parceiro::class);
    }
}
