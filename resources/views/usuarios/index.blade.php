<!-- Carregando Bootstrap e Bootstrap Icons (caso ainda não tenha) -->
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
/>
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
/>

<!-- Sobrescrevendo estilos no modo escuro -->
<style>
/* Força a navbar a ficar escura no modo dark */
.dark .navbar,
.dark .navbar-nav,
.dark .navbar-light,
.dark .bg-white {
  background-color: #1f2937 !important; /* Tailwind gray-900 */
  color: #f9fafb !important;           /* Tailwind gray-50 */
}

/* Força a tabela ficar escura no modo dark */
.dark table,
.dark thead,
.dark tbody,
.dark .table,
.dark .table td,
.dark .table th {
  background-color: #1f2937 !important;
  color: #f9fafb !important;
  border-color: #4b5563 !important;  /* Tailwind gray-600 */
}

/* Ajusta também hover nas linhas */
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
            <div class="overflow-x-auto">
                <!-- Note 'dark:bg-gray-800' abaixo para a tabela -->
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
                                    <form action="{{ route('usuarios.delete', $user->id) }}" method="POST" style="display: inline-block;">
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
            </div>
        </div>
    </div>

    <!-- Modal para criar Novo Usuário -->
    <div id="modalUsuario" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-1/3">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Novo Usuário</h3>

            <div id="errorMessages" class="mb-4 hidden text-sm text-red-600 dark:text-red-400"></div>

            <form id="formUsuario">
                @csrf
                <input type="hidden" id="user_id" name="user_id">

                <!-- Campo Nome -->
                <div>
                    <label class="block mb-1 text-gray-700 dark:text-gray-300">Nome:</label>
                    <input
                      id="name"
                      name="name"
                      type="text"
                      autocomplete="on"
                      required
                      class="form-control rounded border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white"
                    />
                </div>

                <!-- Campo CPF -->
                <div class="mt-3">
                    <label class="block mb-1 text-gray-700 dark:text-gray-300">CPF:</label>
                    <input
                      id="cpf"
                      name="cpf"
                      type="text"
                      autocomplete="on"
                      required
                      class="form-control rounded border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white"
                    />
                </div>

                <!-- Campo Telefone -->
                <div class="mt-3">
                    <label class="block mb-1 text-gray-700 dark:text-gray-300">Telefone:</label>
                    <input
                      id="telefone"
                      name="telefone"
                      type="text"
                      autocomplete="on"
                      required
                      class="form-control rounded border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white"
                    />
                </div>

                <!-- Campo Email -->
                <div class="mt-3">
                    <label class="block mb-1 text-gray-700 dark:text-gray-300">Email:</label>
                    <input
                      id="email"
                      name="email"
                      type="email"
                      autocomplete="on"
                      required
                      class="form-control rounded border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white"
                    />
                </div>

                <!-- Campo Tipo -->
                <div class="mt-3">
                    <label class="block mb-1 text-gray-700 dark:text-gray-300">Tipo:</label>
                    <select
                      id="role"
                      name="role"
                      required
                      class="form-select rounded border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white"
                    >
                        <option value="usuario">Cliente</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <!-- Campo Senha (input-group do Bootstrap para ícone olho) -->
                <div class="mt-3">
                    <label class="block mb-1 text-gray-700 dark:text-gray-300">Senha:</label>
                    <div class="input-group">
                        <input
                          id="password"
                          name="password"
                          type="password"
                          required
                          class="form-control"
                        />
                        <button
                          class="btn btn-outline-secondary"
                          type="button"
                          onclick="togglePassword('password', this)"
                        >
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Confirmar Senha -->
                <div class="mt-3">
                    <label class="block mb-1 text-gray-700 dark:text-gray-300">Confirmar Senha:</label>
                    <div class="input-group">
                        <input
                          id="password_confirmation"
                          name="password_confirmation"
                          type="password"
                          required
                          class="form-control"
                        />
                        <button
                          class="btn btn-outline-secondary"
                          type="button"
                          onclick="togglePassword('password_confirmation', this)"
                        >
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="mt-4 flex justify-end">
                    <button
                      type="button"
                      id="fecharModal"
                      class="mr-2 px-4 py-2 bg-gray-400 text-white rounded"
                    >
                        Cancelar
                    </button>
                    <button
                      type="button"
                      id="salvarUsuario"
                      class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded"
                    >
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts do Bootstrap (opcional) e JS do form -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Alterna a visibilidade da senha e o ícone
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';

            const icon = button.querySelector('i');
            if (icon) {
                icon.classList.toggle('bi-eye');
                icon.classList.toggle('bi-eye-slash');
            }
        }

        // Remove pontuação do CPF
        function limparPontuacaoCPF(cpf) {
            return cpf.replace(/\\D/g, '');
        }

        document.addEventListener('DOMContentLoaded', function () {
            const errorMessages = document.getElementById('errorMessages');

            document.getElementById('salvarUsuario').addEventListener('click', async function () {
                const data = {
                    name: document.getElementById('name').value,
                    cpf: limparPontuacaoCPF(document.getElementById('cpf').value),
                    telefone: document.getElementById('telefone').value,
                    email: document.getElementById('email').value,
                    role: document.getElementById('role').value,
                    password: document.getElementById('password').value,
                    password_confirmation: document.getElementById('password_confirmation').value,
                };

                try {
                    await axios.post("{{ route('usuarios.store') }}", data);
                    alert('Usuário criado com sucesso!');
                    location.reload();
                } catch (error) {
                    if (error.response && error.response.status === 422) {
                        const errors = error.response.data.errors;
                        let messages = '';
                        for (let campo in errors) {
                            messages += '<div>' + errors[campo].join(', ') + '</div>';
                        }
                        errorMessages.innerHTML = messages;
                        errorMessages.classList.remove('hidden');
                    } else {
                        alert('Erro inesperado. Veja o console.');
                        console.error(error);
                    }
                }
            });
        });
    </script>
</x-app-layout>
