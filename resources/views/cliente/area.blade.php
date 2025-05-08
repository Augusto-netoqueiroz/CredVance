<x-app-layout>
    {{-- Cabeçalho --}}
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight">
            Área do Cliente
        </h2>
    </x-slot>

    @if(session('success'))
    <div 
        x-data="{ open: true }"
        x-show="open"
        x-transition
        class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50"
    >
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md p-6 text-center">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                {{ session('success') }}
            </h2>
            <button @click="open = false"
                class="mt-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                OK
            </button>
        </div>
    </div>
    @endif

    <div class="py-10 px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto">

            {{-- BOTÃO PARA ABRIR O MODAL --}}
            <div class="mb-6 flex justify-end">
                <button
                    id="btnOpenModal"
                    class="inline-flex items-center gap-2 px-4 py-2
                        text-sm font-semibold 
                        border border-indigo-600 dark:border-indigo-400
                        text-indigo-700 dark:text-indigo-200
                        bg-white dark:bg-gray-800
                        hover:bg-indigo-100 dark:hover:bg-indigo-600
                        hover:text-indigo-900 dark:hover:text-white
                        rounded-lg shadow-sm transition duration-150
                        focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
                >
                    <x-lucide-plus class="w-5 h-5" />
                    Contratar Novas Cotas
                </button>
            </div>

            {{-- INDICADORES --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <div class="flex items-center gap-4 bg-white dark:bg-gray-800 p-4 rounded-xl shadow hover:shadow-lg transition-transform hover:scale-[1.02]">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 rounded-full">
                        <x-lucide-credit-card class="w-6 h-6" />
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Parcelas em Aberto</div>
                        <div id="parcela_aberto" class="text-2xl font-bold text-blue-600 dark:text-blue-400">–</div>
                    </div>
                </div>

                <div class="flex items-center gap-4 bg-white dark:bg-gray-800 p-4 rounded-xl shadow hover:shadow-lg transition-transform hover:scale-[1.02]">
                    <div class="p-2 bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-300 rounded-full">
                        <x-lucide-check-circle class="w-6 h-6" />
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Parcelas Pagas</div>
                        <div id="parcela_paga" class="text-2xl font-bold text-green-600 dark:text-green-400">–</div>
                    </div>
                </div>

                <div class="flex items-center gap-4 bg-white dark:bg-gray-800 p-4 rounded-xl shadow hover:shadow-lg transition-transform hover:scale-[1.02]">
                    <div class="p-2 bg-yellow-100 dark:bg-yellow-900 text-yellow-600 dark:text-yellow-300 rounded-full">
                        <x-lucide-calendar class="w-6 h-6" />
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Próxima Parcela</div>
                        <div id="proxima_parcela" class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">–</div>
                    </div>
                </div>

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

            {{-- FATURAS --}}
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

            {{-- DOCUMENTOS --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-white mb-4">Documentos</h3>
                <ul id="documentos_list" class="space-y-4">
                    <li class="text-gray-500 dark:text-gray-400">Carregando documentos...</li>
                </ul>
            </div>

        </div>
    </div>

    {{-- MODAL --}}
    <div id="newAccountModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md p-6">
            <div class="absolute top-3 right-3">
                <button id="btnCloseModal" class="text-gray-800 dark:text-gray-200 hover:text-red-600 dark:hover:text-red-400 transition" aria-label="Fechar modal">
                    <x-lucide-x class="w-6 h-6" />
                </button>
            </div>
            <div class="pt-8">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Contratar Novas Contas</h3>
                <p class="mb-4 text-gray-600 dark:text-gray-300">
                    Clique abaixo para abrir o formulário de contratação:
                </p>
                <a href="{{ route('contratos.create') }}"
                    class="inline-block px-4 py-2 
                        border border-indigo-600 dark:border-indigo-400 
                        text-indigo-700 dark:text-indigo-200 
                        bg-white dark:bg-gray-800 
                        hover:bg-indigo-50 dark:hover:bg-indigo-600 
                        hover:text-indigo-900 dark:hover:text-white 
                        font-semibold rounded-lg transition duration-150"
                >
                    Ir para Contratação
                </a>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        if (window.lucide) lucide.createIcons();

        const modal = document.getElementById('newAccountModal');
        document.getElementById('btnOpenModal').addEventListener('click', () => modal.classList.remove('hidden'));
        document.getElementById('btnCloseModal').addEventListener('click', () => modal.classList.add('hidden'));

        fetch("{{ route('cliente.data') }}")
            .then(res => {
                if (!res.ok) throw new Error(`Status ${res.status}`);
                return res.json();
            })
            .then(data => {
                document.getElementById('parcela_aberto').textContent   = data.parcela_aberto;
                document.getElementById('parcela_paga').textContent     = data.parcela_paga;
                document.getElementById('status_consorcio').textContent = data.status_consorcio || '–';

                if (data.proxima_parcela) {
                    document.getElementById('proxima_parcela').textContent =
                        `${data.proxima_parcela.vencimento} – R$ ${data.proxima_parcela.valor}`;
                }

                // Pagamentos
                const tbody = document.getElementById('pagamentos_table');
                tbody.innerHTML = '';
                if (!data.pagamentos.length) {
                    tbody.innerHTML = `<tr><td colspan="4" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">Nenhuma fatura localizada.</td></tr>`;
                    return;
                }
                data.pagamentos.forEach(f => {
                    const tr = document.createElement('tr');
                    tr.className = 'bg-white dark:bg-gray-800';
                    const acao = f.boleto_url
                        ? `<a href="${f.boleto_url}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200">Ver</a>`
                        : '';
                    tr.innerHTML =
                        `<td class="px-4 py-3">${f.vencimento}</td>` +
                        `<td class="px-4 py-3">R$ ${f.valor}</td>` +
                        `<td class="px-4 py-3"><span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium ${
                            f.status === 'pago'
                                ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100'
                                : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100'
                        }">${f.status.charAt(0).toUpperCase() + f.status.slice(1)}</span></td>` +
                        `<td class="px-4 py-3">${acao}</td>`;
                    tbody.appendChild(tr);
                });

                // Documentos
                const docList = document.getElementById('documentos_list');
                docList.innerHTML = '';
                if (!data.documentos.length) {
                    docList.innerHTML = `<li class="text-gray-500 dark:text-gray-400">Nenhum documento disponível no momento.</li>`;
                } else {
                    data.documentos.forEach(doc => {
                        const li = document.createElement('li');
                        li.className = 'flex items-center justify-between bg-gray-50 dark:bg-gray-900 p-4 rounded-lg';
                        li.innerHTML = `
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-file-text w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16c0 1.1.9 2 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>
                                <span>Contrato #${doc.id} - ${doc.data}</span>
                            </div>
                            <a href="${doc.url}" target="_blank" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200 text-sm">Baixar</a>
                        `;
                        docList.appendChild(li);
                    });
                }
            })
            .catch(err => console.error('Fetch error:', err));
    });
    </script>
</x-app-layout>
