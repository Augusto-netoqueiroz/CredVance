// CredVance JavaScript - Versão Corrigida

// ===== MENU MOBILE CORRIGIDO =====
(function() {
    'use strict';
    
    function setupMobileMenuFixed() {
        const navbarToggler = document.querySelector('.navbar-toggler');
        const navbarCollapse = document.querySelector('.navbar-collapse');
        const navLinks = document.querySelectorAll('.nav-link');
        
        if (!navbarToggler || !navbarCollapse) {
            console.log('Elementos do menu não encontrados, tentando novamente...');
            return false;
        }
        
        console.log('Configurando menu mobile...');
        
        // Remove todos os event listeners existentes
        const newToggler = navbarToggler.cloneNode(true);
        navbarToggler.parentNode.replaceChild(newToggler, navbarToggler);
        
        // Atualiza a referência
        const toggler = document.querySelector('.navbar-toggler');
        
        // Estado inicial
        function initializeMenuState() {
            navbarCollapse.classList.remove('show');
            toggler.classList.add('collapsed');
            toggler.setAttribute('aria-expanded', 'false');
            console.log('Menu inicializado no estado fechado');
        }
        
        // Função para abrir o menu
        function openMenu() {
            navbarCollapse.classList.add('show');
            toggler.classList.remove('collapsed');
            toggler.setAttribute('aria-expanded', 'true');
            console.log('Menu aberto');
        }
        
        // Função para fechar o menu
        function closeMenu() {
            navbarCollapse.classList.remove('show');
            toggler.classList.add('collapsed');
            toggler.setAttribute('aria-expanded', 'false');
            console.log('Menu fechado');
        }
        
        // Função para alternar o menu
        function toggleMenu() {
            const isOpen = navbarCollapse.classList.contains('show');
            console.log('Toggle menu - Estado atual:', isOpen ? 'aberto' : 'fechado');
            
            if (isOpen) {
                closeMenu();
            } else {
                openMenu();
            }
        }
        
        // Event listener principal do toggle
        toggler.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            toggleMenu();
        });
        
        // Fechar menu ao clicar em um link
        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                console.log('Link clicado:', this.textContent);
                closeMenu();
            });
        });
        
        // Fechar menu ao clicar fora
        document.addEventListener('click', function(e) {
            const isClickInsideNav = navbarCollapse.contains(e.target) || toggler.contains(e.target);
            
            if (!isClickInsideNav && navbarCollapse.classList.contains('show')) {
                console.log('Clique fora do menu detectado');
                closeMenu();
            }
        });
        
        // Fechar menu ao redimensionar para desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 992 && navbarCollapse.classList.contains('show')) {
                console.log('Redimensionamento para desktop detectado');
                closeMenu();
            }
        });
        
        // Fechar menu com ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && navbarCollapse.classList.contains('show')) {
                console.log('ESC pressionado');
                closeMenu();
            }
        });
        
        // Inicializa o estado
        initializeMenuState();
        
        console.log('Menu mobile configurado com sucesso!');
        return true;
    }
    
    // Tenta configurar imediatamente
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupMobileMenuFixed);
    } else {
        setupMobileMenuFixed();
    }
    
    // Função global para debug
    window.debugMobileMenu = function() {
        const navbarCollapse = document.querySelector('.navbar-collapse');
        const navbarToggler = document.querySelector('.navbar-toggler');
        
        console.log('Estado do menu:', {
            isOpen: navbarCollapse?.classList.contains('show'),
            togglerCollapsed: navbarToggler?.classList.contains('collapsed'),
            ariaExpanded: navbarToggler?.getAttribute('aria-expanded'),
            togglerExists: !!navbarToggler,
            collapseExists: !!navbarCollapse
        });
    };
    
})();

// ===== WHATSAPP BUTTON CORRIGIDO =====
(function() {
    'use strict';
    
    function setupWhatsAppFixed() {
        const whatsappContainer = document.querySelector('.floating-whatsapp, #zapFloatBtnContainer');
        
        if (whatsappContainer) {
            // Força visibilidade
            whatsappContainer.style.cssText = `
                position: fixed !important;
                bottom: 25px !important;
                right: 25px !important;
                z-index: 9999 !important;
                display: flex !important;
                opacity: 1 !important;
                visibility: visible !important;
                pointer-events: auto !important;
            `;
            
            const whatsappButton = whatsappContainer.querySelector('.whatsapp-button, .zapFloatButton');
            if (whatsappButton) {
                whatsappButton.addEventListener('click', function(e) {
                    if (!this.href || this.href.includes('#')) {
                        e.preventDefault();
                        const phoneNumber = '556196258003';
                        const message = 'Olá! Gostaria de saber mais sobre os serviços da CredVance.';
                        const whatsappUrl = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(message)}`;
                        window.open(whatsappUrl, '_blank');
                    }
                });
            }
            
            console.log('WhatsApp configurado!');
        }
    }
    
    // Executa quando DOM estiver pronto
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupWhatsAppFixed);
    } else {
        setupWhatsAppFixed();
    }
    
    // Executa novamente após carregamento completo
    window.addEventListener('load', setupWhatsAppFixed);
    
})();

// ===== JAVASCRIPT PRINCIPAL =====
document.addEventListener('DOMContentLoaded', function() {
    
    // ===== CONFIGURAÇÃO AOS OTIMIZADA =====
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 600,
            easing: 'ease-in-out',
            once: true, // CORREÇÃO: Mudado para true para melhor performance
            offset: 50,
            disable: 'mobile' // CORREÇÃO: Desabilita AOS em mobile para melhor performance
        });
    }

    // ===== SCROLL HANDLER UNIFICADO E OTIMIZADO =====
    const navbar = document.querySelector('.navbar');
    let ticking = false;
    
    function updateOnScroll() {
        const scrolled = window.pageYOffset;
        
        // Navbar scroll effect
        if (scrolled > 50) {
            navbar?.classList.add('navbar-scrolled');
        } else {
            navbar?.classList.remove('navbar-scrolled');
        }
        
        // Parallax effect (apenas em desktop)
        if (window.innerWidth > 768) {
            const heroRobot = document.querySelector('.hero-robot');
            if (heroRobot) {
                const speed = scrolled * 0.2; // CORREÇÃO: Reduzido para melhor performance
                heroRobot.style.transform = `translateY(${speed}px)`;
            }
        }
        
        ticking = false;
    }
    
    function requestTick() {
        if (!ticking) {
            requestAnimationFrame(updateOnScroll);
            ticking = true;
        }
    }
    
    // CORREÇÃO: Um único event listener otimizado
    window.addEventListener('scroll', requestTick, { passive: true });

    // ===== SMOOTH SCROLLING OTIMIZADO =====
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                const offsetTop = target.offsetTop - 80;
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });

    // ===== SIMULADOR FUNCTIONALITY =====
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
        const cotas = parseInt(cotasRange?.value || 1);
        const valorDesejado = parseInt(valorRange?.value || 10000);

        if (valorDisplay) valorDisplay.textContent = valorDesejado.toLocaleString('pt-BR');
        if (cotasDisplay) cotasDisplay.textContent = cotas;

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

        if (parcelaMensal) {
            parcelaMensal.textContent = `R$ ${parcelaInicial.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`;
        }
        if (totalBonus) {
            totalBonus.textContent = `R$ ${retornoFinal.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`;
        }
        if (totalPago) {
            totalPago.textContent = `R$ ${totalPagoCalc.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`;
        }
    }

    // Event listeners para o simulador
    if (valorRange && cotasRange) {
        document.querySelectorAll('input[name="plano"]').forEach(input => {
            input.addEventListener('change', () => {
                updateFromValor();
            });
        });

        valorRange.addEventListener('input', updateFromValor);
        cotasRange.addEventListener('input', updateFromCotas);

        updateFromValor();
    }

    // ===== FORM VALIDATION =====
    const contactForm = document.querySelector('#contato form');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            if (!data.nome || !data.email || !data.telefone || !data.mensagem) {
                alert('Por favor, preencha todos os campos.');
                return;
            }
            
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(data.email)) {
                alert('Por favor, insira um email válido.');
                return;
            }
            
            alert('Mensagem enviada com sucesso! Entraremos em contato em breve.');
            this.reset();
        });
    }

    // ===== FLOATING CTA =====
    const floatingCTA = document.querySelector('.floating-cta button');
    
    if (floatingCTA) {
        floatingCTA.addEventListener('click', function() {
            const contactSection = document.getElementById('contato');
            if (contactSection) {
                contactSection.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }

    // ===== LOADING ANIMATION =====
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('loaded');
            } else {
                entry.target.classList.remove('loaded');
            }
        });
    }, observerOptions);

    document.querySelectorAll('.card, .feature-card').forEach(el => {
        el.classList.add('loading');
        observer.observe(el);
    });

    // ===== COUNTER ANIMATION =====
    function animateCounter(element, target, duration = 2000) {
        let start = 0;
        const increment = target / (duration / 16);
        
        const timer = setInterval(() => {
            start += increment;
            element.textContent = Math.floor(start);
            
            if (start >= target) {
                element.textContent = target;
                clearInterval(timer);
            }
        }, 16);
    }

    const counters = document.querySelectorAll('.counter');
    const counterObserver = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const target = parseInt(entry.target.getAttribute('data-target'));
                animateCounter(entry.target, target);
                counterObserver.unobserve(entry.target);
            }
        });
    });

    counters.forEach(counter => {
        counterObserver.observe(counter);
    });

    // ===== HOVER EFFECTS OTIMIZADOS =====
    document.querySelectorAll('.btn').forEach(button => {
        button.addEventListener('mouseenter', function() {
            if (!this.style.transform.includes('translateY')) {
                this.style.transform = 'translateY(-2px)';
            }
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // ===== LAZY LOADING =====
    const images = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });

    images.forEach(img => imageObserver.observe(img));

    // ===== RIPPLE EFFECT =====
    function createRipple(event) {
        const button = event.currentTarget;
        const circle = document.createElement('span');
        const diameter = Math.max(button.clientWidth, button.clientHeight);
        const radius = diameter / 2;

        circle.style.width = circle.style.height = `${diameter}px`;
        circle.style.left = `${event.clientX - button.offsetLeft - radius}px`;
        circle.style.top = `${event.clientY - button.offsetTop - radius}px`;
        circle.classList.add('ripple');

        const ripple = button.getElementsByClassName('ripple')[0];
        if (ripple) {
            ripple.remove();
        }

        button.appendChild(circle);
    }

    document.querySelectorAll('.btn').forEach(button => {
        button.addEventListener('click', createRipple);
    });

    // ===== CSS PARA RIPPLE =====
    const style = document.createElement('style');
    style.textContent = `
        .btn {
            position: relative;
            overflow: hidden;
        }
        
        .ripple {
            position: absolute;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.6);
            transform: scale(0);
            animation: ripple-animation 0.6s linear;
            pointer-events: none;
        }
        
        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);

    // ===== INICIALIZAÇÃO FINAL OTIMIZADA =====
    console.log('CredVance website carregado com sucesso!');
});

// ===== UTILITY FUNCTIONS =====
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// ===== FUNÇÕES GLOBAIS PARA DEBUG =====
window.forceMenuClose = function() {
    const navbarCollapse = document.querySelector('.navbar-collapse');
    const navbarToggler = document.querySelector('.navbar-toggler');
    
    if (navbarCollapse && navbarToggler) {
        navbarCollapse.classList.remove('show');
        navbarToggler.classList.add('collapsed');
        navbarToggler.setAttribute('aria-expanded', 'false');
        console.log('Menu forçado a fechar');
    }
};

window.forceMenuOpen = function() {
    const navbarCollapse = document.querySelector('.navbar-collapse');
    const navbarToggler = document.querySelector('.navbar-toggler');
    
    if (navbarCollapse && navbarToggler) {
        navbarCollapse.classList.add('show');
        navbarToggler.classList.remove('collapsed');
        navbarToggler.setAttribute('aria-expanded', 'true');
        console.log('Menu forçado a abrir');
    }
};

window.testMenuToggle = function() {
    const navbarToggler = document.querySelector('.navbar-toggler');
    if (navbarToggler) {
        navbarToggler.click();
        console.log('Toggle do menu testado');
    }
};

