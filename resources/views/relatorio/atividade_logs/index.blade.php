<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight">
            {{ __('Logs') }}
        </h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">

            {{-- Abas de filtro --}}
            <div class="mb-6 flex space-x-4">
                <a href="{{ route('logs.index', ['tipo' => 'atividade']) }}"
                   class="px-4 py-2 rounded {{ $tipo === 'atividade' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700' }}">
                    Logs de Atividade
                </a>
                <a href="{{ route('logs.index', ['tipo' => 'boletos']) }}"
                   class="px-4 py-2 rounded {{ $tipo === 'boletos' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700' }}">
                    Logs de Boletos
                </a>
            </div>

            @if ($tipo === 'boletos')
                {{-- Tabela Logs de Boletos --}}
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pagamento ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contrato</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Enviado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data Envio</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aberto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data Abertura</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase max-w-xs break-words">IP</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase max-w-xs break-words">User Agent</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($logs as $log)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">{{ $log->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">{{ $log->pagamento_id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">{{ $log->contrato_id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">{{ $log->cliente->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">{{ $log->enviado ? 'Sim' : 'Não' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">{{ optional($log->enviado_em)->format('d/m/Y H:i') ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">{{ $log->aberto ? 'Sim' : 'Não' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">{{ optional($log->aberto_em)->format('d/m/Y H:i') ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200 max-w-xs break-words">{{ $log->ip ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200 max-w-xs break-words">{{ $log->user_agent ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                    @if($log->pagamento && $log->pagamento->boleto_path)
                                        <a href="{{ route('boleto.log.download', ['pagamentoId' => $log->pagamento->id]) }}"
                                           class="inline-block px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700"
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
                                <td colspan="11" class="text-center p-4 text-sm text-gray-500 dark:text-gray-400">Nenhum log encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @else
                {{-- Tabela Logs de Atividade --}}
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nome</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Módulo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Descrição</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($logs as $log)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">{{ $log->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">{{ $log->nome }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">
                                    {{ $log->data->format('d/m/Y H:i:s') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">{{ $log->ip }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">{{ $log->modulo }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">{{ $log->descricao }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">Nenhum log encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @endif

            <div class="mt-4">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
