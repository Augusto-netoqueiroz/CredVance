<nav class="navbar navbar-expand-lg navbar-light fixed-top bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold fs-3 text-gradient" href="{{ url('/') }}">
            CredVance
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link fw-medium {{ request()->is('/') ? 'active' : '' }}" href="#inicio">Início</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-medium" href="#sobre">Sobre</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-medium" href="#planos">Planos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-medium" href="#simulador">Simulador</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-medium" href="#contato">Contato</a>
                </li>
            </ul>
            
            <div class="d-flex gap-2">
                <a href="{{ url('/area-cliente') }}" class="btn btn-outline-primary">
                    <i class="bi bi-person-circle me-1"></i>
                    Área do Cliente
                </a>
                <a href="{{ url('/cadastro') }}" class="btn btn-gradient">
                    <i class="bi bi-person-plus me-1"></i>
                    Cadastre-se
                </a>
            </div>
        </div>
    </div>
</nav>

