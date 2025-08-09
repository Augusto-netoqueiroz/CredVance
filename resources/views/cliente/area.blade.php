<x-app-layout>
    {{-- Cabeçalho --}}
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight">
            Área do Cliente
        </h2>
    </x-slot>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            /* Cores baseadas no padrão CredVance */
            --primary-color: #1e88e5; /* Azul principal */
            --secondary-color: #0d47a1; /* Azul mais escuro */
            --accent-color: #42a5f5; /* Azul claro */
            --success-color: #4caf50;
            --danger-color: #f44336;
            --warning-color: #ff9800;
            --gradient-primary: linear-gradient(135deg, #1e88e5, #0d47a1);
            --text-primary: #212121;
            --text-secondary: #757575;
            --bg-light: #fafafa;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        /* Inspirado no modelo: Cards com backdrop-filter e bordas arredondadas */
        .credvance-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(13, 71, 161, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        .credvance-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 70px rgba(13, 71, 161, 0.25);
        }

        /* Inspirado no modelo: Botões com gradiente e animações */
        .btn-credvance {
            background: var(--gradient-primary);
            color: white;
            padding: 12px 28px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(30, 136, 229, 0.3);
        }

        .btn-credvance:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(30, 136, 229, 0.4);
            color: white;
        }

        /* Inspirado no modelo: Indicadores com ícones coloridos e animações */
        .indicator-credvance {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .indicator-credvance:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
        }

        .indicator-icon {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .indicator-icon.blue {
            background: var(--primary-color);
        }

        .indicator-icon.emerald {
            background: var(--success-color);
        }

        .indicator-icon.amber {
            background: var(--warning-color);
        }

        .indicator-credvance:hover .indicator-icon {
            transform: scale(1.1);
        }

        /* Inspirado no modelo: Formulários com bordas suaves e animações */
        .form-control-credvance {
            border: 2px solid #e3f2fd;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            width: 100%;
            color: var(--text-primary);
        }

        .form-control-credvance:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(30, 136, 229, 0.15);
            background: white;
            outline: none;
            transform: translateY(-1px);
        }

        /* Inspirado no modelo: Alertas com gradientes suaves */
        .alert-credvance {
            border-radius: 10px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .alert-success-credvance {
            background: linear-gradient(135deg, rgba(76, 175, 80, 0.1), rgba(76, 175, 80, 0.05));
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }

        .alert-danger-credvance {
            background: linear-gradient(135deg, rgba(244, 67, 54, 0.1), rgba(244, 67, 54, 0.05));
            color: var(--danger-color);
            border-left: 4px solid var(--danger-color);
        }

        /* Inspirado no modelo: Tabela com espaçamento e hover effects */
        .table-credvance {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 8px;
        }

        .table-credvance thead th {
            background: var(--bg-light);
            color: var(--text-secondary);
            font-weight: 600;
            padding: 15px 20px;
            text-align: left;
            border-bottom: 2px solid #e0e0e0;
            border-radius: 10px 10px 0 0;
        }

        .table-credvance tbody tr {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
        }

        .table-credvance tbody tr:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .table-credvance tbody td {
            padding: 15px 20px;
            color: var(--text-primary);
            vertical-align: middle;
        }

        .table-credvance tbody tr td:first-child {
            border-radius: 10px 0 0 10px;
        }

        .table-credvance tbody tr td:last-child {
            border-radius: 0 10px 10px 0;
        }

        /* Inspirado no modelo: Status badges com cores suaves */
        .status-badge-credvance {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: capitalize;
        }

        .status-badge-credvance.pago {
            background-color: rgba(76, 175, 80, 0.15);
            color: var(--success-color);
        }

        .status-badge-credvance.pendente {
            background-color: rgba(255, 152, 0, 0.15);
            color: var(--warning-color);
        }

        /* Inspirado no modelo: Modal com backdrop-filter */
        .modal-credvance {
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
        }

        .modal-content-credvance {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 15px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Inspirado no modelo: Animações suaves */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease;
        }

        /* Responsividade inspirada no modelo */
        @media (max-width: 768px) {
            .indicator-credvance {
                text-align: center;
                padding: 15px;
            }

            .table-credvance thead {
                display: none;
            }

            .table-credvance tbody, .table-credvance tr, .table-credvance td {
                display: block;
                width: 100%;
            }

            .table-credvance tr {
                margin-bottom: 15px;
                border: 1px solid #e0e0e0;
                border-radius: 10px;
            }

            .table-credvance td {
                text-align: right;
                padding-left: 50%;
                position: relative;
                border-radius: 0 !important;
            }

            .table-credvance td::before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                width: calc(50% - 20px);
                padding-right: 10px;
                white-space: nowrap;
                text-align: left;
                font-weight: 600;
                color: var(--text-primary);
            }
        }
    </style>

    @if(session('success'))
    <div 
        x-data="{ open: true }"
        x-show="open"
        x-transition
        class="fixed inset-0 flex items-center justify-center z-50 modal-credvance"
    >
        <div class="modal-content-credvance w-full max-w-md p-6 text-center">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                {{ session('success') }}
            </h2>
            <button @click="open = false"
                class="btn-credvance">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                OK
            </button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div 
        x-data="{ open: true }"
        x-show="open"
        x-transition
        class="fixed inset-0 flex items-center justify-center z-50 modal-credvance"
    >
        <div class="modal-content-credvance w-full max-w-md p-6 text-center">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                {{ session('error') }}
            </h2>
            <button @click="open = false"
                class="btn-credvance bg-red-500 hover:bg-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                OK
            </button>
        </div>
    </div>
    @endif

    <div class="py-10 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-blue-50 via-white to-blue-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto animate-fade-in-up">

            {{-- BOTÃO PARA ABRIR O MODAL --}}
            <div class="mb-6 flex justify-end">
                <button
                    id="btnOpenModal"
                    class="btn-credvance"
                >
                    <x-lucide-plus class="w-5 h-5" />
                    Contratar Novas Cotas
                </button>
            </div>

            {{-- INDICADORES --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <div class="indicator-credvance flex items-center gap-4">
                    <div class="indicator-icon blue">
                        <x-lucide-credit-card class="w-6 h-6" />
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 font-medium">Parcelas em Aberto</div>
                        <div id="parcela_aberto" class="text-2xl font-bold text-blue-600 dark:text-blue-400">–</div>
                    </div>
                </div>

                <div class="indicator-credvance flex items-center gap-4">
                    <div class="indicator-icon emerald">
                        <x-lucide-check-circle class="w-6 h-6" />
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 font-medium">Parcelas Pagas</div>
                        <div id="parcela_paga" class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">–</div>
                    </div>
                </div>

                <div class="indicator-credvance flex items-center gap-4">
                    <div class="indicator-icon amber">
                        <x-lucide-calendar class="w-6 h-6" />
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 font-medium">Próxima Parcela</div>
                        <div id="proxima_parcela" class="text-2xl font-bold text-amber-600 dark:text-amber-400">–</div>
                    </div>
                </div>

                <div class="indicator-credvance flex items-center gap-4">
                    <div class="indicator-icon blue">
                        <x-lucide-badge-check class="w-6 h-6" />
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 font-medium">Status do Consórcio</div>
                        <div id="status_consorcio" class="text-2xl font-bold text-blue-600 dark:text-blue-400">–</div>
                    </div>
                </div>
            </div>

            {{-- FATURAS --}}
            <div class="credvance-card p-6 mb-10">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-white mb-6 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Faturas
                </h3>
                <div class="overflow-x-auto">
                    <table class="table-credvance">
                        <thead>
                            <tr>
                                <th>Vencimento</th>
                                <th>Valor</th>
                                <th>Status</th>
                                <th>Pix</th>
                                <th>Boleto</th>
                            </tr>
                        </thead>
                        <tbody id="pagamentos_table">
                            <tr>
                                <td colspan="5" class="text-center text-gray-500 dark:text-gray-400" data-label="Carregando:">
                                    Carregando...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- DOCUMENTOS --}}
            <div class="credvance-card p-6">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-white mb-6 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Documentos
                </h3>
                <ul id="documentos_list" class="space-y-4">
                    <li class="text-gray-500 dark:text-gray-400">Carregando documentos...</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- MODAL --}}
    <div id="newAccountModal" class="fixed inset-0 modal-credvance flex items-center justify-center z-50 hidden">
        <div class="modal-content-credvance w-full max-w-md p-6 relative">
            <div class="absolute top-3 right-3">
                <button id="btnCloseModal" class="text-gray-800 dark:text-gray-200 hover:text-red-500 dark:hover:text-red-400 transition" aria-label="Fechar modal">
                    <x-lucide-x class="w-6 h-6" />
                </button>
            </div>
            <div class="pt-8">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Contratar Novas Contas</h3>
                <p class="mb-4 text-gray-600 dark:text-gray-300">
                    Clique abaixo para abrir o formulário de contratação:
                </p>
                <a href="{{ route('contratos.create') }}"
                    class="btn-credvance w-full justify-center"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                    Ir para Contratação
                </a>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        if (window.lucide) lucide.createIcons();

        // Modal open/close
        const modal = document.getElementById('newAccountModal');
        document.getElementById('btnOpenModal').addEventListener('click', () => modal.classList.remove('hidden'));
        document.getElementById('btnCloseModal').addEventListener('click', () => modal.classList.add('hidden'));
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });

        // Fetch dados do cliente e renderiza
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
                    tbody.innerHTML = `<tr><td colspan="5" class="text-center text-gray-500 dark:text-gray-400" data-label="Nenhuma fatura:">Nenhuma fatura localizada.</td></tr>`;
                    return;
                }
                data.pagamentos.forEach(f => {
                    const tr = document.createElement('tr');

                    // Botão Pix
                    let btnPix = '';
                    if (f.pix && typeof f.pix === "string" && f.pix.trim() !== "") {
                        btnPix = `<button
                            class="btn-copiar-pix btn-credvance text-xs px-3 py-1"
                            data-pix="${f.pix.trim()}"
                            data-id="${f.id}"
                            title="Copiar código Pix"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" /><path d="M5 15V5a2 2 0 0 1 2-2h10"/></svg>
                            Copiar Pix
                        </button>`;
                    }

                    // Botão Baixar Boleto
                    let acao = '';
                    if (f.boleto_url) {
                        acao = `<a href="${f.boleto_url}" target="_blank"
                            class="btn-baixar-boleto btn-credvance text-xs px-3 py-1"
                            data-id="${f.id}"
                            download
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                            Baixar Boleto
                        </a>`;
                    }

                    tr.innerHTML =
                        `<td data-label="Vencimento:">${f.vencimento}</td>` +
                        `<td data-label="Valor:">R$ ${f.valor}</td>` +
                        `<td data-label="Status:"><span class="status-badge-credvance ${f.status}">${f.status.charAt(0).toUpperCase() + f.status.slice(1)}</span></td>` +
                        `<td data-label="Pix:">${btnPix}</td>` +
                        `<td data-label="Boleto:">${acao}</td>`;

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
                        li.className = 'credvance-card p-4 flex items-center justify-between';
                        li.innerHTML = `
                            <div class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="font-medium">Contrato #${doc.id} - ${doc.data}</span>
                            </div>
                            <a href="${doc.url}" target="_blank" class="btn-credvance text-sm px-4 py-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                                Baixar
                            </a>
                        `;
                        docList.appendChild(li);
                    });
                }
            })
            .catch(err => console.error('Fetch error:', err));

        // COPIAR PIX e LOG
        document.getElementById('pagamentos_table').addEventListener('click', function(e) {
            const btnPix = e.target.closest('.btn-copiar-pix');
            if (btnPix) {
                const pixCode = btnPix.getAttribute('data-pix');
                const pagamentoId = btnPix.getAttribute('data-id');
                if (!pixCode) return;
                navigator.clipboard.writeText(pixCode).then(() => {
                    btnPix.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Copiado!`;
                    setTimeout(() => {
                        btnPix.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" /><path d="M5 15V5a2 2 0 0 1 2-2h10"/></svg> Copiar Pix`;
                    }, 1200);
                    // LOG copiar Pix
                    fetch('{{ route('cliente.log-activity') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            type: 'copiou_pix',
                            pagamento_id: pagamentoId
                        })
                    })
                    .then(r => r.json())
                    .then(d => console.log('Log Pix:', d))
                    .catch(err => console.error('Erro log Pix:', err));
                });
                return;
            }
            // BAIXAR BOLETO
            const btnBoleto = e.target.closest('.btn-baixar-boleto');
            if (btnBoleto) {
                const pagamentoId = btnBoleto.getAttribute('data-id');
                e.preventDefault();
                fetch('{{ route('cliente.log-activity') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        type: 'baixou_boleto',
                        pagamento_id: pagamentoId
                    })
                })
                .then(r => r.json())
                .then(d => {
                    console.log('Log Boleto:', d);
                    window.open(btnBoleto.href, '_blank');
                })
                .catch(err => {
                    console.error('Erro log Boleto:', err);
                    window.open(btnBoleto.href, '_blank');
                });
                return false;
            }
        });
    });
    </script>
</x-app-layout>

