<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Consórcio - Cred Vance</title>
    <meta name="description" content="Simule, contrate e gerencie seu consórcio online com praticidade, segurança e planos flexíveis. Parcelas a partir de R$ 155,00. Sem taxa de adesão!">
    <meta name="keywords" content="consórcio, consórcio online, credvance, simulação, parcelamento, investimento">

    <!-- Open Graph -->
    <meta property="og:title" content="Consórcio - Cred Vance">
    <meta property="og:description" content="Simule, contrate e gerencie seu consórcio online com praticidade, segurança e planos flexíveis. Parcelas a partir de R$ 155,00. Sem taxa de adesão!">
    <meta property="og:image" content="https://meucredvance.com.br/images/og-image.jpg">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="CredVance">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Consórcio - Cred Vance">
    <meta name="twitter:description" content="Simule, contrate e gerencie seu consórcio online com praticidade, segurança e planos flexíveis. Parcelas a partir de R$ 155,00. Sem taxa de adesão!">
    <meta name="twitter:image" content="https://meucredvance.com.br/images/og-image.jpg">

    <!-- Favicon -->
    <link rel="icon" href="https://meucredvance.com.br/favicon.ico" type="image/x-icon">
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">

    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Inter', sans-serif;
        }

        .full-hero {
            background: url('{{ asset('assets/img/hero2.png') }}') center center no-repeat;
            background-size: cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: #fff;
            text-shadow: 1px 1px 4px rgba(0,0,0,0.7);
            text-align: center;
            padding: 20px;
        }

        .full-hero h1 {
            font-size: 2rem;
            font-weight: 700;
        }

        .loader {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #ffc107;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .message {
            font-size: 1rem;
        }

        @media (max-width: 600px) {
            .full-hero h1 {
                font-size: 1.5rem;
            }
            .loader {
                width: 40px;
                height: 40px;
                border-width: 4px;
            }
            .message {
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>

    <section class="full-hero">
        <h1>CredVance</h1>
        <div class="loader"></div>
        <p class="message">Aguarde... Redirecionando para a página de cadastro</p>
    </section>

    <script>
        setTimeout(function() {
            window.location.href = "{{ route('register', ['ref' => $parceiro->id]) }}";
        }, 3000);
    </script>

</body>
</html>
