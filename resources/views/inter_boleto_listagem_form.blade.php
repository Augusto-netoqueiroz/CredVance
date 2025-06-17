{{-- resources/views/inter_boleto_listagem_form.blade.php --}}
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Cobranças Inter</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container py-4">
    <h1 class="mb-4">Listar Cobranças</h1>
    <p>
        <a href="{{ route('inter.boletos.form') }}" class="btn btn-secondary btn-sm">Emitir Cobrança</a>
        <a href="{{ route('inter.boletos.consulta.form') }}" class="btn btn-secondary btn-sm">Consultar Cobrança</a>
    </p>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="GET" action="{{ route('inter.boletos.listagem.result') }}">
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="dataInicio" class="form-label">Data Início</label>
                <input type="date" class="form-control" id="dataInicio" name="dataInicio"
                       value="{{ old('dataInicio') }}">
            </div>
            <div class="col-md-4">
                <label for="dataFim" class="form-label">Data Fim</label>
                <input type="date" class="form-control" id="dataFim" name="dataFim"
                       value="{{ old('dataFim') }}">
            </div>
            <div class="col-md-4">
                <label for="status" class="form-label">Status</label>
                <input type="text" class="form-control" id="status" name="status"
                       placeholder="PENDENTE, PAGO, CANCELADO..." value="{{ old('status') }}">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Listar</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
