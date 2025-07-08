<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight">
            Usuários
        </h2>
    </x-slot>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if(session('success'))
        <div class="mb-4 bg-green-100 text-green-800 p-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 bg-red-100 text-red-800 p-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-900 min-h-screen" 
         x-data="{
            users: {{ Js::from($usuarios->map(fn($u) => [
                'id'    => $u->id,
                'name'  => $u->name,
                'email' => $u->email,
                'role'  => ucfirst($u->role),
                // trazemos o booleano 1/0; no JS ele vira true/false
                'status'=> $u->ativo,
            ])) }},
            search: '',
            page: 1,
            perPage: 10,
            get filtered() {
                return this.users.filter(u =>
                    ['name','email','role'].some(k =>
                        (u[k] || '').toLowerCase().includes(this.search.toLowerCase())
                    )
                );
            },
            get totalPages() {
                return Math.max(1, Math.ceil(this.filtered.length / this.perPage));
            },
            get paginated() {
                return this.filtered.slice((this.page-1)*this.perPage, this.page*this.perPage);
            }
        }">

        <!-- Ações e Filtro -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <button id="btnNovoUsuario"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md shadow">
                <i data-lucide="user-plus" class="w-4 h-4 mr-1"></i> Novo Usuário
            </button>
            <input type="text" placeholder="Pesquisar..." x-model="search"
                   class="w-full md:w-1/3 border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" />
        </div>

        <!-- Total -->
        <p class="text-gray-700 dark:text-gray-200 font-medium mt-4">
            Total de usuários: <span x-text="filtered.length"></span>
        </p>

        <!-- Tabela -->
        <div class="overflow-x-auto mt-4">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Nome</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">E-mail</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Perfil</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <template x-for="u in paginated" :key="u.id">
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100" x-text="u.name"></td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100" x-text="u.email"></td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100" x-text="u.role"></td>
                            <td class="px-6 py-4">
                                <span :class="u.status
                                        ? 'px-2 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800'
                                        : 'px-2 inline-flex text-xs font-semibold rounded-full bg-gray-100 text-gray-800'"
                                      x-text="u.status ? 'Ativo' : 'Inativo'">
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center space-x-2">
                                <button type="button"
                                        class="editarUsuario bg-yellow-400 hover:bg-yellow-500 text-gray-900 dark:text-white p-2 rounded shadow"
                                        @click="openEdit(u)">
                                    <i data-lucide="edit-2" class="w-4 h-4"></i>
                                </button>
                                <form method="POST" x-bind:action="`/usuarios/${u.id}/delete`" style="display:inline-block;">
                                    @csrf
                                    <button type="submit"
                                            class="bg-red-600 hover:bg-red-700 text-white p-2 rounded shadow"
                                            onclick="return confirm('Tem certeza que deseja desabilitar este usuário?')">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Paginação padrão do Laravel -->
        <div class="mt-4">
            {{ $usuarios->links() }}
        </div>

    
                    <!-- MODAL NOVO USUÁRIO -->
<div id="modalUsuario" tabindex="-1"
     class="hidden fixed inset-0 z-50 flex justify-center items-start overflow-y-auto overflow-x-hidden bg-black bg-opacity-50">
    <div class="relative w-full max-w-3xl p-4">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-800 max-h-[90vh] overflow-y-auto">
            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600 sticky top-0 bg-white dark:bg-gray-800 z-10">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Novo Usuário</h3>
                <button @click="document.getElementById('modalUsuario').classList.add('hidden')"
                        class="text-gray-400 bg-transparent hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form id="formNovoUsuario" action="{{ route('usuarios.storeBasic') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nome</label>
                        <input type="text" name="name" id="name" required
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">E-mail</label>
                        <input type="email" name="email" id="email" required
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>
                    <div>
                        <label for="cpf" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">CPF</label>
                        <input type="text" name="cpf" id="cpf" required
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>
                    <div>
                        <label for="telefone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Telefone</label>
                        <input type="text" name="telefone" id="telefone" required
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>
                    <div>
                        <label for="cep" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">CEP</label>
                        <div class="relative">
                            <input type="text" name="cep" id="cep" required
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pr-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                            <div id="loaderCep" class="hidden absolute right-2 top-2">
                                <svg class="animate-spin h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="logradouro" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Logradouro</label>
                        <input type="text" name="logradouro" id="logradouro" required
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>
                    <div>
                        <label for="numero" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Número</label>
                        <input type="text" name="numero" id="numero" required
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>
                    <div>
                        <label for="complemento" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Complemento</label>
                        <input type="text" name="complemento" id="complemento" required
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>
                    <div>
                        <label for="bairro" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Bairro</label>
                        <input type="text" name="bairro" id="bairro" required
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>
                    <div>
                        <label for="cidade" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cidade</label>
                        <input type="text" name="cidade" id="cidade" required
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>
                    <div>
                        <label for="uf" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">UF</label>
                        <input type="text" name="uf" id="uf" maxlength="2" required
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>
                    <div>
                        <label for="role" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Perfil</label>
                        <select id="role" name="role" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                            <option value="cliente" selected>Cliente</option>
                            <option value="admin">Administrador</option>
                            <option value="parceiro">Parceiro</option>
                        </select>
                    </div>
                </div>
                <div class="text-right">
                    <button type="submit"
                            class="text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Cadastrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


        <!-- MODAL EDITAR USUÁRIO -->
        <div id="modalEditarUsuario" tabindex="-1"
             class="fixed inset-0 z-50 flex justify-center items-center overflow-y-auto overflow-x-hidden bg-black bg-opacity-50 hidden">
            <div class="relative w-full max-w-lg p-4">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
                    <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Editar Usuário</h3>
                        <button @click="document.getElementById('modalEditarUsuario').classList.add('hidden')"
                                class="text-gray-400 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>
                    <form id="formEditarUsuario" method="POST" action="" class="p-6 space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="edit_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nome</label>
                            <input type="text" name="name" id="edit_name" required
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>
                        <div>
                            <label for="edit_email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">E-mail</label>
                            <input type="email" name="email" id="edit_email" required
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>
                        <div>
                            <label for="edit_role" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Perfil</label>
                            <select name="role" id="edit_role" required
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                <option value="cliente">Cliente</option>
                                <option value="admin">Administrador</option>
                                <option value="parceiro">Parceiro</option>
                            </select>
                        </div>
                        <div class="text-right">
                            <button type="submit"
                                    class="text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- SCRIPT -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const novoModal   = document.getElementById('modalUsuario');
                const editarModal = document.getElementById('modalEditarUsuario');
                const formEditar  = document.getElementById('formEditarUsuario');

                document.getElementById('btnNovoUsuario').addEventListener('click', () => {
                    novoModal.classList.remove('hidden');
                });

                window.openEdit = function(user) {
                    document.getElementById('edit_name').value  = user.name;
                    document.getElementById('edit_email').value = user.email;
                    document.getElementById('edit_role').value  = user.role.toLowerCase();
                    formEditar.action = `/usuario/editar/${user.id}`; // ajuste conforme rota real
                    editarModal.classList.remove('hidden');
                };
            });
        </script>

        <script>
    document.addEventListener('DOMContentLoaded', () => {
        const cepInput = document.getElementById('cep');
        const loader = document.getElementById('loaderCep');

        cepInput.addEventListener('blur', async function() {
            const cep = cepInput.value.replace(/\D/g, '');
            if (cep.length !== 8) return;

            loader.classList.remove('hidden');

            try {
                const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
                const data = await response.json();

                if (!data.erro) {
                    document.getElementById('logradouro').value = data.logradouro || '';
                    document.getElementById('bairro').value = data.bairro || '';
                    document.getElementById('cidade').value = data.localidade || '';
                    document.getElementById('uf').value = data.uf || '';
                }
            } catch (e) {
                console.error('Erro ao consultar CEP:', e);
            } finally {
                loader.classList.add('hidden');
            }
        });
    });
</script>
    </div>
</x-app-layout>
