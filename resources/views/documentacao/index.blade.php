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
    <div class="header">
        <div class="logo">
            <i class="fas fa-book"></i> CredVance Docs
        </div>
        <div class="header-controls">
            <div class="search-box">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" placeholder="Buscar...">
            </div>
            <button class="theme-toggle" onclick="toggleTheme()">
                <i class="fas fa-moon"></i>
            </button>
        </div>
    </div>

    <div class="container">
        <aside class="sidebar">
            <div class="sidebar-content">
                @foreach($sections as $section)
                    <div class="nav-section">
                        <div class="nav-title">{{ $section->title }}</div>
                        <ul class="nav-list">
                            @foreach($section->articles as $article)
                                <li class="nav-item">
                                    <a href="#{{ $article->slug }}" class="nav-link">
                                        <i class="{{ Str::startsWith($article->icon ?? '', 'fa') ? $article->icon : 'fas ' . ($article->icon ?? 'fa-circle') }} nav-icon"></i>
                                        {{ $article->title }}
                                    </a>
                                </li>
                            @endforeach

                        </ul>
                    </div>
                @endforeach
            </div>
        </aside>

        <main class="main-content">
            <div class="content-header">
                <h1 class="page-title">📘 Documentação</h1>
                <p class="page-subtitle">Guia completo de uso do sistema CredVance.</p>
            </div>

            @foreach($sections as $section)
                <div class="section">
                    <div class="section-header">
                        <h2 class="section-title"><i class="fas {{ $section->icon }} section-icon"></i> {{ $section->title }}</h2>
                    </div>
                    <div class="section-content">
                        @foreach($section->articles as $article)
                            <div class="article" id="{{ $article->slug }}">
                                <h3>{{ $article->title }}</h3>
                                <div class="content">{!! $article->content !!}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </main>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const isDark = html.getAttribute('data-theme') === 'dark';
            html.setAttribute('data-theme', isDark ? 'light' : 'dark');
            localStorage.setItem('theme', isDark ? 'light' : 'dark');
        }

        (function () {
            const stored = localStorage.getItem('theme');
            if (stored) {
                document.documentElement.setAttribute('data-theme', stored);
            }
        })();
    </script>
</body>
   
</html>

