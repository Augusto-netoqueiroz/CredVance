<?php

namespace App\Http\Controllers;

use App\Models\Parceiro;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ParceiroController extends Controller
{
    public function redirect($slug)
    {
        $parceiro = Parceiro::where('slug', $slug)->firstOrFail();

        $parceiro->acessos()->create([
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'evento' => 'acesso'
        ]);

        return view('landing.parceiro', compact('parceiro'));
    }

    public function index()
{
    $user = Auth::user();

    $parceiros = Parceiro::where('user_id', $user->id)
        ->withCount(['acessos', 'usuarios'])
        ->get();

    $cotasPorParceiro = DB::table('contratos')
        ->join('users', 'contratos.cliente_id', '=', 'users.id')
        ->whereIn('users.parceiro_id', $parceiros->pluck('id'))
        ->select('users.parceiro_id', DB::raw('SUM(contratos.quantidade_cotas) as total_cotas'))
        ->groupBy('users.parceiro_id')
        ->pluck('total_cotas', 'users.parceiro_id');

    return view('parceiro.index', compact('parceiros', 'cotasPorParceiro'));
}


public function metrics(Parceiro $parceiro)
{
    $user = Auth::user();

    // Verifica se o parceiro realmente pertence ao usuário logado
    if ($parceiro->user_id !== $user->id) {
        abort(403, 'Você não tem permissão para ver essas métricas.');
    }

    $totalAcessos = $parceiro->acessos()->where('evento', 'acesso')->count();
    $cadastros = $parceiro->acessos()->where('evento', 'cadastro')->count();
    $usuarios = User::where('parceiro_id', $parceiro->id)->get();

    $cotasVendidas = DB::table('contratos')
        ->join('users', 'contratos.cliente_id', '=', 'users.id')
        ->where('users.parceiro_id', $parceiro->id)
        ->sum('contratos.quantidade_cotas');

    $acessos = $parceiro->acessos()
        ->where('evento', 'acesso')
        ->orderBy('created_at', 'desc')
        ->limit(50)
        ->get();

    $horas = range(0, 23);
    $accessPerHour = [];
    foreach ($horas as $hour) {
        $accessPerHour[$hour] = $parceiro->acessos()
            ->where('evento', 'acesso')
            ->whereBetween('created_at', [now()->subDays(7)->startOfDay(), now()])
            ->whereRaw('HOUR(created_at) = ?', [$hour])
            ->count();
    }

    $accessPerDay = [];
    for ($i = 29; $i >= 0; $i--) {
        $date = now()->subDays($i)->format('Y-m-d');
        $accessPerDay[$date] = $parceiro->acessos()
            ->where('evento', 'acesso')
            ->whereDate('created_at', $date)
            ->count();
    }

    return view('parceiro.metrics', compact(
        'parceiro',
        'totalAcessos',
        'cadastros',
        'cotasVendidas',
        'usuarios',
        'acessos',
        'accessPerHour',
        'accessPerDay'
    ));
}


}
