<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ darkMode: localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches) }"
      x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'))"
      :class="{ 'dark': darkMode }"
>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

    <!-- Fonts + Tailwind -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <div class="min-h-screen"
         x-data="{
            mobileMenuOpen: false,
            profileDropdownOpen: false,
            userMenuOpen: false,
            isLoggedIn: true,
            user: {
                name: '{{ Auth::check() ? Auth::user()->name : 'João Silva' }}',
                email: '{{ Auth::check() ? Auth::user()->email : 'joao@email.com' }}',
                avatar: 'https://ui-avatars.com/api/?name={{ urlencode(Auth::check() ? Auth::user()->name : 'João Silva') }}&color=7c3aed&background=ede9fe'
            }
        }"
    >
        {{-- NAVBAR Responsiva --}}
        <nav class="bg-white dark:bg-gray-800 shadow border-b border-gray-200 dark:border-gray-700 transition-colors duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo + Links -->
                    <div class="flex items-center">
                        <a href="{{ route('Inicio') }}" class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="text-xl font-bold text-gray-900 dark:text-white">MeuApp</span>
                        </a>

                        {{-- Links (desktop) --}}
                        <div class="hidden md:flex ml-10 space-x-4">
                            <x-nav-link :href="route('Inicio')" :active="request()->routeIs('Inicio')">Início</x-nav-link>

                            @auth
                                @if(auth()->user()->role === 'admin')
                                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-nav-link>
                                    <x-nav-link :href="route('usuarios.index')" :active="request()->routeIs('usuarios.*')">Usuários</x-nav-link>
                                    <x-nav-link :href="route('logs.index')" :active="request()->routeIs('logs.index')">Logs</x-nav-link>
                                @endif

                                @if(in_array(auth()->user()->role, ['admin', 'parceiro']))
                                    <x-nav-link :href="route('admin.parceiros.index')" :active="request()->routeIs('admin.parceiros.*')">Parceiros</x-nav-link>
                                @endif
                            @endauth
                        </div>
                    </div>

                    {{-- Ações lado direito --}}
                    <div class="hidden md:flex items-center space-x-4">
                        {{-- Alternar tema --}}
                        <button @click="darkMode = !darkMode" class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646A9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                            <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </button>

                        {{-- Perfil Dropdown --}}
                        @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" class="flex items-center space-x-2 text-sm focus:outline-none">
                                <img class="h-8 w-8 rounded-full object-cover ring-2 ring-gray-300 dark:ring-gray-600" :src="user.avatar" :alt="user.name">
                                <span class="text-gray-700 dark:text-gray-200">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4 text-gray-400" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" x-transition class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 rounded-lg shadow-lg py-1 z-50">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Perfil</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/20">
                                        Sair
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endauth
                    </div>

                    {{-- Mobile Toggle --}}
                    <div class="md:hidden">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <svg class="h-6 w-6" x-show="!mobileMenuOpen" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                            <svg class="h-6 w-6" x-show="mobileMenuOpen" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Mobile menu --}}
            <div x-show="mobileMenuOpen" class="md:hidden bg-white dark:bg-gray-800 border-t dark:border-gray-700 px-4 py-3 space-y-1">
                <x-responsive-nav-link :href="route('Inicio')" :active="request()->routeIs('Inicio')">Início</x-responsive-nav-link>
                @auth
                    @if(auth()->user()->role === 'admin')
                        <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('usuarios.index')" :active="request()->routeIs('usuarios.*')">Usuários</x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('logs.index')" :active="request()->routeIs('logs.index')">Logs</x-responsive-nav-link>
                    @endif
                @endauth
            </div>
        </nav>

        {{-- CONTEÚDO DA PÁGINA --}}
        <main>
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
