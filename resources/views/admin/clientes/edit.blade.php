@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Cliente</h1>

    <form method="POST" action="{{ route('admin.clientes.update', $cliente->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Nome</label>
            <input type="text" name="name" class="form-control" value="{{ $cliente->user->name }}" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ $cliente->user->email }}" required>
        </div>
        <div class="mb-3">
            <label>CPF</label>
            <input type="text" name="cpf" class="form-control" value="{{ $cliente->cpf }}" required>
        </div>
        <div class="mb-3">
            <label>Telefone</label>
            <input type="text" name="telefone" class="form-control" value="{{ $cliente->telefone }}" required>
        </div>
        <div class="mb-3">
            <label>Endereço</label>
            <textarea name="endereco" class="form-control">{{ $cliente->endereco }}</textarea>
        </div>
        <button class="btn btn-primary">Atualizar</button>
    </form>
</div>
@endsection
