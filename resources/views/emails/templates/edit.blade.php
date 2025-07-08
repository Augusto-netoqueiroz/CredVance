<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Template de Email</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('emails.templates.update', $template) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="nome" class="block font-medium text-sm text-gray-700">Nome</label>
                    <input type="text" name="nome" id="nome" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        value="{{ old('nome', $template->nome) }}">
                </div>

                <div class="mb-4">
                    <label for="tipo" class="block font-medium text-sm text-gray-700">Tipo</label>
                    <input type="text" name="tipo" id="tipo" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        value="{{ old('tipo', $template->tipo) }}">
                </div>

                <div class="mb-4">
                    <label for="assunto_padrao" class="block font-medium text-sm text-gray-700">Assunto Padrão</label>
                    <input type="text" name="assunto_padrao" id="assunto_padrao" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        value="{{ old('assunto_padrao', $template->assunto_padrao) }}">
                </div>

                <div class="mb-4">
                    <label for="corpo_html" class="block font-medium text-sm text-gray-700">Corpo HTML</label>
                    <textarea name="corpo_html" id="corpo_html" rows="10" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm font-mono text-xs">{{ old('corpo_html', $template->corpo_html) }}</textarea>
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('emails.templates.index') }}" class="mr-4 text-gray-700 hover:underline">Cancelar</a>
                    <button type="submit" class="px-4 py-2 bg-yellow-400 rounded font-semibold text-gray-800 hover:bg-yellow-500">Salvar</button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
