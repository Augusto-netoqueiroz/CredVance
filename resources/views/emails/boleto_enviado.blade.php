<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Verificação de E-mail - CredVance</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <style>
        /* Reset e base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #1e88e5 0%, #0d47a1 100%);
            margin: 0;
            padding: 20px 0;
            min-height: 100vh;
        }

        /* Container principal */
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        /* Header com gradiente */
        .email-header {
            background: linear-gradient(135deg, #1e88e5 0%, #0d47a1 100%);
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }

        .email-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.15"/><circle cx="20" cy="80" r="0.5" fill="white" opacity="0.15"/><circle cx="80" cy="30" r="0.5" fill="white" opacity="0.15"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .logo-container {
            position: relative;
            z-index: 2;
            margin-bottom: 20px;
        }

        .logo {
            max-height: 50px;
            filter: brightness(0) invert(1);
        }

        .email-title {
            color: white;
            font-size: 28px;
            font-weight: 700;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 2;
        }

        .email-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
            margin-top: 8px;
            position: relative;
            z-index: 2;
        }

        /* Ícone de verificação */
        .verification-icon {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 2;
        }

        .verification-icon svg {
            width: 40px;
            height: 40px;
            color: white;
        }

        /* Conteúdo principal */
        .email-content {
            padding: 40px 30px;
        }

        .greeting {
            font-size: 24px;
            color: #2c3e50;
            margin-bottom: 20px;
            font-weight: 600;
            text-align: center;
        }

        .main-message {
            font-size: 16px;
            color: #555;
            line-height: 1.6;
            margin-bottom: 30px;
            text-align: center;
        }

        /* Card de boas-vindas */
        .welcome-card {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-radius: 16px;
            padding: 25px;
            margin: 30px 0;
            border: 1px solid rgba(30, 136, 229, 0.1);
            box-shadow: 0 4px 15px rgba(30, 136, 229, 0.1);
            text-align: center;
        }

        .welcome-title {
            color: #1e88e5;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .welcome-title svg {
            margin-right: 8px;
            width: 20px;
            height: 20px;
        }

        .welcome-text {
            color: #64748b;
            font-size: 14px;
            line-height: 1.5;
        }

        /* Botão CTA principal */
        .cta-container {
            text-align: center;
            margin: 40px 0;
        }

        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #1e88e5 0%, #0d47a1 100%);
            color: white;
            text-decoration: none;
            padding: 18px 40px;
            border-radius: 12px;
            font-size: 18px;
            font-weight: 700;
            box-shadow: 0 8px 25px rgba(30, 136, 229, 0.3);
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
        }

        .cta-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .cta-button:hover::before {
            left: 100%;
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(30, 136, 229, 0.4);
        }

        .cta-icon {
            margin-left: 8px;
            width: 18px;
            height: 18px;
            vertical-align: middle;
        }

        /* Seção de link alternativo */
        .alternative-link {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-radius: 16px;
            padding: 25px;
            margin: 30px 0;
            border: 1px solid rgba(30, 136, 229, 0.1);
        }

        .alternative-title {
            color: #1e88e5;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .alternative-title svg {
            margin-right: 8px;
            width: 18px;
            height: 18px;
        }

        .link-box {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            border: 2px solid #1e88e5;
            border-radius: 12px;
            padding: 16px;
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 12px;
            color: #0d47a1;
            word-break: break-all;
            user-select: all;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 10px;
        }

        .link-box:hover {
            background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(30, 136, 229, 0.2);
        }

        .link-instruction {
            font-size: 13px;
            color: #64748b;
            font-style: italic;
        }

        /* Seção de expiração */
        .expiration-notice {
            background: linear-gradient(135deg, #fef3cd 0%, #fde68a 100%);
            border: 1px solid #f59e0b;
            border-radius: 12px;
            padding: 20px;
            margin: 30px 0;
            text-align: center;
        }

        .expiration-title {
            color: #92400e;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .expiration-title svg {
            margin-right: 8px;
            width: 18px;
            height: 18px;
        }

        .expiration-text {
            color: #92400e;
            font-size: 14px;
        }

        /* Seção de benefícios */
        .benefits-section {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-radius: 16px;
            padding: 25px;
            margin: 30px 0;
            border: 1px solid rgba(30, 136, 229, 0.1);
        }

        .benefits-title {
            color: #1e88e5;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            text-align: center;
        }

        .benefits-list {
            list-style: none;
            padding: 0;
        }

        .benefits-list li {
            color: #475569;
            font-size: 14px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .benefits-list li svg {
            margin-right: 10px;
            width: 16px;
            height: 16px;
            color: #10b981;
            flex-shrink: 0;
        }

        /* Seção de suporte */
        .support-section {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-radius: 16px;
            padding: 25px;
            text-align: center;
            margin: 30px 0;
            border: 1px solid rgba(30, 136, 229, 0.1);
        }

        .support-title {
            color: #1e88e5;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .support-text {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .whatsapp-link {
            color: #1e88e5;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            background: rgba(30, 136, 229, 0.1);
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .whatsapp-link:hover {
            background: rgba(30, 136, 229, 0.2);
            transform: translateY(-1px);
        }

        .whatsapp-link svg {
            margin-right: 8px;
            width: 18px;
            height: 18px;
        }

        /* Footer */
        .email-footer {
            background: linear-gradient(135deg, #1e88e5 0%, #0d47a1 100%);
            color: white;
            padding: 30px;
            text-align: center;
            font-size: 13px;
            line-height: 1.6;
        }

        .footer-links {
            margin-top: 15px;
        }

        .footer-links a {
            color: #f1c40f;
            text-decoration: none;
            margin: 0 10px;
            font-weight: 500;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }

        /* Responsividade */
        @media only screen and (max-width: 600px) {
            body {
                padding: 10px 0;
            }
            
            .email-container {
                margin: 0 10px;
                border-radius: 16px;
            }
            
            .email-header,
            .email-content,
            .email-footer {
                padding: 25px 20px;
            }
            
            .email-title {
                font-size: 24px;
            }
            
            .greeting {
                font-size: 20px;
            }
            
            .welcome-card,
            .alternative-link,
            .expiration-notice,
            .benefits-section,
            .support-section {
                padding: 20px;
            }
            
            .cta-button {
                padding: 16px 30px;
                font-size: 16px;
            }
            
            .link-box {
                font-size: 11px;
                padding: 14px;
            }
            
            .verification-icon {
                width: 60px;
                height: 60px;
            }
            
            .verification-icon svg {
                width: 30px;
                height: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="verification-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="logo-container">
                <img src="https://meucredvance.com.br/assets/img/logo.png" alt="CredVance" class="logo">
            </div>
            <h1 class="email-title">Verificação de E-mail</h1>
            <p class="email-subtitle">Confirme seu e-mail para ativar sua conta</p>
        </div>

        <!-- Conteúdo Principal -->
        <div class="email-content">
            <div class="greeting">
                Bem-vindo à CredVance! 🎉
            </div>

            <div class="main-message">
                Obrigado por se cadastrar! Estamos muito felizes em tê-lo conosco. 
                Para começar a aproveitar todos os benefícios da CredVance, você precisa verificar seu e-mail.
            </div>

            <!-- Card de boas-vindas -->
            <div class="welcome-card">
                <div class="welcome-title">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Sua conta está quase pronta!
                </div>
                <div class="welcome-text">
                    Clique no botão abaixo para verificar seu e-mail e entrar automaticamente na sua conta. 
                    É rápido, seguro e você terá acesso imediato a todos os nossos serviços.
                </div>
            </div>

            <!-- Botão CTA principal -->
            <div class="cta-container">
                <a href="{{ $verificationLink }}" target="_blank" class="cta-button">
                    Verificar Meu E-mail
                    <svg class="cta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            </div>

            <!-- Seção de link alternativo -->
            <div class="alternative-link">
                <div class="alternative-title">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                    Problemas com o botão?
                </div>
                <div class="link-box">
                    {{ $verificationLink }}
                </div>
                <div class="link-instruction">
                    Copie e cole este link no seu navegador
                </div>
            </div>

            <!-- Aviso de expiração -->
            <div class="expiration-notice">
                <div class="expiration-title">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Atenção: Link Temporário
                </div>
                <div class="expiration-text">
                    Este link de verificação expira em <strong>15 minutos</strong> por motivos de segurança.
                </div>
            </div>

            <!-- Benefícios -->
            <div class="benefits-section">
                <div class="benefits-title">O que você terá acesso:</div>
                <ul class="benefits-list">
                    <li>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Área do cliente personalizada
                    </li>
                    <li>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Acompanhamento de contratos em tempo real
                    </li>
                    <li>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Suporte especializado e atendimento humanizado
                    </li>
                    <li>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Pagamentos seguros via PIX e boleto
                    </li>
                </ul>
            </div>

            <!-- Seção de suporte -->
            <div class="support-section">
                <div class="support-title">Precisa de Ajuda?</div>
                <div class="support-text">
                    Nossa equipe está pronta para te atender com agilidade e eficiência
                </div>
                <a href="https://wa.me/5561996258003" class="whatsapp-link">
                    <svg fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                    </svg>
                    Falar no WhatsApp
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <div>
                © {{ date('Y') }} CredVance - Soluções Financeiras Inteligentes<br>
                Rua 35 Casa 101 - Setor Tradicional - São Sebastião/DF
            </div>
            <div class="footer-links">
                <a href="mailto:contato@credvance.com.br">contato@credvance.com.br</a>
                <a href="https://wa.me/5561996258003">WhatsApp</a>
                <a href="https://meucredvance.com.br">Site Oficial</a>
            </div>
        </div>
    </div>
</body>
</html>

