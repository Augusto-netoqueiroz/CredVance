<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BoletoLog;

class BoletoLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = BoletoLog::with(['cliente', 'pagamento'])
            ->orderByDesc('enviado_em')
            ->paginate(30);

        return view('logs.boleto_logs', compact('logs'));
    }
}
