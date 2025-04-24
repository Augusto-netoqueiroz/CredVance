<?php

namespace App\Services;

use App\Models\Contrato;
use App\Models\Pagamento;
use Carbon\Carbon;

class PagamentoGenerator
{
    /**
     * Gera as parcelas decrescentes para um contrato,
     * multiplicando pelo número de cotas contratadas.
     */
    public function generate(Contrato $contrato): void
    {
        $cons = $contrato->consorcio;
        $n    = (int) $cons->prazo;
        $a1   = (float) $cons->parcela_mensal;                     // parcela inicial
        $an   = round((2 * $cons->valor_total / $n) - $a1, 2);   // última parcela
        $step = round(($a1 - $an) / ($n - 1), 2);                // decremento
        $q    = (int) $contrato->quantidade_cotas;                // qtd de cotas

        $due = Carbon::now()->addMonth();
        for ($i = 1; $i <= $n; $i++) {
            $unitValue = round($a1 - ($i - 1) * $step, 2);
            Pagamento::create([
                'contrato_id' => $contrato->id,
                'vencimento'  => $due->toDateString(),
                'valor'       => $unitValue * $q,
                'status'      => 'pendente',
            ]);
            $due->addMonth();
        }
    }
}
