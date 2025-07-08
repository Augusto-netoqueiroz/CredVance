<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight">
            {{ __('Parceiros - Links de Indicação') }}
        </h2>
    </x-slot>

    <div class="py-10 px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto">

            <div class="mb-6">
                <a href="{{ route('admin.parceiros.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">
                    Criar Novo Parceiro
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($parceiros as $parceiro)
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow hover:shadow-lg transition relative">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">{{ $parceiro->nome }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Slug: <code>{{ $parceiro->slug }}</code></p>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-1">Usuário: <strong>{{ $parceiro->user->name ?? '-' }}</strong></p>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-1">Acessos: <strong>{{ $parceiro->acessos_count }}</strong></p>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-1">Cadastros: <strong>{{ $parceiro->usuarios_count }}</strong></p>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-1">Cotas Vendidas: <strong>{{ $cotasPorParceiro[$parceiro->id] ?? 0 }}</strong></p>

                        <div class="mt-3 flex space-x-2">
                            <a href="{{ route('admin.parceiros.metrics', $parceiro) }}" class="text-xs bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">Métricas</a>

                            <button
                                onclick="copyLink('{{ url('/parceiro/' . $parceiro->slug) }}', this)"
                                class="text-xs bg-gray-600 text-white px-3 py-1 rounded hover:bg-gray-700"
                            >
                                Copiar Link
                            </button>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400">Nenhum parceiro cadastrado ainda.</p>
                @endforelse
            </div>

        </div>
    </div>

    {{-- Script para copiar link --}}
    <script>
        function copyLink(link, btn) {
            navigator.clipboard.writeText(link).then(() => {
                const originalText = btn.innerText;
                btn.innerText = 'Copiado!';
                btn.disabled = true;
                setTimeout(() => {
                    btn.innerText = originalText;
                    btn.disabled = false;
                }, 1500);
            }).catch(err => {
                console.error('Erro ao copiar link:', err);
            });
        }
    </script>
</x-app-layout>
