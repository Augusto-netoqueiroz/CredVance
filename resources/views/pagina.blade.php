<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Consórcio - Cred Vance</title>
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

  <!-- Main CSS File (com paleta azul/branco) -->
  <link href="assets/css/main.css" rel="stylesheet">

  <!-- ***********************
       Botão flutuante "Cadastre-se Agora!"
       ************************ -->
  <style>
    #btnCadastroFlutuante {
      position: fixed;
      bottom: 80px;      /* antes: 20px */
      right: 20px;
      z-index: 1000;
      border-radius: 50px;
      padding: 12px 20px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      display: block;
    }
    #btnCadastroFlutuante.pulsar {
      animation: pulse 2s infinite;
    }
    @keyframes pulse {
      0%, 100% { transform: scale(1); }
      50%       { transform: scale(1.1); }
    }
  </style>
</head>

<body class="index-page">

  <!-- ======= Header ======= -->
  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

      <!-- Logo / Nome do Site -->
      <a href="#hero" class="logo d-flex align-items-center me-auto">
        <h1 class="sitename">Cred Vance</h1>
      </a>

      <!-- Menu de Navegação -->
      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" class="active">Início</a></li>
          <li><a href="#about">Sobre</a></li>
          <li><a href="#services">Planos</a></li>
          <li><a href="/Service">Simulador de Parcela</a></li>
          <li><a href="#stats">Estatísticas</a></li>
          <li><a href="#contact">Contato</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>
      <!-- Botão de Acesso ao Cliente -->
      <a class="cta-btn" href="{{ route('login') }}">Area do Cliente</a>

    </div>
  </header>
  <!-- End Header -->

  <main class="main">

    <!-- ======= Hero Section ======= -->
    <section id="hero" class="hero text-white" style="
      height: 400px;
      background: url('assets/img/hero2.png') center center no-repeat;
      background-size: cover;
      position: relative;
      overflow: hidden;
      display: flex;
      align-items: center;
    ">
      <div class="container position-relative z-2">
        <div class="row">
          <div class="col-md-6">
            <h1 class="fw-bold display-5 mb-3 text-white">💥 Multiplique seu dinheiro com a CredVance!</h1>
            <p class="fs-4 mb-1 text-white">
              Comece com parcelas de <strong>R$ 155,00</strong> e receba até
              <strong>R$ 3.672,00</strong> com juros garantidos!
            </p>
            <p class="fst-italic text-white">(12 ou 24 meses – sem taxa de adesão!)</p>
            <a href="/Service" class="btn btn-warning fw-bold text-dark px-4 py-2 rounded shadow mt-3 pulse">
              Simular Agora
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- ======= Sobre Section ======= -->
    <section id="about" class="about section light-background">
      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
            <h3>Sobre a Cred Vance</h3>
            <img src="assets/img/about.jpg" class="img-fluid rounded-4 mb-4" alt="">
            <p>
              Somos especialistas em consórcio e ajudamos você a planejar
              e conquistar o seu bem sem pagar juros, oferecendo planos
              flexíveis e atendimento personalizado.
            </p>
          </div>
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="250">
            <div class="content">
              <p class="fst-italic">
                Conquistar seu carro, moto, imóvel ou serviço é mais fácil
                quando você conta com o consórcio certo.
              </p>
              <ul>
                <li><i class="bi bi-check-circle-fill"></i> Planos acessíveis</li>
                <li><i class="bi bi-check-circle-fill"></i> Sem taxa de adesão</li>
                <li><i class="bi bi-check-circle-fill"></i> Equipe dedicada 24h</li>
              </ul>
              <p>
                Conte com a Cred Vance para tornar seus projetos realidade. 
                Quer saber mais? Assista ao vídeo ou fale conosco!
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- End Sobre Section -->

    <!-- ======= Planos Section ======= -->
    <section id="services" class="services section light-background">
      <div class="container section-title" data-aos="fade-up">
        <h2>Planos</h2>
        <p>Confira nossas principais opções de consórcio</p>
      </div>

      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row gy-5">
          <div class="col-xl-4 col-md-6" data-aos="zoom-in" data-aos-delay="200">
            <div class="service-item" onclick="window.location.href='/Service';" style="cursor: pointer;">
              <div class="img">
                <img src="assets/img/services-1.jpg" class="img-fluid" alt="">
              </div>
              <div class="details position-relative">
                <div class="icon">
                  <i class="bi bi-activity"></i>
                </div>
                <h3>Simulador de Parcela</h3>
                <p>Simule já a sua parcela de forma Fácil e Rápida.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- End Planos Section -->

    <!-- ======= Estatísticas Section ======= -->
    <section id="stats" class="stats section dark-background">
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row gy-4">
          <div class="col-lg-3 col-md-6">
            <div class="stats-item d-flex align-items-center w-100 h-100">
              <i class="bi bi-emoji-smile flex-shrink-0"></i>
              <div>
                <span data-purecounter-start="0" data-purecounter-end="232" data-purecounter-duration="1" class="purecounter"></span>
                <p>Clientes Satisfeitos</p>
              </div>
            </div>
          </div>
          <!-- … demais itens … -->
        </div>
      </div>
    </section>
    <!-- End Estatísticas Section -->

    <!-- ======= Contato Section ======= -->
    <section id="contact" class="contact section dark-background">
      <div class="container section-title" data-aos="fade-up">
        <h2>Contato</h2>
        <p>Fale conosco para tirar suas dúvidas ou solicitar mais informações</p>
      </div>
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row gy-4">
          <div class="col-lg-6">
            <div class="info-item d-flex flex-column justify-content-center align-items-center">
              <i class="bi bi-geo-alt"></i>
              <h3>Endereço</h3>
              <p>Rua 35 Casa 101 Setor Tradicional - São Sebastião DF</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="info-item d-flex flex-column justify-content-center align-items-center">
              <i class="bi bi-telephone"></i>
              <h3>Telefone</h3>
              <p>+55 61 99625-8003</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="info-item d-flex flex-column justify-content-center align-items-center">
              <i class="bi bi-envelope"></i>
              <h3>Email</h3>
              <p>contato@credvance.com.br</p>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- End Contato Section -->

  </main>

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer dark-background">
    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="#hero" class="logo d-flex align-items-center">
            <span class="sitename">Cred Vance</span>
          </a>
          <p>Rua 35 Casa 101 Setor Tradicional<br>São Sebastião DF</p>
          <p><strong>Telefone:</strong> +55 61 99625-8003</p>
          <p><strong>Email:</strong> contato@credvance.com.br</p>
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
            <li><i class="bi bi-chevron-right"></i> <a href="#hero">Início</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#about">Sobre</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#services">Planos</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#stats">Estatísticas</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#contact">Contato</a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="container copyright text-center mt-4">
      <p>&copy; <strong class="sitename">Cred Vance</strong>. Todos os direitos reservados.</p>
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

  <!-- Botão flutuante -->
  <a href="{{ route('register') }}" id="btnCadastroFlutuante" class="btn btn-primary pulsar">
    Cadastre-se Agora!
  </a>

</body>
</html>
