<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Email #{{ $email->id }}</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-6">
        <form method="POST" action="{{ route('emails.update', $email) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="email_destino" class="block font-medium text-sm text-gray-700">Destinatário</label>
                <input type="email" name="email_destino" id="email_destino" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                       value="{{ old('email_destino', $email->email_destino) }}">
                @error('email_destino')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email_template_id" class="block font-medium text-sm text-gray-700">Template</label>
                <select name="email_template_id" id="email_template_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @foreach ($templates as $template)
                        <option value="{{ $template->id }}" {{ old('email_template_id', $email->email_template_id) == $template->id ? 'selected' : '' }}>
                            {{ $template->nome }}
                        </option>
                    @endforeach
                </select>
                @error('email_template_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="assunto" class="block font-medium text-sm text-gray-700">Assunto</label>
                <input type="text" name="assunto" id="assunto" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                       value="{{ old('assunto', $email->assunto) }}">
                @error('assunto')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="corpo_customizado" class="block font-medium text-sm text-gray-700">Corpo Customizado (Opcional)</label>
                <textarea name="corpo_customizado" id="corpo_customizado" rows="6" 
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('corpo_customizado', $email->corpo_customizado) }}</textarea>
                @error('corpo_customizado')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="dados_json" class="block font-medium text-sm text-gray-700">Dados JSON (Opcional)</label>
                <textarea name="dados_json" id="dados_json" rows="6" 
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('dados_json', json_encode($email->dados_json, JSON_PRETTY_PRINT)) }}</textarea>
                @error('dados_json')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="px-4 py-2 bg-yellow-400 rounded font-semibold text-gray-800 hover:bg-yellow-500">
                Atualizar Email
            </button>
            <a href="{{ route('emails.index') }}" class="ml-4 text-gray-600 hover:underline">Cancelar</a>
        </form>
    </div>
</x-app-layout>
