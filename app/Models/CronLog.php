<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CronLog extends Model
{
    protected $table = 'cron_log';

    protected $fillable = [
        'command',
        'output',
        'status',
        'executed_at',
    ];

    public $timestamps = true; // created_at, updated_at
}
