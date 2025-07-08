<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\BoletoReminderMail;
use App\Models\BoletoLog;

class EnviarBoletoEmail extends Command
{
    protected $signature = 'boleto:enviar-email {contrato_id}';
    protected $description = 'Envia o email de boleto para o contrato informado';

    public function handle()
    {
        $contrato_id = $this->argument('contrato_id');

        $boleto = DB::table('pagamentos')
            ->join('contratos', 'contratos.id', '=', 'pagamentos.contrato_id')
            ->join('users', 'users.id', '=', 'contratos.cliente_id')
            ->select(
                'pagamentos.*',
                'contratos.id as contrato_id',
                'contratos.cliente_id',
                'users.name as cliente_nome',
                'users.email as cliente_email'
            )
            ->where('contratos.id', $contrato_id)
            ->where('pagamentos.status', 'pendente')
            ->whereNotNull('pagamentos.boleto_path')
            ->orderBy('pagamentos.vencimento')
            ->first();

        if (!$boleto) {
            $this->error('Nenhum boleto pendente encontrado para esse contrato!');
            return 1;
        }

        if (empty($boleto->cliente_email)) {
            $this->error('O cliente não possui email cadastrado!');
            return 1;
        }

        Mail::to($boleto->cliente_email)->send(new BoletoReminderMail($boleto));

        $this->info('Email enviado para ' . $boleto->cliente_email);

        // Atualiza ou cria o log do envio
        BoletoLog::updateOrCreate(
            ['pagamento_id' => $boleto->id],
            [
                'contrato_id' => $boleto->contrato_id,
                'cliente_id' => $boleto->cliente_id,
                'enviado' => true,
                'enviado_em' => now(),
            ]
        );

        return 0;
    }
}
