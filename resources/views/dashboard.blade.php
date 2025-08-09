<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight">
            {{ __("Dashboard Administrativa - Consórcio") }}
        </h2>
    </x-slot>

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

        .indicator-icon.green {
            background: var(--success-color);
        }

        .indicator-icon.indigo {
            background: var(--secondary-color);
        }

        .indicator-icon.red {
            background: var(--danger-color);
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

    <div class="py-10 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-blue-50 via-white to-blue-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto animate-fade-in-up">

            <!-- Cards com Indicadores Modernos -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <div onclick="openModal('modalCotas')" class="cursor-pointer indicator-credvance flex items-center gap-4">
                    <div class="indicator-icon blue">
                        <i data-lucide="copy" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 font-medium">Cotas Vendidas</div>
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $cotasVendidas }}</div>
                    </div>
                </div>

                <div onclick="openModal('modalContratos')" class="cursor-pointer indicator-credvance flex items-center gap-4">
                    <div class="indicator-icon green">
                        <i data-lucide="file-text" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 font-medium">Contratos Ativos</div>
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $contratosCount }}</div>
                    </div>
                </div>

                <div onclick="openModal('modalFaturamento')" class="cursor-pointer indicator-credvance flex items-center gap-4">
                    <div class="indicator-icon indigo">
                        <i data-lucide="dollar-sign" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 font-medium">Faturamento (Mês)</div>
                        <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">R$ {{ number_format($faturamentoMes,2,',','.') }}</div>
                    </div>
                </div>

                <div onclick="openModal('modalPendentes')" class="cursor-pointer indicator-credvance flex items-center gap-4">
                    <div class="indicator-icon red">
                        <i data-lucide="clock" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 font-medium">Pagamentos Pendentes</div>
                        <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $pendentesCount }} | R$ {{ number_format($pendentesValor,2,',','.') }}</div>
                    </div>
                </div>
            </div>

            <!-- Modais com Tabelas -->
            @foreach (['Cotas', 'Contratos', 'Faturamento', 'Pendentes'] as $modal)
            <div
                id="modal{{ $modal }}"
                class="fixed inset-0 z-50 hidden modal-credvance flex items-center justify-center"
                onclick="handleBackdropClick(event, 'modal{{ $modal }}')"
            >
                <div
                    class="modal-content-credvance p-6 w-full max-w-lg relative"
                    onclick="event.stopPropagation()"
                >
                    <div class="flex justify-end space-x-2 mb-4">
                        <button onclick="exportData('{{ strtolower($modal) }}','pdf')" class="btn-credvance text-xs px-3 py-1 bg-blue-600 hover:bg-blue-700">PDF</button>
                        <button onclick="exportData('{{ strtolower($modal) }}','excel')" class="btn-credvance text-xs px-3 py-1 bg-green-600 hover:bg-green-700">Excel</button>
                        <button onclick="exportData('{{ strtolower($modal) }}','csv')" class="btn-credvance text-xs px-3 py-1 bg-gray-600 hover:bg-gray-700">CSV</button>
                    </div>
                    <div class="flex justify-between items-center mb-3">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Detalhes - {{ $modal }}</h2>
                        <button onclick="closeModal('modal{{ $modal }}')" class="text-sm text-red-500 hover:underline">Fechar</button>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">Informações detalhadas sobre {{ strtolower($modal) }}.</p>
                    <div class="overflow-x-auto">
                        <table class="table-credvance">
                            <thead>
                                <tr>
                                    @if($modal==='Cotas'||$modal==='Contratos')
                                        <th>Cliente</th>
                                        <th>Cotas</th>
                                        <th>Criado Em</th>
                                    @elseif($modal==='Faturamento')
                                        <th>Cliente</th>
                                        <th>Valor</th>
                                        <th>Vencimento</th>
                                        <th>Pago Em</th>
                                    @else
                                        <th>Cliente</th>
                                        <th>Valor</th>
                                        <th>Vencimento</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody id="modal{{ $modal }}Table">
                                <tr>
                                    <td class="text-center" colspan="{{ $modal==='Faturamento'?4:3 }}">Carregando...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Gráficos -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                <div class="credvance-card p-6">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-white mb-4">Vendas dos últimos 6 meses</h3>
                    <canvas id="chartVendasMes" class="w-full h-52"></canvas>
                </div>
                <div class="credvance-card p-6">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-white mb-4">Faturamento x Pendências (últimos 6 meses)</h3>
                    <canvas id="chartFaturamento" class="w-full h-52"></canvas>
                </div>
            </div>

            <!-- Alertas e Assembleias -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
                <div class="credvance-card p-6">
                    <h3 class="text-lg font-semibold text-red-600 dark:text-red-400 mb-4">Alertas</h3>
                    <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-300 space-y-1">
                        <li>Cotas em atraso</li>
                        <li>Documentos pendentes</li>
                        <li>Grupos com inadimplência alta</li>
                    </ul>
                </div>
                <div class="credvance-card p-6">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-white mb-4">Próximas Assembleias</h3>
                    <div class="text-gray-500 dark:text-gray-400 text-sm">(Listagem futura)</div>
                </div>
            </div>

            <!-- Atividades Recentes -->
            <div class="credvance-card p-6 mb-10">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-white mb-4">Atividades Recentes</h3>
                <div class="text-gray-500 dark:text-gray-400 text-sm">(Log de atividades recente)</div>
            </div>

            <!-- Atalhos Rápidos -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
                <a href="{{ route('boleto.manage.form') }}" class="btn-credvance bg-green-600 hover:bg-green-700">Registrar boleto</a>
                <a href="{{ route('usuarios.index') }}" class="btn-credvance">Gerenciar Usuários</a>
                <a href="{{ route('contratos.create') }}" class="btn-credvance">Criar Contrato</a>
            </div>

        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ctxBar = document.getElementById('chartVendasMes').getContext('2d');
            new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($graficoMeses) !!},
                    datasets: [{
                        label: 'Cotas Vendidas',
                        data: {!! json_encode($graficoValores) !!},
                        backgroundColor: 'rgba(30, 136, 229, 0.7)', // Primary color CredVance
                        borderRadius: 8
                    }]
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });

            const ctxBar2 = document.getElementById('chartFaturamento').getContext('2d');
            new Chart(ctxBar2, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($graficoFaturamentoLabels) !!},
                    datasets: [
                        {
                            label: 'Recebido',
                            data: {!! json_encode($graficoFaturamentoPago) !!},
                            backgroundColor: 'rgba(76, 175, 80, 0.7)', // Success color CredVance
                            borderRadius: 6
                        },
                        {
                            label: 'Pendente',
                            data: {!! json_encode($graficoFaturamentoPendente) !!},
                            backgroundColor: 'rgba(244, 67, 54, 0.7)', // Danger color CredVance
                            borderRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: { y: { beginAtZero: true } },
                    plugins: { legend: { position: 'bottom' } }
                }
            });

            if (window.lucide) lucide.createIcons();
        });
    </script>

    <!-- Modal Control Functions -->
    <script>
      // Abre modal e busca dados
      function openModal(modalId) {
        const urlMap = {
          modalCotas: '/dashboard/detalhes/cotas',
          modalContratos: '/dashboard/detalhes/contratos',
          modalFaturamento: '/dashboard/detalhes/faturamento',
          modalPendentes: '/dashboard/detalhes/pendentes'
        };
        const url = urlMap[modalId];
        if (!url) return console.error(`URL não encontrada para ${modalId}`);
        fetch(url)
          .then(res => res.json())
          .then(items => {
            const tbody = document.getElementById(`${modalId}Table`);
            tbody.innerHTML = '';
            if (items.length) {
              items.forEach(item => {
                let row = '<tr>';
                if (modalId === 'modalCotas' || modalId === 'modalContratos') {
                  row += `<td data-label="Cliente:">${item['Cliente']}</td>` +
                         `<td data-label="Cotas:">${item['Cotas']}</td>` +
                         `<td data-label="Criado Em:">${item['Criado Em']}</td>`;
                } else if (modalId === 'modalFaturamento') {
                  row += `<td data-label="Cliente:">${item['Cliente']}</td>` +
                         `<td data-label="Valor:">${item['Valor']}</td>` +
                         `<td data-label="Vencimento:">${item['Vencimento']}</td>` +
                         `<td data-label="Pago Em:">${item['Pago Em']}</td>`;
                } else {
                  row += `<td data-label="Cliente:">${item['Cliente']}</td>` +
                         `<td data-label="Valor:">${item['Valor']}</td>` +
                         `<td data-label="Vencimento:">${item['Vencimento']}</td>`;
                }
                row += '</tr>';
                tbody.innerHTML += row;
              });
            } else {
              tbody.innerHTML = `<tr><td class="text-center" colspan="${modalId==='modalFaturamento'?4:3}" data-label="Sem dados:">Sem dados disponíveis</td></tr>`;
            }
            document.getElementById(modalId).classList.remove('hidden');
          })
          .catch(err => {
            console.error(err);
            const tbody = document.getElementById(`${modalId}Table`);
            tbody.innerHTML = `<tr><td class="text-center" colspan="${modalId==='modalFaturamento'?4:3}" data-label="Erro:">Erro ao carregar dados</td></tr>`;
            document.getElementById(modalId).classList.remove('hidden');
          });
      }

      // Fecha modal
      function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
      }

      // Fecha ao clicar no backdrop
      function handleBackdropClick(event, modalId) {
        if (event.target.id === modalId) closeModal(modalId);
      }

      // Exporta dados
      function exportData(modal, type) {
        const exportUrls = {
          cotas: '/dashboard/detalhes/cotas',
          contratos: '/dashboard/detalhes/contratos',
          faturamento: '/dashboard/detalhes/faturamento',
          pendentes: '/dashboard/detalhes/pendentes'
        };
        const base = exportUrls[modal];
        if (!base) {
          console.error('Modal inválido para export:', modal);
          return;
        }
        window.open(`${base}?export=${type}`, '_blank');
      }
    </script>
</x-app-layout>

