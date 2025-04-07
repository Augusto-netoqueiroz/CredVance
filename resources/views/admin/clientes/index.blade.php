@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Clientes</h1>
    <a href="{{ route('admin.clientes.create') }}" class="btn btn-primary mb-3">Novo Cliente</a>
    <table class="table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>CPF</th>
                <th>Telefone</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clientes as $cliente)
                <tr>
                    <td>{{ $cliente->user->name }}</td>
                    <td>{{ $cliente->cpf }}</td>
                    <td>{{ $cliente->telefone }}</td>
                    <td>
                        <a href="{{ route('admin.clientes.edit', $cliente->id) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form method="POST" action="{{ route('admin.clientes.destroy', $cliente->id) }}" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Excluir</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
