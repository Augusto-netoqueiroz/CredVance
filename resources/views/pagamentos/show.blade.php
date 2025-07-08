<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalhe do Boleto') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <h4 class="mb-4">Dados da Cobrança</h4>
                <pre>{{ print_r($cobranca, true) }}</pre>
                <a href="{{ route('pagamentos.index') }}" class="btn btn-secondary mt-3">Voltar</a>
            </div>
        </div>
    </div>
</x-app-layout>
