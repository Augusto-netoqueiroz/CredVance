<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Usuário</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 dark:bg-gray-900 flex items-center justify-center min-h-screen">
    <div class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md w-full max-w-lg">
        <h2 class="text-2xl font-bold text-center text-gray-800 dark:text-white mb-6">Cadastrar Novo Usuário</h2>

        <!-- Exibir mensagem de sucesso -->
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4 text-center">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('usuarios.store') }}">
            @csrf

            <!-- Nome -->
            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300">Nome</label>
                <input type="text" name="name" class="w-full rounded border px-3 py-2 dark:bg-gray-700 dark:text-white" value="{{ old('name') }}" required>
                @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <!-- CPF -->
            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300">CPF</label>
                <input type="text" name="cpf" pattern="\d{11}" title="Digite um CPF válido (11 dígitos, apenas números)" class="w-full rounded border px-3 py-2 dark:bg-gray-700 dark:text-white" value="{{ old('cpf') }}" required>
                @error('cpf') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <!-- E-mail -->
            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300">E-mail</label>
                <input type="email" name="email" class="w-full rounded border px-3 py-2 dark:bg-gray-700 dark:text-white" value="{{ old('email') }}" required>
                @error('email') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <!-- Senha -->
            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300">Senha</label>
                <input type="password" name="password" class="w-full rounded border px-3 py-2 dark:bg-gray-700 dark:text-white" required>
                @error('password') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <!-- Confirmar Senha -->
            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300">Confirmar Senha</label>
                <input type="password" name="password_confirmation" class="w-full rounded border px-3 py-2 dark:bg-gray-700 dark:text-white" required>
            </div>

            <!-- Cargo -->
            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300">Cargo</label>
                <select name="role" class="w-full rounded border px-3 py-2 dark:bg-gray-700 dark:text-white" required>
                    <option value="">Selecione</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                    <option value="usuario" {{ old('role') == 'usuario' ? 'selected' : '' }}>Usuário</option>
                </select>
                @error('role') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <!-- Botão de envio -->
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded font-semibold">
                Cadastrar
            </button>
        </form>
    </div>
</body>
</html>
