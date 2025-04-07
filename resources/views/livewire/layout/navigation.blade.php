<div> <!-- ELEMENTO ROOT ÚNICO -->

    <?php

    use App\Livewire\Actions\Logout;
    use Livewire\Volt\Component;

    new class extends Component
    {
        public function logout(Logout $logout): void
        {
            $logout();
            $this->redirect('/', navigate: true);
        }
    };
    ?>

    <!-- CONTEÚDO DO COMPONENTE -->
    <div class="d-flex min-vh-100" id="app-container">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar bg-theme shadow-sm p-3 border-end">
            <div class="text-center mb-4">
                <a href="{{ route('home') }}" wire:navigate>
                    <x-application-logo class="h-10 w-auto" />
                </a>
            </div>

            <nav class="nav flex-column gap-2">
                <a href="{{ route('home') }}" class="nav-link d-flex align-items-center text-theme">
                    <i class="lucide lucide-home me-2"></i> Dashboard
                </a>

                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.usuarios.index') }}" class="nav-link d-flex align-items-center text-theme position-relative">
                            <i class="lucide lucide-users me-2"></i> Usuários
                            <span class="badge bg-primary ms-auto">{{ \App\Models\User::count() }}</span>
                        </a>

                        <div class="text-uppercase small fw-semibold mt-3 ps-2 text-muted-theme">Administração</div>

                        <a href="#" class="nav-link d-flex align-items-center text-theme">
                            <i class="lucide lucide-settings me-2"></i> Configurações
                        </a>
                        <a href="#" class="nav-link d-flex align-items-center text-theme">
                            <i class="lucide lucide-file-text me-2"></i> Relatórios
                        </a>
                    @endif
                @endauth
            </nav>
        </aside>

        <!-- Main -->
        <div class="flex-grow-1 d-flex flex-column">
            <!-- Topbar -->
            <header id="topbar" class="d-flex justify-between align-items-center px-4 py-3 border-bottom bg-theme shadow-sm">
                <h1 class="h5 mb-0 text-theme">Painel Administrativo</h1>

                <div class="d-flex align-items-center gap-3">
                    <!-- Theme toggle -->
                    <button class="btn btn-outline-theme btn-sm" id="theme-toggle" title="Alternar Tema">
                        <i class="lucide" id="theme-icon" data-lucide="moon"></i>
                    </button>

                    @auth
                        <span class="fw-semibold text-theme">{{ auth()->user()->name }}</span>
                        <button wire:click="logout" class="btn btn-danger btn-sm d-flex align-items-center gap-1">
                            <i class="lucide lucide-log-out"></i> Sair
                        </button>
                    @endauth
                </div>
            </header>

            <!-- Content -->
            <main id="main-content" class="flex-grow-1 p-4 bg-theme">
                @yield('content')
            </main>
        </div>
    </div>

</div> <!-- FECHAMENTO DO ROOT -->

<!-- Bootstrap + Lucide -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>

<!-- Estilo dinâmico -->
<style>
    :root {
        --bg-light: #f8f9fa;
        --bg-dark: #212529;
        --text-light: #212529;
        --text-dark: #f8f9fa;
        --border-light: #dee2e6;
        --border-dark: #343a40;
        --accent: #0d6efd;
    }

    .transition-all {
        transition: all 0.3s ease-in-out;
    }

    body {
        transition: background 0.3s ease, color 0.3s ease;
    }

    .bg-theme {
        background-color: var(--bg-light);
        transition: background 0.3s ease;
    }

    .text-theme {
        color: var(--text-light);
        transition: color 0.3s ease;
    }

    .text-muted-theme {
        color: #6c757d;
    }

    .btn-outline-theme {
        border-color: var(--text-light);
        color: var(--text-light);
    }

    .btn-outline-theme:hover {
        background-color: var(--text-light);
        color: var(--bg-light);
    }

    .sidebar {
        width: 260px;
    }

    body.dark-mode {
        --bg-light: #1e1e2f;
        --bg-dark: #12121a;
        --text-light: #ffffff;
        --text-dark: #f1f1f1;
        --border-light: #2c2c3c;
        --accent: #66b3ff;
    }
</style>

<!-- Script do tema -->
<script>
    lucide.createIcons();

    const html = document.documentElement;
    const body = document.body;
    const themeToggle = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-icon');

    function setTheme(mode) {
        if (mode === 'dark') {
            body.classList.add('dark-mode');
            themeIcon.setAttribute('data-lucide', 'sun');
        } else {
            body.classList.remove('dark-mode');
            themeIcon.setAttribute('data-lucide', 'moon');
        }
        lucide.createIcons();
        localStorage.setItem('theme', mode);
    }

    themeToggle.addEventListener('click', () => {
        const current = localStorage.getItem('theme') === 'dark' ? 'light' : 'dark';
        setTheme(current);
    });

    document.addEventListener('DOMContentLoaded', () => {
        const saved = localStorage.getItem('theme') || 'light';
        setTheme(saved);
    });
</script>
