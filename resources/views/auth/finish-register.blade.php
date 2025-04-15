<x-guest-layout>
    <form method="POST" 
          action="{{ route('usuarios.finishRegister.post', ['user' => $user->id]) }}">
        @csrf

        <!-- Título ou texto de instrução -->
        <div class="mb-4 text-lg font-semibold text-gray-800 dark:text-gray-200">
            Definir senha e finalizar cadastro
        </div>

        <!-- Nova Senha -->
        <div>
            <x-input-label for="password" :value="__('Nova Senha:')" />
            <x-text-input
                id="password"
                class="block mt-1 w-full"
                type="password"
                name="password"
                required
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirmar Nova Senha -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirme a Senha:')" />
            <x-text-input
                id="password_confirmation"
                class="block mt-1 w-full"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Botão de Ação -->
        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ms-4">
                {{ __('Finalizar Cadastro') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
