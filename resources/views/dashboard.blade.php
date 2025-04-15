<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight">
            {{ __('Dashboard Administrativa - Consórcio') }}
        </h2>
    </x-slot>

    <div class="py-10 px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto">

            <!-- Cards com Indicadores Modernos -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <div class="flex items-center gap-4 bg-white dark:bg-gray-800 p-4 rounded-xl shadow hover:shadow-lg transition-transform hover:scale-[1.02]">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 rounded-full">
                        <i data-lucide="users" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Consorciados Ativos</div>
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">--</div>
                    </div>
                </div>

                <div class="flex items-center gap-4 bg-white dark:bg-gray-800 p-4 rounded-xl shadow hover:shadow-lg transition-transform hover:scale-[1.02]">
                    <div class="p-2 bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-300 rounded-full">
                        <i data-lucide="layers" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Grupos Ativos</div>
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">--</div>
                    </div>
                </div>

                <div class="flex items-center gap-4 bg-white dark:bg-gray-800 p-4 rounded-xl shadow hover:shadow-lg transition-transform hover:scale-[1.02]">
                    <div class="p-2 bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-300 rounded-full">
                        <i data-lucide="check-circle" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Cotas Vendidas</div>
                        <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">--</div>
                    </div>
                </div>

                <div class="flex items-center gap-4 bg-white dark:bg-gray-800 p-4 rounded-xl shadow hover:shadow-lg transition-transform hover:scale-[1.02]">
                    <div class="p-2 bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-300 rounded-full">
                        <i data-lucide="dollar-sign" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Faturamento (Mês)</div>
                        <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">--</div>
                    </div>
                </div>
            </div>

            <!-- Gráficos -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-white mb-4">Vendas dos últimos 6 meses</h3>
                    <div class="h-52 bg-gray-100 dark:bg-gray-700 rounded flex items-center justify-center text-gray-500 dark:text-gray-400">
                        (Gráfico de barras futuro)
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-white mb-4">Distribuição por Tipo</h3>
                    <div class="h-52 bg-gray-100 dark:bg-gray-700 rounded flex items-center justify-center text-gray-500 dark:text-gray-400">
                        (Gráfico de pizza futuro)
                    </div>
                </div>
            </div>

            <!-- Alertas e Assembleias -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg">
                    <h3 class="text-lg font-semibold text-red-600 dark:text-red-400 mb-4">Alertas</h3>
                    <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-300 space-y-1">
                        <li>Cotas em atraso</li>
                        <li>Documentos pendentes</li>
                        <li>Grupos com inadimplência alta</li>
                    </ul>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-white mb-4">Próximas Assembleias</h3>
                    <div class="text-gray-500 dark:text-gray-400 text-sm">(Listagem futura)</div>
                </div>
            </div>

            <!-- Atividades Recentes -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg mb-10">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-white mb-4">Atividades Recentes</h3>
                <div class="text-gray-500 dark:text-gray-400 text-sm">(Log de atividades recente)</div>
            </div>

            <!-- Atalhos Rápidos -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
                <a href="#" class="flex items-center justify-center bg-gradient-to-r from-blue-500 to-blue-700 hover:opacity-90 text-white font-medium py-3 rounded-xl shadow transition">Novo Grupo</a>
                <a href="#" class="flex items-center justify-center bg-gradient-to-r from-green-500 to-green-700 hover:opacity-90 text-white font-medium py-3 rounded-xl shadow transition">Registrar Pagamento</a>
                <a href="#" class="flex items-center justify-center bg-gradient-to-r from-indigo-500 to-indigo-700 hover:opacity-90 text-white font-medium py-3 rounded-xl shadow transition">Gerenciar Usuários</a>
                <a href="#" class="flex items-center justify-center bg-gradient-to-r from-purple-500 to-purple-700 hover:opacity-90 text-white font-medium py-3 rounded-xl shadow transition">Inserir Resultado</a>
            </div>

            <!-- Últimos Consorciados -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-white mb-4">Últimos Consorciados Cadastrados</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-200">
                            <tr>
                                <th class="px-4 py-2">Nome</th>
                                <th class="px-4 py-2">Grupo</th>
                                <th class="px-4 py-2">Cota</th>
                                <th class="px-4 py-2">Data de Cadastro</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                                <td class="px-4 py-2">--</td>
                                <td class="px-4 py-2">--</td>
                                <td class="px-4 py-2">--</td>
                                <td class="px-4 py-2">--</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) lucide.createIcons();
        });
    </script>
</x-app-layout>
