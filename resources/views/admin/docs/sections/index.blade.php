<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            Seções da Documentação
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <a href="{{ route('sections.create') }}"
               class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded mb-4">
                + Nova Seção
            </a>

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Título</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Slug</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ícone</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ordem</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($sections as $section)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $section->title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $section->slug }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap"><i class="fas {{ $section->icon }}"></i></td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $section->ordem }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                        <a href="{{ route('sections.edit', $section) }}"
                                           class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-semibold px-3 py-1 rounded">
                                            Editar
                                        </a>
                                        <form action="{{ route('sections.destroy', $section) }}" method="POST" class="inline-block"
                                              onsubmit="return confirm('Excluir?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="bg-red-600 hover:bg-red-700 text-white text-xs font-semibold px-3 py-1 rounded">
                                                Excluir
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if($sections->isEmpty())
                        <p class="mt-4 text-gray-500 dark:text-gray-400">Nenhuma seção cadastrada ainda.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

