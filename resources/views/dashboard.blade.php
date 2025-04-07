<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <h1>Bem-vindo ao painel administrativo</h1>

        @auth
        @if(auth()->user()->role === 'admin')
            <x-nav-link :href="route('usuarios.index')" :active="request()->routeIs('admin.usuarios.*')" wire:navigate>
                Usuários <span class="badge bg-primary ms-1">{{ \App\Models\User::count() }}</span>
            </x-nav-link>
        @endif
    @endauth
    </div>
    <script>
    lucide.createIcons();
</script>
</x-app-layout>
