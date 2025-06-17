<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Teste Boleto Mercado Pago</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/css/bootstrap.min.css">
</head>
<body class="p-5">
  <div class="container col-md-6">
    <h1 class="mb-4">Gerar Boleto de Teste</h1>

    {{-- Sucesso --}}
    @if(session('boleto_url'))
      <div class="alert alert-success">
        <p>Status: <strong>{{ session('status') }}</strong></p>
        <a href="{{ session('boleto_url') }}" target="_blank" class="btn btn-primary">
          Abrir Boleto
        </a>
      </div>
    @endif

    {{-- Erros --}}
    @if($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('boleto.create') }}" method="POST">
      @csrf

      <div class="mb-3">
        <label class="form-label">Valor (R$)</label>
        <input type="number" step="0.01" name="amount"
               value="{{ old('amount','100.00') }}" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Descrição</label>
        <input type="text" name="description"
               value="{{ old('description','Pagamento via boleto sandbox') }}"
               class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">E-mail</label>
        <input type="email" name="email"
               value="{{ old('email','test_user@test.com') }}"
               class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Nome</label>
        <input type="text" name="first_name"
               value="{{ old('first_name','Teste') }}"
               class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Sobrenome</label>
        <input type="text" name="last_name"
               value="{{ old('last_name','Comprador') }}"
               class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Tipo de Identificação</label>
        <select name="identification_type" class="form-select" required>
          <option value="CPF" {{ old('identification_type')=='CPF'? 'selected':'' }}>CPF</option>
          <option value="CNPJ"{{ old('identification_type')=='CNPJ'? 'selected':'' }}>CNPJ</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Número de Identificação</label>
        <input type="text" name="identification_number"
               value="{{ old('identification_number','00000000191') }}"
               class="form-control"
               placeholder="Somente dígitos" required>
      </div>

      <div class="mb-3">
        <label class="form-label">CEP</label>
        <input type="text" name="zip_code"
               value="{{ old('zip_code','01310200') }}"
               class="form-control"
               placeholder="Somente dígitos" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Logradouro</label>
        <input type="text" name="street_name"
               value="{{ old('street_name','Av. Paulista') }}"
               class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Número</label>
        <input type="text" name="street_number"
               value="{{ old('street_number','1000') }}"
               class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Bairro</label>
        <input type="text" name="neighborhood"
               value="{{ old('neighborhood','Bela Vista') }}"
               class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Cidade</label>
        <input type="text" name="city"
               value="{{ old('city','São Paulo') }}"
               class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">UF</label>
        <input type="text" name="federal_unit"
               value="{{ old('federal_unit','SP') }}"
               class="form-control"
               placeholder="Ex: SP, RJ" required>
      </div>

      <button type="submit" class="btn btn-success">Gerar Boleto</button>
    </form>
  </div>
</body>
</html>
