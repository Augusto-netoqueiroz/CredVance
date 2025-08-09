<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight">
            {{ __("Logs") }}
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

        /* Estilos para as abas de filtro */
        .tab-credvance {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .tab-credvance.active {
            background: var(--gradient-primary);
            color: white;
            box-shadow: 0 4px 15px rgba(30, 136, 229, 0.3);
        }

        .tab-credvance:not(.active) {
            background-color: #e0e0e0; /* Cinza claro */
            color: var(--text-secondary);
        }

        .tab-credvance:not(.active):hover {
            background-color: #bdbdbd; /* Cinza um pouco mais escuro no hover */
            color: var(--text-primary);
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

        /* Estilos para o botão de copiar PIX */
        .copy-pix-btn {
            background-color: var(--primary-color);
            color: white;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 0.8rem;
            transition: background-color 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .copy-pix-btn:hover {
            background-color: var(--secondary-color);
        }

        /* Estilos para o botão de download de boleto */
        .btn-download-boleto {
            background-color: var(--success-color);
            color: white;
            padding: 8px 15px;
            border-radius: 8px;
            font-weight: 500;
            transition: background-color 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-download-boleto:hover {
            background-color: #388e3c;
        }

        /* Estilos para o output preview do cron */
        .output-preview {
            background-color: #f5f5f5;
            border-radius: 8px;
            padding: 10px;
            font-family: 'Fira Code', 'Consolas', 'Monaco', monospace;
            font-size: 0.85rem;
            line-height: 1.4;
            white-space: pre-wrap;
            word-break: break-all;
            max-height: 3rem; /* Limita a altura inicial */
            overflow: hidden;
            cursor: pointer;
            transition: max-height 0.3s ease, background-color 0.3s ease;
            border: 1px solid #e0e0e0;
        }

        .output-preview:hover {
            background-color: #eeeeee;
        }

        .output-preview.expanded {
            max-height: none;
        }

        /* Responsividade da tabela */
        @media (max-width: 768px) {
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

    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-blue-50 via-white to-blue-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto credvance-card p-6">

            {{-- Abas de filtro --}}
            <div class="mb-6 flex space-x-4">
                <a href="{{ route('logs.index', ['tipo' => 'atividade']) }}"
                   class="tab-credvance {{ $tipo === 'atividade' ? 'active' : '' }}">
                    Logs de Atividade
                </a>
                <a href="{{ route('logs.index', ['tipo' => 'boletos']) }}"
                   class="tab-credvance {{ $tipo === 'boletos' ? 'active' : '' }}">
                    Logs de Boletos
                </a>
                <a href="{{ route('logs.index', ['tipo' => 'cron']) }}"
                   class="tab-credvance {{ $tipo === 'cron' ? 'active' : '' }}">
                    Logs Cron
                </a>
            </div>

            @if ($tipo === 'boletos')
                {{-- Tabela Logs de Boletos --}}
                <div class="overflow-x-auto">
                    <table class="table-credvance">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pagamento ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contrato</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase" style="max-width: 14rem; min-width: 14rem;">PIX</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Enviado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data Envio</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aberto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data Abertura</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase max-w-xs break-words">IP</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase max-w-xs break-words">User Agent</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $log)
                                <tr>
                                    <td data-label="ID:" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">{{ $log->id }}</td>
                                    <td data-label="Pagamento ID:" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">{{ $log->pagamento_id }}</td>
                                    <td data-label="Contrato:" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">{{ $log->contrato_id }}</td>
                                    <td data-label="Cliente:" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">{{ $log->cliente->name ?? 'N/A' }}</td>

                                    {{-- Coluna PIX com botão copiar --}}
                                    <td data-label="PIX:" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200 max-w-xs overflow-hidden text-ellipsis" style="max-width: 14rem; white-space: nowrap;">
                                        @php
                                            $pixCode = $log->pix ?? ($log->pagamento->pix ?? null);
                                        @endphp

                                        @if(!empty($pixCode))
                                            <div class="flex items-center space-x-2">
                                                <span id="pix-code-{{ $log->id }}" style="display: inline-block; max-width: 12rem; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                                                    {{ $pixCode }}
                                                </span>
                                                <button 
                                                    class="copy-pix-btn"
                                                    data-clipboard-target="#pix-code-{{ $log->id }}"
                                                    title="Copiar PIX"
                                                    type="button"
                                                >
                                                    📋
                                                </button>
                                            </div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>

                                    <td data-label="Enviado:" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">{{ $log->enviado ? 'Sim' : 'Não' }}</td>
                                    <td data-label="Data Envio:" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">{{ optional($log->enviado_em)->format('d/m/Y H:i') ?? '-' }}</td>
                                    <td data-label="Aberto:" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">{{ $log->aberto ? 'Sim' : 'Não' }}</td>
                                    <td data-label="Data Abertura:" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">{{ optional($log->aberto_em)->format('d/m/Y H:i') ?? '-' }}</td>
                                    <td data-label="IP:" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200 max-w-xs break-words">{{ $log->ip ?? '-' }}</td>
                                    <td data-label="User Agent:" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200 max-w-xs break-words">{{ $log->user_agent ?? '-' }}</td>
                                    <td data-label="Ações:" class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                        @if($log->pagamento && $log->pagamento->boleto_path)
                                            <a href="{{ route('boleto.log.download', ['pagamentoId' => $log->pagamento->id]) }}"
                                               class="btn-download-boleto"
                                               target="_blank" rel="noopener noreferrer">
                                                Baixar Boleto
                                            </a>
                                        @else
                                            <span class="text-gray-400">Sem boleto</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center p-4 text-sm text-gray-500 dark:text-gray-400">Nenhum log encontrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            @elseif ($tipo === 'cron')
                {{-- Tabela Logs Cron --}}
                <div class="overflow-x-auto">
                    <table class="table-credvance">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Comando</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Executado em</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase max-w-xl break-words">Output</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $log)
                                <tr>
                                    <td data-label="ID:" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">{{ $log->id }}</td>
                                    <td data-label="Comando:" class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-700 dark:text-gray-200">{{ $log->command }}</td>
                                    <td data-label="Status:" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">{{ $log->status ?? '-' }}</td>
                                    <td data-label="Executado em:" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">{{ optional($log->executed_at)->format('d/m/Y H:i:s') ?? '-' }}</td>
                                    <td data-label="Output:" class="px-6 py-4 max-w-xl text-xs text-gray-700 dark:text-gray-200">
                                        <div class="output-preview" data-full-output="{{ $log->output ?? '-' }}">
                                            {{ $log->output ?? '-' }}
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center p-4 text-sm text-gray-500 dark:text-gray-400">Nenhum log encontrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            @else
                {{-- Tabela Logs de Atividade --}}
                <div class="overflow-x-auto">
                    <table class="table-credvance">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nome</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Módulo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Descrição</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $log)
                                <tr>
                                    <td data-label="ID:" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">{{ $log->id }}</td>
                                    <td data-label="Nome:" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">{{ $log->nome }}</td>
                                    <td data-label="Data:" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">
                                        {{ $log->data->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td data-label="IP:" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">{{ $log->ip }}</td>
                                    <td data-label="Módulo:" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">{{ $log->modulo }}</td>
                                    <td data-label="Descrição:" class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">{{ $log->descricao }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center p-4 text-sm text-gray-500 dark:text-gray-400">Nenhum log encontrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="mt-4">
                {{ $logs->links() }}
            </div>
        </div>
    </div>

    <script>
      document.addEventListener('DOMContentLoaded', () => {
        // Script para expandir output dos logs cron
        document.querySelectorAll('.output-preview').forEach(div => {
          div.style.maxHeight = '3rem'; // inicial limita a 3rem (~3 linhas)
          div.style.overflow = 'hidden';
          div.style.cursor = 'pointer';
          div.style.transition = 'max-height 0.3s ease';

          div.addEventListener('click', () => {
            if(div.style.maxHeight === '3rem') {
              div.style.maxHeight = 'none';
              div.classList.add('expanded');
            } else {
              div.style.maxHeight = '3rem';
              div.classList.remove('expanded');
            }
          });
        });

        // Script para copiar código PIX
        document.querySelectorAll('.copy-pix-btn').forEach(button => {
          button.addEventListener('click', () => {
            const targetSelector = button.getAttribute('data-clipboard-target');
            const pixCodeElement = document.querySelector(targetSelector);
            if (pixCodeElement) {
              const pixText = pixCodeElement.textContent.trim();
              navigator.clipboard.writeText(pixText).then(() => {
                button.textContent = '✔️';
                setTimeout(() => button.textContent = '📋', 1500);
              }).catch(() => {
                alert('Erro ao copiar o código PIX.');
              });
            }
          });
        });
      });
    </script>
</x-app-layout>

