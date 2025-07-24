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
        $n    = (int) $cons->prazo;
        $a1   = (float) $cons->parcela_mensal;
        $an   = $n > 1 ? round((2 * $cons->valor_total / $n) - $a1, 2) : $a1;
        $step = $n > 1 ? round(($a1 - $an) / ($n - 1), 2) : 0;
        $q    = (int) $contrato->quantidade_cotas;

        // ✅ Força o tipo inteiro para evitar erros com Carbon
        $diaVenc = (int) ($contrato->dia_vencimento ?? 1);
        $dataAceite = Carbon::parse($contrato->aceito_em);

        for ($i = 0; $i < $n; $i++) {
            if ($i === 0) {
                // Primeira parcela: 1 dia após o aceite
                $due = $dataAceite->copy()->addDay();
            } else {
                // Demais parcelas: mês seguinte, no melhor dia possível
                $base = $dataAceite->copy()->addMonth($i);
                $ultimoDiaMes = $base->endOfMonth()->day;

                // ✅ Garantir que o dia nunca exceda o fim do mês
                $dia = min($diaVenc, $ultimoDiaMes);

                // ✅ Força novamente o tipo antes de aplicar no Carbon
                $due = $base->copy()->day((int) $dia);
            }

            $unitValue = round($a1 - ($i) * $step, 2);

            $pag = Pagamento::create([
                'contrato_id' => $contrato->id,
                'vencimento'  => $due->toDateString(),
                'valor'       => $unitValue * $q,
                'status'      => 'pendente',
            ]);

            Log::info("Parcela criada ID {$pag->id} Vencimento {$due->format('d/m/Y')}");
            GerarBoletoJob::dispatch($pag->id);
        }
    }
}
