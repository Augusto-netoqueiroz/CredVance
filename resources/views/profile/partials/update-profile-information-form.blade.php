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

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('usuario.update', $user->id) }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <!-- Nome -->
        <div>
            <x-input-label for="name" :value="__('Nome')" />
            <x-text-input id="name" name="name" type="text"
                          class="mt-1 block w-full"
                          :value="old('name', $user->name)"
                          required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('E-mail')" />
            <x-text-input id="email" name="email" type="email"
                          class="mt-1 block w-full"
                          :value="old('email', $user->email)"
                          required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Seu endereço de e-mail não foi verificado.') }}
                        <button form="send-verification"
                                class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
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
            <x-text-input id="cpf" name="cpf" type="text"
                          class="mt-1 block w-full bg-gray-100 text-gray-500 cursor-not-allowed"
                          :value="old('cpf', $user->cpf)"
                          readonly autocomplete="cpf" />
            <x-input-error class="mt-2" :messages="$errors->get('cpf')" />
        </div>

        <!-- Telefone -->
        <div>
            <x-input-label for="telefone" :value="__('Telefone')" />
            <x-text-input id="telefone" name="telefone" type="text"
                          class="mt-1 block w-full"
                          :value="old('telefone', $user->telefone)"
                          required autocomplete="tel" />
            <x-input-error class="mt-2" :messages="$errors->get('telefone')" />
        </div>

        <!-- Endereço: Logradouro -->
        <div>
            <x-input-label for="logradouro" :value="__('Logradouro')" />
            <x-text-input id="logradouro" name="logradouro" type="text"
                          class="mt-1 block w-full"
                          :value="old('logradouro', $user->logradouro)"
                          required autocomplete="street-address" />
            <x-input-error class="mt-2" :messages="$errors->get('logradouro')" />
        </div>

        <!-- Endereço: Número e Complemento lado a lado -->
        <div class="flex gap-4">
            <div class="flex-1">
                <x-input-label for="numero" :value="__('Número')" />
                <x-text-input id="numero" name="numero" type="text"
                              class="mt-1 block w-full"
                              :value="old('numero', $user->numero)"
                              required autocomplete="address-line2" />
                <x-input-error class="mt-2" :messages="$errors->get('numero')" />
            </div>
            <div class="flex-1">
                <x-input-label for="complemento" :value="__('Complemento')" />
                <x-text-input id="complemento" name="complemento" type="text"
                              class="mt-1 block w-full"
                              :value="old('complemento', $user->complemento)"
                              autocomplete="address-line3" />
                <x-input-error class="mt-2" :messages="$errors->get('complemento')" />
            </div>
        </div>

        <!-- Endereço: Bairro -->
        <div>
            <x-input-label for="bairro" :value="__('Bairro')" />
            <x-text-input id="bairro" name="bairro" type="text"
                          class="mt-1 block w-full"
                          :value="old('bairro', $user->bairro)"
                          required autocomplete="address-level2" />
            <x-input-error class="mt-2" :messages="$errors->get('bairro')" />
        </div>

        <!-- Endereço: Cidade, UF e CEP lado a lado -->
        <div class="flex gap-4">
            <div class="flex-1">
                <x-input-label for="cidade" :value="__('Cidade')" />
                <x-text-input id="cidade" name="cidade" type="text"
                              class="mt-1 block w-full"
                              :value="old('cidade', $user->cidade)"
                              required autocomplete="address-level2" />
                <x-input-error class="mt-2" :messages="$errors->get('cidade')" />
            </div>
            <div class="w-32">
                <x-input-label for="uf" :value="__('UF')" />
                <x-text-input id="uf" name="uf" type="text"
                              class="mt-1 block w-full"
                              :value="old('uf', $user->uf)"
                              maxlength="2" required autocomplete="address-level1" />
                <x-input-error class="mt-2" :messages="$errors->get('uf')" />
            </div>
            <div class="w-40">
                <x-input-label for="cep" :value="__('CEP')" />
                <x-text-input id="cep" name="cep" type="text"
                              class="mt-1 block w-full"
                              :value="old('cep', $user->cep)"
                              required autocomplete="postal-code" />
                <x-input-error class="mt-2" :messages="$errors->get('cep')" />
            </div>
        </div>
        <input type="hidden" name="role" value="{{ $user->role }}">

        <div class="flex items-center gap-4">
            <x-primary-button>
                {{ __('Salvar') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition
                   x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Salvo.') }}
                </p>
            @endif
        </div>
    </form>
</section>
