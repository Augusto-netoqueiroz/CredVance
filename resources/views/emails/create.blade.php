<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Criar Email</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

        <form method="POST" action="{{ route('emails.store') }}">
            @csrf

            <div class="mb-4">
                <label for="email_template_id" class="block font-medium text-sm text-gray-700">Template</label>
                <select id="email_template_id" name="email_template_id" class="border-gray-300 rounded w-full" required>
                    <option value="">-- Selecione um template --</option>
                    @foreach($templates as $template)
                        <option value="{{ $template->id }}">{{ $template->nome }}</option>
                    @endforeach
                </select>
                @error('email_template_id')
                    <span class="text-red-600">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email_destino" class="block font-medium text-sm text-gray-700">Email Destino</label>
                <input type="email" id="email_destino" name="email_destino" class="border-gray-300 rounded w-full" required />
                @error('email_destino')
                    <span class="text-red-600">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="assunto" class="block font-medium text-sm text-gray-700">Assunto</label>
                <input type="text" id="assunto" name="assunto" class="border-gray-300 rounded w-full" required />
                @error('assunto')
                    <span class="text-red-600">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="corpo_customizado" class="block font-medium text-sm text-gray-700">Corpo Customizado (Opcional)</label>
                <textarea id="corpo_customizado" name="corpo_customizado" rows="6" class="border-gray-300 rounded w-full"></textarea>
            </div>

            <div class="mb-4">
                <label for="dados_json" class="block font-medium text-sm text-gray-700">Dados JSON (Opcional)</label>
                <textarea id="dados_json" name="dados_json" rows="6" class="border-gray-300 rounded w-full"></textarea>
            </div>

            <div>
                <button type="submit" class="px-4 py-2 bg-yellow-400 text-gray-800 rounded font-semibold hover:bg-yellow-500">
                    Salvar Email
                </button>
            </div>
        </form>

    </div>
</x-app-layout>
