<!-- resources/views/emails/verification.blade.php -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Verificação de E-mail</title>
</head>
<body>
    <h2>Olá!</h2>
    <p>Obrigado por se cadastrar! Clique no link abaixo para verificar seu e-mail e entrar na sua conta automaticamente:</p>
    <p>
        <a href="{{ $verificationLink }}" target="_blank" style="padding:10px 20px; background-color:#3490dc; color:#fff; text-decoration:none; border-radius:5px;">
            Verificar meu e-mail
        </a>
    </p>
    <p>Se o link não funcionar, copie e cole a URL a seguir no seu navegador:</p>
    <p>{{ $verificationLink }}</p>
    <p>Este link expira em 15 minutos.</p>
</body>
</html>
