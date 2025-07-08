<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Seu boleto está disponível - CredVance</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <style>
    .code-box {
      font-family: monospace, monospace;
      background: #f7f8fa;
      padding: 12px 16px;
      border-radius: 8px;
      user-select: all;
      word-break: break-all;
      font-size: 16px;
      margin-top: 6px;
      margin-bottom: 12px;
      color: #0c3c71;
    }
    .label {
      font-weight: 700;
      color: #0c3c71;
      font-size: 15px;
      letter-spacing: 0.5px;
      margin-bottom: 4px;
      display: block;
    }
  </style>
</head>
<body style="background-color:#f2f4f6; margin:0; padding:0;">
  <table align="center" width="100%" bgcolor="#f2f4f6" cellpadding="0" cellspacing="0" style="margin:0;padding:0;">
    <tr>
      <td align="center">
        <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; margin:32px auto; border-radius:12px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.06); font-family:Arial,Helvetica,sans-serif;">
          <!-- Header/Banner -->
          <tr>
            <td align="center" style="background:#0c3c71; padding:0;">
              <img src="https://meucredvance.com.br/assets/img/hero2.png" alt="Banner CredVance" style="width:100%; max-width:600px; display:block; border:0;">
            </td>
          </tr>
          <!-- Logo + headline -->
          <tr>
            <td align="center" style="padding:28px 32px 14px 32px;">
              <img src="https://meucredvance.com.br/assets/img/logo.png" alt="CredVance" style="max-height:44px; display:block; margin:0 auto 14px auto;">
              <h2 style="color:#0c3c71; font-size:22px; margin:0; font-weight:800; letter-spacing:1px;">Seu boleto está pronto para pagamento!</h2>
            </td>
          </tr>
          <!-- Mensagem principal -->
          <tr>
            <td style="padding:0 32px 14px 32px; color:#444; font-size:16px;">
              <p style="margin:0 0 12px 0;">Olá <strong>{{ $cliente->name ?? 'Cliente' }}</strong>!</p>
              <p style="margin:0 0 18px 0;">
                O boleto do seu contrato <strong>#{{ $contrato->id ?? 'N/A' }}</strong> está disponível. Mantenha seus pagamentos em dia e aproveite todos os benefícios da CredVance.
              </p>
            </td>
          </tr>
          <!-- Box de destaque com vencimento e valor -->
          <tr>
            <td align="center" style="padding:10px 32px 22px 32px;">
              <table cellpadding="0" cellspacing="0" style="background:#f7f8fa; border-radius:8px; width:100%; max-width:420px; margin:0 auto;">
                <tr>
                  <td style="padding:16px 18px;">
                    <span class="label">Vencimento:</span>
                    <span style="float:right; color:#333; font-size:15px;">{{ isset($boleto->vencimento) ? \Carbon\Carbon::parse($boleto->vencimento)->format('d/m/Y') : '-' }}</span>
                  </td>
                </tr>
                <tr>
                  <td style="padding:0 18px 8px 18px;">
                    <span class="label">Valor:</span>
                    <span style="float:right; color:#333; font-size:15px;">R$ {{ isset($boleto->valor) ? number_format($boleto->valor, 2, ',', '.') : '-' }}</span>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Linha Digitável -->
          <tr>
            <td style="padding:0 32px 10px 32px;">
              <span class="label">Linha Digitável:</span>
              <div class="code-box">{{ $boleto->linha_digitavel ?? '-' }}</div>
            </td>
          </tr>

          <!-- Código Pix -->
          <tr>
            <td style="padding:0 32px 22px 32px;">
              <span class="label">Código Pix:</span>
              <div class="code-box">{{ $boleto->pix ?? '-' }}</div>
              <p style="font-size:14px; color:#555; margin-top:0;">
                Para copiar, selecione o código Pix acima e pressione Ctrl+C (Cmd+C no Mac).
              </p>
            </td>
          </tr>

          <!-- Botão CTA -->
          <tr>
            <td align="center" style="padding:4px 32px 20px 32px;">
              <a href="https://meucredvance.com.br/login"
                 style="background:#f6c800; color:#0c3c71; font-size:18px; font-weight:bold; text-decoration:none; padding:16px 38px; border-radius:7px; display:inline-block; box-shadow:0 2px 6px rgba(246,200,0,0.10); letter-spacing:1px;">
                Acessar Área do Cliente
              </a>
            </td>
          </tr>
          <!-- Dúvidas e suporte -->
          <tr>
            <td style="padding:12px 32px 0 32px; color:#888; text-align:center; font-size:15px;">
              <span>Dúvidas? Fale com nosso time no WhatsApp: </span>
              <a href="https://wa.me/5561996258003" style="color:#0c3c71; text-decoration:none; font-weight:bold;">+55 61 99625-8003</a>
            </td>
          </tr>
          <!-- Espaço -->
          <tr><td style="height:22px;">&nbsp;</td></tr>
          <!-- Selo ou vantagem -->
          <tr>
            <td align="center" style="padding:0 32px 14px 32px;">
              <table cellpadding="0" cellspacing="0" style="width:100%; max-width:320px; margin:auto;">
                <tr>
                  <td align="center" style="color:#0c3c71; font-size:15px; font-weight:700;">
                    <img src="https://meucredvance.com.br/assets/img/favicon.png" alt="Seguro" width="28" height="28" style="vertical-align:middle;margin-right:8px;">
                    Segurança, facilidade e atendimento humano.
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <!-- Footer -->
          <tr>
            <td align="center" style="background:#0c3c71; color:#fff; padding:20px 16px; font-size:13px; border-radius:0 0 12px 12px;">
              © {{ date('Y') }} CredVance<br>
              Rua 35 Casa 101 - Setor Tradicional - São Sebastião/DF<br>
              <a href="mailto:contato@credvance.com.br" style="color:#f6c800; text-decoration:none;">contato@credvance.com.br</a> |
              <a href="https://wa.me/5561996258003" style="color:#f6c800; text-decoration:none;">WhatsApp</a>
            </td>
          </tr>
        </table>
        <!-- Tracking pixel invisível -->
        <img src="{{ route('email.opened', ['pagamentoId' => $boleto->id]) }}" alt="" width="1" height="1" style="display:none;" />
      </td>
    </tr>
  </table>
</body>
</html>
