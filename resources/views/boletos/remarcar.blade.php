<x-app-layout>
    <div class="container py-6 max-w-3xl mx-auto">
        <h2 class="text-2xl font-bold mb-6">🔁 Remarcar Boleto</h2>

        {{-- Formulário de Busca --}}
        <div class="card p-4 shadow-sm mb-6">
            <div class="mb-3">
                <label for="codigo_solicitacao" class="form-label">Código de Solicitação</label>
                <input type="text" id="codigo_solicitacao" class="form-control" placeholder="Ex: UUID do boleto" required>
            </div>
            <button id="btn-buscar" class="btn btn-primary">🔍 Buscar Boleto</button>
        </div>

        {{-- Exibir Dados do Boleto --}}
        <div id="boleto-info" class="card p-4 shadow-sm mb-4 d-none"></div>

        {{-- Novo Boleto Gerado --}}
        <div id="novo-boleto" class="card p-4 shadow-sm bg-light mt-5 d-none"></div>
    </div>

    <script>
        let valorNominal = null; // <- armazenar o valor do boleto

        document.getElementById('btn-buscar').addEventListener('click', async () => {
            const codigo = document.getElementById('codigo_solicitacao').value;
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
            if (data.error) {
                return alert(data.error);
            }

            const c = data.cobranca;
            const b = data.boleto;

            valorNominal = c.valorNominal; // <- aqui armazena o valor

            document.getElementById('boleto-info').innerHTML = `
                <h5 class="mb-3">📄 Informações do Boleto</h5>
                <ul class="list-unstyled">
                    <li><strong>Nosso Número:</strong> ${b.nossoNumero ?? '-'}</li>
                    <li><strong>Seu Número:</strong> ${c.seuNumero ?? '-'}</li>
                    <li><strong>Valor:</strong> R$ ${parseFloat(c.valorNominal).toFixed(2).replace('.', ',')}</li>
                    <li><strong>Vencimento:</strong> ${c.dataVencimento}</li>
                    <li><strong>Status:</strong> ${c.situacao}</li>
                    <li><strong>Pagador:</strong> ${c.pagador?.nome}</li>
                </ul>
                <div class="mt-4">
                    <label for="nova_data" class="form-label">Nova Data de Vencimento</label>
                    <input type="date" id="nova_data" class="form-control mb-2">
                    <button class="btn btn-success" onclick="remarcarBoleto('${c.codigoSolicitacao}')">✅ Remarcar Boleto</button>
                </div>
            `;
            document.getElementById('boleto-info').classList.remove('d-none');
        });

        async function remarcarBoleto(codigo) {
            const novaData = document.getElementById('nova_data').value;
            const csrfToken = '{{ csrf_token() }}';

            if (!novaData) return alert('Informe a nova data de vencimento');

            const res = await fetch('{{ route('boletos.remarcar') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    codigo_solicitacao: codigo,
                    nova_data: novaData,
                    valor_nominal: valorNominal // <- enviado aqui
                })
            });

            const data = await res.json();
            if (data.error) return alert(data.error);

            const b = data.novoBoleto;
            document.getElementById('novo-boleto').innerHTML = `
                <h5 class="mb-3">✅ Novo Boleto Gerado</h5>
                <ul class="list-unstyled">
                    <li><strong>Código:</strong> ${b.codigoSolicitacao}</li>
                    <li><strong>Vencimento:</strong> ${b.dataVencimento}</li>
                    <li><strong>Valor:</strong> R$ ${parseFloat(b.valorNominal).toFixed(2).replace('.', ',')}</li>
                    ${b.urlVisualizacao ? `<li><strong>PDF:</strong> <a href="${b.urlVisualizacao}" target="_blank">Visualizar</a></li>` : ''}
                    ${b.linhaDigitavel ? `<li><strong>Linha Digitável:</strong> ${b.linhaDigitavel}</li>` : ''}
                    ${b.codigoBarras ? `<li><strong>Código de Barras:</strong> ${b.codigoBarras}</li>` : ''}
                    ${b.qrCode ? `<li class="mt-3"><strong>QR Code:</strong><br><img src="data:image/png;base64,${b.qrCode}" style="max-width: 200px;"></li>` : ''}
                </ul>
            `;
            document.getElementById('novo-boleto').classList.remove('d-none');
        }
    </script>
</x-app-layout>
