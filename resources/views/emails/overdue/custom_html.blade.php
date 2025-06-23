<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Aviso de Pagamento Vencido - CredVance</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f2f4f6;
            font-family: 'Raleway', Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #0c3c71;
            color: #ffffff;
            text-align: center;
            padding: 20px;
        }
        .header img {
            max-height: 50px;
        }
        .content {
            padding: 30px;
            color: #333333;
        }
        .content h1 {
            color: #0c3c71;
            font-size: 24px;
        }
        .button {
            display: inline-block;
            padding: 12px 25px;
            background-color: #f6c800;
            color: #0c3c71;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 20px;
        }
        .footer {
            background-color: #0c3c71;
            color: #ffffff;
            text-align: center;
            padding: 15px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>CredVance</h2>
        </div>
        <div class="content">
            <h1>Pagamento em Atraso</h1>

            <p>Olá {{ $cobranca['pagador']['nome'] ?? 'Cliente' }},</p>

            <p>Identificamos que o seu boleto com vencimento em <strong>{{ $cobranca['dataVencimento'] }}</strong> ainda não foi pago.</p>

            <p><strong>Nosso Número:</strong> {{ $cobranca['seuNumero'] }}<br>
            <strong>Valor:</strong> R$ {{ number_format($cobranca['valorNominal'], 2, ',', '.') }}</p>

            <p>Para visualizar os detalhes da sua fatura e gerar a segunda via do boleto, acesse sua área do cliente:</p>

            <p style="text-align:center;">
                <a href="https://meucredvance.com.br/login" class="button">Acessar Área do Cliente</a>
            </p>

            <p>Se você já realizou o pagamento, por favor, desconsidere este aviso.</p>
        </div>
        <div class="footer">
            © {{ date('Y') }} CredVance<br>
            Rua 35 Casa 101 - Setor Tradicional - São Sebastião/DF<br>
            contato@credvance.com.br | +55 61 99625-8003
        </div>
    </div>
</body>
</html>
