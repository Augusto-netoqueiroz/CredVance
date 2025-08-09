<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Seção
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('sections.update', $section) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-gray-700">Título</label>
                        <input type="text" name="title" class="form-input w-full mt-1" value="{{ $section->title }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Ícone</label>
                        <input type="text" name="icon" class="form-input w-full mt-1" value="{{ $section->icon }}">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Ordem</label>
                        <input type="number" name="ordem" class="form-input w-full mt-1" value="{{ $section->ordem }}">
                    </div>

                    <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                        Atualizar
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
