<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Artigos da Documentação
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('articles.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    + Novo Artigo
                </a>
            </div>

            @if(session('success'))
                <div class="mb-4 text-green-700 bg-green-100 border border-green-400 p-4 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <table class="min-w-full divide-y divide-gray-200 table-auto">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Título</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Seção</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Ordem</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($articles as $article)
                                <tr>
                                    <td class="px-4 py-2">{{ $article->title }}</td>
                                    <td class="px-4 py-2">{{ $article->slug }}</td>
                                    <td class="px-4 py-2">{{ $article->section->title }}</td>
                                    <td class="px-4 py-2">{{ $article->ordem }}</td>
                                    <td class="px-4 py-2 space-x-2">
                                        <a href="{{ route('articles.edit', $article) }}" class="bg-yellow-400 hover:bg-yellow-500 text-white font-bold py-1 px-3 rounded text-sm">Editar</a>
                                        <form action="{{ route('articles.destroy', $article) }}" method="POST" class="inline-block" onsubmit="return confirm('Excluir?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded text-sm">Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if($articles->isEmpty())
                        <p class="text-gray-500 mt-4">Nenhum artigo encontrado.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
