<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Gerenciar Boletos') }}
        </h2>
    </x-slot>

    <div class="py-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
            @if(session('success'))
                <div class="mb-4 bg-green-100 text-green-800 p-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">Lista de Pagamentos</h3>

            <form method="GET" class="mb-4 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2 w-full">
                    <div>
                        <label for="cliente" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cliente</label>
                        <select name="cliente" id="cliente" class="select2 border rounded px-2 py-1 w-full">
                            <option value="">Todos os clientes</option>
                            @foreach(App\Models\User::where('role', 'cliente')->orderBy('name')->get() as $cliente)
                                <option value="{{ $cliente->id }}" {{ request('cliente') == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->id }} - {{ $cliente->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="data_ini" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data Início</label>
                        <input type="date" name="data_ini" id="data_ini" value="{{ request('data_ini') }}" class="border rounded px-2 py-1 w-full">
                    </div>
                    <div>
                        <label for="data_fim" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data Fim</label>
                        <input type="date" name="data_fim" id="data_fim" value="{{ request('data_fim') }}" class="border rounded px-2 py-1 w-full">
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select name="status" id="status" class="border rounded px-2 py-1 w-full">
                            <option value="">Todos</option>
                            <option value="pago" {{ request('status') === 'pago' ? 'selected' : '' }}>Pago</option>
                            <option value="pendente" {{ request('status') === 'pendente' ? 'selected' : '' }}>Pendente</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-wrap items-end gap-2">
                    <div>
                        <label for="per_page" class="text-sm font-medium text-gray-700 dark:text-gray-300">Exibir</label>
                        <select name="per_page" id="per_page" class="border rounded px-2 py-1 ml-2">
                            @foreach([10, 25, 50, 100] as $qtd)
                                <option value="{{ $qtd }}" {{ request('per_page', 25) == $qtd ? 'selected' : '' }}>{{ $qtd }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded text-sm">Atualizar</button>
                </div>
            </form>

            <div class="overflow-x-auto">
                <table id="pagamentosTable" class="min-w-full text-sm text-left text-gray-700 dark:text-gray-300">
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
                            <td class="px-4 py-2">{{ $pag->id }}</td>
                            <td class="px-4 py-2">{{ optional($pag->contrato->cliente)->name }}</td>
                            <td class="px-4 py-2">R$ {{ number_format($pag->valor,2,',','.') }}</td>
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($pag->vencimento)->format('d/m/Y') }}</td>
                            <td class="px-4 py-2">
                                @if($pag->status === 'pago')
                                    <span class="px-2 py-1 bg-green-200 text-green-800 rounded-full text-xs">Pago</span>
                                @elseif($pag->status === 'pendente')
                                    <span class="px-2 py-1 bg-yellow-200 text-yellow-800 rounded-full text-xs">Pendente</span>
                                @else
                                    <span class="px-2 py-1 bg-gray-200 text-gray-800 rounded-full text-xs">{{ ucfirst($pag->status) }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 flex flex-wrap gap-2">
                                {{-- Botão: Enviar Boleto (só se ainda não tiver boleto) --}}
                                @if(!$pag->boleto)
                                    <button type="button" onclick="openUploadModal({{ $pag->id }})" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-xs w-32" title="Enviar Boleto">
                                        <i data-lucide="upload-cloud" class="w-4 h-4"></i>
                                    </button>
                                @endif

                                {{-- Botão: Baixar Boleto --}}
                                @if($pag->boleto)
                                    <a href="{{ route('boleto.download', $pag) }}" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded text-xs w-32 flex items-center justify-center" title="Baixar Boleto">
                                        <i data-lucide="file-text" class="w-4 h-4"></i>
                                    </a>
                                @endif

                                {{-- Botão: Baixar Comprovante --}}
                                @if($pag->comprovante && Storage::disk('local')->exists($pag->comprovante))
                                    <a href="{{ route('boleto.comprovante.download', $pag->id) }}" class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-2 rounded text-xs w-32 flex items-center justify-center" title="Baixar Comprovante">
                                        <i data-lucide="image" class="w-4 h-4"></i>
                                    </a>
                                @endif

                                {{-- Botão: Marcar como Pago (só se o status não for "pago") --}}
                                @if($pag->status !== 'pago')
                                    <button type="button" onclick="openStatusModal({{ $pag->id }})" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded text-xs w-32" title="Marcar como Pago">
                                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                                    </button>
                                @endif
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $pagamentos->links() }}
            </div>
        </div>
    </div>

    <!-- Modal: Upload de Boleto -->
    <div id="uploadModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl w-full max-w-md">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Enviar Boleto</h2>
            <form method="POST" action="{{ route('boleto.manage.upload') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="modalPagamentoId" name="pagamento_id">
                <input type="file" name="boleto" class="mb-4 w-full border rounded p-2" required>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeUploadModal()" class="px-4 py-2 bg-gray-500 text-white rounded">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Enviar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Marcar como Pago -->
    <div id="statusModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl w-full max-w-md">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Confirmar Pagamento</h2>
            <form method="POST" action="{{ route('boleto.marcar.pago') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="statusPagamentoId" name="pagamento_id">
                <textarea name="descricao" class="w-full mb-4 border rounded p-2 text-sm" placeholder="Descrição ou observação"></textarea>
                <input type="file" name="comprovante" class="w-full mb-4 border rounded p-2 text-sm">
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeStatusModal()" class="px-4 py-2 bg-gray-500 text-white rounded">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Confirmar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openUploadModal(id) {
            document.getElementById('modalPagamentoId').value = id;
            document.getElementById('uploadModal').classList.remove('hidden');
            document.getElementById('uploadModal').classList.add('flex');
        }

        function closeUploadModal() {
            document.getElementById('uploadModal').classList.remove('flex');
            document.getElementById('uploadModal').classList.add('hidden');
        }

        function openStatusModal(id) {
            document.getElementById('statusPagamentoId').value = id;
            document.getElementById('statusModal').classList.remove('hidden');
            document.getElementById('statusModal').classList.add('flex');
        }

        function closeStatusModal() {
            document.getElementById('statusModal').classList.remove('flex');
            document.getElementById('statusModal').classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', function () {
            if (window.jQuery && $.fn.select2) {
                $('#cliente').select2({
                    placeholder: "Selecione um cliente",
                    allowClear: true,
                    width: '100%'
                });
            }
        });
    </script>

    <style>
        [title]:hover::after {
            content: attr(title);
            position: absolute;
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            white-space: nowrap;
            z-index: 1000;
        }
    </style>
</x-app-layout>
