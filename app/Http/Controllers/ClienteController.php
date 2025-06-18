<?php

namespace App\Http\Controllers;

use App\Models\Pagamento;
use App\Models\Contrato;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ClienteController extends Controller
{
    /**
     * Exibe a view inicial do cliente (blade).
     */
    public function index()
    {
        // A view carrega dados via AJAX chamando data()
        return view('cliente.area');
    }

    /**
     * Retorna JSON para alimentar a dashboard do cliente via AJAX.
     */
   public function data(): JsonResponse
{
    $user = Auth::user();

    // Buscar pagamentos do cliente, selecionando também o campo 'boleto_path'
    $pagamentosRaw = Pagamento::whereHas('contrato', function($q) use ($user) {
            $q->where('cliente_id', $user->id);
        })
        ->orderBy('vencimento')
        ->get(['id', 'vencimento', 'valor', 'status', 'boleto_path']);

    $pagamentos = $pagamentosRaw->map(function($p) {
        $url = null;
        if ($p->boleto_path) {
            // Verifica existência em storage/app/private/boletos via disco 'boletos'
            if (Storage::disk('boletos')->exists($p->boleto_path)) {
                // Rota de download: ajuste o nome se necessário
                $url = route('pagamentos.download-boleto', ['pagamento' => $p->id]);
            } else {
                // Log de aviso: não encontrou no disco boletos
                Log::warning("ClienteController@data: boleto não encontrado em disk 'boletos': '{$p->boleto_path}'");
            }
        }
        return [
            'id'         => $p->id,
            'vencimento' => is_object($p->vencimento)
                            ? $p->vencimento->format('d/m/Y')
                            : $p->vencimento,
            'valor'      => number_format($p->valor, 2, ',', '.'),
            'status'     => $p->status,
            'boleto_url' => $url,
        ];
    });

    // Contratos com PDF de contrato (mantém conforme antes)
    $contratos = Contrato::where('cliente_id', $user->id)
        ->whereNotNull('pdf_contrato')
        ->orderByDesc('id')
        ->get(['id', 'created_at', 'pdf_contrato']);

    $documentos = $contratos->map(function ($contrato) {
        return [
            'id'   => $contrato->id,
            'data' => $contrato->created_at->format('d/m/Y'),
            'url'  => route('cliente.contrato.download', ['contrato' => $contrato->id]),
        ];
    });

    // Indicadores de parcelas abertas e pagas
    $parcela_aberto  = $pagamentosRaw->where('status', 'pendente')->count();
    $parcela_paga    = $pagamentosRaw->where('status', 'pago')->count();
    $proxima_raw     = $pagamentosRaw->firstWhere('status', 'pendente');
    $proxima_parcela = $proxima_raw
                         ? [
                             'vencimento' => is_object($proxima_raw->vencimento)
                                                ? $proxima_raw->vencimento->format('m/Y')
                                                : $proxima_raw->vencimento,
                             'valor'      => number_format($proxima_raw->valor, 2, ',', '.'),
                           ]
                         : null;

    // Busca status do consórcio direto no contrato mais recente do usuário
    $status_consorcio = Contrato::where('cliente_id', $user->id)
        ->orderByDesc('id')
        ->value('status');

    return response()->json([
        'parcela_aberto'   => $parcela_aberto,
        'parcela_paga'     => $parcela_paga,
        'proxima_parcela'  => $proxima_parcela,
        'status_consorcio' => $status_consorcio,
        'pagamentos'       => $pagamentos,
        'documentos'       => $documentos,
    ]);
}


    /**
     * Download do PDF do boleto, usando disco privado 'boletos'.
     */
    public function downloadBoleto(Pagamento $pagamento)
    {
        $user = Auth::user();

        // Verifica se pertence ao cliente logado
        if ($pagamento->contrato->cliente_id !== $user->id) {
            abort(403, 'Acesso negado.');
        }

        $path = $pagamento->boleto_path; // ex: "2025/06/boleto_123.pdf" ou "boleto_123.pdf"
        if (! $path) {
            abort(404, "Caminho de boleto não definido para Pagamento ID {$pagamento->id}.");
        }

        // Verifica existência no disco 'boletos' (root = storage/app/private/boletos)
        if (! Storage::disk('boletos')->exists($path)) {
            Log::error("ClienteController@downloadBoleto: boleto não encontrado em disk 'boletos': '{$path}' para Pagamento ID {$pagamento->id}");
            abort(404, "Boleto não encontrado.");
        }

        Log::info("Cliente {$user->id} baixou boleto Pagamento ID {$pagamento->id} em private/boletos/{$path}");
        // Nome para download
        $filename = "boleto_{$pagamento->id}.pdf";
        return Storage::disk('boletos')->download($path, $filename);
    }


    public function downloadContrato(Contrato $contrato)
    {
        $user = Auth::user();

        // Verifica se o contrato pertence ao cliente logado
        if ($contrato->cliente_id !== $user->id) {
            abort(403, 'Acesso negado.');
        }

        // Verifica se o arquivo existe no disco 'local' ou 'public', conforme onde foi salvo
        // Supondo que pdf_contrato é algo como "contratos/contrato_58.pdf" e foi salvo em storage/app ou storage/app/public
        // Ajuste conforme onde armazena: se for storage/app, use disk('local'); se storage/app/public, use disk('public').
        $disk = Storage::disk('local');
        if (! $disk->exists($contrato->pdf_contrato)) {
            // caso não exista em local, tenta em public
            $disk = Storage::disk('public');
            if (! $disk->exists($contrato->pdf_contrato)) {
                abort(404, 'Arquivo não encontrado.');
            }
        }

        // Retorna o download
        return $disk->download(
            $contrato->pdf_contrato,
            "contrato_{$contrato->id}.pdf"
        );
    }

    

}
