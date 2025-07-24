<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CredVance - Consórcio Moderno</title>

<meta name="description" content="Simule, contrate e gerencie seu consórcio online com praticidade, segurança e planos flexíveis. Parcelas a partir de R$ 155,00. Sem taxa de adesão!">
  <meta name="keywords" content="consórcio, consórcio online, consórcio fácil, credvance, simulação de consórcio, parcelamento, investimento">

  <!-- Favicons (URL Absoluta para o Google) -->
  <link rel="icon" href="https://meucredvance.com.br/favicon.ico" type="image/x-icon">
  <link rel="icon" type="image/png" href="https://meucredvance.com.br/assets/img/favicon.png">
  <link rel="apple-touch-icon" href="https://meucredvance.com.br/assets/img/apple-touch-icon.png">
  <meta name="msapplication-TileImage" content="https://meucredvance.com.br/assets/img/favicon.png">

  <!-- Open Graph (para preview em redes sociais) -->
  <meta property="og:title" content="Consórcio - Cred Vance">
  <meta property="og:description" content="Simule, contrate e gerencie seu consórcio online com praticidade, segurança e planos flexíveis. Parcelas a partir de R$ 155,00. Sem taxa de adesão!">
  <meta property="og:image" content="https://meucredvance.com.br/images/og-image.jpg"> 
  <meta property="og:url" content="https://meucredvance.com.br/">
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="CredVance">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Consórcio - Cred Vance">
  <meta name="twitter:description" content="Simule, contrate e gerencie seu consórcio online com praticidade, segurança e planos flexíveis. Parcelas a partir de R$ 155,00. Sem taxa de adesão!">
  <meta name="twitter:image" content="https://meucredvance.com.br/images/og-image.jpg">

    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/credvance-styles.css">
</head>
<body>
    <!-- Header/Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3 text-gradient" href="#inicio">CredVance</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="#inicio">Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="#sobre">Sobre</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="#simulador">Simulador</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="#planos">Planos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="#contato">Contato</a>
                    </li>
                </ul>
                
                <div class="d-flex gap-2">
                    <a class="btn btn-outline-primary" href="{{ route('login') }}">Área do Cliente</a>  
                   <a href="{{ route('register') }}" class="btn btn-gradient">Cadastre-se</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
<section id="inicio" class="hero-section position-relative overflow-hidden">
  <img src="assets/images/hero2.png" alt="Robô CredVance" class="hero-bg-full">

  <div class="container position-relative">
    <div class="row align-items-center min-vh-100 py-5">
      <div class="col-lg-6 text-white slide-in-left">
       <span class="badge badge-custom mb-4">
            <i class="bi bi-lightning-fill"></i>
            Sem Taxa de Adesão
            </span>

        <h1 class="display-3 fw-bold mb-4 lh-1">
          <span class="text-warning">💥 Multiplique</span><br>
          seu dinheiro com<br>
          <span class="text-white">CredVance!</span>
        </h1>

        <p class="lead mb-4">
          <strong class="text-warning">Conte com a CredVance</strong> e tenha 
          <strong class="text-white">rendimento de até 20%</strong> no seu investimento!
        </p>

        <p class="text-white-50 mb-4 fst-italic fs-5">
          <i class="bi bi-check-circle-fill text-success me-2"></i>
          <strong class="text-white">Contrate quantas cotas quiser</strong> - sem limitações!
        </p>

        <p class="text-white-50 mb-4">
          Comece com parcelas de <strong class="text-warning">R$ 155,00</strong> e receba até
          <strong class="text-warning"> R$ 3.672,00</strong> com juros garantidos!
        </p>

        <div class="d-flex flex-column flex-sm-row gap-3 mb-4">
          <button class="btn btn-warning btn-lg px-4 py-3 fw-bold text-primary" 
                  onclick="document.getElementById('simulador').scrollIntoView({behavior: 'smooth'})">
            <i class="bi bi-calculator me-2"></i>
            Simular Agora
          </button>
          <button class="btn btn-outline-light btn-lg px-4 py-3"
                  onclick="document.getElementById('sobre').scrollIntoView({behavior: 'smooth'})">
            Saiba Mais
            <i class="bi bi-arrow-right ms-2"></i>
          </button>
        </div>

        <div class="d-flex gap-4 text-white-50">
          <div class="d-flex align-items-center">
            <i class="bi bi-check-circle-fill text-success me-2"></i>
            12 ou 24 meses
          </div>
          <div class="d-flex align-items-center">
            <i class="bi bi-shield-fill-check text-white me-2"></i>
            100% Seguro
          </div>
          <div class="d-flex align-items-center">
            <i class="bi bi-percent text-warning me-2"></i>
            Até 20% de rendimento
          </div>
        </div>
      </div>
    </div>
  </div>
</section>



    <!-- Features Section -->
    <section id="sobre" class="py-5 bg-white">
        <div class="container">
            <div class="row text-center mb-5" data-aos="fade-up">
                <div class="col-12">
                    <h2 class="display-5 fw-bold mb-4">Por que escolher a CredVance?</h2>
                    <p class="lead text-muted mx-auto" style="max-width: 600px;">
                        Oferecemos as melhores condições do mercado com total transparência e segurança
                    </p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card text-center h-100">
                        <div class="feature-icon bg-gradient-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-4">
                            <i class="bi bi-lightning-charge text-white fs-2"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Sem Taxa de Adesão</h4>
                        <p class="text-muted">Comece a investir sem custos iniciais. Transparência total desde o primeiro dia.</p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card text-center h-100">
                        <div class="feature-icon bg-gradient-success rounded-circle d-inline-flex align-items-center justify-content-center mb-4">
                            <i class="bi bi-graph-up-arrow text-white fs-2"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Rendimento Garantido</h4>
                        <p class="text-muted">Até 20% de rendimento com investimentos seguros e rentáveis.</p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card text-center h-100">
                        <div class="feature-icon bg-gradient-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-4">
                            <i class="bi bi-shield-check text-white fs-2"></i>
                        </div>
                        <h4 class="fw-bold mb-3">100% Seguro</h4>
                        <p class="text-muted">Seus investimentos protegidos com as melhores práticas de segurança.</p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-card text-center h-100">
                        <div class="feature-icon bg-gradient-info rounded-circle d-inline-flex align-items-center justify-content-center mb-4">
                            <i class="bi bi-headset text-white fs-2"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Suporte 24/7</h4>
                        <p class="text-muted">Atendimento especializado sempre que você precisar.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Companies Investment Section -->
    <section class="py-5 bg-gradient-to-r from-blue-50 to-purple-50">
        <div class="container">
            <div class="row text-center mb-5" data-aos="fade-up">
                <div class="col-12">
                    <h2 class="display-5 fw-bold mb-4">
                        <i class="bi bi-building text-primary me-3"></i>
                        Nós investimos em empresas
                    </h2>
                    <p class="lead text-muted mx-auto" style="max-width: 600px;">
                        Conheça algumas das empresas parceiras onde a CredVance investe para garantir 
                        <strong class="text-success">rendimento de até 20%</strong> para nossos clientes
                    </p>
                </div>
            </div>
            
            <!-- Companies Carousel -->
            <div class="position-relative" data-aos="fade-up" data-aos-delay="200">
                <div id="companiesCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                    <div class="carousel-inner">
                        <!-- Slide 1 -->
                        <div class="carousel-item active">
                            <div class="row g-4 align-items-center justify-content-center">
                                <div class="col-lg-2 col-md-3 col-4">
                                    <div class="company-logo-card">
                                        <img src="assets/images/IMG_2943.PNG" 
                                             alt="TechFlow" 
                                             class="img-fluid company-logo">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-3 col-4">
                                    <div class="company-logo-card">
                                        <img src="assets/images/IMG_2944.PNG" 
                                             alt="InvestPro" 
                                             class="img-fluid company-logo">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-3 col-4">
                                    <div class="company-logo-card">
                                        <img src="assets/images/IMG_2945.PNG" 
                                             alt="BuildMax" 
                                             class="img-fluid company-logo">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-3 col-4">
                                    <div class="company-logo-card">
                                        <img src="assets/images/IMG_2946.PNG" 
                                             alt="MediCare Plus" 
                                             class="img-fluid company-logo">
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        
                        <!-- Slide 2 -->
                        <div class="carousel-item">
                            <div class="row g-4 align-items-center justify-content-center">
                                <div class="col-lg-2 col-md-3 col-4">
                                    <div class="company-logo-card">
                                        <img src="assets/images/IMG_2951.PNG" 
                                             alt="MediCare Plus" 
                                             class="img-fluid company-logo">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-3 col-4">
                                    <div class="company-logo-card">
                                        <img src="assets/images/IMG_2949.PNG" 
                                             alt="ShopSmart" 
                                             class="img-fluid company-logo">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-3 col-4">
                                    <div class="company-logo-card">
                                        <img src="assets/images/IMG_2948.PNG" 
                                             alt="AutoDrive" 
                                             class="img-fluid company-logo">
                                    </div>
                                </div>
                                
                                 
                            </div>
                        </div>
                    </div>
                    
                    <!-- Carousel Controls -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#companiesCarousel" data-bs-slide="prev">
                        <div class="carousel-control-icon bg-primary rounded-circle p-3">
                            <i class="bi bi-chevron-left text-white fs-4"></i>
                        </div>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#companiesCarousel" data-bs-slide="next">
                        <div class="carousel-control-icon bg-primary rounded-circle p-3">
                            <i class="bi bi-chevron-right text-white fs-4"></i>
                        </div>
                        <span class="visually-hidden">Próximo</span>
                    </button>
                </div>
                
                <!-- Carousel Indicators -->
                <div class="d-flex justify-content-center mt-4">
                    <button type="button" data-bs-target="#companiesCarousel" data-bs-slide-to="0" class="active carousel-indicator-custom me-2" aria-current="true"></button>
                    <button type="button" data-bs-target="#companiesCarousel" data-bs-slide-to="1" class="carousel-indicator-custom"></button>
                </div>
            </div>
            
            <!-- Investment Stats -->
            <div class="row mt-5 text-center" data-aos="fade-up" data-aos-delay="400">
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-card">
                        <div class="stat-icon bg-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                            <i class="bi bi-graph-up-arrow text-white fs-3"></i>
                        </div>
                        <h3 class="fw-bold text-success counter" data-target="20">0</h3>
                        <p class="text-muted mb-0">% Rendimento Máximo</p>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-card">
                        <div class="stat-icon bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                            <i class="bi bi-building text-white fs-3"></i>
                        </div>
                        <h3 class="fw-bold text-primary counter" data-target="50">0</h3>
                        <p class="text-muted mb-0">+ Empresas Parceiras</p>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-card">
                        <div class="stat-icon bg-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                            <i class="bi bi-cash-stack text-white fs-3"></i>
                        </div>
                        <h3 class="fw-bold text-warning">R$ <span class="counter" data-target="10">0</span>M</h3>
                        <p class="text-muted mb-0">Investidos</p>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-card">
                        <div class="stat-icon bg-info rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                            <i class="bi bi-shield-check text-white fs-3"></i>
                        </div>
                        <h3 class="fw-bold text-info counter" data-target="100">0</h3>
                        <p class="text-muted mb-0">% Segurança</p>
                    </div>
                </div>
            </div>
            
            <!-- Call to Action -->
            <div class="text-center mt-5" data-aos="fade-up" data-aos-delay="600">
                <div class="bg-white rounded-4 p-4 shadow-sm d-inline-block">
                    <h4 class="fw-bold mb-3">
                        <i class="bi bi-star-fill text-warning me-2"></i>
                        Faça parte desta rede de sucesso!
                    </h4>
                    <p class="text-muted mb-3">
                        Invista conosco e tenha acesso aos melhores rendimentos do mercado
                    </p>
                    <button class="btn btn-gradient btn-lg px-5" onclick="document.getElementById('simulador').scrollIntoView({behavior: 'smooth'})">
                        <i class="bi bi-rocket-takeoff me-2"></i>
                        Começar Agora
                    </button>
                </div>
            </div>
        </div>
    </section>
        </div>
    </nav>

    <!-- Simulador -->
  <section id="simulador" class="hero-section position-relative overflow-hidden">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
        <div class="card shadow-lg border-0 bg-white bg-opacity-95">
          <div class="card-body p-4">
            <h3 class="card-title text-center mb-4 fw-bold">Simulador Rápido</h3>

            <!-- Seleção de plano -->
            <div class="mb-4 d-flex justify-content-center">
                <div class="btn-group w-100 mb-3" role="group">
            <input type="radio" class="btn-check" name="plano" id="plano12" value="12" autocomplete="off" checked>
            <label class="btn btn-outline-primary btn-sm" for="plano12">12 meses (16%)</label>

            <input type="radio" class="btn-check" name="plano" id="plano24" value="24" autocomplete="off">
            <label class="btn btn-outline-primary btn-sm" for="plano24">24 meses (20%)</label>
            </div>

            </div>

            <!-- Slider de valor -->
            <div class="mb-4">
              <label class="form-label fw-medium">
                Valor desejado: R$ <span id="valorDisplay">50.000</span>
              </label>
              <input type="range" class="form-range" id="valorRange" min="1000" max="100000" step="1000" value="1000">
            </div>

            <!-- Slider de cotas -->
            <div class="mb-4">
              <label class="form-label fw-medium">
                Quantidade de Cotas: <span id="cotasDisplay">0</span>
              </label>
              <input type="range" class="form-range" id="cotasRange" min="1" max="50" step="1" value="1">
            </div>

            <!-- Resultado -->
            <div class="bg-light rounded-3 p-4 mb-4">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted">Parcela inicial por cota:</span>
                <span class="h4 fw-bold text-primary mb-0" id="parcelaMensal">R$ 0,00</span>
              </div>
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted">Total pago (estimado):</span>
                <span class="h5 fw-bold text-dark mb-0" id="totalPago">R$ 0,00</span>
              </div>
              <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted">Retorno final:</span>
                <span class="h5 fw-bold text-success mb-0" id="totalBonus">R$ 0,00</span>
              </div>
            </div>

            <a href="{{ route('register') }}" class="btn btn-gradient w-100 btn-lg py-3">
            Contratar Agora
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Script -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const valorRange = document.getElementById('valorRange');
      const cotasRange = document.getElementById('cotasRange');
      const valorDisplay = document.getElementById('valorDisplay');
      const cotasDisplay = document.getElementById('cotasDisplay');
      const parcelaMensal = document.getElementById('parcelaMensal');
      const totalBonus = document.getElementById('totalBonus');
      const totalPago = document.getElementById('totalPago');

      let isUpdating = false;

      function getRetornoPorCota(plano) {
        return plano === 12 ? 1774.80 : 3672.00;
      }

      function updateFromCotas() {
        if (isUpdating) return;
        isUpdating = true;

        const plano = parseInt(document.querySelector('input[name="plano"]:checked')?.value || 12);
        const retornoCota = getRetornoPorCota(plano);
        const cotas = parseInt(cotasRange.value);

        const novoValor = cotas * retornoCota;

        valorRange.value = Math.min(Math.max(novoValor, 10000), 100000);
        updateSimulator();
        isUpdating = false;
      }

      function updateFromValor() {
        if (isUpdating) return;
        isUpdating = true;

        const plano = parseInt(document.querySelector('input[name="plano"]:checked')?.value || 12);
        const retornoCota = getRetornoPorCota(plano);
        const valorDesejado = parseInt(valorRange.value);

        const cotas = Math.max(1, Math.round(valorDesejado / retornoCota));
        cotasRange.value = cotas;

        updateSimulator();
        isUpdating = false;
      }

      function updateSimulator() {
        const plano = parseInt(document.querySelector('input[name="plano"]:checked')?.value || 12);
        const retornoCota = getRetornoPorCota(plano);
        const cotas = parseInt(cotasRange.value);
        const valorDesejado = parseInt(valorRange.value);

        valorDisplay.textContent = valorDesejado.toLocaleString('pt-BR');
        cotasDisplay.textContent = cotas;

        // Parcelas
        const baseParcelas = plano === 12
          ? [155,150,145,140,135,130,125,120,115,110,105,100]
          : [155,155,150,150,145,145,140,140,135,135,130,130,125,125,120,120,115,115,110,110,105,105,100,100];

        let totalPagoCalc = 0;
        const parcelasAjustadas = baseParcelas.map(p => {
          const total = p * cotas;
          totalPagoCalc += total;
          return total;
        });

        const parcelaInicial = parcelasAjustadas[0] || 0;
        const retornoFinal = retornoCota * cotas;

        parcelaMensal.textContent = `R$ ${parcelaInicial.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`;
        totalBonus.textContent = `R$ ${retornoFinal.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`;
        totalPago.textContent = `R$ ${totalPagoCalc.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`;
      }

      document.querySelectorAll('input[name="plano"]').forEach(input => {
        input.addEventListener('change', () => {
          updateFromValor(); // recalcula com novo retorno por cota
        });
      });

      valorRange.addEventListener('input', updateFromValor);
      cotasRange.addEventListener('input', updateFromCotas);

      updateFromValor();
    });
  </script>
</section>


   
 

   <!-- Plans Section -->
<!-- Plans Section -->
<section id="planos" class="py-5 bg-light">
  <div class="container">
    <div class="row text-center mb-5" data-aos="fade-up">
      <div class="col-12">
        <h2 class="display-5 fw-bold mb-4">Escolha seu plano ideal</h2>
        <p class="lead text-muted mx-auto" style="max-width: 600px;">
          Planos com parcelas decrescentes e rendimento garantido no final
        </p>
      </div>
    </div>

    <div class="row g-4 justify-content-center">
      <!-- Plano 12 Meses -->
      <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
        <div class="card h-100 shadow-sm border-0">
          <div class="card-body text-center p-4">
            <h4 class="card-title fw-bold mb-2">Plano 12 Meses</h4>
            <p class="text-muted mb-4">Rendimento fixo de <strong>16%</strong> ao final do plano</p>
            <div class="mb-4">
              <span class="display-6 fw-bold">R$ 1530,00 pagos</span><br>
              <span class="text-success fw-semibold">R$ 1774,80 de retorno</span><br>
              <small class="text-muted">1ª parcela: R$ 155,00 — última: R$ 100,00</small>
            </div>
            <ul class="list-unstyled mb-4">
              <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Parcelas decrescentes</li>
              <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Sem taxa de adesão</li>
              <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Valor final fixo garantido</li>
              <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Ideal para curto prazo</li>
            </ul>
            <a href="{{ route('register') }}" class="btn btn-dark btn-lg w-100">Contratar Agora</a>
          </div>
        </div>
      </div>

      <!-- Plano 24 Meses -->
      <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
        <div class="card h-100 shadow-lg border-0 position-relative plan-highlight">
          <div class="position-absolute top-0 start-50 translate-middle">
            <span class="badge destaque-badge fw-bold shadow-sm animate-pulse">
            <i class="bi bi-star-fill me-1"></i>
            Melhor Retorno
            </span>
          </div>
          <div class="card-body text-center p-4 pt-5">
            <h4 class="card-title fw-bold mb-2">Plano 24 Meses</h4>
            <p class="text-muted mb-4">Rendimento fixo de <strong>20%</strong> ao final do plano</p>
            <div class="mb-4">
              <span class="display-6 fw-bold">R$ 3060,00 pagos</span><br>
              <span class="text-success fw-semibold">R$ 3672,00 de retorno</span><br>
              <small class="text-muted">1ª parcela: R$ 155,00 — última: R$ 100,00</small>
            </div>
            <ul class="list-unstyled mb-4">
              <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Parcelas decrescentes</li>
              <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Sem taxa de adesão</li>
              <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Retorno superior garantido</li>
              <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Melhor para planejamento</li>
            </ul>
            <a href="{{ route('register') }}" class="btn btn-dark btn-lg w-100">Contratar Agora</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>



    <!-- Testimonials Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row text-center mb-5" data-aos="fade-up">
                <div class="col-12">
                    <h2 class="display-5 fw-bold mb-4">O que nossos clientes dizem</h2>
                    <p class="lead text-muted mx-auto" style="max-width: 600px;">
                        Mais de 1.000 clientes já realizaram seus sonhos conosco
                    </p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                            </div>
                            <p class="text-muted mb-4 fst-italic">
                                "Consegui meu carro novo em apenas 8 meses! O atendimento da CredVance é excepcional."
                            </p>
                            <div>
                                <h6 class="fw-bold mb-0">Maria Silva</h6>
                                <small class="text-muted">Empresária</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                            </div>
                            <p class="text-muted mb-4 fst-italic">
                                "Processo super simples e transparente. Recomendo para todos que querem realizar seus sonhos."
                            </p>
                            <div>
                                <h6 class="fw-bold mb-0">João Santos</h6>
                                <small class="text-muted">Engenheiro</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                            </div>
                            <p class="text-muted mb-4 fst-italic">
                                "Finalmente consegui minha casa própria! A CredVance tornou meu sonho realidade."
                            </p>
                            <div>
                                <h6 class="fw-bold mb-0">Ana Costa</h6>
                                <small class="text-muted">Professora</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contato" class="py-5 bg-dark text-white">
        <div class="container">
            <div class="row text-center mb-5" data-aos="fade-up">
                <div class="col-12">
                    <h2 class="display-5 fw-bold mb-4">Entre em contato</h2>
                    <p class="lead text-white-50 mx-auto" style="max-width: 600px;">
                        Nossa equipe está pronta para ajudar você a realizar seus sonhos
                    </p>
                </div>
            </div>
            
            <div class="row g-5">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-primary rounded-3 p-3 me-4">
                            <i class="bi bi-telephone-fill fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">Telefone</h5>
                            <p class="text-white-50 mb-0">+55 61 99625-8003</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-primary rounded-3 p-3 me-4">
                            <i class="bi bi-envelope-fill fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">Email</h5>
                            <p class="text-white-50 mb-0">contato@credvance.com.br</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <div class="bg-primary rounded-3 p-3 me-4">
                            <i class="bi bi-geo-alt-fill fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">Endereço</h5>
                            <p class="text-white-50 mb-0">Rua 35 Casa 101 Setor Tradicional<br>São Sebastião DF</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="card bg-white bg-opacity-10 border-0 backdrop-blur">
                        <div class="card-body p-4">
                            <h4 class="card-title text-white mb-4">Envie uma mensagem</h4>
                            <form>
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label text-white">Nome</label>
                                        <input type="text" class="form-control bg-white bg-opacity-10 border-white border-opacity-20 text-white" placeholder="Seu nome">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-white">Email</label>
                                        <input type="email" class="form-control bg-white bg-opacity-10 border-white border-opacity-20 text-white" placeholder="seu@email.com">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-white">Telefone</label>
                                    <input type="tel" class="form-control bg-white bg-opacity-10 border-white border-opacity-20 text-white" placeholder="(61) 99999-9999">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label text-white">Mensagem</label>
                                    <textarea class="form-control bg-white bg-opacity-10 border-white border-opacity-20 text-white" rows="4" placeholder="Como podemos ajudar você?"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg w-100">Enviar Mensagem</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <h4 class="fw-bold mb-4 text-gradient">CredVance</h4>
                    <p class="text-white-50">
                        Realizando sonhos através do consórcio mais moderno e seguro do Brasil.
                    </p>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <h5 class="fw-bold mb-4">Links Úteis</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#inicio" class="text-white-50 text-decoration-none">Início</a></li>
                        <li class="mb-2"><a href="#sobre" class="text-white-50 text-decoration-none">Sobre</a></li>
                        <li class="mb-2"><a href="#planos" class="text-white-50 text-decoration-none">Planos</a></li>
                        <li class="mb-2"><a href="#contato" class="text-white-50 text-decoration-none">Contato</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <h5 class="fw-bold mb-4">Produtos</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Consórcio Auto</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Consórcio Imóvel</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Consórcio Moto</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Consórcio Serviços</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <h5 class="fw-bold mb-4">Contato</h5>
                    <div class="text-white-50">
                        <p class="mb-2">+55 61 99625-8003</p>
                        <p class="mb-2">contato@credvance.com.br</p>
                        <p class="mb-0">São Sebastião DF</p>
                    </div>
                </div>
            </div>
            
            <hr class="border-white border-opacity-20 my-4">
            <div class="text-center text-white-50">
                <p class="mb-0">&copy; 2024 CredVance. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

             <!-- Botão flutuante do WhatsApp -->
<div id="zapFloatBtnContainer">
    <div class="zapFloatLabel">Fale conosco</div>
    <a href="https://wa.me/556196258003?text=Ol%C3%A1%2C%20estou%20interessado%20em%20mais%20detalhes." 
       target="_blank" 
       rel="noopener" 
       class="zapFloatButton" 
       aria-label="Abrir conversa no WhatsApp">
        <i class="bi bi-whatsapp"></i>
    </a>
</div>



    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Animation JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/credvance-functions.js"></script>
</body>
</html>

