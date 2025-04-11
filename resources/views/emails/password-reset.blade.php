<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Redefina sua senha</title>
</head>
<body>
    <h2>Olá!</h2>
    <p>Você solicitou a redefinição de senha. Clique no link abaixo para criar uma nova senha:</p>
    <p>
        <a href="{{ $resetLink }}" style="padding:10px 20px; background-color:#3490dc; color:#fff; text-decoration:none; border-radius:5px;">
            Redefinir Senha
        </a>
    </p>
    <p>Se o botão não funcionar, copie e cole a URL no seu navegador:</p>
    <p>{{ $resetLink }}</p>
    <p><em>Nota: Este link expira em 15 minutos.</em></p>
</body>
</html>
