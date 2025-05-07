{{-- resources/views/cliente/index.blade.php --}}
<x-app-layout>
    {{-- Cabeçalho --}}
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight">
            Área do Cliente
        </h2>
    </x-slot>

    <div class="py-10 px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto">

            {{-- ========== INDICADORES ========= --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                {{-- Parcelas em Aberto --}}
                <div class="flex items-center gap-4 bg-white dark:bg-gray-800 p-4 rounded-xl shadow hover:shadow-lg transition-transform hover:scale-[1.02]">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 rounded-full">
                        <x-lucide-credit-card class="w-6 h-6" />
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Parcelas em Aberto</div>
                        <div id="parcela_aberto" class="text-2xl font-bold text-blue-600 dark:text-blue-400">–</div>
                    </div>
                </div>

                {{-- Parcelas Pagas --}}
                <div class="flex items-center gap-4 bg-white dark:bg-gray-800 p-4 rounded-xl shadow hover:shadow-lg transition-transform hover:scale-[1.02]">
                    <div class="p-2 bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-300 rounded-full">
                        <x-lucide-check-circle class="w-6 h-6" />
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Parcelas Pagas</div>
                        <div id="parcela_paga" class="text-2xl font-bold text-green-600 dark:text-green-400">–</div>
                    </div>
                </div>

                {{-- Próxima Parcela --}}
                <div class="flex items-center gap-4 bg-white dark:bg-gray-800 p-4 rounded-xl shadow hover:shadow-lg transition-transform hover:scale-[1.02]">
                    <div class="p-2 bg-yellow-100 dark:bg-yellow-900 text-yellow-600 dark:text-yellow-300 rounded-full">
                        <x-lucide-calendar class="w-6 h-6" />
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Próxima Parcela</div>
                        <div id="proxima_parcela" class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">–</div>
                    </div>
                </div>

                {{-- Status do Consórcio --}}
                <div class="flex items-center gap-4 bg-white dark:bg-gray-800 p-4 rounded-xl shadow hover:shadow-lg transition-transform hover:scale-[1.02]">
                    <div class="p-2 bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-300 rounded-full">
                        <x-lucide-badge-check class="w-6 h-6" />
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Status do Consórcio</div>
                        <div id="status_consorcio" class="text-2xl font-bold text-purple-600 dark:text-purple-400">–</div>
                    </div>
                </div>
            </div>

            {{-- ========== FATURAS ========= --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg mb-10">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-white mb-4">Faturas</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-300">
                        <thead class="bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-200">
                            <tr>
                                <th class="px-4 py-3">Vencimento</th>
                                <th class="px-4 py-3">Valor</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Ação</th>
                            </tr>
                        </thead>
                        <tbody id="pagamentos_table" class="divide-y divide-gray-200 dark:divide-gray-700">
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">
                                    Carregando...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ========== DOCUMENTOS ========= --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-white mb-4">Documentos</h3>
                <ul class="space-y-4">
                    <li class="flex items-center justify-between bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                        <div class="flex items-center gap-2">
                            <x-lucide-file-text class="w-5 h-5 text-gray-500 dark:text-gray-400" />
                            <span>Contrato de Adesão</span>
                        </div>
                        <a href="#" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200 text-sm">
                            Baixar
                        </a>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        if (window.lucide) lucide.createIcons();

        fetch("{{ route('cliente.data') }}")
            .then(res => {
                if (!res.ok) throw new Error(`Status ${res.status}`);
                return res.json();
            })
            .then(data => {
                // Preenche indicadores
                document.getElementById('parcela_aberto').textContent   = data.parcela_aberto;
                document.getElementById('parcela_paga').textContent     = data.parcela_paga;
                document.getElementById('status_consorcio').textContent = data.status_consorcio || '–';

                if (data.proxima_parcela) {
                    document.getElementById('proxima_parcela').textContent =
                        `${data.proxima_parcela.vencimento} – R$ ${data.proxima_parcela.valor}`;
                }

                // Preenche tabela de pagamentos
                const tbody = document.getElementById('pagamentos_table');
                tbody.innerHTML = '';

                if (!data.pagamentos.length) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="4" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">
                                Nenhuma fatura localizada.
                            </td>
                        </tr>`;
                    return;
                }

                data.pagamentos.forEach(f => {
                    const tr = document.createElement('tr');
                    tr.className = 'bg-white dark:bg-gray-800';

                    // Monta o botão Ver apenas se boleto_url estiver preenchido
                    const acao = f.boleto_url
                        ? `<a href="${f.boleto_url}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200">Ver</a>`
                        : '';

                    tr.innerHTML =
                        `<td class="px-4 py-3">${f.vencimento}</td>` +
                        `<td class="px-4 py-3">R$ ${f.valor}</td>` +
                        `<td class="px-4 py-3">
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium ${
                                f.status === 'pago'
                                    ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100'
                                    : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100'
                            }">
                                ${f.status.charAt(0).toUpperCase() + f.status.slice(1)}
                            </span>
                        </td>` +
                        `<td class="px-4 py-3">${acao}</td>`;

                    tbody.appendChild(tr);
                });
            })
            .catch(err => console.error('Fetch error:', err));
    });
    </script>
</x-app-layout>
