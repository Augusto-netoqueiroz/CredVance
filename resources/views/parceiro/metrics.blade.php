<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight">
            {{ __('Métricas do Parceiro:') }} {{ $parceiro->nome }}
        </h2>
    </x-slot>

    <div class="py-10 px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto space-y-10">

            {{-- Indicadores principais --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow text-center">
                    <h4 class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total de Acessos</h4>
                    <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $totalAcessos }}</p>
                </div>

                <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow text-center">
                    <h4 class="text-sm text-gray-500 dark:text-gray-400 mb-1">Cadastros</h4>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $cadastros }}</p>
                </div>

                <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow text-center">
                    <h4 class="text-sm text-gray-500 dark:text-gray-400 mb-1">Cotas Vendidas</h4>
                    <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $cotasVendidas }}</p>
                </div>

                <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow text-center">
                    <h4 class="text-sm text-gray-500 dark:text-gray-400 mb-1">Usuários Ativos</h4>
                    <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $usuarios->count() }}</p>
                </div>
            </div>

            {{-- Gráfico de Acessos --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-lg font-semibold text-gray-700 dark:text-white">Gráfico de Acessos</h4>
                    <select id="filtro" class="px-3 py-1 rounded border dark:bg-gray-700 dark:text-white">
                        <option value="dia">Por Dia</option>
                        <option value="hora">Por Hora</option>
                    </select>
                </div>
                <canvas id="accessChart" class="w-full h-64"></canvas>
            </div>

            {{-- Logs de acessos --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
                <h4 class="text-lg font-semibold text-gray-700 dark:text-white mb-4">Log de Acessos Recentes</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-300">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2">Data/Hora</th>
                                <th class="px-4 py-2">IP</th>
                                <th class="px-4 py-2">User Agent</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($acessos as $acesso)
                                <tr>
                                    <td class="px-4 py-2">{{ $acesso->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-2">{{ $acesso->ip }}</td>
                                    <td class="px-4 py-2 truncate max-w-xs">{{ $acesso->user_agent }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-2 text-center text-gray-500 dark:text-gray-400">Nenhum acesso registrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Usuários indicados --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
                <h4 class="text-lg font-semibold text-gray-700 dark:text-white mb-4">Usuários Indicados</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-300">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2">Nome</th>
                                <th class="px-4 py-2">E-mail</th>
                                <th class="px-4 py-2">Data de Cadastro</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($usuarios as $user)
                                <tr>
                                    <td class="px-4 py-2">{{ $user->name }}</td>
                                    <td class="px-4 py-2">{{ $user->email }}</td>
                                    <td class="px-4 py-2">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-2 text-center text-gray-500 dark:text-gray-400">Nenhum usuário indicado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Botão Voltar --}}
            <div>
                <a href="{{ route('parceiro.index') }}" class="mt-4 inline-block bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                    Voltar
                </a>
            </div>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('accessChart').getContext('2d');

            const labelsDia = {!! json_encode(array_keys($accessPerDay)) !!};
            const dataDia = {!! json_encode(array_values($accessPerDay)) !!};

            const labelsHora = {!! json_encode(array_keys($accessPerHour)) !!};
            const dataHora = {!! json_encode(array_values($accessPerHour)) !!};

            let chart;

            function renderChart(type) {
                if (chart) chart.destroy();

                const labels = type === 'dia' ? labelsDia : labelsHora.map(h => h + 'h');
                const data = type === 'dia' ? dataDia : dataHora;

                chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: `Total de Acessos por ${type === 'dia' ? 'Dia' : 'Hora'}`,
                            data: data,
                            backgroundColor: 'rgba(59, 130, 246, 0.7)',
                            borderRadius: 6,
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            }

            document.getElementById('filtro').addEventListener('change', function () {
                renderChart(this.value);
            });

            renderChart('dia');
        });
    </script>
</x-app-layout>
