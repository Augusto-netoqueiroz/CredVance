<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Cred Vance - Consórcio Premium</title>
  <meta name="description" content="Consórcio moderno, sem taxa de adesão. Multiplique seu dinheiro com a CredVance.">
  <meta name="keywords" content="consórcio, simulação, credvance, fintech, planos, cotas">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts & Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <!-- Bootstrap & Animations -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/css/main.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Roboto', Arial, sans-serif;
      background: #f5f8ff;
      margin: 0;
      color: #22326b;
      scroll-behavior: smooth;
    }
    .header {
      background: #fff;
      border-bottom: 1px solid #e0e4f0;
      box-shadow: 0 2px 12px -8px #22326b11;
      position: fixed; width: 100%; z-index: 100;
      transition: box-shadow .22s;
    }
    .header .container-xl {
      display: flex; align-items: center; justify-content: space-between; min-height: 66px;
    }
    .logo h1 {
      font-weight: 900; color: #22326b; margin: 0; font-size: 2.1rem; letter-spacing: -1.5px;
    }
    .navmenu ul {
      display: flex; gap: 24px; list-style: none; margin: 0; padding: 0;
    }
    .navmenu a {
      color: #22326b; font-weight: 500; text-decoration: none; padding: 8px 0; transition: color .18s;
    }
    .navmenu a:hover, .navmenu a.active {
      color: #3470fa;
      border-bottom: 2px solid #3470fa;
    }
    .cta-btn {
      background: #3470fa;
      color: #fff;
      border-radius: 24px;
      padding: 9px 28px;
      font-weight: 700;
      margin-left: 24px;
      transition: background .14s;
      box-shadow: 0 2px 16px -10px #3470fa66;
      border: none;
      text-decoration: none;
    }
    .cta-btn:hover { background: #22326b; color: #fff; }
    .mobile-nav-toggle { display: none; font-size: 2rem; color: #3470fa; }
    @media (max-width: 900px) {
      .navmenu ul { display: none; }
      .mobile-nav-toggle { display: block; }
      .header .container-xl { flex-wrap: wrap; }
    }

    /* Hero - bloco azul, fundo com curva, imagem à direita */
    .hero-block {
      background: linear-gradient(120deg, #3470fa 65%, #22326b 100%);
      min-height: 550px; width: 100%;
      display: flex; align-items: center; justify-content: center;
      position: relative; padding-top: 80px; padding-bottom: 48px;
      color: #fff; overflow: hidden;
      border-radius: 0 0 60px 60px / 0 0 35px 35px;
    }
    .hero-block .container {
      display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap;
    }
    .hero-content {
      max-width: 540px; z-index: 2;
    }
    .hero-title {
      font-size: 2.7rem; font-weight: 900; margin-bottom: 18px; line-height: 1.07;
      text-shadow: 0 3px 18px #11226626;
    }
    .hero-highlight {
      background: #fff; color: #3470fa; border-radius: .35em; padding: 0 11px; font-weight: 900;
    }
    .hero-content p {
      font-size: 1.19rem; margin-bottom: 8px;
      color: #e6f2ff;
    }
    .hero-content .fst-italic {
      color: #d3e3fd;
      font-size: 1.01rem;
    }
    .hero-buttons { display: flex; gap: 14px; margin-top: 32px;}
    .btn-simular {
      background: #fff; color: #3470fa; font-weight: 800; border: none;
      font-size: 1.13rem; border-radius: 2.2rem; padding: 12px 32px;
      box-shadow: 0 3px 18px -6px #3470fa99;
      animation: pulse 1.3s infinite alternate;
      outline: 2px solid #ffffff44;
      transition: all .14s;
      display: flex; align-items: center; gap: 8px;
    }
    @keyframes pulse {
      to { box-shadow: 0 0 16px 3px #3470fa77; transform: scale(1.025);}
      from { box-shadow: 0 0 0 0 #3470fa00;}
    }
    .btn-simular:hover { background: #e6eeff; color: #22326b;}
    .btn-outline {
      background: transparent; color: #fff; border: 2px solid #fff;
      border-radius: 2.2rem; font-weight: 700; font-size: 1.09rem; padding: 12px 30px;
      display: flex; align-items: center; gap: 8px;
      transition: background .15s, color .15s;
    }
    .btn-outline:hover { background: #ffffff22;}
    .hero-img {
      width: 400px; max-width: 96vw; margin-left: 36px; border-radius: 1.5rem;
      box-shadow: 0 8px 40px -12px #22326b44;
      z-index: 2;
    }
    @media (max-width: 1100px) {
      .hero-block .container { flex-direction: column-reverse; }
      .hero-img { margin: 0 0 32px 0; }
      .hero-content { max-width: 100%; text-align: center; }
      .hero-buttons { justify-content: center; }
    }

    /* Blocos conectados estilo one page */
    .one-block {
      background: #fff;
      margin: -40px auto 0 auto;
      border-radius: 36px;
      max-width: 1200px;
      box-shadow: 0 2px 22px -12px #3470fa33;
      padding: 64px 36px 48px 36px;
      position: relative;
      z-index: 10;
    }
    .about-block {
      margin-top: -32px;
      padding-top: 80px;
    }
    .services-block {
      margin-top: -36px;
      padding-top: 64px;
      background: linear-gradient(180deg,#f5f8ff 80%,#e9f2fd 100%);
    }
    .stats-block {
      margin-top: -36px;
      background: linear-gradient(110deg, #3470fa 80%, #22326b 100%);
      color: #fff;
      border-radius: 32px;
      box-shadow: 0 5px 22px -11px #22326b44;
    }
    .contact-block {
      margin-top: -36px;
      padding-bottom: 64px;
    }

    /* Footer */
    .footer { background: #22326b; color: #fff; border-radius: 1.2rem 1.2rem 0 0; margin-top: 32px; }
    .footer .sitename { color: #3470fa;}
    .footer .social-links a { background: #fff2; border-radius: 50%; width: 34px; height: 34px; display: flex; align-items: center; justify-content: center; margin-right: 7px;}
    .footer .social-links a:hover { background: #3470fa; color: #fff;}
    .scroll-top { position: fixed; bottom: 22px; right: 22px; background: #3470fa; color: #fff; border-radius: 50%; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; font-size: 2rem; z-index: 99;}
    #preloader { display: none;}
  </style>
</head>

<body>
  <!-- Header -->
  <header class="header d-flex align-items-center fixed-top">
    <div class="container-xl position-relative d-flex align-items-center">
      <a href="#hero" class="logo d-flex align-items-center me-auto">
        <h1 class="sitename">Cred Vance</h1>
      </a>
      <nav class="navmenu">
        <ul>
          <li><a href="#hero" class="active">Início</a></li>
          <li><a href="#about" >Sobre</a></li>
          <li><a href="#services">Planos</a></li>
          <li><a href="/Service">Simulador</a></li>
          <li><a href="#stats">Estatísticas</a></li>
          <li><a href="#contact">Contato</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none fas fa-bars"></i>
      </nav>
      <a class="cta-btn ms-3" href="{{ route('login') }}">Área do Cliente</a>
    </div>
  </header>

  <!-- Hero: bloco só -->
  <section id="hero" class="hero-block">
    <div class="container">
      <div class="hero-content" data-aos="fade-right">
        <h1 class="hero-title">
          💥 Multiplique seu dinheiro com <span class="hero-highlight">a CredVance!</span>
        </h1>
        <p>
          Comece com parcelas de <strong>R$ 155,00</strong> e receba até <strong>R$ 3.672,00</strong> com juros garantidos!
        </p>
        <p class="fst-italic mb-3">(12 ou 24 meses – sem taxa de adesão!)</p>
        <div class="hero-buttons">
          <a href="/Service" class="btn btn-simular shadow">
            <i class="fas fa-calculator"></i> Simular Agora
          </a>
          <a href="#services" class="btn btn-outline">
            <i class="fas fa-list"></i> Ver Planos
          </a>
        </div>
      </div>
      <img src="assets/img/hero2.png" alt="Simulação Consórcio CredVance" class="hero-img" data-aos="fade-left" loading="lazy">
    </div>
  </section>

  <main>
    <!-- About block -->
    <section id="about" class="one-block about-block" data-aos="fade-up">
      <div class="row align-items-center">
        <div class="col-lg-6 mb-4 mb-lg-0">
          <h2 class="fw-bold mb-4" style="color: #3470fa;">Sobre a Cred Vance</h2>
          <p style="font-size:1.1rem;">Especialistas em consórcio moderno: planeje, conquiste, multiplique seu dinheiro e atenda seus sonhos de forma transparente.</p>
          <ul class="list-unstyled mt-3 mb-3">
            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Planos acessíveis</li>
            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Sem taxa de adesão</li>
            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Atendimento 100% digital</li>
          </ul>
          <p class="fst-italic">Consórcio para carro, imóvel, serviços, viagens, reformas – escolha o seu projeto!</p>
        </div>
        <div class="col-lg-6">
          <img src="assets/img/about.jpg" class="img-fluid rounded-4 shadow-sm" alt="Sobre a Cred Vance">
        </div>
      </div>
    </section>

    <!-- Services/Planos block -->
    <section id="services" class="one-block services-block" data-aos="fade-up">
      <div class="text-center mb-5">
        <h2 class="fw-bold" style="color: #3470fa;">Planos</h2>
        <p class="lead" style="color:#22326b;opacity:.77;">Escolha seu plano e comece a investir hoje mesmo.</p>
      </div>
      <div class="row gy-4 justify-content-center">
        <div class="col-md-5 col-lg-4">
          <div class="card shadow-lg border-0 h-100 text-center py-4 px-3 rounded-4">
            <div class="card-body">
              <h5 class="fw-bold mb-2" style="color:#22326b;">Plano 12 meses</h5>
              <p class="mb-2">12 parcelas decrescentes<br>De <strong>R$155,00</strong> até <strong>R$100,00</strong></p>
              <div class="mb-2"><span class="badge bg-primary fs-6">Juros 16%</span></div>
              <a href="/Service" class="btn btn-outline-primary rounded-pill mt-2">
                <i class="fas fa-calculator"></i> Simular
              </a>
            </div>
          </div>
        </div>
        <div class="col-md-5 col-lg-4">
          <div class="card shadow-lg border-0 h-100 text-center py-4 px-3 rounded-4">
            <div class="card-body">
              <h5 class="fw-bold mb-2" style="color:#22326b;">Plano 24 meses</h5>
              <p class="mb-2">24 parcelas decrescentes<br>De <strong>R$155,00</strong> até <strong>R$100,00</strong></p>
              <div class="mb-2"><span class="badge bg-primary fs-6">Juros 20%</span></div>
              <a href="/Service" class="btn btn-outline-primary rounded-pill mt-2">
                <i class="fas fa-calculator"></i> Simular
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Estatísticas block -->
    <section id="stats" class="one-block stats-block" data-aos="fade-up">
      <div class="row gy-4 justify-content-center text-center">
        <div class="col-md-4">
          <div class="d-flex align-items-center justify-content-center mb-3">
            <i class="fas fa-smile-beam fa-2x me-2"></i>
            <span class="fw-bold fs-3 purecounter" data-purecounter-start="0" data-purecounter-end="232" data-purecounter-duration="1"></span>
          </div>
          <p class="mb-0">Clientes Satisfeitos</p>
        </div>
        <div class="col-md-4">
          <div class="d-flex align-items-center justify-content-center mb-3">
            <i class="fas fa-hand-holding-usd fa-2x me-2"></i>
            <span class="fw-bold fs-3 purecounter" data-purecounter-start="0" data-purecounter-end="1200" data-purecounter-duration="1"></span>
          </div>
          <p class="mb-0">Planos Contratados</p>
        </div>
        <div class="col-md-4">
          <div class="d-flex align-items-center justify-content-center mb-3">
            <i class="fas fa-headset fa-2x me-2"></i>
            <span class="fw-bold fs-3">24h</span>
          </div>
          <p class="mb-0">Suporte Digital</p>
        </div>
      </div>
    </section>

    <!-- Contato block -->
    <section id="contact" class="one-block contact-block" data-aos="fade-up">
      <div class="text-center mb-4">
        <h2 class="fw-bold" style="color: #3470fa;">Contato</h2>
        <p class="lead">Fale conosco e tire suas dúvidas!</p>
      </div>
      <div class="row gy-4 justify-content-center">
        <div class="col-md-4">
          <div class="d-flex flex-column align-items-center">
            <i class="fas fa-map-marker-alt fa-2x mb-2"></i>
            <h5 class="mb-1">Endereço</h5>
            <p class="mb-0">Rua 35 Casa 101 Setor Tradicional<br>São Sebastião DF</p>
          </div>
        </div>
        <div class="col-md-3">
          <div class="d-flex flex-column align-items-center">
            <i class="fas fa-phone-alt fa-2x mb-2"></i>
            <h5 class="mb-1">Telefone</h5>
            <p class="mb-0">+55 61 99625-8003</p>
          </div>
        </div>
        <div class="col-md-3">
          <div class="d-flex flex-column align-items-center">
            <i class="fas fa-envelope fa-2x mb-2"></i>
            <h5 class="mb-1">Email</h5>
            <p class="mb-0">contato@meucredvance.com.br</p>
          </div>
        </div>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <footer class="footer mt-0 pt-3 pb-2">
    <div class="container">
      <div class="row align-items-center gy-2">
        <div class="col-md-6 text-md-start text-center">
          <span class="sitename fw-bold">Cred Vance</span> © 2024. Todos os direitos reservados.
        </div>
        <div class="col-md-6 text-md-end text-center">
          <div class="social-links d-inline-flex">
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-facebook"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-linkedin"></i></a>
          </div>
        </div>
      </div>
    </div>
  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center" title="Topo">
    <i class="fas fa-arrow-up"></i>
  </a>
  <div id="preloader"></div>

  <!-- JS -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script>
    if (window.AOS) AOS.init({ duration: 900, once: true });
    if (window.PureCounter) new PureCounter();
    window.addEventListener('load', function() {
      const pre = document.getElementById('preloader');
      if (pre) pre.remove();
    });
  </script>
</body>
</html>
