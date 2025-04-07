@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Bem-vindo ao painel administrativo</h1>

        @auth
        @if(auth()->user()->role === 'admin')
            <x-nav-link :href="route('admin.usuarios.index')" :active="request()->routeIs('admin.usuarios.*')" wire:navigate>
                Usuários <span class="badge bg-primary ms-1">{{ \App\Models\User::count() }}</span>
            </x-nav-link>
        @endif
    @endauth
    </div>
    <script>
    lucide.createIcons();
</script>
@endsection
