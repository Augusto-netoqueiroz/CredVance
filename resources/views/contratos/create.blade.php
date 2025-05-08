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

        {{-- Cliente --}}
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

        {{-- Plano de Cota --}}
        <div class="mb-4">
          <label for="consorcio_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Plano de Cota</label>
          <select name="consorcio_id" id="consorcio_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            @foreach($consorcios as $cons)
              <option value="{{ $cons->id }}">{{ $cons->plano }} — {{ $cons->prazo }} meses</option>
            @endforeach
          </select>
        </div>

        {{-- Quantidade de Cotas --}}
        <div class="mb-4">
          <label for="quantidade_cotas" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantidade de Cotas</label>
          <input type="number" name="quantidade_cotas" id="quantidade_cotas" class="mt-1 block w-1/3 rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" value="{{ old('quantidade_cotas', 1) }}" min="1" required>
        </div>

        {{-- Botão --}}
        <div class="text-right">
          <button type="button" onclick="abrirModalContrato()" class="inline-flex items-center px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-white text-sm font-medium rounded-md shadow">
            Criar Contrato
          </button>
        </div>
      </form>
    </div>
  </div>

  {{-- Modal de Confirmação --}}
  <div id="modalContrato" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg w-full max-w-3xl mx-4 sm:mx-6 md:mx-auto p-6 relative max-h-[90vh] overflow-y-auto">
      <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Confirmação do Contrato</h3>
      <div id="conteudoContrato" class="text-sm text-gray-700 dark:text-gray-200 space-y-4"></div>

      <div class="mt-6 space-y-4">
        <label for="cpf_confirm" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirme seu CPF</label>
        <input type="text" id="cpf_confirm" class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">

        <label for="senha_confirm_input" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirme sua senha</label>
        <input type="password" id="senha_confirm_input" class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
      </div>

      <div class="flex justify-end mt-6 gap-3 border-t pt-4 dark:border-gray-700">
        <button type="button" onclick="fecharModalContrato()" class="px-4 py-2 rounded bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-600">
          Cancelar
        </button>
        <button type="button" onclick="confirmarContrato()" class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700 font-semibold">
          Aceitar e Enviar
        </button>
      </div>

      <button onclick="fecharModalContrato()" class="absolute top-2 right-3 text-gray-500 dark:text-gray-300 hover:text-red-600 text-lg font-bold">
        ×
      </button>
    </div>
  </div>

  {{-- Mini Modal de Erro --}}
  <div id="miniModalErro" class="fixed inset-0 z-50 bg-black bg-opacity-30 flex items-center justify-center hidden">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-80 text-center border border-red-300 dark:border-red-500">
      <p id="miniModalErroMsg" class="text-sm text-red-700 dark:text-red-300 font-medium mb-4">Erro</p>
      <button onclick="fecharMiniModalErro()" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700 text-sm">
        OK
      </button>
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

      const plano = document.querySelector('#consorcio_id option:checked')?.textContent || '';
      const qtdCotas = document.querySelector('#quantidade_cotas').value;

      const contrato = `
        <p><strong>CONTRATO DE PARTICIPAÇÃO EM GRUPO DE CONSÓRCIO</strong></p>
        <p>Cliente: <strong>${nome}</strong><br>
        CPF: <strong>${cpf}</strong><br>
        Telefone: <strong>${telefone}</strong><br>
        E-mail: <strong>${email}</strong></p>

        <p>Pelo presente instrumento, o cliente declara ter interesse em participar de grupo de consórcio administrado por nossa empresa, com o objetivo de aquisição futura de bens ou serviços, por meio de autofinanciamento coletivo.</p>

        <p><strong>Plano escolhido:</strong> ${plano}<br>
        <strong>Quantidade de cotas:</strong> ${qtdCotas}</p>

        <p>O cliente se compromete a:</p>
        <ul class="list-disc pl-6">
          <li>Efetuar os pagamentos das parcelas conforme estipulado no plano;</li>
          <li>Respeitar os critérios de contemplação (por sorteio ou lance);</li>
          <li>Manter seus dados atualizados e válidos durante todo o período do grupo;</li>
          <li>Reconhecer que a contemplação depende da regularidade dos pagamentos e da posição no grupo;</li>
          <li>Estar ciente de que inadimplência pode acarretar em exclusão, multas ou perda de direitos.</li>
        </ul>

        <p>Este contrato é celebrado digitalmente e tem validade legal a partir da aceitação eletrônica abaixo.</p>
        <p class="mt-4 font-semibold">Declaro que li, compreendi e estou de acordo com todas as condições acima descritas.</p>
      `;

      document.getElementById('conteudoContrato').innerHTML = contrato;
      document.getElementById('modalContrato').classList.remove('hidden');
    }

    function fecharModalContrato() {
      document.getElementById('modalContrato').classList.add('hidden');
    }

    function exibirMiniModalErro(mensagem, focoId = null) {
      document.getElementById('miniModalErroMsg').innerText = mensagem;
      document.getElementById('miniModalErro').classList.remove('hidden');
      if (focoId) {
        setTimeout(() => {
          document.getElementById(focoId)?.focus();
        }, 100);
      }
    }

    function fecharMiniModalErro() {
      document.getElementById('miniModalErro').classList.add('hidden');
    }

    async function confirmarContrato() {
      const cpfDigitado = document.getElementById('cpf_confirm').value.trim();
      const senha = document.getElementById('senha_confirm_input').value.trim();
      const cpfReal = "{{ Auth::user()->cpf }}";

      if (cpfDigitado !== cpfReal) {
        exibirMiniModalErro("CPF incorreto. Verifique e tente novamente.", 'cpf_confirm');
        return;
      }

      if (!senha || senha.length < 4) {
        exibirMiniModalErro("Senha inválida. Ela deve ter pelo menos 4 caracteres.", 'senha_confirm_input');
        return;
      }

      try {
        const response = await fetch("/verifica-senha", {
          method: "POST",
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
          },
          body: JSON.stringify({ senha })
        });

        if (!response.ok) {
          exibirMiniModalErro("Senha incorreta. Tente novamente.", 'senha_confirm_input');
          return;
        }
      } catch (err) {
        exibirMiniModalErro("Erro na validação da senha. Tente novamente.");
        return;
      }

      document.getElementById('navegador_info').value = navigator.userAgent;
      document.getElementById('resolucao_tela').value = `${screen.width}x${screen.height}`;
      document.getElementById('data_aceite').value = new Date().toISOString();
      document.getElementById('senha_confirm_hidden').value = senha;

      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          (position) => {
            document.getElementById('latitude').value = position.coords.latitude;
            document.getElementById('longitude').value = position.coords.longitude;

            fecharModalContrato();
            document.getElementById('formContrato').submit();
          },
          (error) => {
            exibirMiniModalErro("É necessário autorizar a localização para finalizar o contrato.");
          },
          { enableHighAccuracy: true, timeout: 7000 }
        );
      } else {
        exibirMiniModalErro("Seu navegador não suporta geolocalização.");
      }
    }
  </script>
</x-app-layout>