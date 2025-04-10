<!-- resources/views/landing-page.blade.php -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Landing Page - Cadastro</title>
    <!-- Se estiver usando Vite + Tailwind (ajuste conforme seu setup) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- AlpineJS para controlar modal -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="container mx-auto py-8"
     x-data="{
         showOtpModal: {{ session('show_otp_modal') ? 'true' : 'false' }},
     }"
>
    <!-- Alerts de sucesso/erro -->
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            <strong class="font-bold">Sucesso!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
            <button type="button"
                    class="absolute top-0 bottom-0 right-0 px-4 py-3 focus:outline-none"
                    onclick="this.parentElement.remove()">
                <span class="text-green-500">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <strong class="font-bold">Erro!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
            <button type="button"
                    class="absolute top-0 bottom-0 right-0 px-4 py-3 focus:outline-none"
                    onclick="this.parentElement.remove()">
                <span class="text-red-500">&times;</span>
            </button>
        </div>
    @endif

    <!-- Seção de Cadastro -->
    <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold mb-4">Cadastre-se</h1>
        <form method="POST" action="{{ route('landing.register.submit') }}" class="space-y-4">
            @csrf

            <!-- Nome -->
            <div>
                <label for="name" class="block text-gray-700">Nome</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="border border-gray-300 rounded w-full p-2"
                    value="{{ old('name') }}"
                    required
                />
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-gray-700">E-mail</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="border border-gray-300 rounded w-full p-2"
                    value="{{ old('email') }}"
                    required
                />
                @error('email')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- CPF -->
            <div>
                <label for="cpf" class="block text-gray-700">CPF</label>
                <input
                    type="text"
                    id="cpf"
                    name="cpf"
                    class="border border-gray-300 rounded w-full p-2"
                    value="{{ old('cpf') }}"
                    required
                />
                @error('cpf')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Senha -->
            <div>
                <label for="password" class="block text-gray-700">Senha</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="border border-gray-300 rounded w-full p-2"
                    required
                />
                @error('password')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
            >
                Cadastrar
            </button>
        </form>
    </div>

    <!-- Modal de Verificação de OTP -->
    <div
        class="fixed z-10 inset-0 overflow-y-auto"
        style="display: none;"
        x-show="showOtpModal"
    >
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <!-- Conteúdo do modal -->
            <div
                class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all max-w-md w-full p-6"
                x-transition
            >
                <h2 class="text-xl font-bold mb-4">Verificar OTP</h2>
                <p class="mb-4">Digite o código que enviamos para seu e-mail.</p>

                <form method="POST" action="{{ route('landing.register.verify') }}" class="space-y-4">
                    @csrf

                    <!-- O user_id vem via flash. Precisamos incluí-lo num input hidden -->
                    <input type="hidden" name="user_id" value="{{ session('user_id') }}">

                    <div>
                        <label for="otp_code" class="block text-gray-700">Código</label>
                        <input
                            type="text"
                            id="otp_code"
                            name="otp_code"
                            class="border border-gray-300 rounded w-full p-2"
                            required
                        />
                        @error('otp_code')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button
                            type="button"
                            @click="showOtpModal = false"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                        >
                            Verificar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
</body>
</html>
