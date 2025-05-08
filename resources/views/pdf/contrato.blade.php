<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Contrato PDF</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background-color: #eee; }
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
    <p><strong>Localização:</strong> {{ $contrato->latitude }}, {{ $contrato->longitude }}</p>

    <h3>Informações Financeiras</h3>
    <table>
        <thead>
            <tr>
                <th>Parcela</th>
                <th>Vencimento</th>
                <th>Valor (R$)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pagamentos as $i => $p)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->vencimento)->format('d/m/Y') }}</td>
                    <td>{{ number_format($p->valor, 2, ',', '.') }}</td>
                    <td>{{ ucfirst($p->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
