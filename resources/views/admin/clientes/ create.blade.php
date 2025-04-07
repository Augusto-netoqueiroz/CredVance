@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Novo Cliente</h1>

    <form method="POST" action="{{ route('admin.clientes.store') }}">
        @csrf
        <div class="mb-3">
            <label>Nome</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Senha</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>CPF</label>
            <input type="text" name="cpf" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Telefone</label>
            <input type="text" name="telefone" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Endereço</label>
            <textarea name="endereco" class="form-control"></textarea>
        </div>
        <button class="btn btn-success">Salvar</button>
    </form>
</div>
@endsection
