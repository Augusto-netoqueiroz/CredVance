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
    ];

    public function cliente()
    {
        return $this->hasOne(Cliente::class);
    }
}
