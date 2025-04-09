<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Planos & Simulação - Cred Vance</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&family=Raleway:wght@100;200;300;400;500;600;700;800;900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">
</head>

<body class="index-page">

  <!-- ======= Header ======= -->
  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

      <!-- Logo / Nome do Site -->
      <a href="/pagina#hero" class="logo d-flex align-items-center me-auto">
        <h1 class="sitename">Cred Vance</h1>
      </a>

      <!-- Menu de Navegação -->
      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="/pagina#hero">Início</a></li>
          <li><a href="/pagina#about">Sobre</a></li>
          <li><a href="/pagina#services">Planos</a></li>
          <li><a href="/Service">Simulador de Parcela</a></li>
          <li><a href="/pagina#stats">Estatísticas</a></li>
          <li><a href="/pagina#contact">Contato</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>
      <!-- Botão de Acesso ao Cliente -->
      <a class="cta-btn" href="{{ route('login') }}">Area do Cliente</a>

    </div>
  </header>
  <!-- End Header -->

  <main class="main">

    <!-- ======= Page Title / Breadcrumbs (Fundo escuro) ======= -->
    <div class="page-title dark-background" data-aos="fade"
      style="background-image: url('assets/img/page-title-bg.webp');">
      <div class="container position-relative">
        <h1>Planos &amp; Simulação</h1>
        <p>Calcule sua parcela de consórcio e confira os planos disponíveis.</p>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.html">Home</a></li>
            <li class="current">Planos &amp; Simulação</li>
          </ol>
        </nav>
      </div>
    </div>
    <!-- End Page Title -->

    <!-- ======= Service (Planos) Details Section ======= -->
    <section id="service-details" class="service-details section dark-background">
      <div class="container" data-aos="fade-up">

        <div class="row gy-4">

          <!-- Simulador de Parcelas -->
          <div class="col-lg-4">
            <h3>Simulador de Parcelas</h3>
            <p>
              Preencha os campos abaixo para ter uma estimativa da parcela mensal
              do seu consórcio. (Exemplo simples, apenas para demonstração)
            </p>
            
            <!-- Formulário de Simulação -->
            <form id="consorcioForm" onsubmit="event.preventDefault(); calcularParcela();">
              <div class="mb-3">
                <label for="valorBem" class="form-label">Valor do Bem (R$)</label>
                <input type="number" class="form-control" id="valorBem" placeholder="Ex: 50.000" required>
              </div>
              <div class="mb-3">
                <label for="prazoMeses" class="form-label">Prazo (meses)</label>
                <input type="number" class="form-control" id="prazoMeses" placeholder="Ex: 60" required>
              </div>
              <div class="mb-3">
                <label for="taxaAdministracao" class="form-label">Taxa Administração (%)</label>
                <input type="number" class="form-control" id="taxaAdministracao" placeholder="Ex: 20" required>
              </div>
              
              <button type="submit" class="btn btn-get-started mt-2">Calcular</button>
            </form>

            <!-- Exibição do resultado -->
            <div class="mt-4 p-3 border" id="resultadoSimulacao" style="display: none;">
              <h5>Resultado da Simulação</h5>
              <p class="mb-0" id="valorParcela"></p>
            </div>

          </div>
          <!-- End Left Column (Simulador) -->

          <!-- Planos Disponíveis -->
          <div class="col-lg-8">
            <h3>Planos Disponíveis</h3>
            <p>
              Selecione um plano que atenda às suas necessidades. Temos opções para diversos valores e prazos,
              sempre com taxas competitivas e suporte especializado.
            </p>
            <ul>
              <li><i class="bi bi-check-circle"></i> <span>Plano Carro - até 60 meses</span></li>
              <li><i class="bi bi-check-circle"></i> <span>Plano Moto - até 48 meses</span></li>
              <li><i class="bi bi-check-circle"></i> <span>Plano Imóvel - até 180 meses</span></li>
              <li><i class="bi bi-check-circle"></i> <span>Plano Serviços (Viagem, Reforma etc.)</span></li>
            </ul>
            <p>
              Entre em contato para definir o melhor prazo e valor de crédito e
              conquiste seu sonho sem pagar juros, contando apenas com a taxa de administração.
            </p>

            <img src="assets/img/services.jpg" alt="" class="img-fluid services-img mt-4">
          </div>
          <!-- End Right Column (Planos) -->

        </div><!-- /.row -->

      </div><!-- /.container -->
    </section>
    <!-- End Service Details Section -->

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

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

  <!-- Script de Cálculo de Parcelas (exemplo simples) -->
  <script>
    function calcularParcela() {
      const valorBem = parseFloat(document.getElementById('valorBem').value) || 0;
      const prazo = parseFloat(document.getElementById('prazoMeses').value) || 1;
      const taxaAdm = parseFloat(document.getElementById('taxaAdministracao').value) || 0;

      // Exemplo simples de cálculo:
      // parcelaBase = valorBem / prazo
      // acrescimoTaxa = (valorBem * (taxaAdm / 100)) / prazo
      // parcelaFinal = parcelaBase + acrescimoTaxa
      const parcelaBase = valorBem / prazo;
      const acrescimoTaxa = (valorBem * (taxaAdm / 100)) / prazo;
      const parcelaFinal = parcelaBase + acrescimoTaxa;

      const resultado = document.getElementById('resultadoSimulacao');
      const valorParcela = document.getElementById('valorParcela');
      valorParcela.innerHTML = `Parcela aproximada: R$ ${parcelaFinal.toFixed(2)}`;
      resultado.style.display = 'block';
    }
  </script>

</body>
</html>
