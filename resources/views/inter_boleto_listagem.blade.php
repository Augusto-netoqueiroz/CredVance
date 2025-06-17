{{-- resources/views/inter_boleto_listagem.blade.php --}}
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado da Listagem de Cobranças</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container py-4">
    <h1 class="mb-4">Resultado da Listagem de Cobranças</h1>
    <p>
        <a href="{{ route('inter.boletos.listagem.form') }}" class="btn btn-secondary btn-sm">Voltar a Filtros</a>
        <a href="{{ route('inter.boletos.form') }}" class="btn btn-secondary btn-sm">Emitir Cobrança</a>
    </p>

    {{-- Exibe erros --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    @isset($lista)
        {{-- Exibir JSON completo para debug --}}
        @if(config('app.debug'))
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Resposta completa da API</h5>
                    <pre style="max-height:300px; overflow:auto;">{{ json_encode($lista, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            </div>
        @endif

        {{-- Tentar exibir peça por peça --}}
        @if(is_array($lista) && !empty($lista['items'] ?? $lista['cobrancas'] ?? null))
            @php
                // Ajuste conforme estrutura retornada: alguns APIs usam 'items', outros 'cobrancas', ou retorno direto array.
                $items = $lista['items'] ?? $lista['cobrancas'] ?? $lista;
            @endphp
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Código Solicitação</th>
                        <th>Data Venc.</th>
                        <th>Valor</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($items as $item)
                    <tr>
                        {{-- Ajuste as keys conforme o retorno real --}}
                        <td>{{ $item['codigoSolicitacao'] ?? ($item['id'] ?? '') }}</td>
                        <td>{{ $item['dataVencimento'] ?? ($item['vencimento'] ?? '') }}</td>
                        <td>
                            @if(isset($item['valorNominal']))
                                R$ {{ number_format($item['valorNominal'], 2, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $item['status'] ?? '' }}</td>
                        <td>
                            @if(!empty($item['codigoSolicitacao']))
                                <a href="{{ route('inter.boletos.consulta.form') }}" onclick="event.preventDefault(); document.getElementById('form-consulta-{{ $item['codigoSolicitacao'] }}').submit();" class="btn btn-sm btn-primary">Detalhar</a>
                                <form id="form-consulta-{{ $item['codigoSolicitacao'] }}" method="POST" action="{{ route('inter.boletos.consulta') }}" style="display:none;">
                                    @csrf
                                    <input type="hidden" name="codigoSolicitacao" value="{{ $item['codigoSolicitacao'] }}">
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Nenhuma cobrança encontrada.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        @else
            {{-- Caso retorno não esteja em 'items' --}}
            <p>Resposta inesperada ao listar cobranças.</p>
        @endif
    @endisset
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
