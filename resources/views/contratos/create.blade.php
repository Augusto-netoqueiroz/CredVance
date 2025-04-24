<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight">
      {{ __('Novo Contrato de Cotas') }}
    </h2>
  </x-slot>

  <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
      <form action="{{ route('contratos.store') }}" method="POST">
        @csrf

        <!-- Cliente (User com role=cliente) -->
        <div class="mb-4">
          <label for="cliente_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Cliente
          </label>
          <select name="cliente_id" id="cliente_id"
                  class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600
                         bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            @foreach($clientes as $c)
              <option value="{{ $c->id }}"
                {{ old('cliente_id') == $c->id ? 'selected' : '' }}>
                {{ $c->name }} ({{ $c->email }})
              </option>
            @endforeach
          </select>
          @error('cliente_id')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
          @enderror
        </div>

        <!-- Plano de Cota -->
        <div class="mb-4">
          <label for="consorcio_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Plano de Cota
          </label>
          <select name="consorcio_id" id="consorcio_id"
                  class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600
                         bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            @foreach($consorcios as $cons)
              <option value="{{ $cons->id }}"
                {{ old('consorcio_id') == $cons->id ? 'selected' : '' }}>
                {{ $cons->plano }} — {{ $cons->prazo }} meses
              </option>
            @endforeach
          </select>
          @error('consorcio_id')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
          @enderror
        </div>

        <!-- Quantidade de Cotas -->
        <div class="mb-4">
          <label for="quantidade_cotas" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Quantidade de Cotas
          </label>
          <input type="number" name="quantidade_cotas" id="quantidade_cotas"
                 class="mt-1 block w-1/3 rounded-md border-gray-300 dark:border-gray-600
                        bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                 value="{{ old('quantidade_cotas',1) }}" min="1" required>
          @error('quantidade_cotas')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
          @enderror
        </div>

        <!-- Botão -->
        <div class="text-right">
          <button type="submit"
                  class="inline-flex items-center px-4 py-2 bg-yellow-400 hover:bg-yellow-500
                         text-white text-sm font-medium rounded-md shadow">
            Criar Contrato
          </button>
        </div>
      </form>
    </div>
  </div>
</x-app-layout>
