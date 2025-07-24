<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Redefinir Senha - CredVance</title>
    
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

        .reset-password-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            width: 100%;
        }

        .reset-password-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(13, 71, 161, 0.2);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .reset-password-header {
            background: var(--gradient-primary);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .reset-password-header::before {
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

        .reset-password-header h1 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 10px;
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .reset-password-header p {
            font-size: 1rem;
            opacity: 0.9;
            margin: 0;
            position: relative;
            z-index: 2;
            line-height: 1.5;
        }

        .reset-password-form {
            padding: 40px 30px;
        }

        .info-message {
            background: linear-gradient(135deg, rgba(76, 175, 80, 0.1), rgba(76, 175, 80, 0.05));
            border: 1px solid rgba(76, 175, 80, 0.2);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            color: var(--text-primary);
            font-size: 0.95rem;
            line-height: 1.6;
            border-left: 4px solid var(--success-color);
        }

        .info-message i {
            color: var(--success-color);
            margin-right: 8px;
            font-size: 1.1rem;
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

        .password-strength {
            margin-top: 8px;
            font-size: 0.85rem;
        }

        .strength-bar {
            height: 4px;
            border-radius: 2px;
            background: #e0e0e0;
            margin: 6px 0;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .strength-weak { background: var(--danger-color); width: 25%; }
        .strength-fair { background: var(--warning-color); width: 50%; }
        .strength-good { background: var(--primary-color); width: 75%; }
        .strength-strong { background: var(--success-color); width: 100%; }

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

        .btn-secondary-custom {
            background: var(--bg-light);
            color: var(--text-primary);
            border: 2px solid #e3f2fd;
            margin-top: 15px;
        }

        .btn-secondary-custom:hover {
            background: #e3f2fd;
            transform: translateY(-1px);
            color: var(--text-primary);
            border-color: var(--primary-color);
        }

        .back-to-login {
            text-align: center;
            padding: 25px;
            background: var(--bg-light);
            border-top: 2px solid #e3f2fd;
        }

        .back-to-login a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .back-to-login a:hover {
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
            .reset-password-container {
                padding: 15px;
            }

            .reset-password-header {
                padding: 30px 20px;
            }

            .reset-password-header h1 {
                font-size: 1.8rem;
            }

            .reset-password-form {
                padding: 30px 20px;
            }

            .form-control {
                font-size: 16px; /* Previne zoom no iOS */
            }

            .info-message {
                padding: 15px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            .reset-password-header h1 {
                font-size: 1.6rem;
                flex-direction: column;
                gap: 8px;
            }

            .reset-password-header p {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="reset-password-container">
        <div class="reset-password-card">
            <!-- Header -->
            <div class="reset-password-header">
                <h1>
                    <i class="bi bi-shield-lock"></i>
                    Nova Senha
                </h1>
                <p>Defina uma nova senha segura para sua conta</p>
            </div>

            <!-- Form -->
            <div class="reset-password-form">
                <!-- Info Message -->
                <div class="info-message">
                    <i class="bi bi-check-circle"></i>
                    <strong>Quase pronto!</strong><br>
                    Agora você pode definir uma nova senha segura para sua conta CredVance.
                </div>

                <form method="POST" action="{{ route('landing.reset.update') }}" id="resetPasswordForm" novalidate>
                    @csrf
                    
                    <!-- Token Hidden Field -->
                    <input type="hidden" name="token" value="{{ $token ?? '' }}">

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
                            value="{{ old('email', $email) }}"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="seu@email.com"
                            readonly
                        />
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock"></i>
                            Nova Senha
                        </label>
                        <div class="position-relative">
                            <input
                                id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                type="password"
                                name="password"
                                required
                                autocomplete="new-password"
                                placeholder="Digite sua nova senha"
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
                        <div class="password-strength" id="passwordStrength" style="display: none;">
                            <div class="strength-bar">
                                <div class="strength-fill" id="strengthFill"></div>
                            </div>
                            <span id="strengthText">Força da senha</span>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">
                            <i class="bi bi-lock-fill"></i>
                            Confirmar Nova Senha
                        </label>
                        <div class="position-relative">
                            <input
                                id="password_confirmation"
                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                type="password"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                placeholder="Confirme sua nova senha"
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

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-custom btn-primary-custom">
                        <i class="bi bi-check-circle"></i>
                        Redefinir Senha
                    </button>

                    <!-- Back to Login Button -->
                    <a href="{{ route('login') }}" class="btn btn-custom btn-secondary-custom">
                        <i class="bi bi-arrow-left"></i>
                        Voltar ao Login
                    </a>
                </form>
            </div>

            <!-- Back to Login Link -->
            <div class="back-to-login">
                <p class="mb-0">
                    <strong>Já tem acesso?</strong> 
                    <a href="{{ route('login') }}">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Fazer login
                    </a>
                </p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Aguarda o DOM carregar completamente
        document.addEventListener("DOMContentLoaded", function() {
            console.log("🚀 INICIANDO SCRIPT - CredVance Reset Password Form");

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
                    console.log('✅ Toggle de senha configurado');
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
                    console.log('✅ Toggle de confirmação de senha configurado');
                }
            }

            // Verificador de força da senha
            function checkPasswordStrength(password) {
                let strength = 0;
                let feedback = [];

                // Critérios de força
                if (password.length >= 8) strength += 1;
                else feedback.push('pelo menos 8 caracteres');

                if (/[a-z]/.test(password)) strength += 1;
                else feedback.push('letras minúsculas');

                if (/[A-Z]/.test(password)) strength += 1;
                else feedback.push('letras maiúsculas');

                if (/[0-9]/.test(password)) strength += 1;
                else feedback.push('números');

                if (/[^A-Za-z0-9]/.test(password)) strength += 1;
                else feedback.push('símbolos especiais');

                return { strength, feedback };
            }

            // Configurar validação de senha
            function setupPasswordValidation() {
                const passwordInput = document.getElementById('password');
                const confirmPasswordInput = document.getElementById('password_confirmation');
                const strengthContainer = document.getElementById('passwordStrength');
                const strengthFill = document.getElementById('strengthFill');
                const strengthText = document.getElementById('strengthText');

                if (passwordInput) {
                    passwordInput.addEventListener('input', function() {
                        const password = this.value;
                        const { strength, feedback } = checkPasswordStrength(password);

                        if (password.length > 0) {
                            strengthContainer.style.display = 'block';
                            
                            // Atualizar barra de força
                            strengthFill.className = 'strength-fill';
                            if (strength <= 2) {
                                strengthFill.classList.add('strength-weak');
                                strengthText.textContent = 'Senha fraca';
                                strengthText.style.color = 'var(--danger-color)';
                            } else if (strength === 3) {
                                strengthFill.classList.add('strength-fair');
                                strengthText.textContent = 'Senha razoável';
                                strengthText.style.color = 'var(--warning-color)';
                            } else if (strength === 4) {
                                strengthFill.classList.add('strength-good');
                                strengthText.textContent = 'Senha boa';
                                strengthText.style.color = 'var(--primary-color)';
                            } else if (strength === 5) {
                                strengthFill.classList.add('strength-strong');
                                strengthText.textContent = 'Senha forte';
                                strengthText.style.color = 'var(--success-color)';
                            }

                            // Validação visual
                            if (strength >= 3) {
                                this.classList.remove('is-invalid');
                                this.classList.add('is-valid');
                            } else {
                                this.classList.add('is-invalid');
                                this.classList.remove('is-valid');
                            }
                        } else {
                            strengthContainer.style.display = 'none';
                            this.classList.remove('is-valid', 'is-invalid');
                        }

                        // Verificar confirmação de senha
                        if (confirmPasswordInput.value) {
                            validatePasswordConfirmation();
                        }
                    });
                    console.log('✅ Validação de senha configurada');
                }

                function validatePasswordConfirmation() {
                    const password = passwordInput.value;
                    const confirmPassword = confirmPasswordInput.value;

                    if (confirmPassword.length > 0) {
                        if (password === confirmPassword) {
                            confirmPasswordInput.classList.remove('is-invalid');
                            confirmPasswordInput.classList.add('is-valid');
                        } else {
                            confirmPasswordInput.classList.add('is-invalid');
                            confirmPasswordInput.classList.remove('is-valid');
                        }
                    } else {
                        confirmPasswordInput.classList.remove('is-valid', 'is-invalid');
                    }
                }

                if (confirmPasswordInput) {
                    confirmPasswordInput.addEventListener('input', validatePasswordConfirmation);
                    console.log('✅ Validação de confirmação de senha configurada');
                }
            }

            // Prevenção de submit múltiplo
            function setupFormSubmission() {
                const resetPasswordForm = document.getElementById('resetPasswordForm');
                const submitButton = resetPasswordForm.querySelector('button[type="submit"]');
                
                if (resetPasswordForm && submitButton) {
                    resetPasswordForm.addEventListener('submit', function(e) {
                        const passwordInput = document.getElementById('password');
                        const confirmPasswordInput = document.getElementById('password_confirmation');
                        
                        const password = passwordInput.value;
                        const confirmPassword = confirmPasswordInput.value;
                        
                        // Validações antes de enviar
                        if (password.length < 8) {
                            e.preventDefault();
                            passwordInput.classList.add('is-invalid');
                            passwordInput.focus();
                            showErrorMessage('A senha deve ter pelo menos 8 caracteres.');
                            return;
                        }

                        if (password !== confirmPassword) {
                            e.preventDefault();
                            confirmPasswordInput.classList.add('is-invalid');
                            confirmPasswordInput.focus();
                            showErrorMessage('As senhas não coincidem.');
                            return;
                        }

                        // Desabilitar botão para prevenir duplo submit
                        submitButton.disabled = true;
                        submitButton.innerHTML = '<i class="bi bi-hourglass-split"></i> Redefinindo...';
                        
                        // Reabilitar após 5 segundos (caso haja erro)
                        setTimeout(() => {
                            submitButton.disabled = false;
                            submitButton.innerHTML = '<i class="bi bi-check-circle"></i> Redefinir Senha';
                        }, 5000);
                        
                        console.log('📤 Enviando redefinição de senha');
                    });
                    console.log('✅ Prevenção de duplo submit configurada');
                }
            }

            // Função para mostrar mensagem de erro
            function showErrorMessage(message) {
                const existingAlert = document.querySelector('.alert-danger-custom');
                if (existingAlert) {
                    existingAlert.remove();
                }

                const errorDiv = document.createElement('div');
                errorDiv.className = 'alert-custom alert-danger-custom';
                errorDiv.innerHTML = `
                    <i class="bi bi-exclamation-triangle"></i>
                    ${message}
                `;
                
                const form = document.getElementById('resetPasswordForm');
                form.insertBefore(errorDiv, form.firstChild);
                
                setTimeout(() => {
                    if (errorDiv.parentNode) {
                        errorDiv.style.opacity = '0';
                        errorDiv.style.transform = 'translateY(-10px)';
                        setTimeout(() => {
                            if (errorDiv.parentNode) {
                                errorDiv.remove();
                            }
                        }, 300);
                    }
                }, 4000);
            }

            // Foco automático no primeiro campo com erro
            function focusFirstError() {
                const firstError = document.querySelector('.form-control.is-invalid');
                if (firstError) {
                    firstError.focus();
                    console.log('🎯 Foco no primeiro campo com erro');
                }
            }

            // Auto-hide alerts após 7 segundos
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
                    }, 7000);
                });
                if (alerts.length > 0) {
                    console.log(`✅ Auto-hide configurado para ${alerts.length} alertas`);
                }
            }

            // Animação de entrada suave
            function setupEntryAnimation() {
                const card = document.querySelector('.reset-password-card');
                if (card) {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(30px)';
                    
                    setTimeout(() => {
                        card.style.transition = 'all 0.6s ease';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 100);
                    
                    console.log('✅ Animação de entrada configurada');
                }
            }

            // Inicializar todas as funcionalidades
            setupPasswordToggle();
            setupPasswordValidation();
            setupFormSubmission();
            focusFirstError();
            setupAutoHideAlerts();
            setupEntryAnimation();
            
            console.log("✅ CredVance Reset Password Form: Inicializado com sucesso!");
            console.log("🎉 SCRIPT TOTALMENTE CARREGADO E FUNCIONAL!");
        });
    </script>
</body>
</html>

