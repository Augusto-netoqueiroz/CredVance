<section id="inicio" class="hero-section position-relative overflow-hidden">
    <div class="hero-bg"></div>
    <img src="{{ asset('assets/images/hero2.png') }}" alt="Robô CredVance" class="hero-robot">
    
    <div class="container position-relative">
        <div class="row align-items-center min-vh-100 py-5">
            <div class="col-lg-6 text-white slide-in-left" data-aos="fade-right">
                <span class="badge bg-light bg-opacity-20 text-white mb-4">
                    <i class="bi bi-lightning-fill me-1"></i>
                    Sem Taxa de Adesão
                </span>
                
                <h1 class="display-3 fw-bold mb-4 lh-1">
                    <span class="text-warning">💥 Multiplique</span><br>
                    seu dinheiro com<br>
                    <span class="text-white">CredVance!</span>
                </h1>
                
                <p class="lead mb-4">
                    <strong class="text-warning">Conte com a CredVance</strong> e tenha 
                    <strong class="text-success">rendimento de até 20%</strong> no seu investimento!
                </p>
                
                <p class="text-white-50 mb-4 fst-italic fs-5">
                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                    <strong class="text-white">Contrate quantas cotas quiser</strong> - sem limitações!
                </p>
                
                <p class="text-white-50 mb-4">
                    Comece com parcelas de <strong class="text-warning">R$ 155,00</strong> e receba até
                    <strong class="text-success"> R$ 3.672,00</strong> com juros garantidos!
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
            
            <div class="col-lg-6 slide-in-right" data-aos="fade-left" data-aos-delay="200">
                @include("components.investment-calculator")
            </div>
        </div>
    </div>
</section>

