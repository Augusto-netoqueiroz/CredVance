<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>QR Code da Sessão EMBRACON_GERAL</title>
</head>
<body>
    <h1>QR Code para login</h1>

    @if ($qrcode)
        <img src="{{ $qrcode }}" alt="QR Code para login" style="max-width:300px;">
    @else
        <p>Não foi possível carregar o QR Code.</p>
    @endif
</body>
</html>
