<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parceiro extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'slug', 'user_id'];

    public function acessos()
    {
        return $this->hasMany(ParceiroAcesso::class);
    }

    public function usuarios()
    {
        return $this->hasMany(User::class);
    }

    public function dono()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
