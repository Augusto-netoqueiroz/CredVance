<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight">
      {{ __('Novo Contrato de Cotas') }}
    </h2>
  </x-slot>

  <style>
    body.modal-open {
      overflow: hidden !important;
      position: fixed !important;
      width: 100vw;
    }
    .password-wrapper {
      position: relative;
      display: inline-block;
      width: 100%;
    }
   .toggle-senha {
  position: absolute;
  right: 0.75rem;
  top: 69%;
  transform: translateY(-43%);
  background: none;
  border: none;
  cursor: pointer;
  padding: 0;
  color: #555;
  width: 1.5rem;
  height: 1.5rem;
}

    .toggle-senha svg {
      width: 100%;
      height: 100%;
    }
  </style>

  @if($errors->any())
  <div class="bg-red-100 text-red-700 rounded px-3 py-2 mb-3">
    <ul class="list-disc pl-5">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
  @endif

  <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">

      <!-- Stepper Header -->
      <div class="flex justify-center mb-8 space-x-8">
        <div class="step flex flex-col items-center cursor-pointer" data-step="1">
          <div class="w-8 h-8 rounded-full flex items-center justify-center border-2 border-blue-600 text-blue-600 font-semibold">1</div>
          <span class="text-sm mt-1 text-blue-600 font-semibold">Cliente</span>
        </div>
        <div class="step flex flex-col items-center cursor-pointer" data-step="2">
          <div class="w-8 h-8 rounded-full flex items-center justify-center border-2 border-gray-300 text-gray-400 font-semibold">2</div>
          <span class="text-sm mt-1 text-gray-400">Plano</span>
        </div>
        <div class="step flex flex-col items-center cursor-pointer" data-step="3">
          <div class="w-8 h-8 rounded-full flex items-center justify-center border-2 border-gray-300 text-gray-400 font-semibold">3</div>
          <span class="text-sm mt-1 text-gray-400">Confirmação</span>
        </div>
      </div>

      <form id="formContrato" action="{{ route('contratos.store') }}" method="POST" novalidate>
        @csrf
        <input type="hidden" name="navegador_info" id="navegador_info" required>
        <input type="hidden" name="resolucao_tela" id="resolucao_tela" required>
        <input type="hidden" name="data_aceite" id="data_aceite" required>
        <input type="hidden" name="latitude" id="latitude" required>
        <input type="hidden" name="longitude" id="longitude" required>
        <input type="hidden" name="senha_confirm" id="senha_confirm_hidden" required>

        <!-- Step 1 -->
        <section class="step-content" data-step="1">
          <div class="mb-4">
            <label for="cliente_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cliente</label>
            @if(Auth::user()->role === 'admin')
              <select name="cliente_id" id="cliente_id" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                <option value="" disabled selected>Selecione um cliente</option>
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
        </section>

        <!-- Step 2 -->
        <section class="step-content hidden" data-step="2">
          <div class="mb-4">
            <label for="consorcio_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Plano de Cota</label>
            <select name="consorcio_id" id="consorcio_id" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
              <option value="" disabled selected>Selecione um plano</option>
              @foreach($consorcios as $cons)
                <option value="{{ $cons->id }}" data-prazo="{{ $cons->prazo }}" data-valor-total="{{ $cons->valor_total }}" data-parcela-mensal="{{ $cons->parcela_mensal }}" data-juros="{{ $cons->juros }}" data-valor-final="{{ $cons->valor_final }}">
                  {{ $cons->plano }} — 16% de retorno
                </option>
              @endforeach
            </select>
          </div>

          <div class="mb-4">
            <label for="quantidade_cotas" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantidade de Cotas</label>
            <input type="number" name="quantidade_cotas" id="quantidade_cotas" class="mt-1 block w-1/3 rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" value="{{ old('quantidade_cotas', 1) }}" min="1" required>
          </div>

          <div class="mb-4">
            <label for="dia_vencimento" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Melhor dia de vencimento</label>
            <select name="dia_vencimento" id="dia_vencimento" class="mt-1 block w-1/3 rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" required>
              @for ($i = 1; $i <= 28; $i++)
                <option value="{{ $i }}" {{ old('dia_vencimento', 1) == $i ? 'selected' : '' }}>{{ $i }}</option>
              @endfor
            </select>
            <span class="block text-xs text-gray-500 dark:text-gray-400 mt-1">
              O vencimento da <b>primeira parcela</b> será <b>1 dia após a contratação</b>. As próximas seguem o melhor dia escolhido.
            </span>
          </div>
        </section>

        <!-- Step 3 -->
        <section class="step-content hidden" data-step="3">
          <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Confirmação do Contrato</h3>

            <div id="resumoContrato" class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg text-gray-700 dark:text-gray-200 text-sm">
              <!-- resumo preenchido por JS -->
            </div>
          </div>

          <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg relative">
            <h4 class="font-medium text-gray-800 dark:text-white mb-3">Confirmação de Identidade</h4>
            <div class="space-y-4">
              <div>
                <label for="cpf_confirm" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirme seu CPF</label>
                <input type="text" id="cpf_confirm" class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100" placeholder="Digite seu CPF">
              </div>
              <div class="password-wrapper">
                <label for="senha_confirm_input" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirme sua senha</label>
                <input type="password" id="senha_confirm_input" class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 pr-10" placeholder="Digite sua senha">
                <button type="button" class="toggle-senha" aria-label="Mostrar/ocultar senha" tabindex="-1" id="toggleSenha">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </section>

        <div class="flex justify-between mt-6">
          <button type="button" id="btnAnterior" disabled class="px-4 py-2 rounded bg-gray-300 text-gray-700 hover:bg-gray-400 disabled:opacity-50 disabled:cursor-not-allowed">← Anterior</button>
          <button type="button" id="btnProximo" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Próximo →</button>
          <button type="submit" id="btnFinalizar" class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700 hidden">Finalizar</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Mini Modal de Erro -->
  <div id="miniModalErro" class="fixed inset-0 flex items-center justify-center hidden" style="z-index:2000;">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-80 text-center border border-red-300 dark:border-red-500">
      <p id="miniModalErroMsg" class="text-sm text-red-700 dark:text-red-300 font-medium mb-4">Erro</p>
      <button onclick="fecharMiniModalErro()" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700 text-sm">OK</button>
    </div>
  </div>

  <script>
    const steps = document.querySelectorAll('.step-content');
    const btnAnterior = document.getElementById('btnAnterior');
    const btnProximo = document.getElementById('btnProximo');
    const btnFinalizar = document.getElementById('btnFinalizar');
    const stepIndicators = document.querySelectorAll('.step');

    let currentStep = 1;

    function updateStepDisplay() {
      steps.forEach(s => s.classList.add('hidden'));
      document.querySelector(`.step-content[data-step="${currentStep}"]`).classList.remove('hidden');

      stepIndicators.forEach((ind, idx) => {
        if (idx + 1 === currentStep) {
          ind.querySelector('div').classList.replace('border-gray-300', 'border-blue-600');
          ind.querySelector('div').classList.replace('text-gray-400', 'text-blue-600');
          ind.querySelector('span').classList.replace('text-gray-400', 'text-blue-600');
          ind.style.cursor = 'default';
        } else {
          ind.querySelector('div').classList.replace('border-blue-600', 'border-gray-300');
          ind.querySelector('div').classList.replace('text-blue-600', 'text-gray-400');
          ind.querySelector('span').classList.replace('text-blue-600', 'text-gray-400');
          ind.style.cursor = 'pointer';
        }
      });

      btnAnterior.disabled = currentStep === 1;
      btnProximo.classList.toggle('hidden', currentStep === steps.length);
      btnFinalizar.classList.toggle('hidden', currentStep !== steps.length);
    }

    btnProximo.addEventListener('click', () => {
      if (!validarStep(currentStep)) return;
      currentStep++;
      updateStepDisplay();
      if (currentStep === steps.length) preencherResumo();
    });

    btnAnterior.addEventListener('click', () => {
      if (currentStep > 1) {
        currentStep--;
        updateStepDisplay();
      }
    });

    function validarStep(step) {
      if (step === 1) {
        const cliente = document.getElementById('cliente_id');
        if (cliente && cliente.required && !cliente.value) {
          exibirMiniModalErro('Selecione um cliente antes de continuar.', 'cliente_id');
          return false;
        }
      }
      if (step === 2) {
        const plano = document.getElementById('consorcio_id');
        const qtd = document.getElementById('quantidade_cotas');
        const dia = document.getElementById('dia_vencimento');
        if (!plano.value) {
          exibirMiniModalErro('Selecione um plano de cota.', 'consorcio_id');
          return false;
        }
        if (!qtd.value || qtd.value < 1) {
          exibirMiniModalErro('Informe uma quantidade válida de cotas.', 'quantidade_cotas');
          return false;
        }
        if (!dia.value) {
          exibirMiniModalErro('Informe o melhor dia de vencimento.', 'dia_vencimento');
          return false;
        }
      }
      return true;
    }

    // Intercepta submit para validar CPF e senha e preencher hidden inputs + localização
    document.getElementById('formContrato').addEventListener('submit', async function(event) {
      event.preventDefault();

      const cpfDigitado = document.getElementById('cpf_confirm').value.trim();
      const senha = document.getElementById('senha_confirm_input').value.trim();
      const cpfReal = "{{ Auth::user()->cpf }}";

      if (!cpfDigitado) {
        exibirMiniModalErro("Digite seu CPF para confirmar.", 'cpf_confirm');
        return;
      }
      if (limparCPF(cpfDigitado).length !== 11) {
        exibirMiniModalErro("CPF informado está incompleto ou incorreto. Informe os 11 dígitos.", 'cpf_confirm');
        return;
      }
      if (limparCPF(cpfDigitado) !== limparCPF(cpfReal)) {
        exibirMiniModalErro("O CPF digitado não confere com o seu cadastro. Verifique e tente novamente.", 'cpf_confirm');
        return;
      }
      if (!senha) {
        exibirMiniModalErro("Digite sua senha para confirmar.", 'senha_confirm_input');
        return;
      }
      if (senha.length < 4) {
        exibirMiniModalErro("A senha deve ter pelo menos 4 caracteres.", 'senha_confirm_input');
        return;
      }

      try {
        const res = await fetch("/verifica-senha", {
          method: "POST",
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
          },
          body: JSON.stringify({ senha })
        });

        if (!res.ok) {
          exibirMiniModalErro("Senha incorreta. Verifique sua senha de acesso.", 'senha_confirm_input');
          return;
        }
      } catch (e) {
        exibirMiniModalErro("Erro técnico ao validar a senha. Por favor, tente novamente em instantes.");
        return;
      }

      // Preenche hidden inputs
      document.getElementById('navegador_info').value = navigator.userAgent;
      document.getElementById('resolucao_tela').value = `${screen.width}x${screen.height}`;
      const now = new Date();
      document.getElementById('data_aceite').value = now.getFullYear() + '-' + String(now.getMonth()+1).padStart(2,'0') + '-' + String(now.getDate()).padStart(2,'0') + ' ' + String(now.getHours()).padStart(2,'0') + ':' + String(now.getMinutes()).padStart(2,'0') + ':' + String(now.getSeconds()).padStart(2,'0');
      document.getElementById('senha_confirm_hidden').value = senha;

      // Obter geolocalização antes de enviar
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(pos => {
          document.getElementById('latitude').value = pos.coords.latitude;
          document.getElementById('longitude').value = pos.coords.longitude;
          // Enviar formulário após preencher localização
          event.target.submit();
        }, error => {
          let msg = "É necessário autorizar a localização para continuar.";
          if (error.code === 1) msg = "Permissão de localização negada. Autorize para continuar.";
          else if (error.code === 2) msg = "Localização indisponível. Tente novamente.";
          else if (error.code === 3) msg = "Tempo esgotado para capturar localização. Tente novamente.";
          exibirMiniModalErro(msg);
        }, { enableHighAccuracy: true, timeout: 7000 });
      } else {
        exibirMiniModalErro("Seu navegador não suporta a captura de localização.");
      }
    });

    function limparCPF(cpf) {
      return cpf.replace(/[^\d]/g, '');
    }

    function exibirMiniModalErro(msg, focoId = null) {
      document.getElementById('miniModalErroMsg').innerText = msg;
      document.getElementById('miniModalErro').classList.remove('hidden');
      if (focoId) setTimeout(() => document.getElementById(focoId)?.focus(), 100);
    }

    function fecharMiniModalErro() {
      document.getElementById('miniModalErro').classList.add('hidden');
    }

    function preencherResumo() {
      const clienteOpt = document.getElementById('cliente_id');
      const clienteNome = clienteOpt ? clienteOpt.options[clienteOpt.selectedIndex]?.dataset.nome : document.getElementById('cliente_dados')?.dataset.nome;
      const clienteCPF = clienteOpt ? clienteOpt.options[clienteOpt.selectedIndex]?.dataset.cpf : document.getElementById('cliente_dados')?.dataset.cpf;
      const clienteEmail = clienteOpt ? clienteOpt.options[clienteOpt.selectedIndex]?.dataset.email : document.getElementById('cliente_dados')?.dataset.email;

      const planoOpt = document.getElementById('consorcio_id');
      const planoTexto = planoOpt ? planoOpt.options[planoOpt.selectedIndex]?.text : '';

      const qtd = document.getElementById('quantidade_cotas').value;
      const dia = document.getElementById('dia_vencimento').value;

      const resumoHTML = `
        <p><strong>Cliente:</strong> ${clienteNome} (${clienteCPF}) - ${clienteEmail}</p>
        <p><strong>Plano de Cota:</strong> ${planoTexto}</p>
        <p><strong>Quantidade de Cotas:</strong> ${qtd}</p>
        <p><strong>Melhor dia de vencimento:</strong> ${dia}</p>
      `;

      document.getElementById('resumoContrato').innerHTML = resumoHTML;
    }

    // Toggle senha
    document.getElementById('toggleSenha').addEventListener('click', () => {
      const senhaInput = document.getElementById('senha_confirm_input');
      const tipo = senhaInput.getAttribute('type') === 'password' ? 'text' : 'password';
      senhaInput.setAttribute('type', tipo);
    });

    // Inicializa exibição do step 1
    updateStepDisplay();
  </script>
</x-app-layout>
