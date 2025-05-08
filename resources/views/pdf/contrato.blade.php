<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Contrato PDF</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
    </style>
</head>
<body>
    <h2>Contrato de Consórcio</h2>

    <p><strong>Cliente:</strong> {{ $contrato->cliente->name }}</p>
    <p><strong>CPF:</strong> {{ $contrato->cliente->cpf }}</p>
    <p><strong>Email:</strong> {{ $contrato->cliente->email }}</p>
    <p><strong>Plano:</strong> {{ $contrato->consorcio->plano }}</p>
    <p><strong>Quantidade de cotas:</strong> {{ $contrato->quantidade_cotas }}</p>
    <p><strong>Aceito em:</strong> {{ $contrato->aceito_em }}</p>

    <hr>
    <p><strong>IP:</strong> {{ $contrato->ip }}</p>
    <p><strong>Navegador:</strong> {{ $contrato->navegador_info }}</p>
    <p><strong>Resolução:</strong> {{ $contrato->resolucao }}</p>
    <p><strong>Localização:</strong> {{ $contrato->latitude }}, {{ $contrato->longitude }}</p>
</body>
</html>
