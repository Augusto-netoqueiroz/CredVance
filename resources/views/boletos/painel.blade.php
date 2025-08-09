@extends('layouts.app2')

@section('content')
    <div class="py-4 px-6 max-w-7xl mx-auto">
        {{-- Título --}}
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight mb-6">Painel de Boletos</h2>

        {{-- Filtros --}}
        <form method="GET" class="mb-4 grid grid-cols-1 md:grid-cols-4 gap-4">
            <select name="cliente" class="form-select border rounded px-2 py-1">
                <option value="">Todos os Clientes</option>
                @foreach ($clientes as $c)
                    <option value="{{ $c->id }}" @selected(request('cliente') == $c->id)>{{ $c->name }}</option>
                @endforeach
            </select>

            <select name="status" class="form-select border rounded px-2 py-1">
                <option value="">Todos os Status</option>
                <option value="pago" @selected(request('status') == 'pago')>Pago</option>
                <option value="pendente" @selected(request('status') == 'pendente')>Pendente</option>
                <option value="expirado" @selected(request('status') == 'expirado')>Expirado</option>
            </select>

            <input type="date" name="data_ini" class="form-control border rounded px-2 py-1" value="{{ request('data_ini') }}">
            <input type="date" name="data_fim" class="form-control border rounded px-2 py-1" value="{{ request('data_fim') }}">

            <button class="btn btn-primary col-span-1 md:col-span-4">Filtrar</button>
        </form>

        {{-- Botão Novo --}}
        <div class="text-right mb-2">
            <button class="btn btn-success" onclick="document.getElementById('modalNovoBoleto').showModal()">+ Novo Boleto</button>
        </div>

        {{-- Tabela --}}
        <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded shadow-sm p-3">
            <table class="table-auto w-full border text-sm text-gray-700 dark:text-gray-200">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700">
                        <th class="border px-2 py-1">ID</th>
                        <th class="border px-2 py-1">Cliente</th>
                        <th class="border px-2 py-1">Valor</th>
                        <th class="border px-2 py-1">Vencimento</th>
                        <th class="border px-2 py-1">Status</th>
                        <th class="border px-2 py-1">Solicitação</th>
                        <th class="border px-2 py-1">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pagamentos as $p)
                        <tr>
                            <td class="border px-2 py-1">{{ $p->id }}</td>
                            <td class="border px-2 py-1">{{ optional($p->contrato->cliente)->name }}</td>
                            <td class="border px-2 py-1">R$ {{ number_format($p->valor, 2, ',', '.') }}</td>
                            <td class="border px-2 py-1">{{ $p->vencimento->format('d/m/Y') }}</td>
                            <td class="border px-2 py-1">{{ ucfirst($p->status) }}</td>
                            <td class="border px-2 py-1 text-xs">{{ $p->codigo_solicitacao }}</td>
                            <td class="border px-2 py-1 space-x-1">
                                <button class="btn btn-outline-secondary" onclick="abrirUpload({{ $p->id }})">Upload</button>
                                <button class="btn btn-outline-success" onclick="abrirPago({{ $p->id }})">Pago</button>
                                <button class="btn btn-outline-danger btn-delete" data-id="{{ $p->id }}">Excluir</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">{{ $pagamentos->links() }}</div>
        </div>
    </div>

    {{-- Modal Novo Boleto --}}
    <dialog id="modalNovoBoleto" class="rounded-md border p-6 w-full max-w-2xl">
        <form method="POST" action="{{ route('boleto.criar') }}">
            @csrf
            <h2 class="text-lg font-bold mb-4">Criar Novo Boleto</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label>Cliente</label>
                    <select name="cliente_id" class="form-select w-full" required>
                        @foreach ($clientes as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Nosso Número</label>
                    <input type="text" name="nosso_numero" class="form-control w-full" required>
                </div>
                <div>
                    <label>Valor</label>
                    <input type="number" name="valor" step="0.01" class="form-control w-full" required>
                </div>
                <div>
                    <label>Vencimento</label>
                    <input type="date" name="data_vencimento" class="form-control w-full" required>
                </div>
            </div>
            <div class="text-right mt-4">
                <button type="submit" class="btn btn-success">Criar</button>
                <button type="button" class="btn btn-secondary ml-2" onclick="modalNovoBoleto.close()">Cancelar</button>
            </div>
        </form>
    </dialog>

    {{-- Modal Upload --}}
    <dialog id="modalUpload" class="rounded-md border p-6 w-full max-w-md">
        <form method="POST" action="{{ route('boleto.upload') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="pagamento_id" id="upload_pagamento_id">
            <h2 class="text-lg font-bold mb-4">Upload de Boleto</h2>
            <input type="file" name="boleto" class="form-control mb-4" required>
            <div class="text-right">
                <button type="submit" class="btn btn-primary">Enviar</button>
                <button type="button" class="btn btn-secondary ml-2" onclick="modalUpload.close()">Fechar</button>
            </div>
        </form>
    </dialog>

    {{-- Modal Pago --}}
    <dialog id="modalPago" class="rounded-md border p-6 w-full max-w-md">
        <form method="POST" action="{{ route('boleto.pago') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="pagamento_id" id="pago_pagamento_id">
            <h2 class="text-lg font-bold mb-4">Marcar Como Pago</h2>
            <label>Descrição</label>
            <input type="text" name="descricao" class="form-control mb-2">
            <label>Comprovante</label>
            <input type="file" name="comprovante" class="form-control mb-4">
            <div class="text-right">
                <button type="submit" class="btn btn-success">Confirmar</button>
                <button type="button" class="btn btn-secondary ml-2" onclick="modalPago.close()">Fechar</button>
            </div>
        </form>
    </dialog>

    {{-- JS --}}
    @push('scripts')
    <script>
        function abrirUpload(id) {
            document.getElementById('upload_pagamento_id').value = id;
            document.getElementById('modalUpload').showModal();
        }

        function abrirPago(id) {
            document.getElementById('pago_pagamento_id').value = id;
            document.getElementById('modalPago').showModal();
        }

        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function () {
                if (confirm('Deseja realmente excluir este boleto?')) {
                    fetch(`/boletos/${this.dataset.id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    }).then(res => res.json()).then(json => {
                        if (json.success) location.reload();
                        else alert('Erro: ' + (json.error || 'não foi possível excluir.'));
                    });
                }
            });
        });
    </script>
    @endpush
@endsection
