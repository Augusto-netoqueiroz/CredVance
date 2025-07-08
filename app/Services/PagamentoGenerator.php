<?php
namespace App\Services;

use App\Models\Contrato;
use App\Models\Pagamento;
use Carbon\Carbon;
use App\Jobs\GerarBoletoJob;
use Illuminate\Support\Facades\Log;

class PagamentoGenerator
{
public function generate(Contrato $contrato): void
{
    $cons = $contrato->consorcio;
    $n    = (int)$cons->prazo;
    $a1   = (float)$cons->parcela_mensal;
    $an   = $n > 1 ? round((2 * $cons->valor_total / $n) - $a1, 2) : $a1;
    $step = $n > 1 ? round(($a1 - $an)/($n-1), 2) : 0;
    $q    = (int)$contrato->quantidade_cotas;

    $diaVenc = $contrato->dia_vencimento ?? 1; // Garanta que vem preenchido!

    $dataAceite = Carbon::parse($contrato->aceito_em);

    for ($i = 0; $i < $n; $i++) {
        if ($i === 0) {
            // Primeira: 1 dia após aceite
            $due = $dataAceite->copy()->addDay();
        } else {
            // Demais: mês seguinte, sempre no melhor dia
            $base = $dataAceite->copy()->addMonth($i);
            $ultimoDiaMes = $base->endOfMonth()->day;
            $due = $base->copy()->day(min($diaVenc, $ultimoDiaMes));
        }

        $unitValue = round($a1 - ($i) * $step, 2);
        $pag = Pagamento::create([
            'contrato_id'=> $contrato->id,
            'vencimento' => $due->toDateString(),
            'valor'      => $unitValue * $q,
            'status'     => 'pendente',
        ]);
        Log::info("Parcela criada ID {$pag->id} Vencimento {$due->format('d/m/Y')}");
        GerarBoletoJob::dispatch($pag->id);
    }
}


}
