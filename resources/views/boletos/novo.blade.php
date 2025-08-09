<x-app-layout>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Criar Boleto - CredVance</title>

    <!-- Estilos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #1e88e5;
            --secondary: #0d47a1;
            --gradient: linear-gradient(135deg, #1e88e5, #0d47a1);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--gradient);
        }

        .boleto-container {
            display: flex;
            justify-content: center;
            padding: 3rem 1rem;
            min-height: 100vh;
        }

        .boleto-card {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(20px);
            border-radius: 18px;
            padding: 2rem;
            box-shadow: 0 20px 60px rgba(13, 71, 161, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            max-width: 800px;
            width: 100%;
        }

        .section-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
        }

        .form-control {
            border-radius: 10px;
            border: 2px solid #e3f2fd;
            background-color: #fff;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.15rem rgba(30, 136, 229, 0.25);
        }

        .btn-credvance {
            background: var(--gradient);
            color: #fff;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            padding: 12px;
            transition: all 0.3s ease;
        }

        .btn-credvance:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(30, 136, 229, 0.3);
        }

        .boleto-gerado-card {
            background: #f5faff;
            border-left: 4px solid var(--primary);
            border-radius: 12px;
            padding: 1.5rem;
        }

        code {
            background-color: #eef6ff;
            padding: 4px 6px;
            border-radius: 6px;
            color: var(--secondary);
            font-weight: 600;
            word-break: break-all;
        }
    </style>
</head>

<div class="boleto-container">
    <div class="boleto-card">
        <h2 class="section-title">📄 Criar Novo Boleto</h2>

        {{-- CLIENTE --}}
        <div class="mb-3">
            <label class="form-label">Selecionar Cliente</label>
            <select id="cliente_id" class="form-select">
                <option value="">Selecione...</option>
                @foreach ($clientes as $cliente)
                    <option value="{{ $cliente->id }}">{{ $cliente->name }} ({{ $cliente->cpf }})</option>
                @endforeach
            </select>
        </div>

        {{-- DADOS PAGADOR --}}
        <div id="dados-cliente-editaveis" class="d-none">
            <h6 class="mt-4 mb-3 fw-bold text-secondary">📌 Dados do Pagador</h6>
            <div class="row g-2">
                <div class="col-md-8"><label class="form-label">Nome</label><input type="text" id="nome" class="form-control"></div>
                <div class="col-md-4"><label class="form-label">Tipo Pessoa</label><input type="text" id="tipoPessoa" class="form-control" readonly></div>
            </div>
            <div class="row g-2 mt-2">
                <div class="col-md-6"><label class="form-label">CPF/CNPJ</label><input type="text" id="cpfCnpj" class="form-control"></div>
                <div class="col-md-3"><label class="form-label">DDD</label><input type="text" id="ddd" class="form-control"></div>
                <div class="col-md-3"><label class="form-label">Telefone</label><input type="text" id="telefone" class="form-control"></div>
            </div>
            <div class="mt-3"><label class="form-label">Email</label><input type="email" id="email" class="form-control"></div>
            <div class="row g-2 mt-2">
                <div class="col-md-8"><label class="form-label">Logradouro</label><input type="text" id="logradouro" class="form-control"></div>
                <div class="col-md-4"><label class="form-label">Número</label><input type="text" id="numero" class="form-control"></div>
            </div>
            <div class="row g-2 mt-2">
                <div class="col-md-6"><label class="form-label">Complemento</label><input type="text" id="complemento" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">Bairro</label><input type="text" id="bairro" class="form-control"></div>
            </div>
            <div class="row g-2 mt-2">
                <div class="col-md-6"><label class="form-label">Cidade</label><input type="text" id="cidade" class="form-control"></div>
                <div class="col-md-3"><label class="form-label">UF</label><input type="text" id="uf" class="form-control"></div>
                <div class="col-md-3"><label class="form-label">CEP</label><input type="text" id="cep" class="form-control"></div>
            </div>
        </div>

        {{-- BOLETO --}}
        <div class="mt-4">
            <label class="form-label">Nosso Número</label>
            <input type="text" id="nosso_numero" class="form-control">
        </div>
        <div class="mt-3">
            <label class="form-label">Valor</label>
            <input type="number" id="valor" class="form-control" step="0.01">
        </div>
        <div class="mt-3">
            <label class="form-label">Data de Vencimento</label>
            <input type="date" id="data_vencimento" class="form-control">
        </div>

        <button id="btn-criar" class="btn btn-credvance w-100 mt-4">💳 Gerar Boleto</button>

        <div id="boleto-gerado" class="boleto-gerado-card mt-5 d-none"></div>
    </div>
</div>

<script>
    const csrf = '{{ csrf_token() }}';

    document.getElementById('cliente_id').addEventListener('change', async function () {
        const id = this.value;
        if (!id) return;

        const res = await fetch(`/api/clientes/${id}`);
        const c = await res.json();

        const d = document;
        d.getElementById('nome').value = c.nome;
        d.getElementById('cpfCnpj').value = c.cpfCnpj;
        d.getElementById('email').value = c.email ?? '';
        d.getElementById('logradouro').value = c.logradouro;
        d.getElementById('numero').value = c.numero;
        d.getElementById('complemento').value = c.complemento ?? '';
        d.getElementById('bairro').value = c.bairro;
        d.getElementById('cidade').value = c.cidade;
        d.getElementById('uf').value = c.uf;
        d.getElementById('cep').value = c.cep;

        const tel = c.telefone.replace(/\D/g, '');
        d.getElementById('ddd').value = tel.substring(0, 2);
        d.getElementById('telefone').value = tel.substring(2);
        d.getElementById('tipoPessoa').value = c.cpfCnpj.replace(/\D/g, '').length === 11 ? 'FISICA' : 'JURIDICA';

        d.getElementById('dados-cliente-editaveis').classList.remove('d-none');
    });

    document.getElementById('btn-criar').addEventListener('click', async () => {
        const get = id => document.getElementById(id)?.value?.trim() || '';

        const payload = {
            nosso_numero: get('nosso_numero'),
            valor: parseFloat(get('valor')),
            data_vencimento: get('data_vencimento'),
            num_dias_agenda: 30,
            sacado: {
                nome: get('nome'),
                tipoPessoa: get('tipoPessoa'),
                cpfCnpj: get('cpfCnpj'),
                ddd: get('ddd'),
                telefone: get('telefone'),
                email: get('email'),
                logradouro: get('logradouro'),
                numero: get('numero'),
                complemento: get('complemento'),
                bairro: get('bairro'),
                cidade: get('cidade'),
                uf: get('uf'),
                cep: get('cep'),
            }
        };

        const res = await fetch(`{{ route('boletos.salvar') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
            },
            body: JSON.stringify(payload)
        });

        const json = await res.json();
        if (json.error) return alert(json.error);

        const b = json.boleto?.cobranca;
        const pix = json.boleto?.pix;

        document.getElementById('boleto-gerado').innerHTML = `
            <h5 class="text-success fw-bold mb-3">✅ Boleto Criado com Sucesso</h5>
            <div class="mb-3">
                <strong class="text-primary">🆔 Código de Solicitação:</strong><br>
                <code>${b?.codigoSolicitacao || '—'}</code>
            </div>
            <ul class="list-unstyled">
                <li><strong>Nosso Número:</strong> <code>${b?.seuNumero}</code></li>
                <li><strong>Valor:</strong> R$ ${parseFloat(b?.valorNominal || 0).toFixed(2).replace('.', ',')}</li>
                <li><strong>Vencimento:</strong> ${b?.dataVencimento}</li>
                <li><strong>Status:</strong> ${b?.situacao}</li>
                <li><strong>Tipo Cobrança:</strong> ${b?.tipoCobranca}</li>
                <li><strong>Emissão:</strong> ${b?.dataEmissao}</li>
                <li><strong>TXID PIX:</strong> ${pix?.txid || '—'}</li>
            </ul>
        `;
        document.getElementById('boleto-gerado').classList.remove('d-none');
    });
</script>
</x-app-layout>
