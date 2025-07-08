<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Logs de Envio de Boletos
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full table-auto border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100 text-left">
                            <th class="border px-4 py-2">ID</th>
                            <th class="border px-4 py-2">Pagamento ID</th>
                            <th class="border px-4 py-2">Contrato</th>
                            <th class="border px-4 py-2">Cliente</th>
                            <th class="border px-4 py-2">Enviado</th>
                            <th class="border px-4 py-2">Data Envio</th>
                            <th class="border px-4 py-2">Linha Digitável</th>
                            <th class="border px-4 py-2">Pix (Copia e Cola)</th>
                            <th class="border px-4 py-2">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr class="hover:bg-gray-50">
                                <td class="border px-4 py-2">{{ $log->id }}</td>
                                <td class="border px-4 py-2">{{ $log->pagamento_id }}</td>
                                <td class="border px-4 py-2">{{ $log->contrato_id }}</td>
                                <td class="border px-4 py-2">{{ $log->cliente->name ?? 'N/A' }}</td>
                                <td class="border px-4 py-2">{{ $log->enviado ? 'Sim' : 'Não' }}</td>
                                <td class="border px-4 py-2">{{ optional($log->enviado_em)->format('d/m/Y H:i') ?? '-' }}</td>
                                <td class="border px-4 py-2 font-mono text-xs">
                                    {{ $log->pagamento->linha_digitavel ?? '-' }}
                                </td>
                                <td class="border px-4 py-2 font-mono text-xs">
                                    @if($log->pagamento && $log->pagamento->pix)
                                        <span>{{ $log->pagamento->pix }}</span>
                                        <button onclick="navigator.clipboard.writeText('{{ $log->pagamento->pix }}')" class="ml-2 px-2 py-1 bg-gray-200 rounded text-xs">Copiar</button>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="border px-4 py-2 text-center">
                                    @if($log->pagamento && $log->pagamento->boleto_path)
                                        <a href="{{ route('boleto.log.download', ['pagamentoId' => $log->pagamento->id]) }}" 
                                            class="inline-block px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700"
                                            target="_blank" rel="noopener noreferrer"
                                            >Baixar Boleto</a>
                                    @else
                                        <span class="text-gray-400">Sem boleto</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center p-4">Nenhum log encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
