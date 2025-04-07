@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Usuários</h1>

    <!-- Botão para abrir o modal -->
    <div class="mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNovoUsuario">
            Novo Usuário
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <p><strong>Total de usuários:</strong> {{ $usuarios->count() }}</p>

            <table class="table table-striped mt-3">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Perfil</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->name }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>{{ ucfirst($usuario->role) }}</td>
                        <td>
                            @if($usuario->status === 'ativo')
                                <span class="badge bg-success">Ativo</span>
                            @else
                                <span class="badge bg-secondary">Inativo</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.usuarios.edit', $usuario) }}" class="btn btn-sm btn-warning">Editar</a>
                            <form action="{{ route('admin.usuarios.destroy', $usuario) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Confirma excluir?')">Excluir</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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
                "Accept": "application/json" // Garante que o backend retorne JSON
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
@endsection
