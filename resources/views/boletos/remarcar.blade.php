<x-app-layout>
    <head>
        <title>Remarcar Boleto - CredVance</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Bootstrap + Google Fonts -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

        <style>
            :root {
                --primary: #1e88e5;
                --secondary: #0d47a1;
                --gradient: linear-gradient(135deg, #1e88e5, #0d47a1);
                --text-primary: #212121;
            }

            body {
                background: var(--gradient);
                font-family: 'Inter', sans-serif;
            }

            .remarcar-container {
                display: flex;
                justify-content: center;
                align-items: flex-start;
                min-height: 100vh;
                padding: 2rem 1rem;
            }

            .remarcar-card {
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(15px);
                border-radius: 16px;
                box-shadow: 0 15px 40px rgba(13, 71, 161, 0.2);
                padding: 2rem;
                max-width: 800px;
                width: 100%;
                border: 1px solid rgba(255, 255, 255, 0.3);
            }

            .section-title {
                font-weight: 700;
                font-size: 1.5rem;
                color: var(--primary);
                margin-bottom: 1.5rem;
            }

            .form-label {
                font-weight: 600;
                color: var(--text-primary);
            }

            .btn-credvance {
                background: var(--gradient);
                color: #fff;
                font-weight: 600;
                border: none;
                border-radius: 10px;
                padding: 12px 24px;
                transition: all 0.3s ease;
            }

            .btn-credvance:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(30, 136, 229, 0.4);
            }

            .info-block {
                background-color: #f5f9ff;
                border-left: 4px solid var(--primary);
                border-radius: 10px;
                padding: 1rem 1.25rem;
                margin-bottom: 1rem;
            }

            .info-block code {
                font-weight: 600;
                color: var(--secondary);
                word-break: break-word;
            }

            table {
                font-size: 0.9rem;
            }

            .table-responsive {
                margin-top: 2rem;
            }
        </style>
    </head>

    <div class="remarcar-container">
        <div class="remarcar-card">
            <h2 class="section-title">🔁 Remarcar Boleto</h2>

            {{-- Busca --}}
            <div class="mb-4">
                <label for="codigo_solicitacao" class="form-label">Código de Solicitação</label>
                <input type="text" id="codigo_solicitacao" class="form-control mb-2" placeholder="Ex: UUID do boleto" required>
                <button id="btn-buscar" class="btn btn-credvance w-100">🔍 Buscar Boleto</button>
            </div>

            {{-- Info do Boleto --}}
            <div id="boleto-info" class="d-none"></div>

            {{-- Novo Boleto --}}
            <div id="novo-boleto" class="d-none mt-4"></div>

            {{-- Tabela de boletos recentes --}}
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Nosso Número</th>
                            <th>Vencimento</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th>Código</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pagamentos as $b)
                            <tr>
                                <td>{{ $b->id }}</td>
                                <td>{{ $b->contrato->cliente->name ?? '-' }}</td>
                                <td>{{ $b->nosso_numero }}</td>
                                <td>{{ $b->vencimento }}</td>
                                <td>R$ {{ number_format($b->valor, 2, ',', '.') }}</td>
                                <td>{{ $b->status }}</td>
                                <td><code style="font-size: 0.75rem">{{ $b->codigo_solicitacao }}</code></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" onclick="buscarPorCodigo('{{ $b->codigo_solicitacao }}')">Remarcar</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        let valorNominal = null;

        function buscarPorCodigo(codigo) {
            document.getElementById('codigo_solicitacao').value = codigo;
            document.getElementById('btn-buscar').click();
        }

        document.getElementById('btn-buscar').addEventListener('click', async () => {
            const codigo = document.getElementById('codigo_solicitacao').value.trim();
            if (!codigo) return alert('Informe o código de solicitação');

            const csrfToken = '{{ csrf_token() }}';

            const res = await fetch('{{ route('boletos.buscar') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ codigo_solicitacao: codigo })
            });

            const data = await res.json();
            if (data.error) return alert(data.error);

            const c = data.cobranca;
            const b = data.boleto;

            valorNominal = c.valorNominal;

            document.getElementById('boleto-info').classList.remove('d-none');
            document.getElementById('boleto-info').innerHTML = `
                <div class="info-block">
                    <div><strong>📌 Código de Solicitação:</strong><br><code>${c.codigoSolicitacao}</code></div>
                </div>
                <ul class="mb-3">
                    <li><strong>Nosso Número:</strong> ${b.nossoNumero ?? '-'}</li>
                    <li><strong>Seu Número:</strong> ${c.seuNumero ?? '-'}</li>
                    <li><strong>Valor:</strong> R$ ${parseFloat(c.valorNominal).toFixed(2).replace('.', ',')}</li>
                    <li><strong>Vencimento:</strong> ${c.dataVencimento}</li>
                    <li><strong>Status:</strong> ${c.situacao}</li>
                    <li><strong>Pagador:</strong> ${c.pagador?.nome}</li>
                </ul>
                <div>
                    <label for="nova_data" class="form-label">Nova Data de Vencimento</label>
                    <input type="date" id="nova_data" class="form-control mb-3">
                    <button class="btn btn-success w-100" onclick="remarcarBoleto('${c.codigoSolicitacao}')">✅ Remarcar Boleto</button>
                </div>
            `;
        });

        async function remarcarBoleto(codigo) {
            const novaData = document.getElementById('nova_data').value;
            if (!novaData) return alert('Informe a nova data');

            const res = await fetch('{{ route('boletos.remarcar') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    codigo_solicitacao: codigo,
                    nova_data: novaData,
                    valor_nominal: valorNominal
                })
            });

            const data = await res.json();
            if (data.error) return alert(data.error);

            const b = data.novoBoleto;
            document.getElementById('novo-boleto').classList.remove('d-none');
            document.getElementById('novo-boleto').innerHTML = `
                <h5 class="text-success fw-bold">✅ Novo Boleto Gerado</h5>
                <ul class="mb-2">
                    <li><strong>Código:</strong> <code>${b.codigoSolicitacao}</code></li>
                    <li><strong>Vencimento:</strong> ${b.dataVencimento}</li>
                    <li><strong>Valor:</strong> R$ ${parseFloat(b.valorNominal).toFixed(2).replace('.', ',')}</li>
                    ${b.urlVisualizacao ? `<li><strong>PDF:</strong> <a href="${b.urlVisualizacao}" target="_blank">Visualizar</a></li>` : ''}
                    ${b.linhaDigitavel ? `<li><strong>Linha Digitável:</strong> ${b.linhaDigitavel}</li>` : ''}
                    ${b.codigoBarras ? `<li><strong>Código de Barras:</strong> ${b.codigoBarras}</li>` : ''}
                </ul>
                ${b.qrCode ? `<div><strong>QR Code:</strong><br><img src="data:image/png;base64,${b.qrCode}" style="max-width: 200px;" /></div>` : ''}
            `;
        }
    </script>
</x-app-layout>
