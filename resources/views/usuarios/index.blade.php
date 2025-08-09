<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight">
            Usuários
        </h2>
    </x-slot>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            /* Cores baseadas no padrão CredVance */
            --primary-color: #1e88e5; /* Azul principal */
            --secondary-color: #0d47a1; /* Azul mais escuro */
            --accent-color: #42a5f5; /* Azul claro */
            --success-color: #4caf50;
            --danger-color: #f44336;
            --warning-color: #ff9800;
            --gradient-primary: linear-gradient(135deg, #1e88e5, #0d47a1);
            --text-primary: #212121;
            --text-secondary: #757575;
            --bg-light: #fafafa;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        /* Inspirado no modelo: Cards com backdrop-filter e bordas arredondadas */
        .credvance-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(13, 71, 161, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        .credvance-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 70px rgba(13, 71, 161, 0.25);
        }

        /* Inspirado no modelo: Botões com gradiente e animações */
        .btn-credvance {
            background: var(--gradient-primary);
            color: white;
            padding: 12px 28px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(30, 136, 229, 0.3);
        }

        .btn-credvance:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(30, 136, 229, 0.4);
            color: white;
        }

        /* Inspirado no modelo: Indicadores com ícones coloridos e animações */
        .indicator-credvance {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .indicator-credvance:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
        }

        .indicator-icon {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .indicator-icon.blue {
            background: var(--primary-color);
        }

        .indicator-icon.green {
            background: var(--success-color);
        }

        .indicator-icon.indigo {
            background: var(--secondary-color);
        }

        .indicator-icon.red {
            background: var(--danger-color);
        }

        .indicator-credvance:hover .indicator-icon {
            transform: scale(1.1);
        }

        /* Inspirado no modelo: Formulários com bordas suaves e animações */
        .form-control-credvance {
            border: 2px solid #e3f2fd;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            width: 100%;
            color: var(--text-primary);
        }

        .form-control-credvance:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(30, 136, 229, 0.15);
            background: white;
            outline: none;
            transform: translateY(-1px);
        }

        /* Inspirado no modelo: Alertas com gradientes suaves */
        .alert-credvance {
            border-radius: 10px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .alert-success-credvance {
            background: linear-gradient(135deg, rgba(76, 175, 80, 0.1), rgba(76, 175, 80, 0.05));
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }

        .alert-danger-credvance {
            background: linear-gradient(135deg, rgba(244, 67, 54, 0.1), rgba(244, 67, 54, 0.05));
            color: var(--danger-color);
            border-left: 4px solid var(--danger-color);
        }

        /* Inspirado no modelo: Tabela com espaçamento e hover effects */
        .table-credvance {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 8px;
        }

        .table-credvance thead th {
            background: var(--bg-light);
            color: var(--text-secondary);
            font-weight: 600;
            padding: 15px 20px;
            text-align: left;
            border-bottom: 2px solid #e0e0e0;
            border-radius: 10px 10px 0 0;
        }

        .table-credvance tbody tr {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
        }

        .table-credvance tbody tr:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .table-credvance tbody td {
            padding: 15px 20px;
            color: var(--text-primary);
            vertical-align: middle;
        }

        .table-credvance tbody tr td:first-child {
            border-radius: 10px 0 0 10px;
        }

        .table-credvance tbody tr td:last-child {
            border-radius: 0 10px 10px 0;
        }

        /* Inspirado no modelo: Status badges com cores suaves */
        .status-badge-credvance {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: capitalize;
        }

        .status-badge-credvance.ativo {
            background-color: rgba(76, 175, 80, 0.15);
            color: var(--success-color);
        }

        .status-badge-credvance.inativo {
            background-color: rgba(158, 158, 158, 0.15);
            color: #757575;
        }

        /* Inspirado no modelo: Modal com backdrop-filter */
        .modal-credvance {
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
        }

        .modal-content-credvance {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 15px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            max-height: 90vh; /* Limita a altura do modal */
            overflow-y: auto; /* Adiciona scroll se o conteúdo for maior que a altura máxima */
        }

        /* Inspirado no modelo: Animações suaves */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease;
        }

        /* Responsividade inspirada no modelo */
        @media (max-width: 768px) {
            .indicator-credvance {
                text-align: center;
                padding: 15px;
            }

            .table-credvance thead {
                display: none;
            }

            .table-credvance tbody, .table-credvance tr, .table-credvance td {
                display: block;
                width: 100%;
            }

            .table-credvance tr {
                margin-bottom: 15px;
                border: 1px solid #e0e0e0;
                border-radius: 10px;
            }

            .table-credvance td {
                text-align: right;
                padding-left: 50%;
                position: relative;
                border-radius: 0 !important;
            }

            .table-credvance td::before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                width: calc(50% - 20px);
                padding-right: 10px;
                white-space: nowrap;
                text-align: left;
                font-weight: 600;
                color: var(--text-primary);
            }

            .modal-content-credvance {
                max-width: 95%; /* Ajusta a largura máxima para telas menores */
            }
        }
    </style>

    @if(session('success'))
        <div class="mb-4 alert-success-credvance">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 alert-danger-credvance">
            {{ session('error') }}
        </div>
    @endif

    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-blue-50 via-white to-blue-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 min-h-screen" 
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
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <button id="btnNovoUsuario"
                    class="btn-credvance">
                <i data-lucide="user-plus" class="w-4 h-4 mr-1"></i> Novo Usuário
            </button>
            <input type="text" placeholder="Pesquisar..." x-model="search"
                   class="form-control-credvance" />
        </div>

        <!-- Total -->
        <p class="text-gray-700 dark:text-gray-200 font-medium mb-4">
            Total de usuários: <span x-text="filtered.length"></span>
        </p>

        <!-- Tabela -->
        <div class="overflow-x-auto credvance-card p-6">
            <table class="table-credvance">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Perfil</th>
                        <th>Status</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="u in paginated" :key="u.id">
                        <tr>
                            <td data-label="Nome:" x-text="u.name"></td>
                            <td data-label="E-mail:" x-text="u.email"></td>
                            <td data-label="Perfil:" x-text="u.role"></td>
                            <td data-label="Status:">
                                <span :class="u.status
                                        ? 'status-badge-credvance ativo'
                                        : 'status-badge-credvance inativo'"
                                      x-text="u.status ? 'Ativo' : 'Inativo'">
                                </span>
                            </td>
                            <td data-label="Ações:" class="text-center space-x-2">
                                <button type="button"
                                        class="btn-credvance bg-yellow-500 hover:bg-yellow-600 text-gray-900 dark:text-white p-2 rounded shadow"
                                        @click="openEdit(u)">
                                    <i data-lucide="edit-2" class="w-4 h-4"></i>
                                </button>
                                <form method="POST" x-bind:action="`/usuarios/${u.id}/delete`" style="display:inline-block;">
                                    @csrf
                                    <button type="submit"
                                            class="btn-credvance bg-red-600 hover:bg-red-700 text-white p-2 rounded shadow"
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

      

    
        <!-- MODAL NOVO USUÁRIO -->
        <div id="modalUsuario" tabindex="-1"
             class="hidden fixed inset-0 z-50 modal-credvance flex justify-center items-start overflow-y-auto overflow-x-hidden">
            <div class="relative w-full max-w-3xl p-4 modal-content-credvance">
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
                                       class="form-control-credvance">
                            </div>
                            <div>
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">E-mail</label>
                                <input type="email" name="email" id="email" required
                                       class="form-control-credvance">
                            </div>
                            <div>
                                <label for="cpf" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">CPF</label>
                                <input type="text" name="cpf" id="cpf" required
                                       class="form-control-credvance">
                            </div>
                            <div>
                                <label for="telefone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Telefone</label>
                                <input type="text" name="telefone" id="telefone" required
                                       class="form-control-credvance">
                            </div>
                            <div>
                                <label for="cep" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">CEP</label>
                                <div class="relative">
                                    <input type="text" name="cep" id="cep" required
                                           class="form-control-credvance pr-10">
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
                                       class="form-control-credvance">
                            </div>
                            <div>
                                <label for="numero" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Número</label>
                                <input type="text" name="numero" id="numero" required
                                       class="form-control-credvance">
                            </div>
                            <div>
                                <label for="complemento" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Complemento</label>
                                <input type="text" name="complemento" id="complemento" required
                                       class="form-control-credvance">
                            </div>
                            <div>
                                <label for="bairro" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Bairro</label>
                                <input type="text" name="bairro" id="bairro" required
                                       class="form-control-credvance">
                            </div>
                            <div>
                                <label for="cidade" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cidade</label>
                                <input type="text" name="cidade" id="cidade" required
                                       class="form-control-credvance">
                            </div>
                            <div>
                                <label for="uf" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">UF</label>
                                <input type="text" name="uf" id="uf" maxlength="2" required
                                       class="form-control-credvance">
                            </div>
                            <div>
                                <label for="role" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Perfil</label>
                                <select id="role" name="role" required
                                        class="form-control-credvance">
                                    <option value="cliente" selected>Cliente</option>
                                    <option value="admin">Administrador</option>
                                    <option value="parceiro">Parceiro</option>
                                </select>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit"
                                    class="btn-credvance bg-green-600 hover:bg-green-700">
                                Cadastrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- MODAL EDITAR USUÁRIO -->
        <div id="modalEditarUsuario" tabindex="-1"
             class="hidden fixed inset-0 z-50 modal-credvance flex justify-center items-center overflow-y-auto overflow-x-hidden">
            <div class="relative w-full max-w-lg p-4 modal-content-credvance">
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
                                   class="form-control-credvance">
                        </div>
                        <div>
                            <label for="edit_email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">E-mail</label>
                            <input type="email" name="email" id="edit_email" required
                                   class="form-control-credvance">
                        </div>
                        <div>
                            <label for="edit_role" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Perfil</label>
                            <select name="role" id="edit_role" required
                                    class="form-control-credvance">
                                <option value="cliente">Cliente</option>
                                <option value="admin">Administrador</option>
                                <option value="parceiro">Parceiro</option>
                            </select>
                        </div>
                        <div class="text-right">
                            <button type="submit"
                                    class="btn-credvance bg-green-600 hover:bg-green-700">
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

