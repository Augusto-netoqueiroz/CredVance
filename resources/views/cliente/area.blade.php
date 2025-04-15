<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
            Área do Cliente
        </h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <!-- Grid de Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Parcelas em Aberto -->
            <div class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-100 rounded-xl p-4 shadow hover:shadow-lg hover:scale-[1.02] transition transform">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium">Parcelas em Aberto</h3>
                        <p class="text-2xl font-bold mt-1">-</p>
                    </div>
                    <x-lucide-credit-card class="w-6 h-6" />
                </div>
            </div>

            <!-- Parcelas Pagas -->
            <div class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-100 rounded-xl p-4 shadow hover:shadow-lg hover:scale-[1.02] transition transform">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium">Parcelas Pagas</h3>
                        <p class="text-2xl font-bold mt-1">-</p>
                    </div>
                    <x-lucide-check-circle class="w-6 h-6" />
                </div>
            </div>

            <!-- Próxima Parcela -->
            <div class="bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-100 rounded-xl p-4 shadow hover:shadow-lg hover:scale-[1.02] transition transform">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium">Próxima Parcela</h3>
                        <p class="text-2xl font-bold mt-1">-</p>
                    </div>
                    <x-lucide-calendar class="w-6 h-6" />
                </div>
            </div>

            <!-- Status do Consórcio -->
            <div class="bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-100 rounded-xl p-4 shadow hover:shadow-lg hover:scale-[1.02] transition transform">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium">Status do Consórcio</h3>
                        <p class="text-2xl font-bold mt-1">-</p>
                    </div>
                    <x-lucide-badge-check class="w-6 h-6" />
                </div>
            </div>
        </div>

        <!-- Faturas -->
        <div class="mt-10">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Faturas</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Mês</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Valor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Ação</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        <!-- Exemplo -->
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">Janeiro 2025</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">R$ 500,00</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-100">
                                    Pago
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="#" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200">Ver</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Documentos -->
        <div class="mt-10">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Documentos</h3>
            <ul class="space-y-4">
                <li class="flex items-center justify-between bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                    <div class="flex items-center">
                        <x-lucide-file-text class="w-5 h-5 text-gray-500 dark:text-gray-400 mr-2" />
                        <span class="text-sm text-gray-900 dark:text-white">Contrato de Adesão</span>
                    </div>
                    <a href="#" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200 text-sm">Baixar</a>
                </li>
            </ul>
        </div>
    </div>
</x-app-layout>
