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
        // Para debug, descomente esta linha e acesse a rota para ver se chega aqui:
        // dd('Entrou em ActivityLogController@index');

        // Pega os logs mais recentes, paginando de 15 em 15
        $logs = ActivityLog::orderBy('data', 'desc')->paginate(15);

        return view('activity_logs.index', compact('logs'));
    }
}
