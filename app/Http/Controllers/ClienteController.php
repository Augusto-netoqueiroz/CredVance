<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pagamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Contrato; 

class ClienteController extends Controller
{
    /**
     * Exibe a view inicial do cliente (blade).
     */
    public function index()
    {
        // não precisamos passar $pagamentos aqui
        // pois a view carrega tudo via AJAX em data()
        return view('cliente.area');
    }

    /**
     * Retorna os dados JSON para alimentar a dashboard do cliente via AJAX.
     */
   // no topo, se ainda não tiver

public function data(): JsonResponse
{
    $user = Auth::user();

    // Buscar pagamentos
    $pagamentosRaw = Pagamento::whereHas('contrato', function($q) use ($user) {
            $q->where('cliente_id', $user->id);
        })
        ->orderBy('vencimento')
        ->get(['id', 'vencimento', 'valor', 'status', 'boleto']);

    $pagamentos = $pagamentosRaw->map(function($p) {
        $url = null;
        if ($p->boleto && Storage::disk('boletos')->exists($p->boleto)) {
            $url = route('boleto.download', ['pagamento' => $p->id]);
        }

        return [
            'id'         => $p->id,
            'vencimento' => is_object($p->vencimento)
                            ? $p->vencimento->format('m/Y')
                            : $p->vencimento,
            'valor'      => number_format($p->valor, 2, ',', '.'),
            'status'     => $p->status,
            'boleto_url' => $url,
        ];
    });

    // Buscar contratos com PDFs
    $contratos = Contrato::where('cliente_id', $user->id)
        ->whereNotNull('pdf_contrato')
        ->orderByDesc('id')
        ->get(['id', 'created_at', 'pdf_contrato']);

    $documentos = $contratos->map(function ($contrato) {
        return [
            'id' => $contrato->id,
            'data' => $contrato->created_at->format('d/m/Y'),
            'url' => route('cliente.contrato.download', ['contrato' => $contrato->id]),
        ];
    });

    // Indicadores
    $parcela_aberto   = $pagamentosRaw->where('status', 'pendente')->count();
    $parcela_paga     = $pagamentosRaw->where('status', 'pago')->count();
    $proxima_raw      = $pagamentosRaw->firstWhere('status', 'pendente');
    $proxima_parcela  = $proxima_raw
                         ? [
                             'vencimento' => $proxima_raw['vencimento'],
                             'valor'      => $proxima_raw['valor'],
                           ]
                         : null;
    $status_consorcio = $user->contrato->status ?? null;

    return response()->json([
        'parcela_aberto'   => $parcela_aberto,
        'parcela_paga'     => $parcela_paga,
        'proxima_parcela'  => $proxima_parcela,
        'status_consorcio' => $status_consorcio,
        'pagamentos'       => $pagamentos,
        'documentos'       => $documentos,
    ]);
}


 

public function downloadContrato(Contrato $contrato)
{
    $user = auth()->user();

    // Verifica se o contrato pertence ao cliente logado
    if ($contrato->cliente_id !== $user->id) {
        abort(403, 'Acesso negado.');
    }

    // Verifica se o arquivo existe
    if (!Storage::disk('local')->exists($contrato->pdf_contrato)) {
        abort(404, 'Arquivo não encontrado.');
    }

    // Retorna o download
    return Storage::disk('local')->download(
        $contrato->pdf_contrato,
        "contrato_{$contrato->id}.pdf"
    );
}


}