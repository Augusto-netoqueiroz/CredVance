<!-- resources/views/usuarios/index.blade.php -->

<!-- Carregando Bootstrap e Bootstrap Icons (caso ainda não tenha) -->
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
/>
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
/>

<!-- Sobrescrevendo estilos no modo escuro (Tailwind classes) -->
<style>
.dark .navbar,
.dark .navbar-nav,
.dark .navbar-light,
.dark .bg-white {
  background-color: #1f2937 !important; /* Tailwind gray-900 */
  color: #f9fafb !important;           /* Tailwind gray-50 */
}

.dark table,
.dark thead,
.dark tbody,
.dark .table,
.dark .table td,
.dark .table th {
  background-color: #1f2937 !important;
  color: #f9fafb !important;
  border-color: #4b5563 !important; /* Tailwind gray-600 */
}

.dark tr:hover {
  background-color: #374151 !important; /* Tailwind gray-700 */
}
</style>

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-center w-full text-gray-800 dark:text-gray-200">
                Usuários
            </h2>
            <div class="absolute right-10">
                <button
                  id="btnNovoUsuario"
                  class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded shadow"
                >
                    Novo Usuário
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Mensagem de sucesso (invisível no início) -->
            <div 
              id="successMessage" 
              class="hidden mb-4 p-4 rounded border border-green-300 bg-green-100 text-green-800"
            >
                Usuário criado com sucesso e link de finalização enviado!
            </div>

            <div class="overflow-x-auto">
                <!-- Tabela de usuários -->
                <table class="table min-w-full bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden text-base">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr class="text-left text-gray-700 dark:text-gray-300">
                            <th class="py-4 px-6">ID</th>
                            <th class="py-4 px-6">Nome</th>
                            <th class="py-4 px-6">CPF</th>
                            <th class="py-4 px-6">Email</th>
                            <th class="py-4 px-6">Telefone</th>
                            <th class="py-4 px-6">Tipo</th>
                            <th class="py-4 px-6 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800">
                        @foreach ($usuarios as $user)
                            <tr class="border-b dark:border-gray-700 text-gray-800 dark:text-gray-200">
                                <td class="py-4 px-6">{{ $user->id }}</td>
                                <td class="py-4 px-6">{{ $user->name }}</td>
                                <td class="py-4 px-6">{{ $user->cpf }}</td>
                                <td class="py-4 px-6">{{ $user->email }}</td>
                                <td class="py-4 px-6">{{ $user->telefone }}</td>
                                <td class="py-4 px-6">
                                    <span
                                      class="inline-block px-3 py-1 text-sm font-semibold rounded-full
                                             {{ $user->role === 'admin'
                                                 ? 'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100'
                                                 : 'bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-100'
                                             }}"
                                    >
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-center space-x-2">
                                    <button
                                      class="editarUsuario bg-yellow-400 hover:bg-yellow-500 text-gray-900 dark:text-white px-4 py-2 rounded shadow"
                                      data-id="{{ $user->id }}"
                                    >
                                        ✏️ Editar
                                    </button>
                                    <form 
                                      action="{{ route('usuarios.delete', $user->id) }}" 
                                      method="POST"
                                      style="display: inline-block;"
                                    >
                                        @csrf
                                        <button
                                            type="submit"
                                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded shadow"
                                            onclick="return confirm('Tem certeza que deseja desabilitar este usuário?')"
                                        >
                                            🗑️ Deletar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Paginação -->
                <div class="mt-4">
                    <!-- Usando estilo Bootstrap -->
                    {{ $usuarios->links('pagination::bootstrap-5') }}

                    <!-- Se preferir Tailwind padrão do Laravel, use: -->
                    {{-- {{ $usuarios->links() }} --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal (inspirado no estilo x-guest-layout) -->
    <div id="modalUsuario" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
        <div 
          class="w-full sm:max-w-md mt-6 p-6 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg"
          style="max-height: 90vh; overflow-y: auto;"
        >
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">
                Novo Usuário
            </h3>

            <!-- Mensagens de erro (exibidas se houver validação 422) -->
            <div 
              id="errorMessages" 
              class="hidden mb-4 p-4 bg-red-100 text-red-700 rounded border border-red-300 text-sm"
            ></div>

            <form id="formUsuario">
                @csrf
                <input type="hidden" id="user_id" name="user_id">

                <!-- Nome -->
                <div>
                    <label 
                      for="name" 
                      class="block font-medium text-sm text-gray-700 dark:text-gray-300"
                    >
                        Nome:
                    </label>
                    <input
                      id="name"
                      name="name"
                      type="text"
                      required
                      autocomplete="name"
                      class="block mt-1 w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white"
                    />
                </div>

                <!-- CPF -->
                <div class="mt-4">
                    <label 
                      for="cpf" 
                      class="block font-medium text-sm text-gray-700 dark:text-gray-300"
                    >
                        CPF:
                    </label>
                    <input
                      id="cpf"
                      name="cpf"
                      type="text"
                      required
                      autocomplete="on"
                      class="block mt-1 w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white"
                    />
                </div>

                <!-- Telefone -->
                <div class="mt-4">
                    <label 
                      for="telefone" 
                      class="block font-medium text-sm text-gray-700 dark:text-gray-300"
                    >
                        Telefone:
                    </label>
                    <input
                      id="telefone"
                      name="telefone"
                      type="text"
                      required
                      autocomplete="tel"
                      class="block mt-1 w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white"
                    />
                </div>

                <!-- Email -->
                <div class="mt-4">
                    <label 
                      for="email"
                      class="block font-medium text-sm text-gray-700 dark:text-gray-300"
                    >
                        Email:
                    </label>
                    <input
                      id="email"
                      name="email"
                      type="email"
                      required
                      autocomplete="email"
                      class="block mt-1 w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white"
                    />
                </div>

                <!-- Tipo (Role) -->
                <div class="mt-4">
                    <label 
                      for="role" 
                      class="block font-medium text-sm text-gray-700 dark:text-gray-300"
                    >
                        Tipo:
                    </label>
                    <select
                      id="role"
                      name="role"
                      required
                      class="block mt-1 w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white"
                    >
                        <option value="cliente">Cliente</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <!-- Botões -->
                <div class="flex items-center justify-end mt-4">
                    <button
                      type="button"
                      id="fecharModal"
                      class="px-4 py-2 bg-gray-400 text-white rounded mr-2"
                    >
                        Cancelar
                    </button>

                    <!-- Botão Salvar com spinner -->
                    <button
                      type="button"
                      id="salvarUsuario"
                      class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded relative"
                    >
                        <span id="btnText">Salvar</span>
                        <span id="btnSpinner" class="hidden ml-2">
                            <!-- Exemplo de spinner (Tailwind) -->
                            <svg 
                              class="animate-spin h-5 w-5 text-white" 
                              xmlns="http://www.w3.org/2000/svg" 
                              fill="none" 
                              viewBox="0 0 24 24"
                            >
                              <circle 
                                class="opacity-25" 
                                cx="12" 
                                cy="12" 
                                r="10" 
                                stroke="currentColor" 
                                stroke-width="4"
                              />
                              <path 
                                class="opacity-75" 
                                fill="currentColor" 
                                d="M4 12a8 8 0 018-8v8z"
                              />
                            </svg>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts do Bootstrap (opcional) e JS do form -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Remove pontuação do CPF
        function limparPontuacaoCPF(cpf) {
            return cpf.replace(/\D/g, '');
        }

        document.addEventListener('DOMContentLoaded', function () {
            const modalUsuario = document.getElementById('modalUsuario');
            const btnNovoUsuario = document.getElementById('btnNovoUsuario');
            const btnFecharModal = document.getElementById('fecharModal');
            const btnSalvarUsuario = document.getElementById('salvarUsuario');
            const errorMessages = document.getElementById('errorMessages');
            const successMessage = document.getElementById('successMessage');
            const btnSpinner = document.getElementById('btnSpinner');
            const btnText = document.getElementById('btnText');

            // Abrir modal
            btnNovoUsuario.addEventListener('click', () => {
                modalUsuario.classList.remove('hidden');
                modalUsuario.classList.add('flex');
                errorMessages.classList.add('hidden');
                errorMessages.innerHTML = '';
                // limpa campos
                document.getElementById('formUsuario').reset();
                successMessage.classList.add('hidden'); // caso já tenha aparecido antes
            });

            // Fechar modal
            btnFecharModal.addEventListener('click', () => {
                modalUsuario.classList.remove('flex');
                modalUsuario.classList.add('hidden');
            });

            // Evento Salvar
            btnSalvarUsuario.addEventListener('click', async function () {
                // Preparar UI para "salvando..."
                btnSalvarUsuario.disabled = true;
                btnSpinner.classList.remove('hidden');
                btnText.textContent = 'Salvando...';

                // Pegar dados do form
                const data = {
                    name: document.getElementById('name').value,
                    cpf: limparPontuacaoCPF(document.getElementById('cpf').value),
                    telefone: document.getElementById('telefone').value,
                    email: document.getElementById('email').value,
                    role: document.getElementById('role').value,
                };

                try {
                    // Enviar requisição
                    await axios.post("{{ route('usuarios.storeBasic') }}", data);
                    
                    // Se chegou aqui, deu certo:
                    // Fecha o modal, limpa form e mostra msg de sucesso
                    modalUsuario.classList.remove('flex');
                    modalUsuario.classList.add('hidden');
                    successMessage.classList.remove('hidden');

                } catch (error) {
                    // Se houve erro de validação (422)
                    if (error.response && error.response.status === 422) {
                        const errors = error.response.data.errors;
                        let messages = '';
                        for (let campo in errors) {
                            messages += '<div>' + errors[campo].join(', ') + '</div>';
                        }
                        errorMessages.innerHTML = messages;
                        errorMessages.classList.remove('hidden');
                    } else {
                        // Erro inesperado
                        console.error(error);
                        errorMessages.innerHTML = 'Ocorreu um erro inesperado.';
                        errorMessages.classList.remove('hidden');
                    }
                } finally {
                    // Restaurar estado do botão
                    btnSalvarUsuario.disabled = false;
                    btnSpinner.classList.add('hidden');
                    btnText.textContent = 'Salvar';
                }
            });
        });
    </script>
</x-app-layout>
