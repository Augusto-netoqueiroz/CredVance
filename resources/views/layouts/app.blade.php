<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Título da página --}}
    <title>@yield('title', config('app.name', 'Laravel'))</title>

    {{-- Meta Description e Keywords --}}
    <meta name="description" content="@yield('meta_description', 'Simule e gerencie seu consórcio online com CredVance')">
    <meta name="keywords" content="@yield('meta_keywords', 'consórcio, simulação, finance, CredVance')">

    {{-- Canonical --}}
    <link rel="canonical" href="@yield('canonical', url()->current())" />

    {{-- Favicon --}}
    {{-- Coloque um arquivo favicon.ico em public/ ou ajuste conforme necessário --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    {{-- Se tiver apple-touch-icon --}}
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    {{-- Metadados Open Graph --}}
    <meta property="og:title" content="@yield('og_title', config('app.name', 'CredVance – Seu consórcio online'))">
    <meta property="og:description" content="@yield('og_description', 'Simule e gerencie seu consórcio de forma simples com CredVance.')">
    <meta property="og:image" content="@yield('og_image', asset('assets/img/og-image.png'))">
    <meta property="og:url" content="@yield('og_url', url()->current())">
    <meta property="og:type" content="@yield('og_type', 'website')">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', config('app.name', 'CredVance – Seu consórcio online'))">
    <meta name="twitter:description" content="@yield('twitter_description', 'Simule e gerencie seu consórcio de forma simples com CredVance.')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('assets/img/og-image.png'))">

    {{-- Structured Data (JSON-LD) --}}
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "url": "{{ config('app.url', url('/')) }}",
      "name": "{{ config('app.name', 'CredVance') }}",
      "logo": "{{ asset('assets/img/logo.png') }}"
    }
    </script>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- Vite: CSS e JS principais --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Carregamento inicial do tema (dark mode) --}}
    <script>
        const theme = localStorage.getItem('theme');
        if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>

    {{-- Qualquer CSS inline extra (ex.: estilos específicos de botão flutuante) --}}
    @stack('head')
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        {{-- Navegação --}}
        @include('layouts.navigation')

        {{-- Header específico de página --}}
        @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        {{-- Conteúdo principal --}}
        <main>
            {{ $slot }}
        </main>
    </div>

    {{-- Botão flutuante de cadastro (exemplo) --}}
    {{-- Ajuste estilos ou classes conforme necessário --}}
    <a href="{{ route('register') }}"
       id="btnCadastroFlutuante"
       class="fixed bottom-20 right-5 z-50 rounded-full bg-blue-600 text-white px-4 py-2 shadow-lg hover:bg-blue-700 transition pulse">
        Cadastre-se Agora!
    </a>
    {{-- Caso queira animação pulse, defina em CSS: 
         .pulse { animation: pulse 2s infinite; }
         @keyframes pulse { 0%,100% { transform: scale(1);} 50% { transform: scale(1.05);} }
    --}}

    {{-- Scripts adicionais --}}
    @stack('scripts')
</body>
</html>
