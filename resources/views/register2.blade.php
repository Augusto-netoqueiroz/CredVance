<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registro - CredVance</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            /* Cores baseadas no site CredVance */
            --primary-color: #1e88e5; /* Azul principal do site */
            --secondary-color: #0d47a1; /* Azul mais escuro */
            --accent-color: #42a5f5; /* Azul claro */
            --success-color: #4caf50;
            --danger-color: #f44336;
            --warning-color: #ff9800;
            --gradient-primary: linear-gradient(135deg, #1e88e5, #0d47a1);
            --gradient-bg: linear-gradient(135deg, #1e88e5 0%, #0d47a1 100%);
            --text-primary: #212121;
            --text-secondary: #757575;
            --bg-light: #fafafa;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--gradient-bg);
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        .register-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
        }

        .register-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(13, 71, 161, 0.2);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .register-header {
            background: var(--gradient-primary);
            color: white;
            padding: 30px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .register-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="40" r="1.5" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="80" r="1" fill="rgba(255,255,255,0.1)"/></svg>');
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .register-header h1 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 8px;
            position: relative;
            z-index: 2;
        }

        .register-header p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin: 0;
            position: relative;
            z-index: 2;
        }

        .progress-container {
            padding: 30px 20px;
            background: white;
        }

        .progress-steps {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 35px;
            position: relative;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .progress-line {
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 3px;
            background: #e3f2fd;
            z-index: 1;
            transform: translateY(-50%);
            border-radius: 2px;
        }

        .progress-line-active {
            position: absolute;
            top: 50%;
            left: 0;
            height: 3px;
            background: var(--gradient-primary);
            z-index: 2;
            transform: translateY(-50%);
            transition: width 0.4s ease;
            width: 33.33%;
            border-radius: 2px;
        }

        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 3;
            background: white;
            padding: 0 15px;
        }

        .step-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 3px solid #e3f2fd;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            color: var(--text-secondary);
            font-size: 1.2rem;
            transition: all 0.4s ease;
            margin-bottom: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .step-item.active .step-circle {
            border-color: var(--primary-color);
            background: var(--primary-color);
            color: white;
            transform: scale(1.15);
            box-shadow: 0 6px 20px rgba(30, 136, 229, 0.3);
        }

        .step-item.completed .step-circle {
            border-color: var(--success-color);
            background: var(--success-color);
            color: white;
            box-shadow: 0 6px 20px rgba(76, 175, 80, 0.3);
        }

        .step-label {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-secondary);
            text-align: center;
            white-space: nowrap;
        }

        .step-item.active .step-label {
            color: var(--primary-color);
            font-weight: 600;
        }

        .step-item.completed .step-label {
            color: var(--success-color);
            font-weight: 600;
        }

        /* Form Styles */
        .form-step {
            display: none;
            visibility: hidden;
            opacity: 0;
            transition: opacity 0.4s ease, visibility 0.4s ease;
        }

        .form-step.active {
            display: block;
            visibility: visible;
            opacity: 1;
            animation: fadeInUp 0.5s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-step h3 {
            color: var(--text-primary);
            font-weight: 600;
            margin-bottom: 25px;
            font-size: 1.4rem;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
        }

        .form-label i {
            color: var(--primary-color);
        }

        .form-control {
            border: 2px solid #e3f2fd;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            width: 100%;
            color: var(--text-primary);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(30, 136, 229, 0.15);
            background: white;
            outline: none;
            transform: translateY(-1px);
        }

        .form-control.is-invalid {
            border-color: var(--danger-color);
            background: rgba(244, 67, 54, 0.05);
        }

        .form-control.is-valid {
            border-color: var(--success-color);
            background: rgba(76, 175, 80, 0.05);
        }

        .invalid-feedback {
            display: block;
            color: var(--danger-color);
            font-size: 0.85rem;
            margin-top: 6px;
            font-weight: 500;
        }

        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 6px;
            transition: color 0.3s ease;
            z-index: 10;
            border-radius: 4px;
        }

        .password-toggle:hover {
            color: var(--primary-color);
            background: rgba(30, 136, 229, 0.1);
        }

        .btn-custom {
            padding: 12px 28px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            cursor: pointer;
            min-width: 140px;
            justify-content: center;
        }

        .btn-primary-custom {
            background: var(--gradient-primary);
            color: white;
            box-shadow: 0 4px 15px rgba(30, 136, 229, 0.3);
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(30, 136, 229, 0.4);
            color: white;
        }

        .btn-secondary-custom {
            background: var(--bg-light);
            color: var(--text-primary);
            border: 2px solid #e3f2fd;
        }

        .btn-secondary-custom:hover {
            background: #e3f2fd;
            transform: translateY(-1px);
            color: var(--text-primary);
            border-color: var(--primary-color);
        }

        .form-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e3f2fd;
        }

        .terms-container {
            background: linear-gradient(135deg, #e3f2fd, #f3e5f5);
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid var(--primary-color);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 0.2rem rgba(30, 136, 229, 0.25);
        }

        .login-link {
            text-align: center;
            padding: 25px;
            background: var(--bg-light);
            border-top: 2px solid #e3f2fd;
        }

        .login-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .login-link a:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }

        .alert-custom {
            border-radius: 10px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .alert-danger-custom {
            background: linear-gradient(135deg, rgba(244, 67, 54, 0.1), rgba(244, 67, 54, 0.05));
            color: var(--danger-color);
            border-left: 4px solid var(--danger-color);
        }

        /* Debug styles */
        .debug-info {
            position: fixed;
            top: 15px;
            right: 15px;
            background: rgba(0, 0, 0, 0.85);
            color: white;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            z-index: 9999;
            font-family: 'Courier New', monospace;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .register-container {
                padding: 10px;
            }

            .register-header {
                padding: 25px 15px;
            }

            .register-header h1 {
                font-size: 1.8rem;
            }

            .progress-container {
                padding: 25px 15px;
            }

            .step-label {
                display: none;
            }

            .step-circle {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }

            .form-navigation {
                flex-direction: column;
                gap: 15px;
            }

            .btn-custom {
                width: 100%;
                justify-content: center;
                padding: 14px 20px;
            }

            .form-control {
                font-size: 16px; /* Previne zoom no iOS */
            }

            .debug-info {
                top: 10px;
                right: 10px;
                font-size: 11px;
                padding: 8px 12px;
            }
        }

        @media (max-width: 576px) {
            .register-header h1 {
                font-size: 1.6rem;
            }

            .form-step h3 {
                font-size: 1.2rem;
            }

            .step-circle {
                width: 35px;
                height: 35px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
     

    <div class="register-container">
        <div class="register-card">
            <!-- Header -->
            <div class="register-header">
                <h1>Criar Conta CredVance</h1>
                <p>Junte-se à CredVance e multiplique seu dinheiro com segurança</p>
            </div>

            <!-- Progress Steps -->
            <div class="progress-container">
                <div class="progress-steps">
                    <div class="progress-line"></div>
                    <div class="progress-line-active" id="progressLineActive"></div>
                    
                    <div class="step-item active" data-step="1">
                        <div class="step-circle">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <div class="step-label">Dados Pessoais</div>
                    </div>
                    
                    <div class="step-item" data-step="2">
                        <div class="step-circle">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div class="step-label">Endereço</div>
                    </div>
                    
                    <div class="step-item" data-step="3">
                        <div class="step-circle">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <div class="step-label">Segurança</div>
                    </div>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('register') }}" id="multiStepForm" novalidate>
                    @csrf

                    @if(request()->has('ref'))
                        <input type="hidden" name="parceiro_id" value="{{ request()->get('ref') }}">
                    @endif

                    <!-- Campo hidden para enviar CPF sem máscara -->
                    <input type="hidden" name="cpf_clean" id="cpf_clean" value="{{ old('cpf') }}">

                    <!-- Step 1: Dados Pessoais -->
                    <div class="form-step active" data-step="1" id="step1">
                        <h3><i class="bi bi-person-circle"></i> Dados Pessoais</h3>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">
                                        <i class="bi bi-person"></i>
                                        Nome Completo
                                    </label>
                                    <input
                                        id="name"
                                        class="form-control"
                                        type="text"
                                        name="name"
                                        value="{{ old('name') }}"
                                        required
                                        autofocus
                                        autocomplete="name"
                                        placeholder="Digite seu nome completo"
                                    />
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-label">
                                        <i class="bi bi-envelope"></i>
                                        Email
                                    </label>
                                    <input
                                        id="email"
                                        class="form-control"
                                        type="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        required
                                        autocomplete="username"
                                        placeholder="seu@email.com"
                                    />
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cpf" class="form-label">
                                        <i class="bi bi-card-text"></i>
                                        CPF
                                    </label>
                                    <input
                                        id="cpf"
                                        class="form-control"
                                        type="text"
                                        value="{{ old('cpf') }}"
                                        required
                                        autocomplete="off"
                                        maxlength="14"
                                        placeholder="000.000.000-00"
                                    />
                                    @error('cpf')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="telefone" class="form-label">
                                        <i class="bi bi-telephone"></i>
                                        Telefone
                                    </label>
                                    <input
                                        id="telefone"
                                        class="form-control"
                                        type="text"
                                        name="telefone"
                                        value="{{ old('telefone') }}"
                                        required
                                        autocomplete="off"
                                        placeholder="(11) 99999-9999"
                                    />
                                    @error('telefone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-navigation">
                            <div></div>
                            <button type="button" class="btn btn-custom btn-primary-custom" id="nextBtn1">
                                Próximo
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Endereço -->
                    <div class="form-step" data-step="2" id="step2">
                        <h3><i class="bi bi-house-door"></i> Endereço</h3>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cep" class="form-label">
                                        <i class="bi bi-geo-alt"></i>
                                        CEP
                                    </label>
                                    <input
                                        id="cep"
                                        class="form-control"
                                        type="text"
                                        name="cep"
                                        value="{{ old('cep') }}"
                                        required
                                        autocomplete="off"
                                        maxlength="9"
                                        placeholder="00000-000"
                                    />
                                    @error('cep')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="logradouro" class="form-label">
                                        <i class="bi bi-house"></i>
                                        Logradouro
                                    </label>
                                    <input
                                        id="logradouro"
                                        class="form-control"
                                        type="text"
                                        name="logradouro"
                                        value="{{ old('logradouro') }}"
                                        required
                                        autocomplete="off"
                                        placeholder="Rua, Avenida, etc."
                                    />
                                    @error('logradouro')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="numero" class="form-label">
                                        <i class="bi bi-hash"></i>
                                        Número
                                    </label>
                                    <input
                                        id="numero"
                                        class="form-control"
                                        type="text"
                                        name="numero"
                                        value="{{ old('numero') }}"
                                        required
                                        autocomplete="off"
                                        placeholder="123"
                                    />
                                    @error('numero')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="complemento" class="form-label">
                                        <i class="bi bi-building"></i>
                                        Complemento
                                    </label>
                                    <input
                                        id="complemento"
                                        class="form-control"
                                        type="text"
                                        name="complemento"
                                        value="{{ old('complemento') }}"
                                        autocomplete="off"
                                        placeholder="Apto, Bloco, etc. (opcional)"
                                    />
                                    @error('complemento')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bairro" class="form-label">
                                        <i class="bi bi-map"></i>
                                        Bairro
                                    </label>
                                    <input
                                        id="bairro"
                                        class="form-control"
                                        type="text"
                                        name="bairro"
                                        value="{{ old('bairro') }}"
                                        required
                                        autocomplete="off"
                                        placeholder="Nome do bairro"
                                    />
                                    @error('bairro')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cidade" class="form-label">
                                        <i class="bi bi-building"></i>
                                        Cidade
                                    </label>
                                    <input
                                        id="cidade"
                                        class="form-control"
                                        type="text"
                                        name="cidade"
                                        value="{{ old('cidade') }}"
                                        required
                                        autocomplete="off"
                                        placeholder="Nome da cidade"
                                    />
                                    @error('cidade')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="uf" class="form-label">
                                        <i class="bi bi-flag"></i>
                                        UF
                                    </label>
                                    <input
                                        id="uf"
                                        class="form-control"
                                        type="text"
                                        name="uf"
                                        value="{{ old('uf') }}"
                                        required
                                        autocomplete="off"
                                        maxlength="2"
                                        placeholder="SP"
                                        style="text-transform: uppercase;"
                                    />
                                    @error('uf')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-navigation">
                            <button type="button" class="btn btn-custom btn-secondary-custom" id="prevBtn2">
                                <i class="bi bi-arrow-left"></i>
                                Anterior
                            </button>
                            <button type="button" class="btn btn-custom btn-primary-custom" id="nextBtn2">
                                Próximo
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 3: Segurança -->
                    <div class="form-step" data-step="3" id="step3">
                        <h3><i class="bi bi-shield-check"></i> Segurança</h3>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="form-label">
                                        <i class="bi bi-lock"></i>
                                        Senha
                                    </label>
                                    <div class="position-relative">
                                        <input
                                            id="password"
                                            class="form-control"
                                            type="password"
                                            name="password"
                                            required
                                            autocomplete="new-password"
                                            placeholder="Digite uma senha segura"
                                        />
                                        <button
                                            type="button"
                                            class="password-toggle"
                                            id="togglePassword"
                                            tabindex="-1"
                                            aria-label="Mostrar/ocultar senha"
                                        >
                                            <i class="bi bi-eye" id="togglePasswordIcon"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation" class="form-label">
                                        <i class="bi bi-check-circle"></i>
                                        Confirmar Senha
                                    </label>
                                    <div class="position-relative">
                                        <input
                                            id="password_confirmation"
                                            class="form-control"
                                            type="password"
                                            name="password_confirmation"
                                            required
                                            autocomplete="new-password"
                                            placeholder="Confirme sua senha"
                                        />
                                        <button
                                            type="button"
                                            class="password-toggle"
                                            id="togglePasswordConfirmation"
                                            tabindex="-1"
                                            aria-label="Mostrar/ocultar confirmação de senha"
                                        >
                                            <i class="bi bi-eye" id="togglePasswordConfirmationIcon"></i>
                                        </button>
                                    </div>
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="terms-container">
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="terms"
                                    required
                                />
                                <label class="form-check-label" for="terms">
                                    <strong>Eu concordo com os</strong> 
                                    <a href="#" class="text-decoration-none">Termos de Uso</a> 
                                    <strong>e</strong> 
                                    <a href="#" class="text-decoration-none">Política de Privacidade</a>
                                    <strong>da CredVance</strong>
                                </label>
                            </div>
                        </div>

                        <div class="form-navigation">
                            <button type="button" class="btn btn-custom btn-secondary-custom" id="prevBtn3">
                                <i class="bi bi-arrow-left"></i>
                                Anterior
                            </button>
                            <button type="submit" class="btn btn-custom btn-primary-custom">
                                <i class="bi bi-check-circle"></i>
                                Criar Conta
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Login Link -->
            <div class="login-link">
                <p class="mb-0">
                    <strong>Já possui uma conta CredVance?</strong> 
                    <a href="{{ route('login') }}">Faça login aqui</a>
                </p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Aguarda o DOM carregar completamente
        document.addEventListener("DOMContentLoaded", function() {
     

            // Variáveis globais
            let currentStep = 1;
            const totalSteps = 3;

            // Prevenção de submit ao pressionar Enter (exceto no último passo)
            const multiStepForm = document.getElementById("multiStepForm");
            if (multiStepForm) {
                multiStepForm.addEventListener("keydown", function(e) {
                    if (e.key === "Enter") {
                        const activeStepEl = document.querySelector(".form-step.active");
                        const isLastStep = activeStepEl && activeStepEl.dataset.step === String(totalSteps);
                        const isSubmitButtonFocused = document.activeElement.type === "submit";

                        if (!isLastStep || !isSubmitButtonFocused) {
                            e.preventDefault();
                            if (document.activeElement.tagName === 'INPUT') {
                                const currentInput = document.activeElement;
                                const formInputs = Array.from(activeStepEl.querySelectorAll('input, select, textarea, button'));
                                const currentIndex = formInputs.indexOf(currentInput);
                                if (currentIndex > -1 && currentIndex < formInputs.length - 1) {
                                    const nextInput = formInputs[currentIndex + 1];
                                    if (nextInput && nextInput.focus) {
                                        nextInput.focus();
                                    } else if (nextInput && nextInput.click) {
                                        nextInput.click();
                                    }
                                } else if (currentIndex === formInputs.length - 1) {
                                    const nextButton = activeStepEl.querySelector('.btn-primary-custom');
                                    if (nextButton) {
                                        nextButton.click();
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Função para atualizar debug info
            

            // Função para atualizar a barra de progresso
            function updateProgressLine() {
                const progressLine = document.getElementById('progressLineActive');
                if (progressLine) {
                    const percentage = (currentStep / totalSteps) * 100;
                    progressLine.style.width = percentage + '%';
                   
                }
            }

            // Função para mostrar uma etapa específica
            function showStep(step) {
               
                
                const allSteps = document.querySelectorAll('.form-step');
                
                
                allSteps.forEach((el, index) => {
                    el.classList.remove('active');
                    el.style.display = 'none';
                    
                });
                
                const currentStepEl = document.getElementById(`step${step}`);
                if (currentStepEl) {
                    currentStepEl.classList.add('active');
                    currentStepEl.style.display = 'block';
                    
                } else {
                     
                }
                
                // Atualizar indicadores de etapa
                document.querySelectorAll('.step-item').forEach((el, index) => {
                    const stepNumber = index + 1;
                    el.classList.remove('active', 'completed');
                    
                    if (stepNumber === step) {
                        el.classList.add('active');
                    } else if (stepNumber < step) {
                        el.classList.add('completed');
                        const icon = el.querySelector('.step-circle i');
                        if (icon) {
                            icon.className = 'bi bi-check';
                        }
                    }
                });

                updateProgressLine();
                updateDebugInfo();
            }

            // Função para validar etapa atual
            function validateCurrentStep() {
                const currentStepEl = document.querySelector(`[data-step="${currentStep}"]`);
                if (!currentStepEl) {
                    console.error(`❌ Etapa ${currentStep} não encontrada para validação!`);
                    return false;
                }

                const requiredInputs = currentStepEl.querySelectorAll('input[required]');
                let isValid = true;

                 

                requiredInputs.forEach(input => {
                    const value = input.value.trim();
                    
                    if (!value) {
                        input.classList.add('is-invalid');
                        input.classList.remove('is-valid');
                        isValid = false;
                        
                    } else {
                        input.classList.remove('is-invalid');
                        input.classList.add('is-valid');
                       
                    }
                });

                if (!isValid) {
                    showErrorMessage(currentStepEl, 'Por favor, preencha todos os campos obrigatórios.');
                }

                return isValid;
            }

            // Função para mostrar mensagem de erro
            function showErrorMessage(container, message) {
                const existingAlert = container.querySelector('.alert-custom');
                if (existingAlert) {
                    existingAlert.remove();
                }

                const errorDiv = document.createElement('div');
                errorDiv.className = 'alert-custom alert-danger-custom';
                errorDiv.innerHTML = `
                    <i class="bi bi-exclamation-triangle"></i>
                    ${message}
                `;
                
                container.insertBefore(errorDiv, container.firstChild);
                
                setTimeout(() => {
                    if (errorDiv.parentNode) {
                        errorDiv.remove();
                    }
                }, 3000);
            }

            // Função para próxima etapa
            function nextStep() {
               
                if (validateCurrentStep() && currentStep < totalSteps) {
                    currentStep++;
                    showStep(currentStep);
                     
                } else {
                    
                }
            }

            // Função para etapa anterior
            function prevStep() {
                 
                if (currentStep > 1) {
                    currentStep--;
                    showStep(currentStep);
                     
                }
            }

            // Máscaras para campos
            function applyMasks() {
                const cpfInput = document.getElementById('cpf');
                const telefoneInput = document.getElementById('telefone');
                const cepInput = document.getElementById('cep');

                // Máscara para CPF
                if (cpfInput) {
                    cpfInput.addEventListener('input', function(e) {
                        let value = e.target.value.replace(/\D/g, '');
                        
                        // Atualizar campo hidden com CPF limpo
                        const cpfCleanInput = document.getElementById('cpf_clean');
                        if (cpfCleanInput) {
                            cpfCleanInput.value = value;
                        }
                        
                        // Aplicar máscara visual
                        value = value.replace(/(\d{3})(\d)/, '$1.$2');
                        value = value.replace(/(\d{3})(\d)/, '$1.$2');
                        value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                        e.target.value = value;
                        
                        // Validação de CPF
                        const cleanCpf = e.target.value.replace(/\D/g, '');
                        if (cleanCpf.length === 11) {
                            if (validateCPF(cleanCpf)) {
                                e.target.classList.remove('is-invalid');
                                e.target.classList.add('is-valid');
                            } else {
                                e.target.classList.add('is-invalid');
                                e.target.classList.remove('is-valid');
                                showErrorMessage(e.target.closest('.form-step'), 'CPF inválido. Verifique os números digitados.');
                            }
                        } else if (cleanCpf.length > 0) {
                            e.target.classList.remove('is-valid', 'is-invalid');
                        }
                    });
                }

                // Máscara para telefone
                if (telefoneInput) {
                    telefoneInput.addEventListener('input', function(e) {
                        let value = e.target.value.replace(/\D/g, '');
                        if (value.length <= 10) {
                            value = value.replace(/(\d{2})(\d)/, '($1) $2');
                            value = value.replace(/(\d{4})(\d)/, '$1-$2');
                        } else {
                            value = value.replace(/(\d{2})(\d)/, '($1) $2');
                            value = value.replace(/(\d{5})(\d)/, '$1-$2');
                        }
                        e.target.value = value;
                        
                        if (value.length >= 14) {
                            e.target.classList.remove('is-invalid');
                            e.target.classList.add('is-valid');
                        } else if (value.length > 0 && value.length < 14) {
                            e.target.classList.add('is-invalid');
                            e.target.classList.remove('is-valid');
                            showErrorMessage(e.target.closest('.form-step'), 'Telefone deve ter pelo menos 10 dígitos.');
                        }
                    });
                }

                // Máscara para CEP
                if (cepInput) {
                    cepInput.addEventListener('input', function(e) {
                        let value = e.target.value.replace(/\D/g, '');
                        value = value.replace(/(\d{5})(\d)/, '$1-$2');
                        e.target.value = value;
                        
                        if (value.length === 9) {
                            buscarCEP(value);
                        }
                    });
                }
            }

            // Validação de CPF
            function validateCPF(cpf) {
                cpf = cpf.replace(/[^\d]+/g, '');
                if (cpf.length !== 11 || !!cpf.match(/(\d)\1{10}/)) return false;
                
                let soma = 0;
                let resto;
                
                for (let i = 1; i <= 9; i++) {
                    soma = soma + parseInt(cpf.substring(i-1, i)) * (11 - i);
                }
                
                resto = (soma * 10) % 11;
                if ((resto === 10) || (resto === 11)) resto = 0;
                if (resto !== parseInt(cpf.substring(9, 10))) return false;
                
                soma = 0;
                for (let i = 1; i <= 10; i++) {
                    soma = soma + parseInt(cpf.substring(i-1, i)) * (12 - i);
                }
                
                resto = (soma * 10) % 11;
                if ((resto === 10) || (resto === 11)) resto = 0;
                if (resto !== parseInt(cpf.substring(10, 11))) return false;
                
                return true;
            }

            // Validação de email
            function validateEmail(email) {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            }

            // Buscar CEP via API
            function buscarCEP(cep) {
                const cleanCep = cep.replace(/\D/g, '');
                
                if (cleanCep.length !== 8) return;
                
               
                
                const logradouroInput = document.getElementById('logradouro');
                const bairroInput = document.getElementById('bairro');
                const cidadeInput = document.getElementById('cidade');
                const ufInput = document.getElementById('uf');
                
                if (logradouroInput) logradouroInput.value = 'Carregando...';
                if (bairroInput) bairroInput.value = 'Carregando...';
                if (cidadeInput) cidadeInput.value = 'Carregando...';
                if (ufInput) ufInput.value = 'Carregando...';
                
                fetch(`https://viacep.com.br/ws/${cleanCep}/json/`)
                    .then(response => response.json())
                    .then(data => {
                        
                        
                        if (data.erro) {
                            console.error('❌ CEP não encontrado');
                            showErrorMessage(document.querySelector('.form-step.active'), 'CEP não encontrado. Verifique o número digitado.');
                            
                            if (logradouroInput) logradouroInput.value = '';
                            if (bairroInput) bairroInput.value = '';
                            if (cidadeInput) cidadeInput.value = '';
                            if (ufInput) ufInput.value = '';
                            
                            return;
                        }
                        
                        if (logradouroInput) {
                            logradouroInput.value = data.logradouro || '';
                            logradouroInput.classList.add('is-valid');
                        }
                        if (bairroInput) {
                            bairroInput.value = data.bairro || '';
                            bairroInput.classList.add('is-valid');
                        }
                        if (cidadeInput) {
                            cidadeInput.value = data.localidade || '';
                            cidadeInput.classList.add('is-valid');
                        }
                        if (ufInput) {
                            ufInput.value = data.uf || '';
                            ufInput.classList.add('is-valid');
                        }
                        
                       
                        
                        const numeroInput = document.getElementById('numero');
                        if (numeroInput) {
                            setTimeout(() => numeroInput.focus(), 100);
                        }
                    })
                    .catch(error => {
                        console.error('❌ Erro ao buscar CEP:', error);
                        showErrorMessage(document.querySelector('.form-step.active'), 'Erro ao buscar CEP. Tente novamente.');
                        
                        if (logradouroInput) logradouroInput.value = '';
                        if (bairroInput) bairroInput.value = '';
                        if (cidadeInput) cidadeInput.value = '';
                        if (ufInput) ufInput.value = '';
                    });
            }

            // Toggle de senha
            function setupPasswordToggle() {
                const togglePassword = document.getElementById('togglePassword');
                const togglePasswordConfirmation = document.getElementById('togglePasswordConfirmation');
                
                if (togglePassword) {
                    togglePassword.addEventListener('click', function() {
                        const passwordInput = document.getElementById('password');
                        const icon = document.getElementById('togglePasswordIcon');
                        
                        if (passwordInput.type === 'password') {
                            passwordInput.type = 'text';
                            icon.className = 'bi bi-eye-slash';
                        } else {
                            passwordInput.type = 'password';
                            icon.className = 'bi bi-eye';
                        }
                    });
                }
                
                if (togglePasswordConfirmation) {
                    togglePasswordConfirmation.addEventListener('click', function() {
                        const passwordInput = document.getElementById('password_confirmation');
                        const icon = document.getElementById('togglePasswordConfirmationIcon');
                        
                        if (passwordInput.type === 'password') {
                            passwordInput.type = 'text';
                            icon.className = 'bi bi-eye-slash';
                        } else {
                            passwordInput.type = 'password';
                            icon.className = 'bi bi-eye';
                        }
                    });
                }
            }

            // Validação em tempo real para email
            function setupEmailValidation() {
                const emailInput = document.getElementById('email');
                if (emailInput) {
                    emailInput.addEventListener('blur', function() {
                        const email = this.value.trim();
                        if (email && validateEmail(email)) {
                            this.classList.remove('is-invalid');
                            this.classList.add('is-valid');
                        } else if (email) {
                            this.classList.add('is-invalid');
                            this.classList.remove('is-valid');
                            showErrorMessage(this.closest('.form-step'), 'Email inválido. Verifique o formato digitado.');
                        }
                    });
                }
            }

            // Event listeners para botões de navegação
            const nextBtn1 = document.getElementById('nextBtn1');
            const nextBtn2 = document.getElementById('nextBtn2');
            const prevBtn2 = document.getElementById('prevBtn2');
            const prevBtn3 = document.getElementById('prevBtn3');

            // Garantir que o CPF seja enviado sem máscara no submit
            if (multiStepForm) {
                multiStepForm.addEventListener('submit', function(e) {
                    const cpfInput = document.getElementById('cpf');
                    const cpfCleanInput = document.getElementById('cpf_clean');
                    
                    if (cpfInput && cpfCleanInput) {
                        const cleanCpf = cpfInput.value.replace(/\D/g, '');
                        cpfCleanInput.value = cleanCpf;
                        
                        // Temporariamente adicionar name ao campo hidden e remover do campo mascarado
                        cpfCleanInput.name = 'cpf';
                        cpfInput.removeAttribute('name');
                        
                         
                    }
                });
            }

            if (nextBtn1) {
                nextBtn1.addEventListener('click', function(e) {
                    e.preventDefault();
                     
                    nextStep();
                });
            }

            if (nextBtn2) {
                nextBtn2.addEventListener('click', function(e) {
                    e.preventDefault();
                     
                    nextStep();
                });
            }

            if (prevBtn2) {
                prevBtn2.addEventListener('click', function(e) {
                    e.preventDefault();
                     
                    prevStep();
                });
            }

            if (prevBtn3) {
                prevBtn3.addEventListener('click', function(e) {
                    e.preventDefault();
                     
                    prevStep();
                });
            }

            // Inicializar todas as funcionalidades
            applyMasks();
            setupPasswordToggle();
            setupEmailValidation();
            showStep(currentStep);
            
             
        });
    </script>
</body>
</html>

