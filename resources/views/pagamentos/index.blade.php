<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h2 class="font-semibold text-2xl text-gray-800">
                {{ __('Boletos Inter (API)') }}
            </h2>
            <form method="GET" action="{{ route('pagamentos.index') }}" class="flex gap-2">
                <input
                    type="text"
                    name="codigo"
                    class="form-control border rounded px-3 py-1 w-56"
                    placeholder="Buscar por Código do Boleto"
                    value="{{ old('codigo', $codigo ?? '') }}"
                />
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                    Buscar
                </button>
                @if(!empty($codigo))
                    <a href="{{ route('pagamentos.index') }}" class="btn btn-outline-secondary">
                        Limpar
                    </a>
                @endif
            </form>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="alert alert-danger mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white shadow-xl rounded-2xl p-6">
                <div class="overflow-x-auto">
                    <table class="table table-hover table-bordered align-middle mb-0">
                        <thead class="bg-blue-900 text-white">
                            <tr>
                                <th>#</th>
                                <th>Nosso Número</th>
                                <th>Vencimento</th>
                                <th>Valor</th>
                                <th>Status</th>
                                <th>Pagador</th>
                                <th class="text-center" style="width:120px;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($boletos as $boleto)
                                @php
                                    $cobranca = $boleto['cobranca'] ?? [];
                                    $status = $cobranca['situacao'] ?? null;
                                    $badge = match($status) {
                                        'PAGO' => 'success',
                                        'PENDENTE' => 'warning',
                                        'ATRASADO' => 'danger',
                                        'EXPIRADO' => 'secondary',
                                        default => 'secondary'
                                    };
                                @endphp
                                <tr class="transition hover:bg-blue-50">
                                    <td class="font-mono text-xs">{{ $cobranca['codigoSolicitacao'] ?? '-' }}</td>
                                    <td>{{ $cobranca['seuNumero'] ?? '-' }}</td>
                                    <td>
                                        @if(!empty($cobranca['dataVencimento']))
                                            {{ \Carbon\Carbon::parse($cobranca['dataVencimento'])->format('d/m/Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <span class="font-semibold text-gray-700">R$ {{ number_format($cobranca['valorNominal'] ?? 0, 2, ',', '.') }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $badge }} text-white px-2 py-1 rounded">
                                            {{ $status ?? '-' }}
                                        </span>
                                    </td>
                                    <td>{{ $cobranca['pagador']['nome'] ?? '-' }}</td>
                                    <td class="text-center">
                                        @if(!empty($cobranca['codigoSolicitacao']))
                                            <a href="{{ route('pagamentos.show', $cobranca['codigoSolicitacao']) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Detalhes
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Nenhum boleto encontrado{{ $codigo ? ' para o código pesquisado.' : ' na API Inter.' }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
