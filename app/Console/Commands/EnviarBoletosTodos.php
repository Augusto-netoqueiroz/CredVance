<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\BoletoReminderMail;
use App\Models\BoletoLog;
use Carbon\Carbon;

class EnviarBoletosTodos extends Command
{
   protected $signature = 'boletos:enviar-todos';
    protected $description = 'Envia email de boletos para contratos com boletos pendentes vencidos ou com vencimento nos próximos 10 dias, que ainda não foram enviados';

    public function handle()
    {
        $hoje = Carbon::today();
        $limite = Carbon::today()->addDays(10);

        $boletos = DB::table('pagamentos')
            ->join('contratos', 'contratos.id', '=', 'pagamentos.contrato_id')
            ->join('users', 'users.id', '=', 'contratos.cliente_id')
            ->leftJoin('boleto_logs', 'boleto_logs.pagamento_id', '=', 'pagamentos.id')
            ->select(
                'pagamentos.*',
                'contratos.id as contrato_id',
                'contratos.cliente_id',
                'users.name as cliente_nome',
                'users.email as cliente_email',
                'boleto_logs.enviado as enviado_log'
            )
            ->where('pagamentos.status', 'pendente')
            ->whereNotNull('pagamentos.boleto_path')
            ->where(function ($query) use ($hoje, $limite) {
                $query->whereBetween('pagamentos.vencimento', [$hoje->toDateString(), $limite->toDateString()])
                      ->orWhere('pagamentos.vencimento', '<', $hoje->toDateString());
            })
            ->where(function ($query) {
                // Envia apenas se não houver log de envio ou enviado = false
                $query->whereNull('boleto_logs.enviado')
                      ->orWhere('boleto_logs.enviado', false);
            })
            ->orderBy('pagamentos.vencimento')
            ->get();

        $this->info('Total de boletos para enviar: ' . $boletos->count());

        foreach ($boletos as $boleto) {
            if (empty($boleto->cliente_email)) {
                $this->warn("Contrato #{$boleto->contrato_id} sem email cadastrado. Pulando...");
                continue;
            }

            try {
                Mail::to($boleto->cliente_email)->send(new BoletoReminderMail($boleto));

                BoletoLog::create([
                    'pagamento_id' => $boleto->id,
                    'contrato_id' => $boleto->contrato_id,
                    'cliente_id' => $boleto->cliente_id,
                    'enviado' => true,
                    'enviado_em' => now(),
                ]);

                $this->info("Email enviado para {$boleto->cliente_email} - Contrato #{$boleto->contrato_id}");
            } catch (\Exception $e) {
                $this->error("Erro ao enviar para {$boleto->cliente_email}: " . $e->getMessage());
            }
        }

        $this->info('Envio finalizado!');
        return 0;
    }
}
