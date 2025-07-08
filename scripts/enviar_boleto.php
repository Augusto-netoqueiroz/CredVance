<?php

use App\Models\Pagamento;
use App\Services\FilaEmailService;

$pagamento = Pagamento::find(949);
if (!$pagamento) {
    echo "Pagamento 949 não encontrado!\n";
    return;
}

$dadosExtras = [
    'cliente' => $pagamento->contrato->cliente,
    'contrato' => $pagamento->contrato,
    'boleto' => $pagamento,
];

$pdfPath = $pagamento->boleto_path ?? null;
if ($pdfPath) {
    $dadosExtras['pdf'] = $pdfPath;
}

FilaEmailService::fila(
    'boleto',
    'augusto.netoqueiroz@gmail.com',
    'Seu boleto mensal - CredVance',
    null, // corpo vazio porque a view blade será usada
    $dadosExtras,
    now()
);

echo "Email de boleto inserido na fila!\n";

// Enviar os e-mails da fila pendente (apenas boletos)
(new \App\Jobs\ProcessarFilaEmails)->handle();

echo "Envio processado!\n";
