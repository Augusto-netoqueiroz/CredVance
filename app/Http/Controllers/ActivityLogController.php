<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Illuminate\Support\Carbon;

class ActivityLogController extends Controller
{
    /**
     * Exibe a lista de logs de atividade.
     */
    public function index(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
        return redirect()
            ->route('Inicio')
            ->with('error', 'Você não tem permissão para acessar essa página');
    }
        $logs = ActivityLog::orderBy('data', 'desc')->paginate(15);

        return view('activity_logs.index', compact('logs'));
    }
}
