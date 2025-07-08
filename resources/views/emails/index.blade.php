<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Emails Enviados / Pendentes</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="mb-4 flex justify-between items-center">
            <form method="GET" action="{{ route('emails.index') }}" class="flex space-x-2">
                <select name="status" class="rounded border-gray-300">
                    <option value="">Todos Status</option>
                    <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                    <option value="enviando" {{ request('status') == 'enviando' ? 'selected' : '' }}>Enviando</option>
                    <option value="enviado" {{ request('status') == 'enviado' ? 'selected' : '' }}>Enviado</option>
                    <option value="erro" {{ request('status') == 'erro' ? 'selected' : '' }}>Erro</option>
                </select>
                <button type="submit" class="px-4 py-1 bg-yellow-400 rounded font-semibold text-gray-800 hover:bg-yellow-500">Filtrar</button>
            </form>

            <a href="{{ route('emails.create') }}" class="px-4 py-2 bg-yellow-400 rounded font-semibold text-gray-800 hover:bg-yellow-500">Criar Email</a>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Destinatário</th>
                        <th class="px-4 py-2 text-left">Assunto</th>
                        <th class="px-4 py-2 text-left">Template</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Enviado Em</th>
                        <th class="px-4 py-2 text-left">Visualizado Em</th>
                        <th class="px-4 py-2 text-left">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($emails as $email)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $email->id }}</td>
                            <td class="px-4 py-2">{{ $email->email_destino }}</td>
                            <td class="px-4 py-2">{{ $email->assunto }}</td>
                            <td class="px-4 py-2">{{ $email->template->nome ?? '-' }}</td>
                            <td class="px-4 py-2 capitalize">{{ $email->status }}</td>
                            <td class="px-4 py-2">{{ $email->enviado_em?->format('d/m/Y H:i') ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $email->visualizado_em?->format('d/m/Y H:i') ?? '-' }}</td>
                            <td class="px-4 py-2 space-x-2">
                                <a href="{{ route('emails.show', $email) }}" class="text-blue-600 hover:underline">Ver</a>

                                
                                <form action="{{ route('emails.destroy', $email) }}" method="POST" class="inline" onsubmit="return confirm('Remover email?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Excluir</button>
                                </form>
                               
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $emails->links() }}
            </div>
        </div>

    </div>
</x-app-layout>
