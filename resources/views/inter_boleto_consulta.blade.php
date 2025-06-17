{{-- resources/views/inter_boleto_consulta.blade.php --}}
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Cobrança Inter</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container py-4">
    <h1 class="mb-4">Consultar Cobrança - Inter</h1>
    <p><a href="{{ route('inter.boletos.form') }}" class="btn btn-secondary btn-sm">Voltar à Emissão</a></p>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('inter.boletos.consulta') }}">
        @csrf
        <div class="mb-3">
            <label for="codigoSolicitacao" class="form-label">Código de Solicitação</label>
            <input type="text" class="form-control" id="codigoSolicitacao" name="codigoSolicitacao"
                   value="{{ old('codigoSolicitacao') }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Consultar</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
