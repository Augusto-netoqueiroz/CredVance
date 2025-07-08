<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTracking extends Model
{
    protected $table = 'email_tracking';

    protected $fillable = [
        'email_id',
        'tipo_evento',
        'ip',
        'user_agent',
    ];

    public $timestamps = false;

    public function email()
    {
        return $this->belongsTo(Email::class, 'email_id');
}
}
