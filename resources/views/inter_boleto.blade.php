{{-- resources/views/inter_boleto.blade.php --}}
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inter Cobrança (Emissão / Consulta / Listagem)</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container py-4">

    <h1 class="mb-4">Inter Cobrança</h1>

    {{-- Nav-tabs --}}
    @php
        $active = session('active_tab', 'emitir');
    @endphp

    <ul class="nav nav-tabs mb-3" id="tabMenu" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link @if($active==='emitir') active @endif"
                    id="tab-emitir"
                    data-bs-toggle="tab"
                    data-bs-target="#content-emitir"
                    type="button" role="tab">
                Emitir Cobrança
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link @if($active==='consultar') active @endif"
                    id="tab-consultar"
                    data-bs-toggle="tab"
                    data-bs-target="#content-consultar"
                    type="button" role="tab">
                Consultar Cobrança
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link @if($active==='listar') active @endif"
                    id="tab-listar"
                    data-bs-toggle="tab"
                    data-bs-target="#content-listar"
                    type="button" role="tab">
                Listar Cobranças
            </button>
        </li>
    </ul>

    <div class="tab-content" id="tabContent">

        {{-- Aba Emitir Cobrança --}}
        <div class="tab-pane fade @if($active==='emitir') show active @endif"
             id="content-emitir" role="tabpanel">

            {{-- Mensagens de erro ou sucesso --}}
            @if($errors->any() && $active==='emitir')
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('boleto_data') && $active==='emitir')
                @php $bd = session('boleto_data'); @endphp
                <div class="alert alert-success">
                    <h5>Cobrança emitida com sucesso!</h5>
                    @if(isset($bd['codigoSolicitacao']))
                        <p><strong>Código de Solicitação:</strong> {{ $bd['codigoSolicitacao'] }}</p>
                    @endif
                    @if(config('app.debug') && isset($bd['rawResponse']))
                        <details class="mt-2">
                            <summary>Resposta completa da API</summary>
                            <pre style="max-height:300px; overflow:auto;">{{ json_encode($bd['rawResponse'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </details>
                    @endif
                    <p>Aguarde callback ou consulte na aba “Consultar Cobrança”.</p>
                </div>
            @endif

            {{-- Formulário de emissão --}}
            <form method="POST" action="{{ route('inter.boletos.create') }}">
                @csrf

                {{-- Seu Número --}}
                <div class="mb-3">
                    <label for="nosso_numero" class="form-label">Seu Número (seuNumero)</label>
                    <input type="text" class="form-control" id="nosso_numero" name="nosso_numero"
                           value="{{ old('nosso_numero') }}" maxlength="15" required>
                </div>

                {{-- Valor Nominal --}}
                <div class="mb-3">
                    <label for="valor" class="form-label">Valor Nominal (R$)</label>
                    <input type="number" step="0.01" class="form-control" id="valor" name="valor"
                           value="{{ old('valor') }}" required>
                </div>

                {{-- Data de Vencimento --}}
                <div class="mb-3">
                    <label for="data_vencimento" class="form-label">Data de Vencimento</label>
                    <input type="date" class="form-control" id="data_vencimento" name="data_vencimento"
                           value="{{ old('data_vencimento') }}" required>
                </div>

                {{-- Num Dias Agenda --}}
                <div class="mb-3">
                    <label for="num_dias_agenda" class="form-label">Num Dias Agenda (0-60)</label>
                    <input type="number" class="form-control" id="num_dias_agenda" name="num_dias_agenda"
                           value="{{ old('num_dias_agenda', 0) }}" min="0" max="60">
                    <div class="form-text">Dias após vencimento para cancelamento automático.</div>
                </div>

                {{-- Dados do Pagador --}}
                <fieldset class="border p-3 mb-3">
                    <legend class="w-auto px-2">Dados do Pagador</legend>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="sacado_nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="sacado_nome" name="sacado[nome]"
                                   value="{{ old('sacado.nome') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="sacado_cpf_cnpj" class="form-label">CPF/CNPJ</label>
                            <input type="text" class="form-control" id="sacado_cpf_cnpj" name="sacado[cpf_cnpj]"
                                   value="{{ old('sacado.cpf_cnpj') }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="sacado_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="sacado_email" name="sacado[email]"
                                   value="{{ old('sacado.email') }}">
                        </div>
                        <div class="col-md-2">
                            <label for="sacado_ddd" class="form-label">DDD</label>
                            <input type="text" class="form-control" id="sacado_ddd" name="sacado[ddd]"
                                   value="{{ old('sacado.ddd') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="sacado_telefone" class="form-label">Telefone</label>
                            <input type="text" class="form-control" id="sacado_telefone" name="sacado[telefone]"
                                   value="{{ old('sacado.telefone') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="sacado_tipoPessoa" class="form-label">Tipo Pessoa</label>
                        <select class="form-select" id="sacado_tipoPessoa" name="sacado[tipoPessoa]" required>
                            <option value="FISICA" {{ old('sacado.tipoPessoa')==='FISICA' ? 'selected':'' }}>FISICA</option>
                            <option value="JURIDICA" {{ old('sacado.tipoPessoa')==='JURIDICA' ? 'selected':'' }}>JURIDICA</option>
                        </select>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="sacado_logradouro" class="form-label">Logradouro</label>
                            <input type="text" class="form-control" id="sacado_logradouro" name="sacado[logradouro]"
                                   value="{{ old('sacado.logradouro') }}" required>
                        </div>
                        <div class="col-md-2">
                            <label for="sacado_numero" class="form-label">Número</label>
                            <input type="text" class="form-control" id="sacado_numero" name="sacado[numero]"
                                   value="{{ old('sacado.numero') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="sacado_complemento" class="form-label">Complemento (opcional)</label>
                            <input type="text" class="form-control" id="sacado_complemento" name="sacado[complemento]"
                                   value="{{ old('sacado.complemento') }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="sacado_bairro" class="form-label">Bairro</label>
                            <input type="text" class="form-control" id="sacado_bairro" name="sacado[bairro]"
                                   value="{{ old('sacado.bairro') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="sacado_cidade" class="form-label">Cidade</label>
                            <input type="text" class="form-control" id="sacado_cidade" name="sacado[cidade]"
                                   value="{{ old('sacado.cidade') }}" required>
                        </div>
                        <div class="col-md-2">
                            <label for="sacado_uf" class="form-label">UF</label>
                            <input type="text" class="form-control" id="sacado_uf" name="sacado[uf]"
                                   value="{{ old('sacado.uf') }}" maxlength="2" required>
                        </div>
                        <div class="col-md-2">
                            <label for="sacado_cep" class="form-label">CEP</label>
                            <input type="text" class="form-control" id="sacado_cep" name="sacado[cep]"
                                   value="{{ old('sacado.cep') }}" required>
                        </div>
                    </div>
                </fieldset>

                {{-- Campos Opcionais --}}
                <details class="mb-3">
                    <summary class="btn btn-sm btn-outline-secondary">Campos Opcionais</summary>
                    <div class="border p-3 mt-2">

                        {{-- Desconto --}}
                        <h5>Desconto</h5>
                        <div class="row mb-2">
                            <div class="col-md-3">
                                <label class="form-label">Taxa (%)</label>
                                <input type="number" step="0.01" class="form-control" name="desconto[taxa]"
                                       value="{{ old('desconto.taxa') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Código</label>
                                <input type="text" class="form-control" name="desconto[codigo]"
                                       value="{{ old('desconto.codigo') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Dias</label>
                                <input type="number" class="form-control" name="desconto[quantidadeDias]"
                                       value="{{ old('desconto.quantidadeDias') }}">
                            </div>
                        </div>

                        {{-- Multa --}}
                        <h5>Multa</h5>
                        <div class="row mb-2">
                            <div class="col-md-3">
                                <label class="form-label">Taxa (%)</label>
                                <input type="number" step="0.01" class="form-control" name="multa[taxa]"
                                       value="{{ old('multa.taxa') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Código</label>
                                <input type="text" class="form-control" name="multa[codigo]"
                                       value="{{ old('multa.codigo') }}">
                            </div>
                        </div>

                        {{-- Mora --}}
                        <h5>Mora</h5>
                        <div class="row mb-2">
                            <div class="col-md-3">
                                <label class="form-label">Taxa (%)</label>
                                <input type="number" step="0.01" class="form-control" name="mora[taxa]"
                                       value="{{ old('mora.taxa') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Código</label>
                                <input type="text" class="form-control" name="mora[codigo]"
                                       value="{{ old('mora.codigo') }}">
                            </div>
                        </div>

                        {{-- Mensagem --}}
                        <h5>Mensagem</h5>
                        @for($i = 1; $i <= 5; $i++)
                            <div class="mb-2">
                                <label class="form-label">Linha {{ $i }}</label>
                                <input type="text" class="form-control" name="mensagem[linha{{ $i }}]"
                                       value="{{ old('mensagem.linha'.$i) }}">
                            </div>
                        @endfor

                        {{-- Beneficiário Final --}}
                        <h5>Beneficiário Final</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nome</label>
                                <input type="text" class="form-control" name="beneficiarioFinal[nome]"
                                       value="{{ old('beneficiarioFinal.nome') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">CPF/CNPJ</label>
                                <input type="text" class="form-control" name="beneficiarioFinal[cpfCnpj]"
                                       value="{{ old('beneficiarioFinal.cpfCnpj') }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Logradouro</label>
                                <input type="text" class="form-control" name="beneficiarioFinal[logradouro]"
                                       value="{{ old('beneficiarioFinal.logradouro') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Número</label>
                                <input type="text" class="form-control" name="beneficiarioFinal[numero]"
                                       value="{{ old('beneficiarioFinal.numero') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Complemento</label>
                                <input type="text" class="form-control" name="beneficiarioFinal[complemento]"
                                       value="{{ old('beneficiarioFinal.complemento') }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Bairro</label>
                                <input type="text" class="form-control" name="beneficiarioFinal[bairro]"
                                       value="{{ old('beneficiarioFinal.bairro') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Cidade</label>
                                <input type="text" class="form-control" name="beneficiarioFinal[cidade]"
                                       value="{{ old('beneficiarioFinal.cidade') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">UF</label>
                                <input type="text" class="form-control" name="beneficiarioFinal[uf]" maxlength="2"
                                       value="{{ old('beneficiarioFinal.uf') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">CEP</label>
                                <input type="text" class="form-control" name="beneficiarioFinal[cep]"
                                       value="{{ old('beneficiarioFinal.cep') }}">
                            </div>
                        </div>

                    </div>
                </details>

                <button type="submit" class="btn btn-primary">Emitir Cobrança</button>
            </form>

        </div>

        {{-- Aba Consultar Cobrança --}}
        <div class="tab-pane fade @if($active==='consultar') show active @endif"
             id="content-consultar" role="tabpanel">

            {{-- Mensagens de erro --}}
            @if($errors->any() && $active==='consultar')
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Exibe detalhes se houver --}}
            @if(session('detalhes') && $active==='consultar')
                @php $detalhes = session('detalhes'); @endphp
                <div class="alert alert-success">
                    <h5>Detalhes da Cobrança</h5>
                    @if(config('app.debug'))
                        <details class="mt-2">
                            <summary>Resposta completa da API</summary>
                            <pre style="max-height:300px; overflow:auto;">{{ json_encode($detalhes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </details>
                    @endif
                </div>
                @php
                    if (isset($detalhes['cobranca']) && is_array($detalhes['cobranca'])) {
                        $c = $detalhes['cobranca'];
                        $b = $detalhes['boleto'] ?? [];
                        $p = $detalhes['pix'] ?? [];
                    } else {
                        $c = $detalhes;
                        $b = $detalhes;
                        $p = $detalhes;
                    }
                    $codigo = $c['codigoSolicitacao'] ?? null;
                    $linhaDigitavel = $b['linhaDigitavel'] ?? ($detalhes['linhaDigitavel'] ?? null);
                    $codigoBarras   = $b['codigoBarras']   ?? ($detalhes['codigoBarras']   ?? null);
                @endphp

                <div class="card mb-3">
                    <div class="card-body">
                        @if($linhaDigitavel)
                            <p><strong>Linha Digitável:</strong> {{ $linhaDigitavel }}</p>
                        @endif
                        @if($codigoBarras)
                            <p><strong>Código de Barras:</strong> {{ $codigoBarras }}</p>
                        @endif
                        @if(!empty($c['dataVencimento']) || !empty($c['vencimento']))
                            <p><strong>Data de Vencimento:</strong> {{ $c['dataVencimento'] ?? ($c['vencimento'] ?? '') }}</p>
                        @endif
                        @if(!empty($c['dataEmissao']))
                            <p><strong>Data de Emissão:</strong> {{ $c['dataEmissao'] }}</p>
                        @endif
                        @if(isset($c['valorNominal']))
                            <p><strong>Valor Nominal:</strong> R$ {{ number_format((float)$c['valorNominal'], 2, ',', '.') }}</p>
                        @endif
                        @if(!empty($c['status']) || !empty($c['situacao']))
                            <p><strong>Status:</strong> {{ $c['status'] ?? $c['situacao'] }}</p>
                        @endif

                        {{-- Links originais (se houver) --}}
                        @if(!empty($detalhes['links']) && is_array($detalhes['links']))
                            <h6>Links disponíveis:</h6>
                            <ul>
                                @foreach($detalhes['links'] as $linkObj)
                                    @if(!empty($linkObj['href']))
                                        <li><a href="{{ $linkObj['href'] }}" target="_blank">{{ $linkObj['rel'] ?? 'Link' }}</a></li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif

                        {{-- Botões PDF --}}
                        @if($codigo)
                            <a href="{{ route('inter.boletos.pdfview', ['codigo'=>$codigo]) }}"
                               target="_blank"
                               class="btn btn-outline-primary mb-2">
                                Ver Boleto em PDF
                            </a>
                            <form method="POST" action="{{ route('inter.boletos.download') }}"
                                  target="_blank" style="display:inline;">
                                @csrf
                                <input type="hidden" name="codigoSolicitacao" value="{{ $codigo }}">
                                <button type="submit" class="btn btn-success mb-2">
                                    Download do Boleto (PDF)
                                </button>
                            </form>
                            <div class="form-text">Clique para baixar ou visualizar o PDF do boleto.</div>

                            {{-- Exibição inline em iframe --}}
                            <div class="mt-3" style="height:600px;">
                                <iframe
                                    src="{{ route('inter.boletos.pdfview', ['codigo'=>$codigo]) }}"
                                    width="100%" height="100%" frameborder="0">
                                </iframe>
                            </div>
                        @else
                            <p class="text-warning">Código de Solicitação não disponível para download/exibição do PDF.</p>
                        @endif

                    </div>
                </div>
            @endif

            {{-- Form de consulta --}}
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

        {{-- Aba Listar Cobranças --}}
        <div class="tab-pane fade @if($active==='listar') show active @endif"
             id="content-listar" role="tabpanel">

            {{-- Mensagens de erro --}}
            @if($errors->any() && $active==='listar')
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form de filtros --}}
            <form method="GET" action="{{ route('inter.boletos.listagem') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="dataInicio" class="form-label">Data Início</label>
                        <input type="date" class="form-control" id="dataInicio" name="dataInicio"
                               value="{{ old('dataInicio') }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="dataFim" class="form-label">Data Fim</label>
                        <input type="date" class="form-control" id="dataFim" name="dataFim"
                               value="{{ old('dataFim') }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <input type="text" class="form-control" id="status" name="status"
                               placeholder="PENDENTE, PAGO, CANCELADO..." value="{{ old('status') }}">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Listar</button>
            </form>

            {{-- Exibir resultado da listagem --}}
            @if(session('lista') && $active==='listar')
                @php $lista = session('lista'); @endphp

                {{-- JSON completo em debug --}}
                @if(config('app.debug'))
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5>Resposta completa da API</h5>
                            <pre style="max-height:300px; overflow:auto;">{{ json_encode($lista, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    </div>
                @endif

                @php
                    // Detecta array de itens conforme estrutura retornada: ['cobrancas'] ou ['items'] ou array simples
                    if (is_array($lista) && !empty($lista['cobrancas'] ?? null)) {
                        $items = $lista['cobrancas'];
                    } elseif (is_array($lista) && !empty($lista['items'] ?? null)) {
                        $items = $lista['items'];
                    } elseif (is_array($lista)) {
                        $items = $lista;
                    } else {
                        $items = [];
                    }
                @endphp

                {{-- Exibe resumo de paginação, se disponível --}}
                @if(is_array($lista) && isset($lista['totalElementos']))
                    <p>
                        Total de cobranças: {{ $lista['totalElementos'] }}
                        @if(isset($lista['totalPaginas']))
                            | Total de páginas: {{ $lista['totalPaginas'] }}
                        @endif
                    </p>
                @endif

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Código Solicitação</th>
                            <th>Seu Número</th>
                            <th>Situação</th>
                            <th>Data Emissão</th>
                            <th>Data Vencimento</th>
                            <th>Valor Nominal</th>
                            <th>Linha Digitável</th>
                            <th>Código de Barras</th>
                            <th>Pix Copia e Cola</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($items as $item)
                        @php
                            // Cada item: ['cobranca'=>..., 'boleto'=>..., 'pix'=>...]
                            $c = $item['cobranca'] ?? [];
                            $b = $item['boleto']   ?? [];
                            $p = $item['pix']      ?? [];
                        @endphp
                        <tr>
                            <td>{{ $c['codigoSolicitacao'] ?? '' }}</td>
                            <td>{{ $c['seuNumero'] ?? '' }}</td>
                            <td>{{ $c['situacao'] ?? ($c['status'] ?? '') }}</td>
                            <td>{{ $c['dataEmissao'] ?? '' }}</td>
                            <td>{{ $c['dataVencimento'] ?? ($c['vencimento'] ?? '') }}</td>
                            <td>
                                @if(isset($c['valorNominal']))
                                    R$ {{ number_format((float)$c['valorNominal'], 2, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $b['linhaDigitavel'] ?? '-' }}</td>
                            <td>{{ $b['codigoBarras'] ?? '-' }}</td>
                            <td style="max-width:200px; word-break:break-all;">
                                {{ $p['pixCopiaECola'] ?? '-' }}
                            </td>
                            <td>
                                {{-- Botão para consultar direto --}}
                                @if(!empty($c['codigoSolicitacao']))
                                    <form method="POST" action="{{ route('inter.boletos.consulta') }}" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="codigoSolicitacao" value="{{ $c['codigoSolicitacao'] }}">
                                        <button type="submit" class="btn btn-sm btn-primary">Detalhar</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">Nenhuma cobrança encontrada.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            @endif

        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var active = "{{ session('active_tab', 'emitir') }}";
        var tabTriggerEl;
        if (active === 'emitir') {
            tabTriggerEl = document.querySelector('#tab-emitir');
        } else if (active === 'consultar') {
            tabTriggerEl = document.querySelector('#tab-consultar');
        } else if (active === 'listar') {
            tabTriggerEl = document.querySelector('#tab-listar');
        }
        if (tabTriggerEl) {
            var tab = new bootstrap.Tab(tabTriggerEl);
            tab.show();
        }
    });
</script>
</body>
</html>
