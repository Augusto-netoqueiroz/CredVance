<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentação Completa - Sistema CredVance</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0c3c71;
            --secondary-color: #1e5a96;
            --accent-color: #3b82f6;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --bg-color: #ffffff;
            --surface-color: #f8fafc;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --border-color: #e5e7eb;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        [data-theme="dark"] {
            --bg-color: #0f172a;
            --surface-color: #1e293b;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --border-color: #334155;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-primary);
            line-height: 1.6;
            transition: all 0.3s ease;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* Header */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 70px;
            background: var(--surface-color);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            z-index: 1000;
            box-shadow: var(--shadow);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .header-controls {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .search-box {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-input {
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            background: var(--bg-color);
            color: var(--text-primary);
            font-size: 0.875rem;
            width: 300px;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 0.75rem;
            color: var(--text-secondary);
        }

        .theme-toggle {
            padding: 0.5rem;
            border: none;
            background: none;
            color: var(--text-secondary);
            cursor: pointer;
            border-radius: 0.375rem;
            transition: all 0.3s ease;
        }

        .theme-toggle:hover {
            background: var(--border-color);
            color: var(--text-primary);
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 70px;
            left: 0;
            width: 320px;
            height: calc(100vh - 70px);
            background: var(--surface-color);
            border-right: 1px solid var(--border-color);
            overflow-y: auto;
            z-index: 999;
            transition: transform 0.3s ease;
        }

        .sidebar-content {
            padding: 1.5rem;
        }

        .nav-section {
            margin-bottom: 2rem;
        }

        .nav-title {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-secondary);
            margin-bottom: 0.75rem;
        }

        .nav-list {
            list-style: none;
        }

        .nav-item {
            margin-bottom: 0.25rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            font-size: 0.875rem;
        }

        .nav-link:hover,
        .nav-link.active {
            background: var(--accent-color);
            color: white;
            transform: translateX(4px);
        }

        .nav-icon {
            width: 16px;
            height: 16px;
        }

        /* Main Content */
        .main-content {
            margin-left: 320px;
            margin-top: 70px;
            flex: 1;
            padding: 2rem;
            max-width: calc(100vw - 320px);
        }

        .content-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            font-size: 1.125rem;
            color: var(--text-secondary);
            margin-bottom: 1.5rem;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .breadcrumb-separator {
            color: var(--border-color);
        }

        /* Sections */
        .section {
            background: var(--surface-color);
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
        }

        .section:hover {
            box-shadow: var(--shadow-lg);
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: between;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .section-icon {
            width: 24px;
            height: 24px;
            color: var(--accent-color);
        }

        .collapse-btn {
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.375rem;
            transition: all 0.3s ease;
        }

        .collapse-btn:hover {
            background: var(--border-color);
            color: var(--text-primary);
        }

        .section-content {
            transition: all 0.3s ease;
        }

        .section-content.collapsed {
            display: none;
        }

        /* Tables */
        .table-container {
            overflow-x: auto;
            border-radius: 0.75rem;
            border: 1px solid var(--border-color);
            margin: 1.5rem 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: var(--bg-color);
        }

        th {
            background: var(--surface-color);
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--text-primary);
            border-bottom: 1px solid var(--border-color);
            font-size: 0.875rem;
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.875rem;
        }

        tr:hover {
            background: var(--surface-color);
        }

        /* Code Blocks */
        .code-container {
            position: relative;
            margin: 1.5rem 0;
        }

        .code-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--text-primary);
            color: var(--bg-color);
            padding: 0.75rem 1rem;
            border-radius: 0.5rem 0.5rem 0 0;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .copy-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 0.375rem;
            cursor: pointer;
            font-size: 0.75rem;
            transition: all 0.3s ease;
        }

        .copy-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        pre {
            background: #2d3748 !important;
            margin: 0;
            padding: 1.5rem;
            border-radius: 0 0 0.5rem 0.5rem;
            overflow-x: auto;
            font-size: 0.875rem;
            line-height: 1.5;
        }

        code {
            background: var(--surface-color);
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
            color: var(--accent-color);
            font-family: 'Fira Code', 'Monaco', 'Consolas', monospace;
        }

        /* Lists */
        .custom-list {
            list-style: none;
            margin: 1rem 0;
        }

        .custom-list li {
            position: relative;
            padding-left: 2rem;
            margin-bottom: 0.75rem;
            line-height: 1.6;
        }

        .custom-list li::before {
            content: '→';
            position: absolute;
            left: 0;
            color: var(--accent-color);
            font-weight: bold;
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .badge-primary {
            background: rgba(59, 130, 246, 0.1);
            color: var(--accent-color);
        }

        .badge-success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .badge-warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }

        .badge-danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }

        /* Cards */
        .card {
            background: var(--bg-color);
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin: 1rem 0;
            box-shadow: var(--shadow);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--border-color);
        }

        .card-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        /* Alerts */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 0.75rem;
            margin: 1.5rem 0;
            border-left: 4px solid;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .alert-info {
            background: rgba(59, 130, 246, 0.1);
            border-color: var(--accent-color);
            color: var(--text-primary);
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border-color: var(--success-color);
            color: var(--text-primary);
        }

        .alert-warning {
            background: rgba(245, 158, 11, 0.1);
            border-color: var(--warning-color);
            color: var(--text-primary);
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            border-color: var(--danger-color);
            color: var(--text-primary);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                max-width: 100vw;
            }

            .search-input {
                width: 200px;
            }
        }

        @media (max-width: 768px) {
            .header {
                padding: 0 1rem;
            }

            .main-content {
                padding: 1rem;
            }

            .page-title {
                font-size: 2rem;
            }

            .section {
                padding: 1.5rem;
            }

            .search-input {
                width: 150px;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .section {
            animation: fadeIn 0.6s ease-out;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--surface-color);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-secondary);
        }

        /* Print styles */
        @media print {
            .header, .sidebar {
                display: none;
            }

            .main-content {
                margin: 0;
                max-width: 100%;
            }

            .section {
                break-inside: avoid;
                box-shadow: none;
                border: 1px solid #ccc;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="logo">
            <i class="fas fa-file-invoice-dollar"></i>
            <span>CredVance Docs</span>
        </div>
        <div class="header-controls">
            <div class="search-box">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" placeholder="Buscar na documentação..." id="searchInput">
            </div>
            <button class="theme-toggle" id="themeToggle">
                <i class="fas fa-moon"></i>
            </button>
            <button class="theme-toggle" id="menuToggle" style="display: none;">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </header>

    <div class="container">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-content">
                <div class="nav-section">
                    <div class="nav-title">Visão Geral</div>
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a href="#introducao" class="nav-link">
                                <i class="fas fa-home nav-icon"></i>
                                Introdução
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#arquivos" class="nav-link">
                                <i class="fas fa-folder nav-icon"></i>
                                Arquivos e Localização
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#fluxo" class="nav-link">
                                <i class="fas fa-project-diagram nav-icon"></i>
                                Fluxo Geral
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="nav-section">
                    <div class="nav-title">Sistema de Boletos</div>
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a href="#estrutura-tabela-logs" class="nav-link">
                                <i class="fas fa-database nav-icon"></i>
                                Tabela de Logs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#detalhes-comando" class="nav-link">
                                <i class="fas fa-terminal nav-icon"></i>
                                Comando Envio
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#metodo-pixel" class="nav-link">
                                <i class="fas fa-eye nav-icon"></i>
                                Tracking Pixel
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#classe-mailable" class="nav-link">
                                <i class="fas fa-envelope nav-icon"></i>
                                Classe Mailable
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="nav-section">
                    <div class="nav-title">Integração Bancária</div>
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a href="#inter-banco" class="nav-link">
                                <i class="fas fa-university nav-icon"></i>
                                API Banco Inter
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#pagamento-controller" class="nav-link">
                                <i class="fas fa-credit-card nav-icon"></i>
                                Controllers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#expiracao-reemissao" class="nav-link">
                                <i class="fas fa-redo nav-icon"></i>
                                Reemissão
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="nav-section">
                    <div class="nav-title">Interface e Logs</div>
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a href="#controller-logs" class="nav-link">
                                <i class="fas fa-list nav-icon"></i>
                                Controller Logs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#views-logs" class="nav-link">
                                <i class="fas fa-eye nav-icon"></i>
                                Views
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#area-cliente" class="nav-link">
                                <i class="fas fa-user nav-icon"></i>
                                Área do Cliente
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="nav-section">
                    <div class="nav-title">Configuração</div>
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a href="#rotas" class="nav-link">
                                <i class="fas fa-route nav-icon"></i>
                                Rotas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#execucao" class="nav-link">
                                <i class="fas fa-play nav-icon"></i>
                                Execução
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#recomendacoes" class="nav-link">
                                <i class="fas fa-lightbulb nav-icon"></i>
                                Recomendações
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <div class="content-header">
                <div class="breadcrumb">
                    <span>CredVance</span>
                    <span class="breadcrumb-separator">/</span>
                    <span>Documentação</span>
                    <span class="breadcrumb-separator">/</span>
                    <span>Sistema de Boletos</span>
                </div>
                <h1 class="page-title">Sistema de Envio, Integração Bancária e Log de Boletos</h1>
                <p class="page-subtitle">Documentação completa e guia de funcionamento do sistema CredVance</p>
            </div>

            <!-- Introdução -->
            <section class="section" id="introducao">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="fas fa-info-circle section-icon"></i>
                        Introdução
                    </h2>
                    <button class="collapse-btn" onclick="toggleSection('introducao')">
                        <i class="fas fa-chevron-up"></i>
                    </button>
                </div>
                <div class="section-content">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <div>
                            <strong>Sobre este sistema:</strong> O CredVance é uma plataforma completa para gestão de boletos bancários, integração com APIs bancárias e controle de cobrança. Esta documentação serve como um oráculo completo do sistema.
                        </div>
                    </div>
                    
                    <p>O sistema CredVance oferece uma solução completa para:</p>
                    <ul class="custom-list">
                        <li><strong>Envio automatizado de boletos</strong> via e-mail com tracking de abertura</li>
                        <li><strong>Integração com API do Banco Inter</strong> para emissão e consulta de boletos</li>
                        <li><strong>Sistema de logs completo</strong> para auditoria e controle</li>
                        <li><strong>Área do cliente</strong> com acesso a boletos e PIX</li>
                        <li><strong>Reemissão automática</strong> de boletos vencidos</li>
                        <li><strong>Dashboard administrativo</strong> para gestão completa</li>
                    </ul>

                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-chart-line"></i>
                            <h3 class="card-title">Principais Funcionalidades</h3>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                            <div style="text-align: center; padding: 1rem;">
                                <i class="fas fa-envelope" style="font-size: 2rem; color: var(--accent-color); margin-bottom: 0.5rem;"></i>
                                <h4>Envio Automático</h4>
                                <p style="font-size: 0.875rem; color: var(--text-secondary);">E-mails com boletos anexados e tracking pixel</p>
                            </div>
                            <div style="text-align: center; padding: 1rem;">
                                <i class="fas fa-university" style="font-size: 2rem; color: var(--success-color); margin-bottom: 0.5rem;"></i>
                                <h4>API Bancária</h4>
                                <p style="font-size: 0.875rem; color: var(--text-secondary);">Integração completa com Banco Inter</p>
                            </div>
                            <div style="text-align: center; padding: 1rem;">
                                <i class="fas fa-chart-bar" style="font-size: 2rem; color: var(--warning-color); margin-bottom: 0.5rem;"></i>
                                <h4>Analytics</h4>
                                <p style="font-size: 0.875rem; color: var(--text-secondary);">Logs detalhados e métricas de performance</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Arquivos e Localização -->
            <section class="section" id="arquivos">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="fas fa-folder section-icon"></i>
                        Arquivos e Localização
                    </h2>
                    <button class="collapse-btn" onclick="toggleSection('arquivos')">
                        <i class="fas fa-chevron-up"></i>
                    </button>
                </div>
                <div class="section-content">
                    <p>Esta seção apresenta todos os arquivos principais do sistema e suas respectivas localizações no projeto Laravel.</p>
                    
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Arquivo / Classe</th>
                                    <th>Localização</th>
                                    <th>Descrição</th>
                                    <th>Tipo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code>EnviarBoletosTodos.php</code></td>
                                    <td><code>app/Console/Commands/</code></td>
                                    <td>Comando Artisan para envio automático de boletos pendentes</td>
                                    <td><span class="badge badge-primary">Command</span></td>
                                </tr>
                                <tr>
                                    <td><code>BoletoReminderMail.php</code></td>
                                    <td><code>app/Mail/</code></td>
                                    <td>Classe Mailable para composição do e-mail com boleto</td>
                                    <td><span class="badge badge-success">Mailable</span></td>
                                </tr>
                                <tr>
                                    <td><code>boleto_enviado.blade.php</code></td>
                                    <td><code>resources/views/emails/</code></td>
                                    <td>Template responsivo do e-mail com pixel de tracking</td>
                                    <td><span class="badge badge-warning">View</span></td>
                                </tr>
                                <tr>
                                    <td><code>BoletoLog.php</code></td>
                                    <td><code>app/Models/</code></td>
                                    <td>Model para registros de envio e abertura de e-mails</td>
                                    <td><span class="badge badge-primary">Model</span></td>
                                </tr>
                                <tr>
                                    <td><code>EmailController.php</code></td>
                                    <td><code>app/Http/Controllers/</code></td>
                                    <td>Controller para tracking de abertura via pixel</td>
                                    <td><span class="badge badge-danger">Controller</span></td>
                                </tr>
                                <tr>
                                    <td><code>InterBoletoService.php</code></td>
                                    <td><code>app/Services/</code></td>
                                    <td>Serviço de integração com API do Banco Inter</td>
                                    <td><span class="badge badge-success">Service</span></td>
                                </tr>
                                <tr>
                                    <td><code>PagamentoController.php</code></td>
                                    <td><code>app/Http/Controllers/</code></td>
                                    <td>Controller principal para gestão de pagamentos via API</td>
                                    <td><span class="badge badge-danger">Controller</span></td>
                                </tr>
                                <tr>
                                    <td><code>ActivityLoggerService.php</code></td>
                                    <td><code>app/Services/</code></td>
                                    <td>Serviço centralizado para logs de atividades</td>
                                    <td><span class="badge badge-success">Service</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-lightbulb"></i>
                        <div>
                            <strong>Dica:</strong> Todos os arquivos seguem a estrutura padrão do Laravel. Os Services estão na pasta <code>app/Services/</code> para melhor organização do código.
                        </div>
                    </div>
                </div>
            </section>

            <!-- Fluxo Geral -->
            <section class="section" id="fluxo">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="fas fa-project-diagram section-icon"></i>
                        Fluxo Geral e Integração
                    </h2>
                    <button class="collapse-btn" onclick="toggleSection('fluxo')">
                        <i class="fas fa-chevron-up"></i>
                    </button>
                </div>
                <div class="section-content">
                    <h3>Fluxo Principal do Sistema</h3>
                    <ol class="custom-list">
                        <li>O comando Artisan <code>boleto:enviar-todos</code> é executado (manual ou via cron)</li>
                        <li>Sistema consulta boletos pendentes vencidos ou próximos ao vencimento (10 dias)</li>
                        <li>Para cada boleto válido, dispara envio via <code>BoletoReminderMail</code></li>
                        <li>Registro é criado na tabela <code>boleto_logs</code> para auditoria</li>
                        <li>E-mail contém pixel invisível para tracking de abertura</li>
                        <li>Ao abrir o e-mail, dados são registrados (IP, User-Agent, timestamp)</li>
                        <li>Logs podem ser visualizados via dashboard administrativo</li>
                    </ol>

                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-university"></i>
                            <h3 class="card-title">Fluxo de Integração Banco Inter</h3>
                        </div>
                        <ol class="custom-list">
                            <li><strong>Emissão:</strong> Sistema consome API Inter via <code>InterBoletoService</code></li>
                            <li><strong>Sincronização:</strong> Status atualizado automaticamente (PENDENTE → PAGO → EXPIRADO)</li>
                            <li><strong>Download:</strong> PDFs e PIX disponíveis 100% online</li>
                            <li><strong>Controle:</strong> Impossível deletar boletos, apenas consultar ou reemitir</li>
                        </ol>
                    </div>

                    <div class="code-container">
                        <div class="code-header">
                            <span><i class="fas fa-terminal"></i> Exemplo de Execução</span>
                            <button class="copy-btn" onclick="copyCode(this)">Copiar</button>
                        </div>
                        <pre><code class="language-bash"># Envio manual de todos os boletos pendentes
php artisan boleto:enviar-todos

# Verificar logs do sistema
tail -f storage/logs/laravel.log

# Executar via cron (diário às 09:00)
0 9 * * * cd /var/www/credvance && php artisan boleto:enviar-todos</code></pre>
                    </div>
                </div>
            </section>

            <!-- Estrutura da Tabela boleto_logs -->
            <section class="section" id="estrutura-tabela-logs">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="fas fa-database section-icon"></i>
                        Estrutura da Tabela <code>boleto_logs</code>
                    </h2>
                    <button class="collapse-btn" onclick="toggleSection('estrutura-tabela-logs')">
                        <i class="fas fa-chevron-up"></i>
                    </button>
                </div>
                <div class="section-content">
                    <p>A tabela <code>boleto_logs</code> é o coração do sistema de auditoria, registrando todos os envios e aberturas de e-mails.</p>
                    
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Coluna</th>
                                    <th>Tipo</th>
                                    <th>Descrição</th>
                                    <th>Índices</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code>id</code></td>
                                    <td>bigint unsigned, PK</td>
                                    <td>Identificador único do registro de log</td>
                                    <td><span class="badge badge-primary">Primary</span></td>
                                </tr>
                                <tr>
                                    <td><code>pagamento_id</code></td>
                                    <td>bigint unsigned, FK</td>
                                    <td>Referência ao pagamento/boleto</td>
                                    <td><span class="badge badge-warning">Foreign</span></td>
                                </tr>
                                <tr>
                                    <td><code>contrato_id</code></td>
                                    <td>bigint unsigned, FK</td>
                                    <td>Referência ao contrato relacionado</td>
                                    <td><span class="badge badge-warning">Foreign</span></td>
                                </tr>
                                <tr>
                                    <td><code>cliente_id</code></td>
                                    <td>bigint unsigned, FK</td>
                                    <td>Referência ao cliente proprietário</td>
                                    <td><span class="badge badge-warning">Foreign</span></td>
                                </tr>
                                <tr>
                                    <td><code>enviado</code></td>
                                    <td>boolean</td>
                                    <td>Flag indicando se o e-mail foi enviado</td>
                                    <td><span class="badge badge-success">Index</span></td>
                                </tr>
                                <tr>
                                    <td><code>enviado_em</code></td>
                                    <td>timestamp</td>
                                    <td>Data e hora exata do envio</td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td><code>aberto</code></td>
                                    <td>boolean</td>
                                    <td>Flag indicando se o e-mail foi aberto</td>
                                    <td><span class="badge badge-success">Index</span></td>
                                </tr>
                                <tr>
                                    <td><code>aberto_em</code></td>
                                    <td>timestamp</td>
                                    <td>Data e hora da primeira abertura</td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td><code>ip</code></td>
                                    <td>varchar(45)</td>
                                    <td>Endereço IP do cliente na abertura</td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td><code>user_agent</code></td>
                                    <td>varchar(255)</td>
                                    <td>User-Agent do cliente de e-mail</td>
                                    <td>-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <strong>Importante:</strong> Esta tabela mantém histórico completo de todos os envios, permitindo análise de taxa de entrega e abertura dos e-mails.
                        </div>
                    </div>
                </div>
            </section>

            <!-- Área do Cliente -->
            <section class="section" id="area-cliente">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="fas fa-user section-icon"></i>
                        Área do Cliente - Faturas e PIX
                    </h2>
                    <button class="collapse-btn" onclick="toggleSection('area-cliente')">
                        <i class="fas fa-chevron-up"></i>
                    </button>
                </div>
                <div class="section-content">
                    <p>A área do cliente foi modernizada com novas funcionalidades para melhor experiência do usuário.</p>

                    <h3>Principais Melhorias Implementadas</h3>
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-mobile-alt"></i>
                            <h3 class="card-title">Nova Coluna PIX</h3>
                        </div>
                        <ul class="custom-list">
                            <li>Adicionada coluna "PIX" na tabela de faturas</li>
                            <li>Botão <strong>[Copiar PIX]</strong> azul e responsivo para pagamentos com PIX disponível</li>
                            <li>Exibe "—" quando PIX não está disponível</li>
                            <li>Funcionalidade de cópia automática para área de transferência</li>
                        </ul>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-download"></i>
                            <h3 class="card-title">Botão Baixar Boleto</h3>
                        </div>
                        <ul class="custom-list">
                            <li>Coluna "Ação" renomeada para <strong>"Boleto"</strong></li>
                            <li>Botão alterado para <strong>[Baixar boleto]</strong> com design responsivo</li>
                            <li>Adaptação automática para dispositivos móveis</li>
                            <li>Download direto do PDF do boleto</li>
                        </ul>
                    </div>

                    <h3>Exemplo Visual da Tabela</h3>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Vencimento</th>
                                    <th>Valor</th>
                                    <th>Status</th>
                                    <th>PIX</th>
                                    <th>Boleto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>10/07/2025</td>
                                    <td>R$ 100,00</td>
                                    <td><span class="badge badge-warning">Pendente</span></td>
                                    <td><button style="background: var(--accent-color); color: white; border: none; padding: 0.5rem 1rem; border-radius: 0.375rem; cursor: pointer;">Copiar PIX</button></td>
                                    <td><button style="background: var(--accent-color); color: white; border: none; padding: 0.5rem 1rem; border-radius: 0.375rem; cursor: pointer;">Baixar boleto</button></td>
                                </tr>
                                <tr>
                                    <td>10/08/2025</td>
                                    <td>R$ 100,00</td>
                                    <td><span class="badge badge-success">Pago</span></td>
                                    <td>—</td>
                                    <td><button style="background: var(--accent-color); color: white; border: none; padding: 0.5rem 1rem; border-radius: 0.375rem; cursor: pointer;">Baixar boleto</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h3>Log de Atividades do Cliente</h3>
                    <div class="alert alert-info">
                        <i class="fas fa-chart-line"></i>
                        <div>
                            <strong>Tracking Avançado:</strong> Todas as ações do cliente (copiar PIX, baixar boleto) são registradas via AJAX para análise de comportamento e auditoria.
                        </div>
                    </div>

                    <div class="code-container">
                        <div class="code-header">
                            <span><i class="fas fa-code"></i> Implementação do Log AJAX</span>
                            <button class="copy-btn" onclick="copyCode(this)">Copiar</button>
                        </div>
                        <pre><code class="language-javascript">// Exemplo de implementação do log de atividades
function logActivity(type, pagamentoId) {
    fetch('/cliente/log-activity', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            type: type, // 'copiou_pix' ou 'baixou_boleto'
            pagamento_id: pagamentoId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.ok) {
            console.log('Atividade registrada com sucesso');
        }
    });
}</code></pre>
                    </div>
                </div>
            </section>

            <!-- Integração Banco Inter -->
            <section class="section" id="inter-banco">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="fas fa-university section-icon"></i>
                        Integração Banco Inter (API) - Fluxo Completo
                    </h2>
                    <button class="collapse-btn" onclick="toggleSection('inter-banco')">
                        <i class="fas fa-chevron-up"></i>
                    </button>
                </div>
                <div class="section-content">
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <strong>Integração Completa:</strong> O sistema possui integração total com a API v3 do Banco Inter para criação, consulta e gestão de boletos bancários.
                        </div>
                    </div>

                    <h3>Serviço Central: InterBoletoService</h3>
                    <p>Localizado em <code>app/Services/InterBoletoService.php</code>, este serviço centraliza toda a comunicação com a API do Banco Inter.</p>

                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-cogs"></i>
                            <h3 class="card-title">Funcionalidades Disponíveis</h3>
                        </div>
                        <ul class="custom-list">
                            <li><strong>Criação de cobranças:</strong> Boletos com controle de vencimento e agenda</li>
                            <li><strong>Listagem completa:</strong> Todos os boletos emitidos com filtros avançados</li>
                            <li><strong>Consulta detalhada:</strong> Status e informações via <code>codigoSolicitacao</code></li>
                            <li><strong>Download de PDF:</strong> Geração em tempo real do boleto</li>
                            <li><strong>PIX dinâmico:</strong> Código "Copia e Cola" sempre atualizado</li>
                            <li><strong>Logs completos:</strong> Todas as operações são registradas para troubleshooting</li>
                        </ul>
                    </div>

                    <h3>Estados dos Boletos</h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin: 1.5rem 0;">
                        <div class="card" style="text-align: center;">
                            <div style="color: var(--warning-color); font-size: 2rem; margin-bottom: 0.5rem;">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h4>PENDENTE</h4>
                            <p style="font-size: 0.875rem; color: var(--text-secondary);">Aguardando pagamento até vencimento + agenda</p>
                        </div>
                        <div class="card" style="text-align: center;">
                            <div style="color: var(--success-color); font-size: 2rem; margin-bottom: 0.5rem;">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h4>PAGO</h4>
                            <p style="font-size: 0.875rem; color: var(--text-secondary);">Pagamento confirmado pelo banco</p>
                        </div>
                        <div class="card" style="text-align: center;">
                            <div style="color: var(--danger-color); font-size: 2rem; margin-bottom: 0.5rem;">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <h4>EXPIRADO</h4>
                            <p style="font-size: 0.875rem; color: var(--text-secondary);">Vencido e fora do prazo de agenda</p>
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>
                            <strong>Importante:</strong> Não é possível deletar boletos na API do Inter. Para inadimplentes, reemita automaticamente novos boletos após expiração.
                        </div>
                    </div>

                    <h3>Configuração de Ambiente</h3>
                    <div class="code-container">
                        <div class="code-header">
                            <span><i class="fas fa-cog"></i> Variáveis de Ambiente (.env)</span>
                            <button class="copy-btn" onclick="copyCode(this)">Copiar</button>
                        </div>
                        <pre><code class="language-bash"># Configurações da API Banco Inter
INTER_CLIENT_ID=seu_client_id_aqui
INTER_CLIENT_SECRET=seu_client_secret_aqui
INTER_CERTIFICATE_PATH=/path/to/certificate.crt
INTER_PRIVATE_KEY_PATH=/path/to/private.key
INTER_CONTA_CORRENTE=12345678
INTER_ENVIRONMENT=sandbox # ou production</code></pre>
                    </div>
                </div>
            </section>

            <!-- Busca e Listagem de Boletos -->
            <section class="section" id="busca-listagem">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="fas fa-search section-icon"></i>
                        Busca e Listagem de Boletos Inter via API
                    </h2>
                    <button class="collapse-btn" onclick="toggleSection('busca-listagem')">
                        <i class="fas fa-chevron-up"></i>
                    </button>
                </div>
                <div class="section-content">
                    <p>Sistema permite listar todos os boletos dos últimos 30 dias e buscar boletos específicos pelo código de solicitação.</p>

                    <h3>Funcionalidades de Busca</h3>
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-list"></i>
                            <h3 class="card-title">Listagem Geral</h3>
                        </div>
                        <ul class="custom-list">
                            <li>Lista todos os boletos dos últimos 30 dias automaticamente</li>
                            <li>Filtros por período, status e outras opções</li>
                            <li>Paginação automática para grandes volumes</li>
                            <li>Atualização em tempo real via API</li>
                        </ul>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-search"></i>
                            <h3 class="card-title">Busca Individual</h3>
                        </div>
                        <ul class="custom-list">
                            <li>Busca por <strong>código de solicitação</strong> específico</li>
                            <li>Retorna detalhes completos do boleto</li>
                            <li>Informações de status atualizadas</li>
                            <li>Links diretos para PDF e PIX</li>
                        </ul>
                    </div>

                    <div class="code-container">
                        <div class="code-header">
                            <span><i class="fas fa-code"></i> Exemplo de Controller</span>
                            <button class="copy-btn" onclick="copyCode(this)">Copiar</button>
                        </div>
                        <pre><code class="language-php">public function index(Request $request, InterBoletoService $inter)
{
    $codigo = $request->input('codigo');
    $boletos = [];

    try {
        if ($codigo) {
            // Busca individual por código
            $cobranca = $inter->getCobranca($codigo);
            if ($cobranca && isset($cobranca['codigoSolicitacao'])) {
                $boletos[] = [
                    'cobranca' => $cobranca,
                    'boleto'   => [],
                    'pix'      => [],
                ];
            }
        } else {
            // Listagem geral dos últimos 30 dias
            $filters = [
                'dataInicio' => now()->subDays(30)->format('Y-m-d'),
                'dataFim'    => now()->format('Y-m-d'),
            ];
            $apiResponse = $inter->listCobrancas($filters);
            $boletos = $apiResponse['cobrancas'] ?? [];
        }
    } catch (\Throwable $e) {
        session()->flash('error', 'Erro ao consultar API Inter: ' . $e->getMessage());
    }
        
    return view('pagamentos.index', compact('boletos', 'codigo'));
}</code></pre>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-lightbulb"></i>
                        <div>
                            <strong>Dica:</strong> Sempre pesquise usando o código de solicitação exibido na tabela. Se não encontrar, o boleto pode estar em ambiente diferente (prod/sandbox).
                        </div>
                    </div>
                </div>
            </section>

            <!-- Reemissão de Boletos -->
            <section class="section" id="expiracao-reemissao">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="fas fa-redo section-icon"></i>
                        Expiração, Reemissão e Estratégias de Cobrança
                    </h2>
                    <button class="collapse-btn" onclick="toggleSection('expiracao-reemissao')">
                        <i class="fas fa-chevron-up"></i>
                    </button>
                </div>
                <div class="section-content">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>
                            <strong>Atenção:</strong> Boletos expirados na API não podem mais ser pagos. O sistema implementa reemissão automática para garantir continuidade da cobrança.
                        </div>
                    </div>

                    <h3>Ciclo de Vida dos Boletos</h3>
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-calendar-alt"></i>
                            <h3 class="card-title">Fases do Boleto</h3>
                        </div>
                        <ol class="custom-list">
                            <li><strong>Emissão:</strong> Boleto criado com vencimento + numDiasAgenda</li>
                            <li><strong>Pendente:</strong> Aguardando pagamento até vencimento + agenda</li>
                            <li><strong>Expirado:</strong> Após prazo da agenda, não aceita mais pagamento</li>
                            <li><strong>Reemissão:</strong> Novo boleto gerado automaticamente para inadimplentes</li>
                        </ol>
                    </div>

                    <h3>Comando de Reemissão</h3>
                    <p>O sistema possui comando específico para reemissão automática de boletos vencidos/expirados.</p>

                    <div class="code-container">
                        <div class="code-header">
                            <span><i class="fas fa-terminal"></i> Comandos de Reemissão</span>
                            <button class="copy-btn" onclick="copyCode(this)">Copiar</button>
                        </div>
                        <pre><code class="language-bash"># Reemitir um pagamento específico
php artisan pagamento:reemite-boleto 123

# Reemitir todos os boletos vencidos/expirados
php artisan pagamento:reemite-boleto --all

# Reemitir todos os boletos de um contrato
php artisan pagamento:reemite-boleto --contrato=10</code></pre>
                    </div>

                    <h3>Fluxo de Reemissão</h3>
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-cogs"></i>
                            <h3 class="card-title">Processo Automatizado</h3>
                        </div>
                        <ol class="custom-list">
                            <li>Comando identifica pagamentos vencidos sem quitação</li>
                            <li>Consulta status atual na API Inter</li>
                            <li>Se status = EXPIRADO, inicia processo de reemissão</li>
                            <li>Cria novo boleto com nova data de vencimento</li>
                            <li>Atualiza dados: código solicitação, PIX, linha digitável</li>
                            <li>Baixa e salva PDF do novo boleto</li>
                            <li>Registra todas as operações em <code>sistema_logs</code></li>
                        </ol>
                    </div>

                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <strong>Automação Completa:</strong> O processo de reemissão é totalmente automatizado, garantindo que clientes inadimplentes sempre tenham uma opção de pagamento válida.
                        </div>
                    </div>

                    <h3>Logs do Sistema</h3>
                    <p>Todas as operações de reemissão são registradas na tabela <code>sistema_logs</code> para auditoria completa.</p>

                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Campo</th>
                                    <th>Descrição</th>
                                    <th>Exemplo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code>modulo</code></td>
                                    <td>Módulo responsável pela ação</td>
                                    <td>ReemissaoBoleto</td>
                                </tr>
                                <tr>
                                    <td><code>referencia_id</code></td>
                                    <td>ID do pagamento reemitido</td>
                                    <td>123</td>
                                </tr>
                                <tr>
                                    <td><code>acao</code></td>
                                    <td>Tipo de ação executada</td>
                                    <td>reemissao_automatica</td>
                                </tr>
                                <tr>
                                    <td><code>resultado</code></td>
                                    <td>Status da operação</td>
                                    <td>sucesso / erro</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Execução e Configuração -->
            <section class="section" id="execucao">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="fas fa-play section-icon"></i>
                        Execução e Configuração
                    </h2>
                    <button class="collapse-btn" onclick="toggleSection('execucao')">
                        <i class="fas fa-chevron-up"></i>
                    </button>
                </div>
                <div class="section-content">
                    <h3>Comandos Principais</h3>
                    <div class="code-container">
                        <div class="code-header">
                            <span><i class="fas fa-terminal"></i> Comandos Artisan</span>
                            <button class="copy-btn" onclick="copyCode(this)">Copiar</button>
                        </div>
                        <pre><code class="language-bash"># Envio de boletos pendentes
php artisan boleto:enviar-todos

# Reemissão de boletos vencidos
php artisan pagamento:reemite-boleto --all

# Verificar status do sistema
php artisan queue:work

# Limpar cache do sistema
php artisan cache:clear
php artisan config:clear</code></pre>
                    </div>

                    <h3>Configuração do Cron</h3>
                    <p>Para automatizar a execução dos comandos, configure o cron do servidor:</p>

                    <div class="code-container">
                        <div class="code-header">
                            <span><i class="fas fa-clock"></i> Configuração Crontab</span>
                            <button class="copy-btn" onclick="copyCode(this)">Copiar</button>
                        </div>
                        <pre><code class="language-bash"># Editar crontab
crontab -e

# Adicionar as seguintes linhas:
# Execução do scheduler do Laravel (obrigatório)
* * * * * cd /var/www/credvance && php artisan schedule:run >> /dev/null 2>&1

# Envio de boletos diário às 09:00
0 9 * * * cd /var/www/credvance && php artisan boleto:enviar-todos

# Reemissão de boletos às 10:00
0 10 * * * cd /var/www/credvance && php artisan pagamento:reemite-boleto --all</code></pre>
                    </div>

                    <h3>Configuração de Filas</h3>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <div>
                            <strong>Recomendação:</strong> Para ambientes de produção, configure filas para processamento assíncrono de e-mails e operações pesadas.
                        </div>
                    </div>

                    <div class="code-container">
                        <div class="code-header">
                            <span><i class="fas fa-cogs"></i> Configuração de Filas</span>
                            <button class="copy-btn" onclick="copyCode(this)">Copiar</button>
                        </div>
                        <pre><code class="language-bash"># Iniciar worker de filas
php artisan queue:work --daemon

# Configurar supervisor para manter worker ativo
sudo nano /etc/supervisor/conf.d/laravel-worker.conf

# Conteúdo do arquivo supervisor:
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/credvance/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/credvance/storage/logs/worker.log</code></pre>
                    </div>
                </div>
            </section>

            <!-- Recomendações -->
            <section class="section" id="recomendacoes">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="fas fa-lightbulb section-icon"></i>
                        Recomendações e Boas Práticas
                    </h2>
                    <button class="collapse-btn" onclick="toggleSection('recomendacoes')">
                        <i class="fas fa-chevron-up"></i>
                    </button>
                </div>
                <div class="section-content">
                    <h3>Segurança e Performance</h3>
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-shield-alt"></i>
                            <h3 class="card-title">Segurança</h3>
                        </div>
                        <ul class="custom-list">
                            <li><strong>Certificados SSL:</strong> Mantenha certificados da API Inter sempre atualizados</li>
                            <li><strong>Logs sensíveis:</strong> Não registre dados sensíveis em logs públicos</li>
                            <li><strong>Permissões:</strong> Configure permissões corretas em <code>storage/app/private/boletos</code></li>
                            <li><strong>Backup:</strong> Faça backup regular da tabela <code>boleto_logs</code></li>
                        </ul>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-tachometer-alt"></i>
                            <h3 class="card-title">Performance</h3>
                        </div>
                        <ul class="custom-list">
                            <li><strong>Filas:</strong> Use queues para envio assíncrono de e-mails</li>
                            <li><strong>Cache:</strong> Implemente cache para consultas frequentes à API</li>
                            <li><strong>Índices:</strong> Mantenha índices otimizados nas tabelas de log</li>
                            <li><strong>Limpeza:</strong> Implemente rotina de limpeza de logs antigos</li>
                        </ul>
                    </div>

                    <h3>Monitoramento</h3>
                    <div class="alert alert-warning">
                        <i class="fas fa-chart-line"></i>
                        <div>
                            <strong>Métricas Importantes:</strong> Monitore taxa de entrega, abertura de e-mails, falhas de API e tempo de resposta das operações.
                        </div>
                    </div>

                    <div class="code-container">
                        <div class="code-header">
                            <span><i class="fas fa-chart-bar"></i> Consultas de Monitoramento</span>
                            <button class="copy-btn" onclick="copyCode(this)">Copiar</button>
                        </div>
                        <pre><code class="language-sql">-- Taxa de abertura de e-mails (últimos 30 dias)
SELECT 
    COUNT(*) as total_enviados,
    SUM(CASE WHEN aberto = 1 THEN 1 ELSE 0 END) as total_abertos,
    ROUND((SUM(CASE WHEN aberto = 1 THEN 1 ELSE 0 END) * 100.0 / COUNT(*)), 2) as taxa_abertura
FROM boleto_logs 
WHERE enviado_em >= DATE_SUB(NOW(), INTERVAL 30 DAY);

-- Boletos por status (API Inter)
SELECT status, COUNT(*) as quantidade 
FROM pagamentos 
WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
GROUP BY status;

-- Logs de erro do sistema
SELECT modulo, acao, COUNT(*) as erros
FROM sistema_logs 
WHERE resultado = 'erro' 
AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
GROUP BY modulo, acao;</code></pre>
                    </div>

                    <h3>Melhorias Futuras</h3>
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-rocket"></i>
                            <h3 class="card-title">Roadmap de Desenvolvimento</h3>
                        </div>
                        <ul class="custom-list">
                            <li><strong>Dashboard Analytics:</strong> Painel com métricas em tempo real</li>
                            <li><strong>Webhooks:</strong> Recebimento automático de status de pagamento</li>
                            <li><strong>Notificações:</strong> Alertas para falhas e baixa taxa de abertura</li>
                            <li><strong>API REST:</strong> Endpoints para integração com sistemas externos</li>
                            <li><strong>Relatórios:</strong> Exportação em PDF/Excel de logs e métricas</li>
                            <li><strong>Multi-banco:</strong> Integração com outros bancos além do Inter</li>
                        </ul>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>
    <script>
        // Theme Toggle
        const themeToggle = document.getElementById('themeToggle');
        const body = document.body;
        const icon = themeToggle.querySelector('i');

        // Load saved theme
        const savedTheme = localStorage.getItem('theme') || 'light';
        if (savedTheme === 'dark') {
            body.setAttribute('data-theme', 'dark');
            icon.className = 'fas fa-sun';
        }

        themeToggle.addEventListener('click', () => {
            const currentTheme = body.getAttribute('data-theme');
            if (currentTheme === 'dark') {
                body.removeAttribute('data-theme');
                icon.className = 'fas fa-moon';
                localStorage.setItem('theme', 'light');
            } else {
                body.setAttribute('data-theme', 'dark');
                icon.className = 'fas fa-sun';
                localStorage.setItem('theme', 'dark');
            }
        });

        // Mobile Menu Toggle
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');

        // Show menu toggle on mobile
        function checkMobile() {
            if (window.innerWidth <= 1024) {
                menuToggle.style.display = 'block';
            } else {
                menuToggle.style.display = 'none';
                sidebar.classList.remove('open');
            }
        }

        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
        });

        window.addEventListener('resize', checkMobile);
        checkMobile();

        // Search Functionality
        const searchInput = document.getElementById('searchInput');
        const sections = document.querySelectorAll('.section');

        searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            
            sections.forEach(section => {
                const content = section.textContent.toLowerCase();
                if (content.includes(searchTerm) || searchTerm === '') {
                    section.style.display = 'block';
                } else {
                    section.style.display = 'none';
                }
            });
        });

        // Smooth Scrolling for Navigation
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const targetId = link.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);
                
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    
                    // Update active link
                    document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
                    link.classList.add('active');
                    
                    // Close mobile menu
                    if (window.innerWidth <= 1024) {
                        sidebar.classList.remove('open');
                    }
                }
            });
        });

        // Section Collapse/Expand
        function toggleSection(sectionId) {
            const section = document.getElementById(sectionId);
            const content = section.querySelector('.section-content');
            const button = section.querySelector('.collapse-btn i');
            
            if (content.classList.contains('collapsed')) {
                content.classList.remove('collapsed');
                button.className = 'fas fa-chevron-up';
            } else {
                content.classList.add('collapsed');
                button.className = 'fas fa-chevron-down';
            }
        }

        // Copy Code Functionality
        function copyCode(button) {
            const codeBlock = button.closest('.code-container').querySelector('code');
            const text = codeBlock.textContent;
            
            navigator.clipboard.writeText(text).then(() => {
                const originalText = button.textContent;
                button.textContent = 'Copiado!';
                button.style.background = 'rgba(16, 185, 129, 0.2)';
                
                setTimeout(() => {
                    button.textContent = originalText;
                    button.style.background = 'rgba(255, 255, 255, 0.1)';
                }, 2000);
            });
        }

        // Highlight current section in navigation
        function updateActiveNavigation() {
            const sections = document.querySelectorAll('.section');
            const navLinks = document.querySelectorAll('.nav-link');
            
            let currentSection = '';
            
            sections.forEach(section => {
                const rect = section.getBoundingClientRect();
                if (rect.top <= 100 && rect.bottom >= 100) {
                    currentSection = section.id;
                }
            });
            
            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${currentSection}`) {
                    link.classList.add('active');
                }
            });
        }

        // Update navigation on scroll
        window.addEventListener('scroll', updateActiveNavigation);
        
        // Initialize
        updateActiveNavigation();
    </script>
</body>
</html>

