{{-- resources/views/sections/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Criar Seção
        </h2>
    </x-slot>

    {{-- FontAwesome e Select2 --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('sections.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-700">Título</label>
                        <input type="text" name="title" class="form-input w-full mt-1" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-1">Ícone (FontAwesome)</label>
                        <div class="flex items-center gap-4">
                            <select id="iconSelector" class="form-select w-full" onchange="updateIcon()">
                                <option value="">Selecione um ícone...</option>
                                <option value="fa-book">📘 fa-book</option>
                                <option value="fa-cog">⚙️ fa-cog</option>
                                <option value="fa-question-circle">❓ fa-question-circle</option>
                                <option value="fa-info-circle">ℹ️ fa-info-circle</option>
                                <option value="fa-check-circle">✅ fa-check-circle</option>
                                <option value="fa-users">👥 fa-users</option>
                                <option value="fa-phone">📞 fa-phone</option>
                                <option value="fa-envelope">✉️ fa-envelope</option>
                                <option value="fa-chart-line">📈 fa-chart-line</option>
                                <option value="fa-shield-alt">🛡️ fa-shield-alt</option>
                            </select>

                            <i id="previewIcon" class="fas fa-book text-2xl text-gray-700"></i>
                        </div>

                        <input type="text" name="icon" id="iconInput" class="form-input w-full mt-2" placeholder="fa-book" value="fa-book" />
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Ordem</label>
                        <input type="number" name="ordem" class="form-input w-full mt-1">
                    </div>

                    <button class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                        Salvar
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- JS Scripts --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#iconSelector').select2({
                placeholder: "Busque um ícone...",
                width: '100%'
            });

            $('#iconSelector').on('change', function () {
                updateIcon();
            });
        });

        function updateIcon() {
            const selected = document.getElementById('iconSelector').value;
            document.getElementById('iconInput').value = selected;
            document.getElementById('previewIcon').className = `fas ${selected} text-2xl text-gray-700`;
        }
    </script>
</x-app-layout>
