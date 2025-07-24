<x-app-layout>
    <div class="container py-6 max-w-3xl mx-auto">
        <h2 class="text-2xl font-bold mb-6">📄 Criar Novo Boleto</h2>

        <div class="card p-4 shadow-sm">
            {{-- CLIENTE --}}
            <div class="mb-3">
                <label class="form-label">Selecionar Cliente</label>
                <select id="cliente_id" class="form-select" required>
                    <option value="">Selecione...</option>
                    @foreach ($clientes as $cliente)
                        <option value="{{ $cliente->id }}">{{ $cliente->name }} ({{ $cliente->cpf }})</option>
                    @endforeach
                </select>
            </div>

            {{-- CAMPOS AUTO-PREENCHIDOS DO CLIENTE --}}
            <div id="dados-cliente-editaveis" class="mb-4 d-none">
                <h6>📌 Dados do Pagador</h6>
                <div class="row g-2">
                    <div class="col-md-8">
                        <label class="form-label">Nome</label>
                        <input type="text" id="nome" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tipo Pessoa</label>
                        <input type="text" id="tipoPessoa" class="form-control" readonly>
                    </div>
                </div>

                <div class="row g-2 mt-2">
                    <div class="col-md-6">
                        <label class="form-label">CPF/CNPJ</label>
                        <input type="text" id="cpfCnpj" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">DDD</label>
                        <input type="text" id="ddd" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Telefone</label>
                        <input type="text" id="telefone" class="form-control">
                    </div>
                </div>

                <div class="mt-3">
                    <label class="form-label">Email</label>
                    <input type="email" id="email" class="form-control">
                </div>

                <div class="row g-2 mt-2">
                    <div class="col-md-8">
                        <label class="form-label">Logradouro</label>
                        <input type="text" id="logradouro" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Número</label>
                        <input type="text" id="numero" class="form-control">
                    </div>
                </div>

                <div class="row g-2 mt-2">
                    <div class="col-md-6">
                        <label class="form-label">Complemento</label>
                        <input type="text" id="complemento" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Bairro</label>
                        <input type="text" id="bairro" class="form-control">
                    </div>
                </div>

                <div class="row g-2 mt-2">
                    <div class="col-md-6">
                        <label class="form-label">Cidade</label>
                        <input type="text" id="cidade" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">UF</label>
                        <input type="text" id="uf" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">CEP</label>
                        <input type="text" id="cep" class="form-control">
                    </div>
                </div>
            </div>

            {{-- DADOS DO BOLETO --}}
            <div class="mb-3 mt-4">
                <label class="form-label">Nosso Número</label>
                <input type="text" id="nosso_numero" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Valor</label>
                <input type="number" id="valor" class="form-control" step="0.01" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Data de Vencimento</label>
                <input type="date" id="data_vencimento" class="form-control" required>
            </div>

            <button id="btn-criar" class="btn btn-success w-100 mt-3">💳 Gerar Boleto</button>
        </div>

        <div id="boleto-gerado" class="card p-4 shadow-sm bg-light mt-5 d-none"></div>
    </div>

    <script>
        const csrf = '{{ csrf_token() }}';

        document.getElementById('cliente_id').addEventListener('change', async function () {
            const id = this.value;
            if (!id) return;

            const res = await fetch(`/api/clientes/${id}`);
            const c = await res.json();

            document.getElementById('nome').value = c.nome;
            document.getElementById('cpfCnpj').value = c.cpfCnpj;
            document.getElementById('email').value = c.email ?? '';
            document.getElementById('logradouro').value = c.logradouro;
            document.getElementById('numero').value = c.numero;
            document.getElementById('complemento').value = c.complemento ?? '';
            document.getElementById('bairro').value = c.bairro;
            document.getElementById('cidade').value = c.cidade;
            document.getElementById('uf').value = c.uf;
            document.getElementById('cep').value = c.cep;

            const telefoneLimpo = c.telefone.replace(/\D/g, '');
            document.getElementById('ddd').value = telefoneLimpo.substring(0, 2);
            document.getElementById('telefone').value = telefoneLimpo.substring(2);
            document.getElementById('tipoPessoa').value = c.cpfCnpj.replace(/\D/g, '').length === 11 ? 'FISICA' : 'JURIDICA';

            document.getElementById('dados-cliente-editaveis').classList.remove('d-none');
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

            console.log('[Payload Enviado]', payload);

            const res = await fetch(`{{ route('boletos.salvar') }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
                body: JSON.stringify(payload)
            });

            const json = await res.json();
            console.log('[Resposta da API]', json);

            if (json.error) return alert(json.error);

           const b = json.boleto?.cobranca;
            const pix = json.boleto?.pix;

            const html = `
                <h5 class="mb-3">✅ Boleto Criado</h5>
                <ul class="list-unstyled">
                    <li><strong>Nosso Número:</strong> ${b?.seuNumero}</li>
                    <li><strong>Valor:</strong> R$ ${parseFloat(b?.valorNominal || 0).toFixed(2).replace('.', ',')}</li>
                    <li><strong>Vencimento:</strong> ${b?.dataVencimento}</li>
                    <li><strong>Situação:</strong> ${b?.situacao}</li>
                    <li><strong>Tipo Cobrança:</strong> ${b?.tipoCobranca}</li>
                    <li><strong>Emissão:</strong> ${b?.dataEmissao}</li>
                    <li><strong>TXID PIX:</strong> ${pix?.txid || '—'}</li>
                </ul>
            `;
            document.getElementById('boleto-gerado').innerHTML = html;
            document.getElementById('boleto-gerado').classList.remove('d-none');
        });
    </script>
</x-app-layout>
