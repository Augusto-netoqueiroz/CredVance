<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Contrato Criado - CredVance</title>
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
            {{-- Se quiser, pode trocar por uma logo <img src="https://meucredvance.com.br/logo.png" alt="CredVance"> --}}
            <h2>CredVance</h2>
        </div>
        <div class="content">
            <h1>Contrato Criado com Sucesso</h1>
            <p>Olá {{ $contrato->cliente->name }},</p>

            <p>Seu contrato foi criado com sucesso em nosso sistema!</p>

            <p>
                <strong>Número do Contrato:</strong> {{ $contrato->id }}<br>
                <strong>Plano:</strong> {{ $contrato->consorcio->plano ?? '-' }}<br>
                <strong>Quantidade de Cotas:</strong> {{ $contrato->quantidade_cotas }}<br>
                <strong>Data de Aceite:</strong> {{ \Carbon\Carbon::parse($contrato->aceito_em)->format('d/m/Y H:i') }}
            </p>

            <p>
                O PDF do seu contrato está em anexo a este e-mail.<br>
                Guarde esse documento para futuras consultas.
            </p>

            <p style="text-align:center;">
                <a href="https://meucredvance.com.br/login" class="button">Acessar Área do Cliente</a>
            </p>

            <p style="font-size:13px;color:#888;margin-top:20px;">
                Se tiver dúvidas, entre em contato com nosso atendimento.<br>
                Obrigado por escolher a CredVance!
            </p>
        </div>
        <div class="footer">
            © {{ date('Y') }} CredVance<br>
            Rua 35 Casa 101 - Setor Tradicional - São Sebastião/DF<br>
            contato@credvance.com.br | +55 61 99625-8003
        </div>
    </div>
    <img src="{{ route('email.opened', ['emailId' => $emailId]) }}" style="display:none" alt="." />

</body>
</html>
