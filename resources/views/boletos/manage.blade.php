<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Gerenciar Boletos') }}
        </h2>
    </x-slot>

```
<div class="py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
        @if(session('success'))
            <div class="mb-4 bg-green-100 text-green-800 p-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">Lista de Pagamentos</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-300">
                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-200">
                    <tr>
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">Cliente</th>
                        <th class="px-4 py-2">Valor</th>
                        <th class="px-4 py-2">Vencimento</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($pagamentos as $pag)
                    <tr class="bg-white dark:bg-gray-800">
                        <td class="px-4 py-2 align-middle">{{ $pag->id }}</td>
                        <td class="px-4 py-2 align-middle">{{ optional($pag->contrato->cliente)->name }}</td>
                        <td class="px-4 py-2 align-middle">R$ {{ number_format($pag->valor,2,',','.') }}</td>
                        <td class="px-4 py-2 align-middle">{{ \Carbon\Carbon::parse($pag->vencimento)->format('d/m/Y') }}</td>
                        <td class="px-4 py-2 align-middle">
                            @if($pag->status === 'pago')
                                <span class="px-2 py-1 bg-green-200 text-green-800 rounded-full text-xs">Pago</span>
                            @elseif($pag->status === 'pendente')
                                <span class="px-2 py-1 bg-yellow-200 text-yellow-800 rounded-full text-xs">Pendente</span>
                            @else
                                <span class="px-2 py-1 bg-gray-200 text-gray-800 rounded-full text-xs">{{ ucfirst($pag->status) }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 align-middle space-x-2">
                            <button
                                type="button"
                                onclick="openUploadModal({{ $pag->id }})"
                                class="text-blue-600 hover:text-blue-800 text-sm"
                            >
                                Upload
                            </button>
                            @if($pag->boleto)
                                <a href="{{ route('boleto.download', $pag) }}"
                                   class="text-green-600 hover:text-green-800 text-sm"
                                >
                                    Download
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal de Upload -->
<div id="uploadModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-30 items-center justify-center" onclick="closeUploadModal()">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg w-full max-w-md" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Enviar Boleto</h3>
            <button onclick="closeUploadModal()" class="text-red-500 hover:underline">Fechar</button>
        </div>
        <form id="uploadForm" action="{{ route('boleto.manage.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="pagamento_id" id="modalPagamentoId">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Arquivo (PDF)</label>
                <input type="file" name="boleto" accept="application/pdf" required class="mt-1 block w-full text-gray-900 dark:text-gray-100" />
                @error('boleto')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                    Enviar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openUploadModal(id) {
        document.getElementById('modalPagamentoId').value = id;
        const modal = document.getElementById('uploadModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeUploadModal() {
        const modal = document.getElementById('uploadModal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }
</script>
```

</x-app-layout>
