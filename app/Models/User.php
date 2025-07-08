<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'cpf',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'uf',
        'cep',
        'telefone',
        'otp_code',
        'otp_expires_at',
        'password',
        'role',
        'ativo',
        'parceiro_id',
    ];

    public function cliente()
    {
        return $this->hasOne(Cliente::class);
    }

    public function parceiro()
    {
        return $this->belongsTo(Parceiro::class);
    }

    public function contratos()
    {
        return $this->hasMany(Contrato::class, 'cliente_id');
    }

    public function parceiroResponsavel()
    {
        return $this->hasOne(Parceiro::class, 'user_id');
    }
}
