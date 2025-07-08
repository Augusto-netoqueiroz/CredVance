<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($template) ? 'Editar Template' : 'Criar Template' }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm rounded-lg p-6">

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ isset($template) ? route('emails.templates.update', $template) : route('emails.templates.store') }}">
                @csrf
                @if(isset($template))
                    @method('PUT')
                @endif

                <div class="mb-4">
                    <label for="nome" class="block font-medium text-sm text-gray-700">Nome</label>
                    <input type="text" name="nome" id="nome" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        value="{{ old('nome', $template->nome ?? '') }}">
                </div>

                <div class="mb-4">
                    <label for="tipo" class="block font-medium text-sm text-gray-700">Tipo</label>
                    <input type="text" name="tipo" id="tipo" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        value="{{ old('tipo', $template->tipo ?? '') }}">
                    <small class="text-gray-500">Exemplo: boleto, contrato, promoção</small>
                </div>

                <div class="mb-4">
                    <label for="assunto_padrao" class="block font-medium text-sm text-gray-700">Assunto Padrão</label>
                    <input type="text" name="assunto_padrao" id="assunto_padrao" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        value="{{ old('assunto_padrao', $template->assunto_padrao ?? '') }}">
                </div>

                <div class="mb-4">
                    <label for="corpo_html" class="block font-medium text-sm text-gray-700">Corpo HTML</label>
                    <textarea name="corpo_html" id="corpo_html" rows="15" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">{{ old('corpo_html', $template->corpo_html ?? '') }}</textarea>
                    <small class="text-gray-500">Use Blade syntax para variáveis, ex: <code>{{ '{' }}{ $cliente->name }}{{ '}' }}</code></small>
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('emails.templates.index') }}" class="mr-4 text-gray-700 hover:underline">Cancelar</a>
                    <button type="submit"
                        class="px-6 py-2 bg-yellow-400 hover:bg-yellow-500 rounded text-gray-800 font-semibold">
                        {{ isset($template) ? 'Atualizar' : 'Criar' }}
                    </button>
                </div>
            </form>

            {{-- Preview Dinâmico --}}
            <div class="mt-8">
                <h3 class="font-semibold text-lg mb-2">Prévia do Email (HTML renderizado)</h3>
                <div id="preview" class="p-4 border rounded bg-gray-50 overflow-auto max-h-96"></div>
            </div>

        </div>
    </div>

    {{-- TinyMCE --}}
    <script src="https://cdn.tiny.cloud/1/umei0xyc2w5mdzqmkjj48cxp3mye2ledw4yir0ziszcii9jk/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
      tinymce.init({
        selector: '#corpo_html',
        plugins: 'link lists code',
        toolbar: 'undo redo | bold italic underline | bullist numlist | link | code',
        menubar: false,
        branding: false,
        height: 400,
        setup: function(editor) {
            function updatePreview() {
                document.getElementById('preview').innerHTML = editor.getContent();
            }
            editor.on('init', updatePreview);
            editor.on('keyup change', updatePreview);
        }
      });
    </script>
</x-app-layout>
