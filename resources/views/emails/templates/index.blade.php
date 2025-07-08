<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Templates de Email</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-4">
            <a href="{{ route('emails.templates.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-yellow-400 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-400 transition">
                Criar Novo Template
            </a>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                @if(session('success'))
                    <div class="mb-4 text-green-600 font-semibold">{{ session('success') }}</div>
                @endif

                <table class="min-w-full table-auto">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 text-left">ID</th>
                            <th class="px-4 py-2 text-left">Nome</th>
                            <th class="px-4 py-2 text-left">Tipo</th>
                            <th class="px-4 py-2 text-left">Assunto</th>
                            <th class="px-4 py-2 text-left">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($templates as $template)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $template->id }}</td>
                            <td class="px-4 py-2">{{ $template->nome }}</td>
                            <td class="px-4 py-2">{{ $template->tipo }}</td>
                            <td class="px-4 py-2">{{ $template->assunto_padrao }}</td>
                            <td class="px-4 py-2 space-x-2">
                                <a href="{{ route('emails.templates.edit', $template->id) }}" 
                                   class="text-blue-600 hover:underline">Editar</a>

                                <form action="{{ route('emails.templates.destroy', $template->id) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja remover?')">
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
                    {{ $templates->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
