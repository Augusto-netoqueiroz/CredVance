<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Planos & Simulação - Cred Vance</title>
  <meta name="description" content="Consórcio CredVance: parcelas decrescentes com retorno garantido">
  <meta name="keywords" content="consórcio, simulação, parcelas, CredVance">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <!-- Ajustes de responsividade e estilo específico -->
  <style>
    /* Tornar bordas das tabelas arredondadas */
    #service-details table {
      border-collapse: separate !important;
      border-spacing: 0;
      border-radius: 0.5rem;
      overflow: hidden;
    }

    /* Padding e tamanho reduzido */
    #service-details .table-sm th,
    #service-details .table-sm td {
      padding: 0.3rem;
      color: #000 !important; /* texto preto, mesmo em fundo escuro */
    }
    #service-details .table-sm {
      font-size: 0.9rem;
    }

    /* Cabeçalhos da seção */
    #service-details h3 {
      font-size: 1.1rem;
      margin-bottom: 0.5rem;
    }

    /* Inputs e botões pequenos */
    #service-details .form-control-sm {
      font-size: 0.9rem;
      padding: 0.25rem 0.5rem;
    }
    #service-details .btn-light {
      background-color: #f8f9fa;
      border: 1px solid #ddd;
      color: #000;
    }

    /* Corrige texto do resultado da simulação */
    #resultadoSimulacao,
    #resultadoSimulacao th,
    #resultadoSimulacao td,
    #resultadoSimulacao p {
      color: #000 !important;
    }

    @media (max-width: 575.98px) {
      #service-details .row > div {
        margin-bottom: 1rem;
      }
    }
  </style>
</head>

<body class="index-page">

  <!-- ======= Header ======= -->
  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">
      <a href="/pagina#hero" class="logo d-flex align-items-center me-auto">
        <h1 class="sitename">Cred Vance</h1>
      </a>
      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="/pagina#hero">Início</a></li>
          <li><a href="/pagina#about">Sobre</a></li>
          <li><a href="/pagina#services">Planos</a></li>
          <li><a href="/pagina#simulacao">Simulador de Parcelas</a></li>
          <li><a href="/pagina#stats">Estatísticas</a></li>
          <li><a href="/pagina#contact">Contato</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>
      <a class="cta-btn" href="{{ route('login') }}">Área do Cliente</a>
    </div>
  </header>
  <!-- End Header -->

  <main class="main">
    <!-- ======= Page Title ======= -->
    <div class="page-title dark-background" id="simulacao" data-aos="fade"
         style="background-image: url('assets/img/page-title-bg.webp');">
      <div class="container position-relative">
        <h1>Planos &amp; Simulação</h1>
        <p>Entenda nosso modelo de consórcio e simule quantas cotas desejar.</p>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.html">Home</a></li>
            <li class="current">Planos &amp; Simulação</li>
          </ol>
        </nav>
      </div>
    </div>
    <!-- End Page Title -->

    <!-- ======= Planos & Simulação Section ======= -->
    <section id="service-details" class="service-details section dark-background">
      <div class="container" data-aos="fade-up">
        <div class="row mb-5 align-items-start">
          <!-- Tabela de Parcelas -->
          <div class="col-12 col-md-6 mb-4 mb-md-0">
            <h3>Tabela de Parcelas por Cota</h3>
            <p class="small">
              Cada cota possui 12 parcelas mensais decrescentes de <strong>R$155,00</strong> até
              <strong>R$100,00</strong> (redução de R$5,00 por mês). Ao final, o total pago por cota
              é <strong>R$1.530,00</strong> e o retorno garantido é 12× a primeira parcela:
              <strong>R$1.860,00</strong>.
            </p>
            <table class="table table-bordered table-sm">
              <thead>
                <tr><th>Mês</th><th>Valor (R$)</th></tr>
              </thead>
              <tbody>
                <tr><td>1</td><td>155,00</td></tr>
                <tr><td>2</td><td>150,00</td></tr>
                <tr><td>3</td><td>145,00</td></tr>
                <tr><td>4</td><td>140,00</td></tr>
                <tr><td>5</td><td>135,00</td></tr>
                <tr><td>6</td><td>130,00</td></tr>
                <tr><td>7</td><td>125,00</td></tr>
                <tr><td>8</td><td>120,00</td></tr>
                <tr><td>9</td><td>115,00</td></tr>
                <tr><td>10</td><td>110,00</td></tr>
                <tr><td>11</td><td>105,00</td></tr>
                <tr><td>12</td><td>100,00</td></tr>
              </tbody>
              <tfoot>
                <tr><th>Total Pago</th><td>1.530,00</td></tr>
                <tr><th>Retorno Final</th><td>1.860,00</td></tr>
              </tfoot>
            </table>
          </div>

          <!-- Simulador de Parcelas -->
          <div class="col-12 col-md-6">
            <h3>Simulador de Parcelas</h3>
            <form id="consorcioForm" onsubmit="event.preventDefault(); calcularParcela();">
              <div class="mb-2">
                <label for="quantidadeCotas" class="form-label small">Quantidade de Cotas</label>
                <input type="number"
                       class="form-control form-control-sm"
                       id="quantidadeCotas"
                       placeholder="Ex: 2"
                       min="1"
                       required>
              </div>
              <button type="submit" class="btn btn-light btn-sm w-100">Calcular</button>
            </form>
            <div class="mt-3 p-2 border bg-light" id="resultadoSimulacao"
                 style="display: none; font-size: 0.9rem;"></div>
          </div>
        </div>
      </div>
    </section>
    <!-- End Planos & Simulação Section -->
  </main>
  <!-- End main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer dark-background">
    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="index.html" class="logo d-flex align-items-center">
            <span class="sitename">Cred Vance</span>
          </a>
          <div class="footer-contact pt-3">
            <p>R. Exemplo, 123<br>São Paulo/SP</p>
            <p class="mt-3"><strong>Telefone:</strong> +55 11 99999-9999</p>
            <p><strong>Email:</strong> contato@credvance.com.br</p>
          </div>
          <div class="social-links d-flex mt-4">
            <a href="#"><i class="bi bi-twitter"></i></a>
            <a href="#"><i class="bi bi-facebook"></i></a>
            <a href="#"><i class="bi bi-instagram"></i></a>
            <a href="#"><i class="bi bi-linkedin"></i></a>
          </div>
        </div>
        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Links Úteis</h4>
          <ul>
            <li><i class="bi bi-chevron-right"></i> <a href="index.html#hero">Início</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="index.html#about">Sobre</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="index.html#services">Planos</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="index.html#stats">Estatísticas</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="index.html#contact">Contato</a></li>
          </ul>
        </div>
        <div class="col-lg-6 col-md-12 footer-newsletter">
          <h4>Newsletter</h4>
          <p>Receba nossas novidades e ofertas exclusivas diretamente no seu email!</p>
          <form action="forms/newsletter.php" method="post" class="php-email-form">
            <div class="newsletter-form">
              <input type="email" name="email" placeholder="Seu email">
              <input type="submit" value="Assinar">
            </div>
            <div class="loading">Enviando...</div>
            <div class="error-message"></div>
            <div class="sent-message">Sua inscrição foi realizada com sucesso!</div>
          </form>
        </div>
      </div>
    </div>
    <div class="container copyright text-center mt-4">
      <p>
        &copy; <strong class="px-1 sitename">Cred Vance</strong>
        Todos os direitos reservados.
      </p>
      <div class="credits">
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
        Distributed by <a href="https://themewagon.com">ThemeWagon</a>
      </div>
    </div>
  </footer>
  <!-- End Footer -->

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
  </a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>

  <!-- Script de Cálculo de Parcelas -->
  <script>
    function calcularParcela() {
      const q = parseInt(document.getElementById('quantidadeCotas').value) || 1;
      const a1 = 155, an = 100, n = 12;
      const step = (a1 - an) / (n - 1);
      let totalPago = 0;
      let html = `<h5 class="small">Simulação de Parcelas</h5>
                  <table class="table table-sm">
                    <thead>
                      <tr><th>Mês</th><th>Por Cota (R$)</th><th>Total (R$)</th></tr>
                    </thead><tbody>`;
      for (let i = 1; i <= n; i++) {
        const valorPorCota = a1 - (i - 1) * step;
        const valorTotal   = valorPorCota * q;
        totalPago += valorTotal;
        html += `<tr><td>${i}</td><td>${valorPorCota.toFixed(2)}</td><td>${valorTotal.toFixed(2)}</td></tr>`;
      }
      html += `</tbody></table>`;
      const retornoFinal = a1 * n * q;
      html += `<p class="small"><strong>Total Pago:</strong> R$ ${totalPago.toFixed(2)}</p>`;
      html += `<p class="small"><strong>Retorno Final:</strong> R$ ${retornoFinal.toFixed(2)}</p>`;
      const res = document.getElementById('resultadoSimulacao');
      res.innerHTML = html;
      res.style.display = 'block';
    }
  </script>

  <!-- Script para iniciar AOS e remover o preloader -->
  <script>
    if (window.AOS) AOS.init({ duration: 800, once: true });
    window.addEventListener('load', function() {
      const pre = document.getElementById('preloader');
      if (pre) pre.remove();
    });
  </script>

</body>
</html>
