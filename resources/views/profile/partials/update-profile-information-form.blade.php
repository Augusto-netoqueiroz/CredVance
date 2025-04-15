<section>
    <!-- Exibir mensagem de sucesso -->
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Sucesso!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
            <button type="button"
                    class="absolute top-0 bottom-0 right-0 px-4 py-3 focus:outline-none"
                    onclick="this.parentElement.remove()">
                <span class="text-green-500">&times;</span>
            </button>
        </div>
    @endif

    <!-- Exibir mensagem de erro -->
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Erro!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
            <button type="button"
                    class="absolute top-0 bottom-0 right-0 px-4 py-3 focus:outline-none"
                    onclick="this.parentElement.remove()">
                <span class="text-red-500">&times;</span>
            </button>
        </div>
    @endif

    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Informações do Perfil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Atualize as informações de perfil da sua conta e seu endereço de e-mail.') }}
        </p>
    </header>

    <!-- Se você quiser manter o botão para reenviar verificação de email -->
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <!-- Ajuste a rota para a que você criou no web.php, por exemplo: "usuario.update" -->
    <form method="post" action="{{ route('usuario.update', $user->id) }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Nome -->
        <div>
            <x-input-label for="name" :value="__('Nome')" />
            <x-text-input
                id="name"
                name="name"
                type="text"
                class="mt-1 block w-full"
                :value="old('name', $user->name)"
                required
                autofocus
                autocomplete="name"
            />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('E-mail')" />
            <x-text-input
                id="email"
                name="email"
                type="email"
                class="mt-1 block w-full"
                :value="old('email', $user->email)"
                required
                autocomplete="username"
            />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Seu endereço de e-mail não foi verificado.') }}

                        <button
                            form="send-verification"
                            class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                        >
                            {{ __('Clique aqui para reenviar o e-mail de verificação.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('Um novo link de verificação foi enviado para seu e-mail.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- CPF (Exibir, mas não permitir edição) -->
        <div>
            <x-input-label for="cpf" :value="__('CPF')" />
            <x-text-input
                id="cpf"
                name="cpf"
                type="text"
                class="mt-1 block w-full bg-gray-100 text-gray-500 cursor-not-allowed"
                :value="old('cpf', $user->cpf)"
                readonly
                autocomplete="cpf"
            />
            <x-input-error class="mt-2" :messages="$errors->get('cpf')" />
        </div>

        <!-- Telefone -->
        <div>
            <x-input-label for="telefone" :value="__('Telefone')" />
            <x-text-input
                id="telefone"
                name="telefone"
                type="text"
                class="mt-1 block w-full"
                :value="old('telefone', $user->telefone)"
                required
                autocomplete="tel"
            />
            <x-input-error class="mt-2" :messages="$errors->get('telefone')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>
                {{ __('Salvar') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >
                    {{ __('Salvo.') }}
                </p>
            @endif
        </div>
    </form>
</section>
