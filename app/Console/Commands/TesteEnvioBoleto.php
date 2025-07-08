<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pagamento;
use App\Models\BoletoLog;
use Illuminate\Support\Facades\Mail;

class TesteEnvioBoleto extends Command
{
    protected $signature = 'teste:envio-boleto {pagamento_id}';
    protected $description = 'Testa envio de email de boleto para um pagamento específico e registra log';

    public function handle()
    {
        $pagamentoId = $this->argument('pagamento_id');

        $pag = Pagamento::with('cliente', 'contrato')->find($pagamentoId);
        if (! $pag) {
            $this->error("Pagamento ID {$pagamentoId} não encontrado.");
            return 1;
        }

        $cliente = $pag->cliente;
        $contrato = $pag->contrato;

        if (! $cliente || ! $cliente->email) {
            $this->error("Cliente não encontrado ou sem email para pagamento ID {$pagamentoId}.");
            return 1;
        }

        $dados = [
            'boleto' => $pag,
            'cliente' => $cliente,
            'contrato' => $contrato,
        ];

        $bodyHtml = view('emails.boleto_enviado', $dados)->render();

        try {
            Mail::send([], [], function ($message) use ($cliente, $bodyHtml, $pag) {
                $message->to($cliente->email)
                    ->subject('Seu boleto está disponível - CredVance')
                    ->html($bodyHtml);

                if (!empty($pag->boleto_path)) {
                    $pdfPath = storage_path('app/private/' . $pag->boleto_path);
                    if (file_exists($pdfPath)) {
                        $message->attach($pdfPath);
                    }
                }
            });

            // Cria ou atualiza log na tabela boleto_logs
            $log = BoletoLog::updateOrCreate(
                ['pagamento_id' => $pag->id],
                [
                    'contrato_id' => $contrato->id ?? null,
                    'cliente_id' => $cliente->id ?? null,
                    'enviado' => true,
                    'enviado_em' => now(),
                    // abre o campo aberto e outros campos ficam em defaults (false, null)
                ]
            );

            $this->info("Email enviado com sucesso para {$cliente->email} e log registrado.");

        } catch (\Exception $e) {
            $this->error("Erro ao enviar email: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
