<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Redefinir sua senha - CredVance</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <style>
    /* Reset e base */
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: linear-gradient(135deg, #1e88e5 0%, #0d47a1 100%);
      padding: 20px 0;
      min-height: 100vh;
    }
    .email-container {
      max-width: 600px;
      margin: 0 auto;
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }
    .email-header {
      background: linear-gradient(135deg, #1e88e5 0%, #0d47a1 100%);
      padding: 40px 30px;
      text-align: center;
      position: relative;
    }
    .email-header::before {
      content: '';
      position: absolute; top: 0; left: 0; right: 0; bottom: 0;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
      opacity: 0.3;
    }
    .logo-round {
      background: rgba(255,255,255,0.2);
      width: 80px; height: 80px;
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      margin: 0 auto 20px; position: relative; z-index: 2;
    }
    .logo-round img {
      max-height: 40px;
      max-width: 40px;
      filter: brightness(0) invert(1);
      display: block;
      margin: auto;
    }
    .email-title {
      color: #ffffff; /* forte branco */
      font-size: 28px; font-weight: 700;
      margin: 0; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
      position: relative; z-index: 2;
    }
    .email-subtitle {
      color: rgba(255,255,255,0.9); font-size: 16px;
      margin-top: 8px; position: relative; z-index: 2;
    }
    .email-content { padding: 40px 30px; }
    .greeting { font-size: 24px; color: #2c3e50; margin-bottom: 20px; font-weight: 600; text-align: center; }
    .main-message { font-size: 16px; color: #555; line-height: 1.6; margin-bottom: 30px; text-align: center; }
    .cta-container { text-align: center; margin: 40px 0; }
    .cta-button {
      display: inline-block;
      background: linear-gradient(135deg, #1e88e5 0%, #0d47a1 100%);
      color: white; text-decoration: none; padding: 18px 40px;
      border-radius: 12px; font-size: 18px; font-weight: 700;
      box-shadow: 0 8px 25px rgba(30,136,229,0.3);
      transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 1px;
      position: relative; overflow: hidden;
    }
    .cta-button:hover { transform: translateY(-3px); box-shadow: 0 12px 35px rgba(30,136,229,0.4); }
    .footer {
      background: linear-gradient(135deg, #1e88e5 0%, #0d47a1 100%);
      color: white; padding: 30px; text-align: center;
      font-size: 13px; line-height: 1.6;
    }
    .footer a { color: #f1c40f; text-decoration: none; margin: 0 10px; }
    .footer a:hover { text-decoration: underline; }
    @media only screen and (max-width: 600px) {
      body { padding: 10px 0; }
      .email-container { margin: 0 10px; border-radius: 16px; }
      .email-header, .email-content, .footer { padding: 25px 20px; }
      .email-title { font-size: 24px; }
      .greeting { font-size: 20px; }
      .cta-button { padding: 16px 30px; font-size: 16px; }
    }
  </style>
</head>
<body>
  <div class="email-container">
    <div class="email-header">
      <div class="logo-round">
        <img src="https://meucredvance.com.br/assets/img/logo.png" alt="CredVance">
      </div>
      <h1 class="email-title">REDEFINIR SENHA</h1>
      <p class="email-subtitle">Proteja sua conta</p>
    </div>
    <div class="email-content">
      <p class="greeting">Olá!</p>
      <p class="main-message">
        Você solicitou a redefinição de senha. Clique no botão abaixo para criar uma nova senha:
      </p>
      <div class="cta-container">
        <a href="{{ $resetLink }}" class="cta-button" target="_blank" style="color:#fff;">
          Redefinir Senha
          <svg class="cta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M13 7l5 5m0 0l-5 5m5-5H6"/>
          </svg>
        </a>
      </div>
      <div class="main-message">
        Se o botão não funcionar, copie e cole a URL abaixo no seu navegador:
      </p>
      <div class="main-message code-box">{{ $resetLink }}</div>
      <p class="main-message"><em>Nota: Este link expira em 15 minutos.</em></p>
    </div>
    <div class="footer">
      © {{ date('Y') }} CredVance - Soluções Financeiras Inteligentes<br>
      <a href="mailto:contato@credvance.com.br">contato@credvance.com.br</a> |
      <a href="https://wa.me/5561996258003">WhatsApp</a> |
      <a href="https://meucredvance.com.br">Site Oficial</a>
    </div>
  </div>
</body>
</html>
