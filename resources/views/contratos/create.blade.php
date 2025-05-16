<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight">
      {{ __('Novo Contrato de Cotas') }}
    </h2>
  </x-slot>

  <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
      <form id="formContrato" action="{{ route('contratos.store') }}" method="POST">
        @csrf
        <input type="hidden" name="navegador_info" id="navegador_info">
        <input type="hidden" name="resolucao_tela" id="resolucao_tela">
        <input type="hidden" name="data_aceite" id="data_aceite">
        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">
        <input type="hidden" name="senha_confirm" id="senha_confirm_hidden">

        <div class="mb-4">
          <label for="cliente_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cliente</label>
          @if(Auth::user()->role === 'admin')
            <select name="cliente_id" id="cliente_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
              @foreach($clientes as $c)
                <option value="{{ $c->id }}" data-cpf="{{ $c->cpf ?? '000.000.000-00' }}" data-telefone="{{ $c->telefone ?? '(00) 00000-0000' }}" data-email="{{ $c->email }}" data-nome="{{ $c->name }}">
                  {{ $c->name }} ({{ $c->email }})
                </option>
              @endforeach
            </select>
          @else
            <input type="hidden" name="cliente_id" value="{{ Auth::user()->id }}">
            <input type="text" disabled readonly id="cliente_dados" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100" value="{{ Auth::user()->name }} ({{ Auth::user()->email }})" data-cpf="{{ Auth::user()->cpf ?? '000.000.000-00' }}" data-telefone="{{ Auth::user()->telefone ?? '(00) 00000-0000' }}" data-email="{{ Auth::user()->email }}" data-nome="{{ Auth::user()->name }}">
          @endif
        </div>

        <div class="mb-4">
          <label for="consorcio_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Plano de Cota</label>
          <select name="consorcio_id" id="consorcio_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            @foreach($consorcios as $cons)
              <option value="{{ $cons->id }}"
                      data-prazo="{{ $cons->prazo }}"
                      data-valor-total="{{ $cons->valor_total }}"
                      data-parcela-mensal="{{ $cons->parcela_mensal }}"
                      data-juros="{{ $cons->juros }}"
                      data-valor-final="{{ $cons->valor_final }}">
                {{ $cons->plano }} — {{ $cons->prazo }} meses
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-4">
          <label for="quantidade_cotas" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantidade de Cotas</label>
          <input type="number" name="quantidade_cotas" id="quantidade_cotas" class="mt-1 block w-1/3 rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" value="{{ old('quantidade_cotas', 1) }}" min="1" required>
        </div>

        <div class="mb-4">
          <label for="dia_vencimento" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dia de vencimento das parcelas</label>
          <input type="number" name="dia_vencimento" id="dia_vencimento" class="mt-1 block w-1/3 rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" value="{{ old('dia_vencimento', now()->day) }}" min="1" max="31" required>
          <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">A primeira parcela será na data de criação do contrato; as demais, no dia escolhido de cada mês.</p>
        </div>

        <div class="text-right">
          <button type="button" onclick="abrirModalContrato()" class="inline-flex items-center px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-white text-sm font-medium rounded-md shadow">
            Criar Contrato
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal de Confirmação -->
  <div id="modalContrato" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4 hidden">
    <div class="w-full max-w-3xl bg-white dark:bg-gray-800 rounded-xl shadow-xl overflow-hidden" style="max-height: 80vh;">
      <div class="flex justify-between items-center border-b px-4 py-2 dark:border-gray-700 sticky top-0 bg-white dark:bg-gray-800 z-10">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Confirmação do Contrato</h3>
        <button onclick="fecharModalContrato()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
        </button>
      </div>
      <div class="overflow-auto p-4" style="max-height: calc(80vh - 130px);">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div id="conteudoContrato" class="text-sm text-gray-700 dark:text-gray-200"></div>
          <div class="flex flex-col">
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
              <h4 class="font-medium text-gray-800 dark:text-white mb-3">Confirmação de Identidade</h4>
              <div class="space-y-3">
                <div>
                  <label for="cpf_confirm" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirme seu CPF</label>
                  <input type="text" id="cpf_confirm" class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                </div>
                <div>
                  <label for="senha_confirm_input" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirme sua senha</label>
                  <input type="password" id="senha_confirm_input" class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="border-t p-4 flex justify-end gap-3 sticky bottom-0 bg-white dark:bg-gray-800 dark:border-gray-700">
        <button type="button" onclick="fecharModalContrato()" class="px-4 py-2 rounded bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-600">Cancelar</button>
        <button type="button" onclick="confirmarContrato()" class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700 font-semibold">Aceitar e Enviar</button>
      </div>
    </div>
  </div>

  <script>
    function abrirModalContrato() {
      const isAdmin = "{{ Auth::user()->role }}" === "admin";
      let nome, email, cpf, telefone;
      if (isAdmin) {
        const cliente = document.querySelector('#cliente_id option:checked');
        nome = cliente.dataset.nome;
        email = cliente.dataset.email;
        cpf = cliente.dataset.cpf;
        telefone = cliente.dataset.telefone;
      } else {
        const dados = document.getElementById('cliente_dados');
        nome = dados.dataset.nome;
        email = dados.dataset.email;
        cpf = dados.dataset.cpf;
        telefone = dados.dataset.telefone;
      }
      const opt = document.querySelector('#consorcio_id option:checked');
      const prazo = parseInt(opt.dataset.prazo, 10);
      const vTotal = parseFloat(opt.dataset.valorTotal);
      const vInicio = parseFloat(opt.dataset.parcelaMensal);
      const juros = parseFloat(opt.dataset.juros);
      const vFinal = parseFloat(opt.dataset.valorFinal);
      const qtd = parseInt(document.getElementById('quantidade_cotas').value, 10);
      const diaVenc = parseInt(document.getElementById('dia_vencimento').value, 10);

      let vals = [];
      if (prazo === 12) {
        let v = vInicio;
        for (let i = 0; i < 12; i++) {
          vals.push(Math.max(v, 100));
          v = Math.max(v - 5, 100);
        }
      } else if (prazo === 24) {
        let v = vInicio;
        for (let i = 0; i < 24; i++) {
          vals.push(Math.max(v, 100));
          if ((i + 1) % 2 === 0) v = Math.max(v - 5, 100);
        }
      } else {
        const base = +(vTotal / prazo).toFixed(2);
        for (let i = 0; i < prazo; i++) vals.push(base);
      }

      const today = new Date();
      const diaHoje = String(today.getDate()).padStart(2,'0');
      const mesHoje = String(today.getMonth()+1).padStart(2,'0');
      const anoHoje = today.getFullYear();
      const venc1 = `${diaHoje}/${mesHoje}/${anoHoje}`;

      const totalPago = vals.reduce((a, b) => a + b, 0) * qtd;
      const retorno = vFinal * qtd;
      const primeiraPar = (vals[0] * qtd).toFixed(2);
      const ultimaPar = (vals[vals.length - 1] * qtd).toFixed(2);

      const contratoHTML = `
        <div>
          <h4 class="font-bold text-base text-gray-900 dark:text-white mb-2">CONTRATO DE PARTICIPAÇÃO</h4>
          <div class="mb-3">
            <p class="text-xs"><span class="font-medium">Nome:</span> ${nome}</p>
            <p class="text-xs"><span class="font-medium">CPF:</span> ${cpf}</p>
            <p class="text-xs"><span class="font-medium">E-mail:</span> ${email}</p>
            <p class="text-xs mb-2"><span class="font-medium">Plano:</span> ${opt.textContent.trim()}</span></p>
            <p class="text-xs"><span class="font-medium">Cotas:</span> ${qtd}</p>
          </div>
          <div class="overflow-auto max-h-24 mb-3 text-xs">
            <ul class="list-disc pl-4">
              <li>Em caso de desistência, receberá de volta o valor já pago, <strong>menos taxa administrativa de 23%</strong>.</li>
              <li>Participante declara estar ciente das regras de contemplação e penalidades por atraso.</li>
              <li>Dados devem ser mantidos atualizados pelo participante.</li>
              <li>Não pagamento de parcela poderá acarretar cobrança judicial.</li>
              <li>Foro eleito: comarca de Brasília/DF.</li>
            </ul>
          </div>
          <div class="bg-gray-50 dark:bg-gray-700 p-2 rounded mb-3">
            <p class="text-xs font-medium mb-1">Detalhamento Financeiro:</p>
            <div class="grid grid-cols-2 gap-1 text-xs">
              <p><span class="font-medium">1ª parcela:</span></p>
              <p class="text-right">R$ ${primeiraPar}</p>
              <p><span class="font-medium">Última parcela:</span></p>
              <p class="text-right">R$ ${ultimaPar}</p>
              <p><span class="font-medium">Total a pagar:</span></p>
              <p class="text-right">R$ ${totalPago.toFixed(2)}</p>
              <p><span class="font-medium">Valor final (${juros}%):</span></p>
              <p class="text-right">R$ ${retorno.toFixed(2)}</p>
              <p><span class="font-medium">Vencimento 1ª parcela:</span></p>
              <p class="text-right">${venc1}</p>
            </div>
          </div>
          <p class="text-xs font-medium text-center">Declaro que li, compreendi e estou de acordo com todas as condições acima descritas.</p>
        </div>`;

      document.getElementById('conteudoContrato').innerHTML = contratoHTML;
      document.getElementById('modalContrato').classList.remove('hidden');
    }

    function fecharModalContrato() {
      document.getElementById('modalContrato').classList.add('hidden');
    }

    function exibirMiniModalErro(msg, focoId = null) {
      document.getElementById('miniModalErroMsg').innerText = msg;
      document.getElementById('miniModalErro').classList.remove('hidden');
      if (focoId) setTimeout(() => document.getElementById(focoId)?.focus(), 100);
    }

    function fecharMiniModalErro() {
      document.getElementById('miniModalErro').classList.add('hidden');
    }

    async function confirmarContrato() {
      const cpfDigitado = document.getElementById('cpf_confirm').value.trim();
      const senha = document.getElementById('senha_confirm_input').value.trim();
      const cpfReal = "{{ Auth::user()->cpf }}";

      if (cpfDigitado !== cpfReal) return exibirMiniModalErro("CPF incorreto.", 'cpf_confirm');
      if (!senha || senha.length < 4) return exibirMiniModalErro("Senha inválida.", 'senha_confirm_input');

      try {
        const res = await fetch("/verifica-senha", {
          method: "POST",
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
          body: JSON.stringify({ senha })
        });

        if (!res.ok) return exibirMiniModalErro("Senha incorreta.", 'senha_confirm_input');
      } catch (e) {
        return exibirMiniModalErro("Erro na validação da senha.");
      }

      document.getElementById('navegador_info').value = navigator.userAgent;
      document.getElementById('resolucao_tela').value = `${screen.width}x${screen.height}`;
      document.getElementById('data_aceite').value = new Date().toISOString();
      document.getElementById('senha_confirm_hidden').value = senha;

      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(pos => {
          document.getElementById('latitude').value = pos.coords.latitude;
          document.getElementById('longitude').value = pos.coords.longitude;
          fecharModalContrato();
          document.getElementById('formContrato').submit();
        }, () => {
          exibirMiniModalErro("É necessário autorizar a localização.");
        }, { enableHighAccuracy: true, timeout: 7000 });
      } else {
        exibirMiniModalErro("Navegador não suporta geolocalização.");
      }
    }
  </script>
</x-app-layout>