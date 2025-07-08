{{-- resources/views/auth/register.blade.php --}}
<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

           @if(request()->has('ref'))
                <input type="hidden" name="parceiro_id" value="{{ request()->get('ref') }}">
            @endif

        {{-- Name --}}
        <div>
            <x-input-label for="name" :value="__('Nome')" />
            <x-text-input
                id="name"
                class="block mt-1 w-full"
                type="text"
                name="name"
                :value="old('name')"
                required
                autofocus
                autocomplete="name"
            />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        {{-- Email --}}
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input
                id="email"
                class="block mt-1 w-full"
                type="email"
                name="email"
                :value="old('email')"
                required
                autocomplete="username"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- CPF --}}
        <div class="mt-4">
            <x-input-label for="cpf" :value="__('CPF')" />
            <x-text-input
                id="cpf"
                class="block mt-1 w-full"
                type="text"
                name="cpf"
                :value="old('cpf')"
                required
                autocomplete="off"
                maxlength="14"
            />
            <x-input-error :messages="$errors->get('cpf')" class="mt-2" />
        </div>

        {{-- Telefone --}}
        <div class="mt-4">
            <x-input-label for="telefone" :value="__('Telefone')" />
            <x-text-input
                id="telefone"
                class="block mt-1 w-full"
                type="text"
                name="telefone"
                :value="old('telefone')"
                required
                autocomplete="off"
            />
            <x-input-error :messages="$errors->get('telefone')" class="mt-2" />
        </div>

        {{-- CEP --}}
        <div class="mt-4">
            <x-input-label for="cep" :value="__('CEP')" />
            <x-text-input
                id="cep"
                class="block mt-1 w-full"
                type="text"
                name="cep"
                :value="old('cep')"
                required
                autocomplete="off"
                maxlength="9"
            />
            <x-input-error :messages="$errors->get('cep')" class="mt-2" />
        </div>

        {{-- Logradouro --}}
        <div class="mt-4">
            <x-input-label for="logradouro" :value="__('Logradouro')" />
            <x-text-input
                id="logradouro"
                class="block mt-1 w-full"
                type="text"
                name="logradouro"
                :value="old('logradouro')"
                required
                autocomplete="off"
            />
            <x-input-error :messages="$errors->get('logradouro')" class="mt-2" />
        </div>

        {{-- Número --}}
        <div class="mt-4">
            <x-input-label for="numero" :value="__('Número')" />
            <x-text-input
                id="numero"
                class="block mt-1 w-full"
                type="text"
                name="numero"
                :value="old('numero')"
                required
                autocomplete="off"
            />
            <x-input-error :messages="$errors->get('numero')" class="mt-2" />
        </div>

        {{-- Complemento --}}
        <div class="mt-4">
            <x-input-label for="complemento" :value="__('Complemento')" />
            <x-text-input
                id="complemento"
                class="block mt-1 w-full"
                type="text"
                name="complemento"
                :value="old('complemento')"
                autocomplete="off"
            />
            <x-input-error :messages="$errors->get('complemento')" class="mt-2" />
        </div>

        {{-- Bairro --}}
        <div class="mt-4">
            <x-input-label for="bairro" :value="__('Bairro')" />
            <x-text-input
                id="bairro"
                class="block mt-1 w-full"
                type="text"
                name="bairro"
                :value="old('bairro')"
                required
                autocomplete="off"
            />
            <x-input-error :messages="$errors->get('bairro')" class="mt-2" />
        </div>

        {{-- Cidade --}}
        <div class="mt-4">
            <x-input-label for="cidade" :value="__('Cidade')" />
            <x-text-input
                id="cidade"
                class="block mt-1 w-full"
                type="text"
                name="cidade"
                :value="old('cidade')"
                required
                autocomplete="off"
            />
            <x-input-error :messages="$errors->get('cidade')" class="mt-2" />
        </div>

        {{-- UF --}}
        <div class="mt-4">
            <x-input-label for="uf" :value="__('UF')" />
            <x-text-input
                id="uf"
                class="block mt-1 w-full"
                type="text"
                name="uf"
                :value="old('uf')"
                required
                autocomplete="off"
                maxlength="2"
            />
            <x-input-error :messages="$errors->get('uf')" class="mt-2" />
        </div>

        {{-- Password --}}
        <div class="mt-4">
            <x-input-label for="password" :value="__('Senha')" />
            <div class="relative">
                <x-text-input
                    id="password"
                    class="block mt-1 w-full pr-10"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                />
                <button
                    type="button"
                    id="togglePassword"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700"
                    tabindex="-1"
                >
                    <svg
                        id="togglePasswordIcon"
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.543 7-1.275 4.057-5.065 7-9.543 7-4.477 0-8.268-2.943-9.542-7z"
                        />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Confirm Password --}}
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirme a Senha')" />
            <div class="relative">
                <x-text-input
                    id="password_confirmation"
                    class="block mt-1 w-full pr-10"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                />
                <button
                    type="button"
                    id="togglePasswordConfirmation"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700"
                    tabindex="-1"
                >
                    <svg
                        id="togglePasswordConfirmationIcon"
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.543 7-1.275 4.057-5.065 7-9.543 7-4.477 0-8.268-2.943-9.542-7z"
                        />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- Link para login se já registrado --}}
        <div class="flex items-center justify-end mt-4">
            <a
                class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                href="{{ route('login') }}"
            >
                {{ __('Já registrado?') }}
            </a>
            <x-primary-button class="ms-4">
                {{ __('Registrar') }}
            </x-primary-button>
        </div>
    </form>

    {{-- Script direto para garantir carregamento e funcionamento do "olhinho" e CEP --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Script de registro: rodou');

        // Toggle senha
        const pwdInput = document.getElementById('password');
        const pwdConfirmInput = document.getElementById('password_confirmation');
        const togglePwdBtn = document.getElementById('togglePassword');
        const togglePwdConfirmBtn = document.getElementById('togglePasswordConfirmation');
        const iconPwd = document.getElementById('togglePasswordIcon');
        const iconPwdConfirm = document.getElementById('togglePasswordConfirmationIcon');

        const eyeSVG = `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                           viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.543 7-1.275 4.057-5.065 7-9.543 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>`;
        const eyeOffSVG = `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                              viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 012.223-3.607m3.178-2.51A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.02 10.02 0 01-4.132 5.087m-3.412.888a3 3 0 01-4.243-4.243" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3l18 18" />
                        </svg>`;

        function toggle() {
            if (!pwdInput || !pwdConfirmInput) return;
            const isHidden = pwdInput.type === 'password';
            const newType = isHidden ? 'text' : 'password';
            pwdInput.type = newType;
            pwdConfirmInput.type = newType;
            const svgHTML = isHidden ? eyeOffSVG : eyeSVG;
            if (iconPwd) iconPwd.innerHTML = svgHTML;
            if (iconPwdConfirm) iconPwdConfirm.innerHTML = svgHTML;
        }
        if (togglePwdBtn) togglePwdBtn.addEventListener('click', toggle);
        if (togglePwdConfirmBtn) togglePwdConfirmBtn.addEventListener('click', toggle);

        // CEP e endereço
        const cepInput = document.getElementById('cep');
        const logradouroInput = document.getElementById('logradouro');
        const bairroInput = document.getElementById('bairro');
        const cidadeInput = document.getElementById('cidade');
        const ufInput = document.getElementById('uf');

        if (cepInput) {
            console.log('Campo CEP encontrado');
            cepInput.addEventListener('input', function () {
                let v = cepInput.value.replace(/\D/g, '').slice(0, 8);
                if (v.length > 5) {
                    cepInput.value = v.slice(0, 5) + '-' + v.slice(5);
                } else {
                    cepInput.value = v;
                }
            });
            cepInput.addEventListener('blur', function () {
                let cep = cepInput.value.replace(/\D/g, '');
                if (cep.length !== 8) {
                    console.log('CEP inválido (não tem 8 dígitos)');
                    return;
                }
                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(response => {
                        if (!response.ok) throw new Error('Erro na consulta do CEP');
                        return response.json();
                    })
                    .then(data => {
                        if (data.erro) {
                            console.warn('CEP não encontrado');
                            return;
                        }
                        console.log('ViaCEP retornou:', data);
                        if (logradouroInput) logradouroInput.value = data.logradouro || '';
                        if (bairroInput) bairroInput.value = data.bairro || '';
                        if (cidadeInput) cidadeInput.value = data.localidade || '';
                        if (ufInput) ufInput.value = data.uf || '';
                    })
                    .catch(error => {
                        console.error('Erro ao buscar CEP:', error);
                    });
            });
        } else {
            console.warn('Elemento #cep não encontrado');
        }
    });
    </script>
</x-guest-layout>
