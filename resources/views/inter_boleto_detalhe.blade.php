{{-- resources/views/inter_boleto_detalhe.blade.php --}}
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes da Cobrança Inter</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container py-4">
    <h1 class="mb-4">Detalhes da Cobrança</h1>
    <p>
        <a href="{{ route('inter.boletos.consulta.form') }}" class="btn btn-secondary btn-sm">Nova Consulta</a>
        <a href="{{ route('inter.boletos.form') }}" class="btn btn-secondary btn-sm">Voltar à Emissão</a>
        <a href="{{ route('inter.boletos.listagem.form') }}" class="btn btn-secondary btn-sm">Listar Cobranças</a>
    </p>

    {{-- Exibe erros se vier via redirect --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    {{-- Exibe detalhes --}}
    @isset($detalhes)
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Resposta da API</h5>
                <pre style="max-height:300px; overflow:auto;">{{ json_encode($detalhes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        </div>
        {{-- Campos mais relevantes --}}
        @if(!empty($detalhes['linhaDigitavel']))
            <p><strong>Linha Digitável:</strong> {{ $detalhes['linhaDigitavel'] }}</p>
        @endif
        @if(!empty($detalhes['codigoBarras']))
            <p><strong>Código de Barras:</strong> {{ $detalhes['codigoBarras'] }}</p>
        @endif
        @if(!empty($detalhes['vencimento']))
            <p><strong>Data de Vencimento:</strong> {{ $detalhes['vencimento'] }}</p>
        @endif
        @if(!empty($detalhes['valorNominal']))
            <p><strong>Valor Nominal:</strong> R$ {{ number_format($detalhes['valorNominal'], 2, ',', '.') }}</p>
        @endif
        @if(!empty($detalhes['status']))
            <p><strong>Status:</strong> {{ $detalhes['status'] }}</p>
        @endif

        @if(!empty($detalhes['links']) && is_array($detalhes['links']))
            <h5>Links:</h5>
            <ul>
            @foreach($detalhes['links'] as $linkObj)
                @if(!empty($linkObj['href']))
                    <li>
                        <a href="{{ $linkObj['href'] }}" target="_blank">
                            {{ $linkObj['rel'] ?? 'Boleto/PDF' }}
                        </a>
                    </li>
                @endif
            @endforeach
            </ul>
        @endif
    @endisset
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
