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
                <div onclick="openModal('modalCotas')" class="cursor-pointer flex items-center gap-4 bg-white dark:bg-gray-800 p-4 rounded-xl shadow hover:shadow-lg transition-transform hover:scale-[1.02]">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 rounded-full">
                        <i data-lucide="copy" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Cotas Vendidas</div>
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $cotasVendidas }}</div>
                    </div>
                </div>

                <div onclick="openModal('modalContratos')" class="cursor-pointer flex items-center gap-4 bg-white dark:bg-gray-800 p-4 rounded-xl shadow hover:shadow-lg transition-transform hover:scale-[1.02]">
                    <div class="p-2 bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-300 rounded-full">
                        <i data-lucide="file-text" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Contratos Ativos</div>
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $contratosCount }}</div>
                    </div>
                </div>

                <div onclick="openModal('modalFaturamento')" class="cursor-pointer flex items-center gap-4 bg-white dark:bg-gray-800 p-4 rounded-xl shadow hover:shadow-lg transition-transform hover:scale-[1.02]">
                    <div class="p-2 bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-300 rounded-full">
                        <i data-lucide="dollar-sign" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Faturamento (Mês)</div>
                        <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">R$ {{ number_format($faturamentoMes,2,',','.') }}</div>
                    </div>
                </div>

                <div onclick="openModal('modalPendentes')" class="cursor-pointer flex items-center gap-4 bg-white dark:bg-gray-800 p-4 rounded-xl shadow hover:shadow-lg transition-transform hover:scale-[1.02]">
                    <div class="p-2 bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-300 rounded-full">
                        <i data-lucide="clock" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Pagamentos Pendentes</div>
                        <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $pendentesCount }} | R$ {{ number_format($pendentesValor,2,',','.') }}</div>
                    </div>
                </div>
            </div>

            <!-- Modais com Tabelas -->
            @foreach (['Cotas', 'Contratos', 'Faturamento', 'Pendentes'] as $modal)
            <div
                id="modal{{ $modal }}"
                class="fixed inset-0 z-50 hidden bg-black bg-opacity-30 flex items-center justify-center"
                onclick="handleBackdropClick(event, 'modal{{ $modal }}')"
            >
                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg w-full max-w-lg relative"
                    onclick="event.stopPropagation()"
                >
                    <!-- Export Buttons -->
                    <div class="flex justify-end space-x-2 mb-4">
                        <button onclick="exportData('{{ strtolower($modal) }}','pdf')" class="text-xs bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition">PDF</button>
                        <button onclick="exportData('{{ strtolower($modal) }}','excel')" class="text-xs bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 transition">Excel</button>
                        <button onclick="exportData('{{ strtolower($modal) }}','csv')" class="text-xs bg-gray-600 text-white px-3 py-1 rounded hover:bg-gray-700 transition">CSV</button>
                    </div>

                    <div class="flex justify-between items-center mb-3">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Detalhes - {{ $modal }}</h2>
                        <button onclick="closeModal('modal{{ $modal }}')" class="text-sm text-red-500 hover:underline">Fechar</button>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">Informações detalhadas sobre {{ strtolower($modal) }}.</p>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-300">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    @if($modal==='Cotas'||$modal==='Contratos')
                                        <th class="px-4 py-2">Cliente</th>
                                        <th class="px-4 py-2">Cotas</th>
                                        <th class="px-4 py-2">Criado Em</th>
                                    @elseif($modal==='Faturamento')
                                        <th class="px-4 py-2">Cliente</th>
                                        <th class="px-4 py-2">Valor</th>
                                        <th class="px-4 py-2">Vencimento</th>
                                        <th class="px-4 py-2">Pago Em</th>
                                    @else
                                        <th class="px-4 py-2">Cliente</th>
                                        <th class="px-4 py-2">Valor</th>
                                        <th class="px-4 py-2">Vencimento</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody id="modal{{ $modal }}Table">
                                <tr>
                                    <td class="px-4 py-2 text-center" colspan="{{ $modal==='Faturamento'?4:3 }}">Carregando...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Gráficos -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-white mb-4">Vendas dos últimos 6 meses</h3>
                    <div class="h-52 bg-gray-100 dark:bg-gray-700 rounded flex items-center justify-center text-gray-500 dark:text-gray-400">(Gráfico de barras futuro)</div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-white mb-4">Distribuição por Tipo</h3>
                    <div class="h-52 bg-gray-100 dark:bg-gray-700 rounded flex items-center justify-center text-gray-500 dark:text-gray-400">(Gráfico de pizza futuro)</div>
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
                <a href="{{ route('usuarios.index') }}" class="flex items-center justify-center bg-gradient-to-r from-indigo-500 to-indigo-700 hover:opacity-90 text-white font-medium py-3 rounded-xl shadow transition">Gerenciar Usuários</a>
                <a href="{{ route('contratos.create') }}" class="flex items-center justify-center bg-gradient-to-r from-purple-500 to-purple-700 hover:opacity-90 text-white font-medium py-3 rounded-xl shadow transition">Criar Contrato</a>
            </div>

        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            switch (id) {
                case 'modalCotas': fetchCotas(); break;
                case 'modalContratos': fetchContratos(); break;
                case 'modalFaturamento': fetchFaturamento(); break;
                case 'modalPendentes': fetchPendentes(); break;
            }
        }
        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }
        function handleBackdropClick(event, id) {
            if (event.target.id === id) closeModal(id);
        }

        function populateTable(id, data, columns) {
            const tbody = document.getElementById(id + 'Table');
            tbody.innerHTML = '';
            data.forEach(item => {
                const tr = document.createElement('tr');
                tr.className = 'bg-white dark:bg-gray-800 border-b dark:border-gray-700';
                columns.forEach(col => {
                    const td = document.createElement('td');
                    td.className = 'px-4 py-2';
                    td.textContent = item[col] || '-';
                    tr.appendChild(td);
                });
                tbody.appendChild(tr);
            });
        }

        function fetchCotas() {
            fetch('{{ route('dashboard.detalhes.cotas') }}')
                .then(res => res.json())
                .then(data => {
                    data.sort((a, b) => {
                        const da = new Date(a['Criado Em'].split('/').reverse().join('-'));
                        const db = new Date(b['Criado Em'].split('/').reverse().join('-')); 
                        if (da < db) return -1;
                        if (da > db) return 1;
                        return a['Cliente'].localeCompare(b['Cliente']);
                    });
                    populateTable('modalCotas', data, ['Cliente','Cotas','Criado Em']);
                });
        }
        function fetchContratos() {
            fetch('{{ route('dashboard.detalhes.contratos') }}')
                .then(res => res.json())
                .then(data => {
                    data.sort((a, b) => {
                        const da = new Date(a['Criado Em'].split('/').reverse().join('-'));
                        const db = new Date(b['Criado Em'].split('/').reverse().join('-')); 
                        if (da < db) return -1;
                        if (da > db) return 1;
                        return a['Cliente'].localeCompare(b['Cliente']);
                    });
                    populateTable('modalContratos', data, ['Cliente','Cotas','Criado Em']);
                });
        }
        function fetchFaturamento() {
            fetch('{{ route('dashboard.detalhes.faturamento') }}')
                .then(res => res.json())
                .then(data => {
                    data.sort((a, b) => {
                        const da = new Date(a['Vencimento'].split('/').reverse().join('-')); 
                        const db = new Date(b['Vencimento'].split('/').reverse().join('-')); 
                        if (da < db) return -1;
                        if (da > db) return 1;
                        return a['Cliente'].localeCompare(b['Cliente']);
                    });
                    populateTable('modalFaturamento', data, ['Cliente','Valor','Vencimento','Pago Em']);
                });
        }
        function fetchPendentes() {
            fetch('{{ route('dashboard.detalhes.pendentes') }}')
                .then(res => res.json())
                .then(data => {
                    data.sort((a, b) => {
                        const da = new Date(a['Vencimento'].split('/').reverse().join('-')); 
                        const db = new Date(b['Vencimento'].split('/').reverse().join('-')); 
                        if (da < db) return -1;
                        if (da > db) return 1;
                        return a['Cliente'].localeCompare(b['Cliente']);
                    });
                    populateTable('modalPendentes', data, ['Cliente','Valor','Vencimento']);
                });
        }

        function exportData(modal, format) {
            window.open(`/dashboard/detalhes/${modal}?export=${format}`, '_blank');
        }

        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) lucide.createIcons();
        });
    </script>
</x-app-layout>
