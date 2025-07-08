<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight">
            {{ __('Novo Parceiro') }}
        </h2>
    </x-slot>

    {{-- Bootstrap Core + Bootstrap Select + jQuery --}}
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta3/css/bootstrap-select.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta3/js/bootstrap-select.min.js"></script>

    <div class="py-10 px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-xl shadow">

            <form method="POST" action="{{ route('admin.parceiros.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-200 mb-1">Nome:</label>
                    <input type="text" name="nome" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-200 mb-1">Slug (URL):</label>
                    <input type="text" name="slug" placeholder="ex: parceiro-x" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-200 mb-1">Usuário Responsável:</label>
                    <select name="user_id"
                            class="selectpicker w-full text-sm dark:bg-gray-700 dark:text-white"
                            data-live-search="true"
                            data-live-search-placeholder="Buscar usuário..."
                            title="Selecione um usuário" required>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">
                                ID: {{ $user->id }} - Nome: {{ $user->name }} - Email: {{ $user->email }} - Cargo: {{ ucfirst($user->role) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    Salvar
                </button>
            </form>

        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.selectpicker').selectpicker();
        });
    </script>

    <style>
        .bootstrap-select .dropdown-menu.inner li a {
            font-size: 0.85rem;
            padding: 6px 8px;
        }
        .bootstrap-select .bs-searchbox input {
            font-size: 0.85rem;
        }
        .bootstrap-select .btn {
            font-size: 0.9rem;
        }
    </style>
</x-app-layout>
