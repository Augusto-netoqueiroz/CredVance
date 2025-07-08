<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pagamento;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\EnviarBoletoClienteMail; // ajuste conforme seu projeto

class EnviarBoletosPendentesCommand extends Command
{
    protected $signature = 'boletos:enviar-pendentes';
    protected $description = 'Envia boletos vencidos ou a vencer em até 10 dias para os clientes';

    public function handle()
    {
        $hoje = Carbon::today();
        $limite = Carbon::today()->addDays(10);

        $pagamentos = Pagamento::whereIn('status', ['pendente']) // ajuste status se necessário
            ->where(function($q) use ($hoje, $limite) {
                $q->where('vencimento', '<', $hoje)
                  ->orWhereBetween('vencimento', [$hoje, $limite]);
            })
            ->whereNull('enviado_em')
            ->get();

        foreach ($pagamentos as $pagamento) {
            // Pega o email do cliente (ajuste relacionamento conforme seu sistema)
            $clienteEmail = optional($pagamento->contrato->cliente)->email;

            if ($clienteEmail) {
                Mail::to($clienteEmail)->send(new EnviarBoletoClienteMail($pagamento));
                $pagamento->enviado_em = Carbon::now();
                $pagamento->save();
                $this->info("Enviado para {$clienteEmail}");
            }
        }
    }
}
