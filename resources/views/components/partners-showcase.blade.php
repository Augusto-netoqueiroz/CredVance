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
                                    <img src="{{ asset('assets/images/empresa1.png') }}" 
                                         alt="TechFlow" 
                                         class="img-fluid company-logo">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-3 col-4">
                                <div class="company-logo-card">
                                    <img src="{{ asset('assets/images/empresa2.png') }}" 
                                         alt="InvestPro" 
                                         class="img-fluid company-logo">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-3 col-4">
                                <div class="company-logo-card">
                                    <img src="{{ asset('assets/images/empresa3.png') }}" 
                                         alt="BuildMax" 
                                         class="img-fluid company-logo">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-3 col-4">
                                <div class="company-logo-card">
                                    <img src="{{ asset('assets/images/empresa4.png') }}" 
                                         alt="MediCare Plus" 
                                         class="img-fluid company-logo">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-3 col-4">
                                <div class="company-logo-card">
                                    <img src="{{ asset('assets/images/empresa5.png') }}" 
                                         alt="ShopSmart" 
                                         class="img-fluid company-logo">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-3 col-4">
                                <div class="company-logo-card">
                                    <img src="{{ asset('assets/images/empresa6.png') }}" 
                                         alt="AutoDrive" 
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
                                    <img src="{{ asset('assets/images/empresa4.png') }}" 
                                         alt="MediCare Plus" 
                                         class="img-fluid company-logo">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-3 col-4">
                                <div class="company-logo-card">
                                    <img src="{{ asset('assets/images/empresa5.png') }}" 
                                         alt="ShopSmart" 
                                         class="img-fluid company-logo">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-3 col-4">
                                <div class="company-logo-card">
                                    <img src="{{ asset('assets/images/empresa6.png') }}" 
                                         alt="AutoDrive" 
                                         class="img-fluid company-logo">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-3 col-4">
                                <div class="company-logo-card">
                                    <img src="{{ asset('assets/images/empresa1.png') }}" 
                                         alt="TechFlow" 
                                         class="img-fluid company-logo">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-3 col-4">
                                <div class="company-logo-card">
                                    <img src="{{ asset('assets/images/empresa2.png') }}" 
                                         alt="InvestPro" 
                                         class="img-fluid company-logo">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-3 col-4">
                                <div class="company-logo-card">
                                    <img src="{{ asset('assets/images/empresa3.png') }}" 
                                         alt="BuildMax" 
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

<style>
/* Company Logo Cards */
.company-logo-card {
    background: white;
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.company-logo-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.company-logo {
    max-height: 60px;
    max-width: 100%;
    object-fit: contain;
    filter: grayscale(20%);
    transition: filter 0.3s ease;
}

.company-logo-card:hover .company-logo {
    filter: grayscale(0%);
}

/* Carousel Controls */
.carousel-control-prev,
.carousel-control-next {
    width: auto;
    opacity: 1;
}

.carousel-control-icon {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.carousel-control-icon:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
}

/* Custom Carousel Indicators */
.carousel-indicator-custom {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: none;
    background-color: rgba(13, 110, 253, 0.3);
    transition: all 0.3s ease;
}

.carousel-indicator-custom.active {
    background-color: #0d6efd;
    transform: scale(1.2);
}

/* Stats Cards */
.stat-card {
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-icon {
    width: 60px;
    height: 60px;
    transition: all 0.3s ease;
}

.stat-card:hover .stat-icon {
    transform: scale(1.1);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .company-logo-card {
        height: 100px;
        padding: 1rem;
    }
    
    .company-logo {
        max-height: 50px;
    }
    
    .carousel-control-icon {
        width: 40px;
        height: 40px;
    }
}
</style>

