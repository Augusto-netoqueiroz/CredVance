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
        'telefone',
        'password',
        'role',
        'ativo',
    ];

    public function cliente()
    {
        return $this->hasOne(Cliente::class);
    }
}
