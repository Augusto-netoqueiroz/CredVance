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

    // Começa no dia seguinte ao aceite
    $due  = Carbon::parse($contrato->aceito_em)->addDay();

    for ($i = 1; $i <= $n; $i++) {
        $unitValue = round($a1 - ($i - 1) * $step, 2);
        $pag = Pagamento::create([
            'contrato_id'=> $contrato->id,
            'vencimento' => $due->toDateString(),
            'valor'      => $unitValue * $q,
            'status'     => 'pendente',
        ]);
        Log::info("Parcela criada ID {$pag->id}");
        GerarBoletoJob::dispatch($pag->id);

        // Para os próximos, sempre soma 30 dias corridos (não mês a mês, exatamente 30 dias)
        $due->addDays(30);
    }
}

}
