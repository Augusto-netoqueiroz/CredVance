<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Em Construção - CredVance Consórcio</title>
    <style>
        /* Gradiente de azul mais escuro */
        body {
            background: linear-gradient(135deg, #003366, #4B9CD3); /* Azul escuro e suave */
            color: white;
            font-family: 'Poppins', sans-serif;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 90%;
        }

        .logo {
            width: 200px;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 28px;
            font-weight: bold;
            color: #FFFFFF;
        }

        p {
            font-size: 18px;
            opacity: 0.9;
        }

        /* Animação do trabalhador */
        .worker {
            width: 120px;
            animation: floating 2s infinite ease-in-out;
        }

        @keyframes floating {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        .footer {
            margin-top: 20px;
            font-size: 14px;
            opacity: 0.8;
        }
    </style>
</head>
<body>

    <div class="container">
        <img src="{{ asset('images/logo.png') }}" alt="CredVance Consórcio" class="logo">
        <h1>Estamos em Construção</h1>
        <p>Em breve nosso site estará disponível com muitas novidades!</p>
        <img src="https://cdn-icons-png.flaticon.com/512/1839/1839376.png" alt="Trabalhador" class="worker">
        <div class="footer">
            <p>&copy; {{ date('Y') }} CredVance Consórcio. Todos os direitos reservados.</p>
        </div>
    </div>

</body>
</html>
