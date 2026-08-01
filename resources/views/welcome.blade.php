<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ setting('school_name', config('app.name', 'ZamEdu')) }} — Sistema de Gestão Escolar</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --green-900: #1B5E20;
            --green-800: #2E7D32;
            --green-700: #388E3C;
            --green-600: #43A047;
            --green-500: #4CAF50;
            --green-100: #E8F5E9;
            --green-50: #F1F8E9;
            --yellow-600: #F9A825;
            --yellow-500: #FFB300;
            --yellow-400: #FFC107;
            --yellow-100: #FFF8E1;
            --gray-900: #212121;
            --gray-700: #616161;
            --gray-500: #9E9E9E;
            --gray-200: #EEEEEE;
            --gray-100: #F5F5F5;
            --gray-50: #FAFAFA;
            --white: #FFFFFF;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--gray-900);
            background: var(--gray-50);
            line-height: 1.6;
        }

        h1, h2, h3, h4, .heading {
            font-family: 'Poppins', sans-serif;
        }

        a { text-decoration: none; color: inherit; }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* ============ TOP BAR ============ */
        .top-bar {
            background: var(--white);
            border-bottom: 1px solid var(--gray-200);
            padding: 12px 0;
        }

        .top-bar .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .brand-logo {
            width: 48px;
            height: 48px;
            background: var(--green-800);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 22px;
            flex-shrink: 0;
        }

        .brand-text h1 {
            font-size: 18px;
            font-weight: 700;
            color: var(--gray-900);
            line-height: 1.2;
        }

        .brand-text span {
            font-size: 12px;
            color: var(--gray-700);
            font-weight: 400;
        }

        .top-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .top-actions .btn-login {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 22px;
            background: var(--green-800);
            color: var(--white);
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: background 0.2s;
        }

        .top-actions .btn-login:hover {
            background: var(--green-900);
        }

        .top-actions .btn-demo {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            background: var(--white);
            color: var(--green-800);
            border: 2px solid var(--green-800);
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s;
        }

        .top-actions .btn-demo:hover {
            background: var(--green-100);
        }

        /* ============ YELLOW ACCENT BAR ============ */
        .accent-bar {
            height: 4px;
            background: linear-gradient(90deg, var(--yellow-500), var(--yellow-400), var(--green-500));
        }

        /* ============ NAVBAR ============ */
        .main-nav {
            background: var(--green-800);
            padding: 0;
        }

        .main-nav .container {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 0;
        }

        .nav-links a {
            display: block;
            padding: 14px 24px;
            color: var(--white);
            font-size: 14px;
            font-weight: 500;
            transition: background 0.2s;
            white-space: nowrap;
        }

        .nav-links a:hover,
        .nav-links a.active {
            background: rgba(255,255,255,0.15);
        }

        .nav-links a i {
            margin-right: 6px;
        }

        /* ============ HERO / CAROUSEL ============ */
        .hero-section {
            background: linear-gradient(135deg, var(--green-900) 0%, var(--green-700) 100%);
            position: relative;
            overflow: hidden;
        }

        .hero-inner {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
            padding: 80px 0;
        }

        .hero-content h2 {
            font-size: 42px;
            font-weight: 800;
            color: var(--white);
            line-height: 1.2;
            margin-bottom: 20px;
        }

        .hero-content h2 .highlight {
            color: var(--yellow-400);
        }

        .hero-content p {
            font-size: 17px;
            color: rgba(255,255,255,0.85);
            margin-bottom: 32px;
            line-height: 1.7;
        }

        .hero-buttons {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
        }

        .btn-hero-primary {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 28px;
            background: var(--yellow-500);
            color: var(--gray-900);
            font-weight: 700;
            font-size: 15px;
            border-radius: 8px;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }

        .btn-hero-primary:hover {
            background: var(--yellow-400);
            transform: translateY(-1px);
        }

        .btn-hero-secondary {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 28px;
            background: transparent;
            color: var(--white);
            font-weight: 600;
            font-size: 15px;
            border-radius: 8px;
            border: 2px solid rgba(255,255,255,0.4);
            transition: all 0.2s;
            cursor: pointer;
        }

        .btn-hero-secondary:hover {
            border-color: var(--white);
            background: rgba(255,255,255,0.1);
        }

        .hero-image {
            position: relative;
        }

        .hero-image img {
            width: 100%;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .hero-badges {
            display: flex;
            gap: 24px;
            margin-top: 32px;
        }

        .hero-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(255,255,255,0.8);
            font-size: 13px;
            font-weight: 500;
        }

        .hero-badge i {
            color: var(--yellow-400);
            font-size: 14px;
        }

        /* ============ QUICK ACCESS SECTION ============ */
        .quick-access {
            padding: 60px 0;
            background: var(--white);
            border-bottom: 1px solid var(--gray-200);
        }

        .section-header {
            text-align: center;
            margin-bottom: 48px;
        }

        .section-header h2 {
            font-size: 30px;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 8px;
        }

        .section-header .underline-accent {
            width: 60px;
            height: 4px;
            background: var(--green-600);
            margin: 12px auto 16px;
            border-radius: 2px;
        }

        .section-header p {
            font-size: 16px;
            color: var(--gray-700);
            max-width: 600px;
            margin: 0 auto;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .service-card {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            padding: 32px 28px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--green-600);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .service-card:hover {
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
            transform: translateY(-4px);
            border-color: var(--green-600);
        }

        .service-card:hover::before {
            transform: scaleX(1);
        }

        .service-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 18px;
        }

        .service-icon.green {
            background: var(--green-100);
            color: var(--green-800);
        }

        .service-icon.yellow {
            background: var(--yellow-100);
            color: var(--yellow-600);
        }

        .service-icon.blue {
            background: #E3F2FD;
            color: #1565C0;
        }

        .service-card h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 10px;
        }

        .service-card p {
            font-size: 14px;
            color: var(--gray-700);
            line-height: 1.6;
            margin-bottom: 16px;
        }

        .service-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
            font-weight: 600;
            color: var(--green-800);
            transition: gap 0.2s;
        }

        .service-link:hover {
            gap: 10px;
            color: var(--green-600);
        }

        /* ============ FEATURES ============ */
        .features-section {
            padding: 60px 0;
            background: var(--gray-50);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .feature-card {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            padding: 28px 24px;
            display: flex;
            gap: 16px;
            align-items: flex-start;
            transition: all 0.25s ease;
        }

        .feature-card:hover {
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            border-color: var(--green-500);
        }

        .feature-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: var(--green-100);
            color: var(--green-800);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .feature-card h4 {
            font-family: 'Inter', sans-serif;
            font-size: 15px;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 6px;
        }

        .feature-card p {
            font-size: 13px;
            color: var(--gray-700);
            line-height: 1.5;
        }

        /* ============ ABOUT SECTION ============ */
        .about-section {
            padding: 60px 0;
            background: var(--white);
            border-top: 1px solid var(--gray-200);
        }

        .about-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }

        .about-image img {
            width: 100%;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        }

        .about-content h2 {
            font-size: 28px;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 16px;
        }

        .about-content p {
            font-size: 15px;
            color: var(--gray-700);
            line-height: 1.7;
            margin-bottom: 20px;
        }

        .about-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-top: 24px;
        }

        .stat-box {
            text-align: center;
            padding: 16px;
            background: var(--green-50);
            border-radius: 10px;
            border: 1px solid var(--green-100);
        }

        .stat-box .number {
            font-family: 'Poppins', sans-serif;
            font-size: 28px;
            font-weight: 800;
            color: var(--green-800);
        }

        .stat-box .label {
            font-size: 12px;
            color: var(--gray-700);
            font-weight: 500;
            margin-top: 4px;
        }

        /* ============ FOOTER ============ */
        .site-footer {
            background: var(--gray-900);
            color: rgba(255,255,255,0.7);
            padding: 48px 0 0;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 40px;
            padding-bottom: 40px;
        }

        .footer-brand h3 {
            font-family: 'Poppins', sans-serif;
            font-size: 20px;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 12px;
        }

        .footer-brand p {
            font-size: 14px;
            line-height: 1.7;
            margin-bottom: 16px;
        }

        .footer-brand .powered-by {
            font-size: 12px;
            color: rgba(255,255,255,0.5);
        }

        .footer-brand .powered-by strong {
            color: var(--yellow-400);
        }

        .footer-col h4 {
            font-size: 14px;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 16px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .footer-col ul {
            list-style: none;
        }

        .footer-col ul li {
            margin-bottom: 10px;
        }

        .footer-col ul li a {
            font-size: 14px;
            color: rgba(255,255,255,0.65);
            transition: color 0.2s;
        }

        .footer-col ul li a:hover {
            color: var(--yellow-400);
        }

        .footer-col ul li i {
            margin-right: 8px;
            color: var(--green-500);
            width: 16px;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.1);
            padding: 20px 0;
            text-align: center;
            font-size: 13px;
            color: rgba(255,255,255,0.4);
        }

        /* ============ MOBILE MENU ============ */
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--white);
            font-size: 22px;
            cursor: pointer;
            padding: 10px;
        }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 992px) {
            .hero-inner {
                grid-template-columns: 1fr;
                padding: 50px 0;
                text-align: center;
            }
            .hero-content h2 { font-size: 32px; }
            .hero-buttons { justify-content: center; }
            .hero-badges { justify-content: center; }
            .hero-image { display: none; }
            .services-grid { grid-template-columns: 1fr; }
            .features-grid { grid-template-columns: 1fr; }
            .about-grid { grid-template-columns: 1fr; }
            .footer-grid { grid-template-columns: 1fr; }

            .nav-links { display: none; }
            .mobile-toggle { display: block; }
            .nav-links.open {
                display: flex;
                flex-direction: column;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: var(--green-800);
                z-index: 100;
                box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            }
        }

        @media (max-width: 600px) {
            .top-actions .btn-demo { display: none; }
            .hero-content h2 { font-size: 26px; }
            .about-stats { grid-template-columns: 1fr; }
            .hero-badges { flex-direction: column; align-items: center; }
        }
    </style>
</head>

<body>

    <!-- ====== TOP HEADER BAR ====== -->
    <header class="top-bar">
        <div class="container">
            <a href="{{ url('/') }}" class="brand">
                <div class="brand-logo">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="brand-text">
                    <h1>{{ setting('school_name', config('app.name', 'ZamEdu')) }}</h1>
                    <span>Sistema de Gestão Escolar</span>
                </div>
            </a>

            <div class="top-actions">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-login">
                        <i class="fas fa-chart-line"></i> Painel de Controlo
                    </a>
                @else
                    <a href="{{ route('demo.access') }}" class="btn-demo">
                        <i class="fas fa-play-circle"></i> Demo
                    </a>
                    <a href="{{ route('login') }}" class="btn-login">
                        <i class="fas fa-right-to-bracket"></i> Entrar no Sistema
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- ====== YELLOW ACCENT BAR ====== -->
    <div class="accent-bar"></div>

    <!-- ====== GREEN NAVIGATION ====== -->
    <nav class="main-nav" style="position: relative;">
        <div class="container">
            <button class="mobile-toggle" id="mobileToggle">
                <i class="fas fa-bars"></i>
            </button>
            <ul class="nav-links" id="navLinks">
                <li><a href="#inicio" class="active"><i class="fas fa-home"></i> Início</a></li>
                <li><a href="#servicos"><i class="fas fa-concierge-bell"></i> Serviços</a></li>
                <li><a href="#recursos"><i class="fas fa-cogs"></i> Recursos</a></li>
                <li><a href="#sobre"><i class="fas fa-info-circle"></i> Sobre</a></li>
                <li><a href="#contacto"><i class="fas fa-envelope"></i> Contactos</a></li>
            </ul>
        </div>
    </nav>

    <!-- ====== HERO SECTION ====== -->
    <section class="hero-section" id="inicio">
        <div class="container">
            <div class="hero-inner">

                <div class="hero-content">
                    <h2>
                        Gestão Escolar <span class="highlight">Inteligente</span> para a sua Instituição
                    </h2>
                    <p>
                        Bem-vindo ao <strong>{{ setting('school_name', config('app.name', 'ZamEdu')) }}</strong>.
                        Plataforma integrada para controlo académico, gestão de propinas,
                        comunicação com encarregados e emissão automática de pautas e boletins.
                    </p>

                    <div class="hero-buttons">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn-hero-primary">
                                <i class="fas fa-gauge-high"></i> Acessar Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn-hero-primary">
                                <i class="fas fa-right-to-bracket"></i> Entrar na Conta
                            </a>
                            <a href="{{ route('public.pre-enrollment') }}" class="btn-hero-secondary">
                                <i class="fas fa-file-pen"></i> Pré-Matrícula Online
                            </a>
                        @endauth
                    </div>

                    <div class="hero-badges">
                        <div class="hero-badge">
                            <i class="fas fa-shield-halved"></i>
                            <span>100% Seguro</span>
                        </div>
                        <div class="hero-badge">
                            <i class="fas fa-mobile-screen-button"></i>
                            <span>Acesso Mobile</span>
                        </div>
                        <div class="hero-badge">
                            <i class="fas fa-credit-card"></i>
                            <span>Integração M-Pesa / e-Mola</span>
                        </div>
                    </div>
                </div>

                <div class="hero-image">
                    <img src="{{ asset('images/hero-showcase.png') }}"
                         alt="Painel {{ setting('school_name', 'ZamEdu') }}"
                         onerror="this.parentElement.style.display='none'">
                </div>

            </div>
        </div>
    </section>

    <!-- ====== SERVIÇOS ONLINE ====== -->
    <section class="quick-access" id="servicos">
        <div class="container">

            <div class="section-header">
                <h2>Serviços Online</h2>
                <div class="underline-accent"></div>
                <p>Acesse os principais serviços da escola sem deslocação. Tudo disponível online, 24 horas por dia.</p>
            </div>

            <div class="services-grid">
                <!-- Card 1 -->
                <div class="service-card">
                    <div class="service-icon green">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h3>Pré-Matrícula Online</h3>
                    <p>Inicie o processo de candidatura do seu educando sem filas. Preencha os dados e receba a confirmação da secretaria.</p>
                    <a href="{{ route('public.pre-enrollment') }}" class="service-link">
                        Inscrever Aluno <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <!-- Card 2 -->
                <div class="service-card">
                    <div class="service-icon yellow">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h3>Listas de Material</h3>
                    <p>Consulte a lista de livros, manuais e materiais escolares recomendados para cada ano de escolaridade.</p>
                    <a href="{{ route('public.material-lists') }}" class="service-link">
                        Ver Lista Escolar <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <!-- Card 3 -->
                <div class="service-card">
                    <div class="service-icon blue">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <h3>Avisos & Propinas</h3>
                    <p>Aceda a comunicados oficiais, calendário académico e verificação de referências de pagamento.</p>
                    <a href="{{ route('public.announcements') }}" class="service-link">
                        Consultar Avisos <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

        </div>
    </section>

    <!-- ====== RECURSOS DO SISTEMA ====== -->
    <section class="features-section" id="recursos">
        <div class="container">

            <div class="section-header">
                <h2>Funcionalidades do Sistema</h2>
                <div class="underline-accent"></div>
                <p>Construído para atender diretores, professores, secretaria e encarregados de educação.</p>
            </div>

            <div class="features-grid">

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div>
                        <h4>Gestão Académica</h4>
                        <p>Turmas, disciplinas, atribuição de professores e emissão automática de cadernetas de notas.</p>
                    </div>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div>
                        <h4>Propinas & Pagamentos</h4>
                        <p>Geração de mensalidades, aplicação de multas por atraso e conciliação de recibos.</p>
                    </div>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <h4>Portal de Pais</h4>
                        <p>Encarregados acompanham faltas, notas e histórico financeiro no smartphone.</p>
                    </div>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chalkboard-user"></i>
                    </div>
                    <div>
                        <h4>Portal do Professor</h4>
                        <p>Lançamento de pautas ACS, ACP e ACF com cálculo imediato de médias e estatísticas.</p>
                    </div>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div>
                        <h4>Relatórios & Exportação</h4>
                        <p>Mapas financeiros, estatísticas de aprovação e exportação para PDF e Excel.</p>
                    </div>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-sliders"></i>
                    </div>
                    <div>
                        <h4>Personalizável</h4>
                        <p>Logótipo, paleta de cores e parâmetros de ano lectivo configuráveis por cada escola.</p>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- ====== SOBRE A INSTITUIÇÃO ====== -->
    <section class="about-section" id="sobre">
        <div class="container">
            <div class="about-grid">

                <div class="about-image">
                    <img src="https://images.unsplash.com/photo-1580582932707-520aed937b7b?auto=format&fit=crop&w=800&q=80"
                         alt="Sala de Aula">
                </div>

                <div class="about-content">
                    <h2>Compromisso com a Educação de Qualidade</h2>
                    <p>
                        O <strong>{{ setting('school_name', config('app.name', 'ZamEdu')) }}</strong> é uma plataforma moderna de gestão escolar
                        desenvolvida para instituições de ensino em Moçambique. Combinamos rigor
                        académico com tecnologia acessível para melhorar a transparência administrativa
                        e a proximidade com as famílias.
                    </p>
                    <p>
                        O nosso sistema permite a gestão completa do ciclo escolar — desde a matrícula
                        até à emissão de boletins finais — com integração de pagamentos móveis e
                        comunicação em tempo real.
                    </p>

                    <div class="about-stats">
                        <div class="stat-box">
                            <div class="number">100%</div>
                            <div class="label">Digital</div>
                        </div>
                        <div class="stat-box">
                            <div class="number">24/7</div>
                            <div class="label">Disponível</div>
                        </div>
                        <div class="stat-box">
                            <div class="number">MZ</div>
                            <div class="label">Feito em Moçambique</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ====== FOOTER ====== -->
    <footer class="site-footer" id="contacto">
        <div class="container">
            <div class="footer-grid">

                <div class="footer-brand">
                    <h3><i class="fas fa-graduation-cap" style="margin-right: 8px;"></i>{{ setting('school_name', config('app.name', 'ZamEdu')) }}</h3>
                    <p>
                        Sistema Inteligente de Gestão Escolar. Desenvolvido para proporcionar
                        excelência académica, transparência administrativa e proximidade com as famílias.
                    </p>
                    <div class="powered-by">
                        Desenvolvido por <strong>FDS Software</strong>
                    </div>
                </div>

                <div class="footer-col">
                    <h4>Links Úteis</h4>
                    <ul>
                        <li><a href="{{ route('login') }}"><i class="fas fa-right-to-bracket"></i>Entrar no Sistema</a></li>
                        <li><a href="{{ route('public.pre-enrollment') }}"><i class="fas fa-user-plus"></i>Pré-Matrícula</a></li>
                        <li><a href="{{ route('public.material-lists') }}"><i class="fas fa-book-open"></i>Listas de Material</a></li>
                        <li><a href="{{ route('public.payment-check') }}"><i class="fas fa-receipt"></i>Verificar Pagamento</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>Contactos</h4>
                    <ul>
                        <li><a href="tel:{{ setting('phone', '+258 84 000 0000') }}"><i class="fas fa-phone"></i>{{ setting('phone', '+258 84 000 0000') }}</a></li>
                        <li><a href="mailto:{{ setting('email', 'contacto@zamedu.co.mz') }}"><i class="fas fa-envelope"></i>{{ setting('email', 'contacto@zamedu.co.mz') }}</a></li>
                        <li><span><i class="fas fa-location-dot"></i>{{ setting('address', 'Maputo, Moçambique') }}</span></li>
                    </ul>
                </div>

            </div>

            <div class="footer-bottom">
                &copy; {{ date('Y') }} {{ setting('school_name', config('app.name', 'ZamEdu')) }}. Todos os direitos reservados.
            </div>
        </div>
    </footer>

    <!-- ====== SCRIPTS ====== -->
    <script>
        document.getElementById('mobileToggle')?.addEventListener('click', function () {
            document.getElementById('navLinks')?.classList.toggle('open');
        });

        // Smooth scroll for nav links
        document.querySelectorAll('.nav-links a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
                document.getElementById('navLinks')?.classList.remove('open');
            });
        });
    </script>

</body>
</html>
