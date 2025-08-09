<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            Novo Artigo
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <form action="{{ route('articles.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-white">Seção</label>
                        <select name="section_id" class="form-select w-full mt-1" required>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-white">Título</label>
                        <input type="text" name="title" class="form-input w-full mt-1" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-white">Conteúdo</label>
                        <textarea name="content" id="editor" class="form-input w-full mt-1" rows="10"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-white">Ordem</label>
                        <input type="number" name="ordem" class="form-input w-full mt-1">
                    </div>
                    <button class="btn btn-success">Salvar</button>
                </form>
            </div>
        </div>
    </div>

    @section('scripts')
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: '#editor',
            plugins: 'code link lists table',
            toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright | bullist numlist | link | code',
            height: 400
        });
    </script>
    @endsection
</x-app-layout>
