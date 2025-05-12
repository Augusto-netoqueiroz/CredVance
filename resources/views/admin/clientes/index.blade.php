@extends('layouts.app')

@section('content')
<div class="container" x-data="{
    users: @json($usuarios->map(fn($u) => [
        'id'    => $u->id,
        'name'  => $u->name,
        'email' => $u->email,
        'role'  => ucfirst($u->role),
        'status'=> $u->status
    ])),
    search: '',
    page: 1,
    perPage: 10,
    filteredUsers() {
        return this.users.filter(user => {
            return ['name','email','role','status'].some(key =>
                user[key].toLowerCase().includes(this.search.toLowerCase())
            );
        });
    },
    totalPages() {
        return Math.ceil(this.filteredUsers().length / this.perPage) || 1;
    },
    paginatedUsers() {
        return this.filteredUsers().slice((this.page - 1) * this.perPage, this.page * this.perPage);
    }
}">
    <h1 class="mb-4 flex items-center gap-2 text-2xl">
        <i data-lucide="users" class="inline"></i> Usuários
    </h1>

    <div class="mb-3 flex items-center gap-2">
        <button class="btn btn-primary flex items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalNovoUsuario">
            <i data-lucide="user-plus"></i> Novo Usuário
        </button>
        <input type="text" placeholder="Pesquisar..." class="form-control w-1/3 ms-auto" x-model="search">
    </div>

    <div class="card">
        <div class="card-body">
            <p><strong>Total de usuários:</strong> <span x-text="filteredUsers().length"></span></p>

            <div class="overflow-x-auto">
                <table class="table table-striped mt-3">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Perfil</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="usuario in paginatedUsers()" :key="usuario.id">
                            <tr>
                                <td x-text="usuario.name"></td>
                                <td x-text="usuario.email"></td>
                                <td x-text="usuario.role"></td>
                                <td>
                                    <span :class="{
                                        'badge bg-success': usuario.status === 'ativo',
                                        'badge bg-secondary': usuario.status !== 'ativo'
                                    }" x-text="usuario.status === 'ativo' ? 'Ativo' : 'Inativo'"></span>
                                </td>
                                <td class="flex gap-1">
                                    <a :href="`/admin/usuarios/${usuario.id}/edit`" class="btn btn-sm btn-warning flex items-center gap-1">
                                        <i data-lucide="edit-2"></i>
                                    </a>
                                    <form :action="`/admin/usuarios/${usuario.id}`" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger flex items-center gap-1" onclick="return confirm('Confirma excluir?')">
                                            <i data-lucide="trash-2"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            <div class="mt-3 flex justify-center items-center space-x-2">
                <button class="btn btn-sm btn-outline-primary" :disabled="page <= 1" @click="page--">Anterior</button>
                <template x-for="num in Array.from({ length: totalPages() }, (_, i) => i + 1)" :key="num">
                    <button
                        class="btn btn-sm"
                        :class="num === page ? 'btn-primary' : 'btn-outline-primary'"
                        @click="page = num"
                        x-text="num"
                    ></button>
                </template>
                <button class="btn btn-sm btn-outline-primary" :disabled="page >= totalPages()" @click="page++">Próximo</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DE CRIAÇÃO DE USUÁRIO -->
<div class="modal fade" id="modalNovoUsuario" tabindex="-1" aria-labelledby="modalNovoUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNovoUsuarioLabel">Novo Usuário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <form id="formNovoUsuario">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="cpf" class="form-label">CPF</label>
                        <input type="text" class="form-control" id="cpf" name="cpf" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input type="text" class="form-control" id="telefone" name="telefone">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Perfil</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="cliente" selected>Cliente</option>
                            <option value="admin">Administrador</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script para AJAX -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("formNovoUsuario").addEventListener("submit", function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        fetch("/admin/usuarios", { 
            method: "POST",
            body: formData,
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                "Accept": "application/json"
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => { throw new Error(text); }); 
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert("Usuário cadastrado com sucesso!");
                location.reload();
            } else {
                alert("Erro: " + (data.message || "Verifique os campos!"));
            }
        })
        .catch(error => {
            console.error("Erro na requisição:", error);
            alert("Erro ao conectar com o servidor. Verifique os logs.");
        });
    });
});

});
</script>

<!-- Lucide Icons -->
<script src="https://unpkg.com/lucide/dist/lucide.min.js"></script>
<script>lucide.replace();</script>
@endsection
