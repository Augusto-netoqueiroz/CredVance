<section>

    @if (session('status'))
    <div class="bg-green-100 text-green-800 p-3 rounded mb-2">
        {{ session('status') }}
    </div>
@endif

@if ($errors->any())
    <div class="bg-red-100 text-red-800 p-3 rounded mb-2">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif



    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Resetar Senha') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Ao clicar no botão abaixo, enviaremos um e-mail com o link de redefinição de senha.') }}
        </p>
    </header>

    <!-- O formulário envia um POST para password.email -->
    <form method="POST" action="{{ route('password.email.auth') }}" class="mt-4">
        @csrf
        <!-- Se quiser forçar o e-mail do usuário logado, inclua um hidden -->
        <input type="hidden" name="email" value="{{ Auth::user()->email }}" />

        <div class="flex items-center justify-start">
            <x-primary-button>
                {{ __('Enviar link de redefinição') }}
            </x-primary-button>
        </div>
    </form>
</section>
