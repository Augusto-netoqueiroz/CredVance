<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - CredVance</title>
    
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
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            width: 100%;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(13, 71, 161, 0.2);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .login-header {
            background: var(--gradient-primary);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .login-header::before {
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

        .login-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            position: relative;
            z-index: 2;
        }

        .login-header p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin: 0;
            position: relative;
            z-index: 2;
        }

        .login-form {
            padding: 40px 30px;
        }

        .form-group {
            margin-bottom: 25px;
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
            padding: 14px 16px;
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

        .form-check {
            margin: 25px 0;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            margin-top: 0.125em;
            margin-right: 10px;
            border: 2px solid #e3f2fd;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 0.2rem rgba(30, 136, 229, 0.25);
        }

        .form-check-label {
            font-size: 0.95rem;
            color: var(--text-primary);
            font-weight: 500;
        }

        .btn-custom {
            padding: 14px 30px;
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
            width: 100%;
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

        .login-links {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 25px 0;
            flex-wrap: wrap;
            gap: 15px;
        }

        .login-links a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .login-links a:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }

        .register-link {
            text-align: center;
            padding: 25px;
            background: var(--bg-light);
            border-top: 2px solid #e3f2fd;
        }

        .register-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .register-link a:hover {
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

        .alert-success-custom {
            background: linear-gradient(135deg, rgba(76, 175, 80, 0.1), rgba(76, 175, 80, 0.05));
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }

        .alert-danger-custom {
            background: linear-gradient(135deg, rgba(244, 67, 54, 0.1), rgba(244, 67, 54, 0.05));
            color: var(--danger-color);
            border-left: 4px solid var(--danger-color);
        }

        .alert-info-custom {
            background: linear-gradient(135deg, rgba(30, 136, 229, 0.1), rgba(30, 136, 229, 0.05));
            color: var(--primary-color);
            border-left: 4px solid var(--primary-color);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .login-container {
                padding: 15px;
            }

            .login-header {
                padding: 30px 20px;
            }

            .login-header h1 {
                font-size: 2rem;
            }

            .login-form {
                padding: 30px 20px;
            }

            .form-control {
                font-size: 16px; /* Previne zoom no iOS */
            }

            .login-links {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
        }

        @media (max-width: 576px) {
            .login-header h1 {
                font-size: 1.8rem;
            }

            .login-header p {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Header -->
            <div class="login-header">
                <h1>Bem-vindo</h1>
                <p>Acesse sua conta CredVance</p>
            </div>

            <!-- Form -->
            <div class="login-form">
                <!-- Success Message -->
                @if (session('success'))
                    <div class="alert-custom alert-success-custom">
                        <i class="bi bi-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Session Status -->
                @if (session('status'))
                    <div class="alert-custom alert-info-custom">
                        <i class="bi bi-info-circle"></i>
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
                    @csrf

                    <!-- Email Address -->
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope"></i>
                            Email
                        </label>
                        <input
                            id="email"
                            class="form-control @error('email') is-invalid @enderror"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="seu@email.com"
                        />
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock"></i>
                            Senha
                        </label>
                        <div class="position-relative">
                            <input
                                id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="Digite sua senha"
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

                    <!-- Remember Me -->
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            id="remember_me"
                            name="remember"
                        />
                        <label class="form-check-label" for="remember_me">
                            Lembrar de mim
                        </label>
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="btn btn-custom btn-primary-custom">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Entrar
                    </button>

                    <!-- Links -->
                    <div class="login-links">
                        <a href="{{ route('password.forgot') }}">
                            <i class="bi bi-key"></i>
                            Esqueceu sua senha?
                        </a>
                        <a href="{{ route('register') }}">
                            <i class="bi bi-person-plus"></i>
                            Criar conta
                        </a>
                    </div>
                </form>
            </div>

            <!-- Register Link -->
            <div class="register-link">
                <p class="mb-0">
                    <strong>Novo na CredVance?</strong> 
                    <a href="{{ route('register') }}">Crie sua conta gratuita</a>
                </p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Aguarda o DOM carregar completamente
        document.addEventListener("DOMContentLoaded", function() {
            

            // Toggle de senha
            function setupPasswordToggle() {
                const togglePassword = document.getElementById('togglePassword');
                
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
                        }
                    });
                    
                }
            }

            // Validação de email
            function validateEmail(email) {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            }

            // Prevenção de submit múltiplo
            function setupFormSubmission() {
                const loginForm = document.getElementById('loginForm');
                const submitButton = loginForm.querySelector('button[type="submit"]');
                
                if (loginForm && submitButton) {
                    loginForm.addEventListener('submit', function(e) {
                        // Desabilitar botão para prevenir duplo submit
                        submitButton.disabled = true;
                        submitButton.innerHTML = '<i class="bi bi-hourglass-split"></i> Entrando...';
                        
                        // Reabilitar após 3 segundos (caso haja erro)
                        setTimeout(() => {
                            submitButton.disabled = false;
                            submitButton.innerHTML = '<i class="bi bi-box-arrow-in-right"></i> Entrar';
                        }, 3000);
                        
                        
                    });
                    
                }
            }

            // Foco automático no primeiro campo com erro
            function focusFirstError() {
                const firstError = document.querySelector('.form-control.is-invalid');
                if (firstError) {
                    firstError.focus();
                     
                }
            }

            // Auto-hide alerts após 5 segundos
            function setupAutoHideAlerts() {
                const alerts = document.querySelectorAll('.alert-custom');
                alerts.forEach(alert => {
                    setTimeout(() => {
                        if (alert.parentNode) {
                            alert.style.opacity = '0';
                            alert.style.transform = 'translateY(-10px)';
                            setTimeout(() => {
                                if (alert.parentNode) {
                                    alert.remove();
                                }
                            }, 300);
                        }
                    }, 5000);
                });
                if (alerts.length > 0) {
                    
                }
            }

            // Inicializar todas as funcionalidades
            setupPasswordToggle();
            setupEmailValidation();
            setupFormSubmission();
            focusFirstError();
            setupAutoHideAlerts();
            
             
        });
    </script>
</body>
</html>

