<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLoggerService
{
    public static function registrar(string $modulo, string $descricao)
    {
        $user = Auth::user();

        ActivityLog::create([
            'nome'     => $user->name ?? 'Sistema',
            'ip'       => Request::ip(),
            'modulo'   => $modulo,
            'descricao'=> $descricao,
            'data'     => now(),
        ]);
    }
}
