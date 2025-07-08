<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Parceiro;
use App\Models\ParceiroAcesso;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParceiroAdminController extends Controller
{
    public function index()
    {
        // Total de cotas por parceiro
        $cotasPorParceiro = DB::table('contratos')
            ->join('users', 'contratos.cliente_id', '=', 'users.id')
            ->select('users.parceiro_id', DB::raw('SUM(contratos.quantidade_cotas) as total_cotas'))
            ->groupBy('users.parceiro_id')
            ->pluck('total_cotas', 'users.parceiro_id');

        $parceiros = Parceiro::withCount(['acessos', 'usuarios'])->get();

        return view('admin.parceiros.index', compact('parceiros', 'cotasPorParceiro'));
    }

    public function create()
    {
        // Listar todos os usuários para o admin escolher o responsável
        $users = User::all();

        return view('admin.parceiros.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'slug' => 'required|string|unique:parceiros,slug',
            'user_id' => 'required|exists:users,id',
        ]);

        Parceiro::create([
            'nome' => $request->nome,
            'slug' => $request->slug,
            'user_id' => $request->user_id,
        ]);

        return redirect()->route('admin.parceiros.index')->with('success', 'Parceiro criado com sucesso.');
    }

    public function metrics(Parceiro $parceiro)
    {
        $totalAcessos = $parceiro->acessos()->where('evento', 'acesso')->count();
        $cadastros = $parceiro->acessos()->where('evento', 'cadastro')->count();
        $usuarios = User::where('parceiro_id', $parceiro->id)->get();

        // Total de cotas vendidas
        $cotasVendidas = DB::table('contratos')
            ->join('users', 'contratos.cliente_id', '=', 'users.id')
            ->where('users.parceiro_id', $parceiro->id)
            ->sum('contratos.quantidade_cotas');

        // Últimos acessos
        $acessos = $parceiro->acessos()
            ->where('evento', 'acesso')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        // Gráfico: Acessos por hora (7 dias)
        $horas = range(0, 23);
        $accessPerHour = [];
        foreach ($horas as $hour) {
            $accessPerHour[$hour] = $parceiro->acessos()
                ->where('evento', 'acesso')
                ->whereBetween('created_at', [now()->subDays(7)->startOfDay(), now()])
                ->whereRaw('HOUR(created_at) = ?', [$hour])
                ->count();
        }

        // Gráfico: Acessos por dia (30 dias)
        $accessPerDay = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $accessPerDay[$date] = $parceiro->acessos()
                ->where('evento', 'acesso')
                ->whereDate('created_at', $date)
                ->count();
        }

        return view('admin.parceiros.metrics', compact(
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
