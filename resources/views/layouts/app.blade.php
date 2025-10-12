<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistema Visionários') }} - @yield('title', 'Gestão Escolar')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            /* Cores baseadas no logo da "Escola dos Visionários" */

            /* 1. Azul do Livro */
            --primary: #19437C;
            /* Azul Escuro (Livro) - Profissional e Confiável */
            --primary-dark: #103058;
            /* Azul mais escuro para hover/bordas */
            --primary-light: #3669A7;
            /* Azul mais claro para toques sutis */

            /* 2. Verde do Globo */
            --secondary: #4BA83C;
            /* Verde Vibrante (Globo) - Crescimento e Renovação */
            --secondary-dark: #377E2A;
            /* Verde mais escuro */

            /* 3. Laranja/Amarelo do Sol */
            --accent: #F9A825;
            /* Amarelo/Laranja Dourado (Raio de Sol) - Energia e Otimismo */

            /* Cores de Status - Harmonizadas */
            --success: #4BA83C;
            /* Verde (igual ao --secondary) */
            --warning: #F9A825;
            /* Amarelo/Laranja (igual ao --accent) */
            --danger: #DC3545;
            /* Vermelho padrão, profissional e claro */
            --info: #3669A7;
            /* Azul informação (igual ao --primary-light) */

            /* Fundo da Barra Lateral (Sidebar) */
            --sidebar-bg: linear-gradient(180deg, #19437C 0%, #103058 100%);
            --sidebar-text: #FFFFFF;
            --sidebar-text-muted: rgba(255, 255, 255, 0.75);
            --sidebar-active: rgba(255, 255, 255, 0.1);
            --sidebar-hover: rgba(255, 255, 255, 0.06);

            /* Cores de Conteúdo e Texto */
            --content-bg: #f7fafc;
            --card-bg: #FFFFFF;
            --border-color: #e2e8f0;
            --text-primary: #1a202c;
            --text-secondary: #4a5568;
            --text-muted: #718096;

            /* Tamanhos (Mantidos) */
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 65px;
            --header-height: 65px;
            --border-radius: 10px;
            --shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        [data-bs-theme="dark"] {
            /* Ajustes para o Modo Escuro */
            --content-bg: #0F1A2B;
            /* Fundo escuro baseado no primary-dark */
            --card-bg: #1A2E4B;
            /* Cartões em tom de azul escuro */
            --border-color: #334155;
            --text-primary: #f1f5f9;
            --text-secondary: #cbd5e1;
            --text-muted: #64748b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background-color: var(--content-bg);
            color: var(--text-primary);
            line-height: 1.5;
            font-size: 13px;
            /* Reduzido de 14px */
            overflow-x: hidden;
        }

        /* ===== SIDEBAR ESCOLAR ===== */
        .school-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            z-index: 1040;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-lg);
            display: flex;
            flex-direction: column;
        }

        .school-sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        /* Header da Escola - Reduzido */
        .school-header {
            height: var(--header-height);
            padding: 15px 18px;
            /* Reduzido */
            display: flex;
            align-items: center;
            background: rgba(0, 0, 0, 0.15);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .school-logo {
            width: 42px;
            /* Reduzido */
            height: 42px;
            /* Reduzido */
            background: linear-gradient(135deg, var(--secondary), var(--secondary-dark));
            border-radius: 12px;
            /* Reduzido */
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            /* Reduzido */
            flex-shrink: 0;
            box-shadow: 0 3px 12px rgba(212, 175, 55, 0.3);
        }

        .school-logo i {
            color: white;
            font-size: 20px;
            /* Reduzido */
        }

        .school-brand {
            flex: 1;
            min-width: 0;
            transition: all 0.3s ease;
        }

        .school-sidebar.collapsed .school-brand {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .school-name {
            font-size: 16px;
            /* Reduzido */
            font-weight: 700;
            color: var(--sidebar-text);
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }

        .school-subtitle {
            font-size: 11px;
            /* Reduzido */
            color: var(--sidebar-text-muted);
            font-weight: 500;
            margin-top: 1px;
        }

        .sidebar-toggle {
            width: 32px;
            /* Reduzido */
            height: 32px;
            /* Reduzido */
            border: none;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--sidebar-text);
            cursor: pointer;
            transition: all 0.3s ease;
            margin-left: 8px;
            /* Reduzido */
        }

        .sidebar-toggle:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.05);
        }

        /* Navegação Escolar - Reduzida */
        .school-nav {
            flex: 1;
            overflow-y: auto;
            padding: 15px 0;
            /* Reduzido */
        }

        .nav-section {
            margin-bottom: 20px;
            /* Reduzido */
        }

        .nav-section-title {
            padding: 0 18px 10px;
            /* Reduzido */
            font-size: 10px;
            /* Reduzido */
            font-weight: 600;
            color: var(--sidebar-text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .school-sidebar.collapsed .nav-section-title {
            opacity: 0;
            height: 0;
            padding: 0;
            margin: 0;
        }

        .nav-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-item {
            margin-bottom: 3px;
            /* Reduzido */
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 18px;
            /* Reduzido */
            color: var(--sidebar-text);
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            min-height: 44px;
            /* Reduzido */
            font-weight: 500;
        }

        .school-sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 12px;
        }

        .nav-link:hover {
            background: var(--sidebar-hover);
            color: var(--sidebar-text);
            padding-left: 22px;
            /* Reduzido */
        }

        .school-sidebar.collapsed .nav-link:hover {
            padding: 12px;
        }

        .nav-link.active {
            background: var(--sidebar-active);
            color: var(--sidebar-text);
            border-right: 3px solid var(--secondary);
            /* Reduzido */
            font-weight: 600;
        }

        .nav-icon {
            width: 20px;
            /* Reduzido */
            height: 20px;
            /* Reduzido */
            margin-right: 12px;
            /* Reduzido */
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 16px;
            /* Reduzido */
        }

        .school-sidebar.collapsed .nav-icon {
            margin-right: 0;
        }

        .nav-text {
            flex: 1;
            font-size: 13px;
            /* Reduzido */
            white-space: nowrap;
            transition: all 0.3s ease;
        }

        .school-sidebar.collapsed .nav-text {
            opacity: 0;
            width: 0;
        }

        .nav-badge {
            margin-left: auto;
            font-size: 9px;
            /* Reduzido */
            padding: 3px 6px;
            /* Reduzido */
            border-radius: 12px;
            /* Reduzido */
            font-weight: 600;
            min-width: 18px;
            /* Reduzido */
            text-align: center;
            transition: all 0.3s ease;
        }

        .school-sidebar.collapsed .nav-badge {
            opacity: 0;
            transform: scale(0);
        }

        .badge-primary {
            background: var(--accent);
            color: white;
        }

        .badge-success {
            background: var(--success);
            color: white;
        }

        .badge-warning {
            background: var(--warning);
            color: white;
        }

        .badge-danger {
            background: var(--danger);
            color: white;
        }

        /* Área do Usuário - Reduzida */
        .user-area {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 15px 18px;
            /* Reduzido */
            background: rgba(0, 0, 0, 0.15);
        }

        .user-profile {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            /* Reduzido */
        }

        .school-sidebar.collapsed .user-profile {
            justify-content: center;
            margin-bottom: 8px;
            /* Reduzido */
        }

        .user-avatar {
            width: 40px;
            /* Reduzido */
            height: 40px;
            /* Reduzido */
            border-radius: 50%;
            background: linear-gradient(135deg, var(--secondary), var(--secondary-dark));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            /* Reduzido */
            font-size: 16px;
            /* Reduzido */
            font-weight: 700;
            flex-shrink: 0;
            box-shadow: 0 3px 12px rgba(212, 175, 55, 0.3);
        }

        .school-sidebar.collapsed .user-avatar {
            margin-right: 0;
        }

        .user-info {
            flex: 1;
            min-width: 0;
            transition: all 0.3s ease;
        }

        .school-sidebar.collapsed .user-info {
            opacity: 0;
            width: 0;
        }

        .user-name {
            font-size: 13px;
            /* Reduzido */
            font-weight: 600;
            color: var(--sidebar-text);
            margin-bottom: 1px;
        }

        .user-role {
            font-size: 11px;
            /* Reduzido */
            color: var(--sidebar-text-muted);
            font-weight: 500;
        }

        .logout-btn {
            width: 100%;
            padding: 10px 12px;
            /* Reduzido */
            border: none;
            background: rgba(229, 62, 62, 0.9);
            color: white;
            border-radius: var(--border-radius);
            font-size: 12px;
            /* Reduzido */
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .school-sidebar.collapsed .logout-btn {
            padding: 10px;
        }

        .logout-btn:hover {
            background: var(--danger);
            transform: translateY(-1px);
            box-shadow: 0 3px 12px rgba(229, 62, 62, 0.3);
        }

        .logout-text {
            margin-left: 6px;
            /* Reduzido */
            transition: all 0.3s ease;
        }

        .school-sidebar.collapsed .logout-text {
            opacity: 0;
            width: 0;
        }

        /* ===== CONTEÚDO PRINCIPAL ===== */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        .main-content.collapsed {
            margin-left: var(--sidebar-collapsed-width);
        }

        .main-content.expanded {
            margin-left: 0;
        }

        /* Header Principal - Reduzido */
        .main-header {
            height: var(--header-height);
            background: var(--card-bg);
            border-bottom: 1px solid var(--border-color);
            padding: 0 25px;
            /* Reduzido */
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1030;
            box-shadow: var(--shadow);
        }

        .header-left {
            display: flex;
            align-items: center;
        }

        .mobile-menu-btn {
            width: 40px;
            /* Reduzido */
            height: 40px;
            /* Reduzido */
            border: 1px solid var(--border-color);
            background: var(--card-bg);
            border-radius: var(--border-radius);
            display: none;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            /* Reduzido */
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .mobile-menu-btn:hover {
            background: var(--content-bg);
            color: var(--primary);
            transform: translateY(-1px);
        }

        .page-title {
            font-size: 20px;
            /* Reduzido */
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
            display: flex;
            align-items: center;
            font-family: 'Poppins', sans-serif;
        }

        .page-title i {
            margin-right: 10px;
            /* Reduzido */
            color: var(--primary);
            font-size: 18px;
            /* Reduzido */
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 12px;
            /* Reduzido */
        }

        .header-search {
            position: relative;
        }

        .search-input {
            width: 280px;
            /* Reduzido */
            padding: 10px 40px 10px 16px;
            /* Reduzido */
            border: 1px solid var(--border-color);
            border-radius: 20px;
            /* Reduzido */
            font-size: 13px;
            /* Reduzido */
            background: var(--content-bg);
            color: var(--text-primary);
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(26, 54, 93, 0.1);
            background: var(--card-bg);
        }

        .search-input::placeholder {
            color: var(--text-muted);
        }

        .search-icon {
            position: absolute;
            right: 12px;
            /* Reduzido */
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 14px;
            /* Reduzido */
        }

        .header-btn {
            width: 40px;
            /* Reduzido */
            height: 40px;
            /* Reduzido */
            border: 1px solid var(--border-color);
            background: var(--card-bg);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .header-btn:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 3px 12px rgba(26, 54, 93, 0.3);
        }

        .notification-badge {
            position: absolute;
            top: -3px;
            /* Reduzido */
            right: -3px;
            /* Reduzido */
            background: var(--danger);
            color: white;
            font-size: 9px;
            /* Reduzido */
            padding: 2px 5px;
            /* Reduzido */
            border-radius: 12px;
            /* Reduzido */
            min-width: 16px;
            /* Reduzido */
            height: 16px;
            /* Reduzido */
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        /* Área de Conteúdo - Reduzida */
        .content-area {
            flex: 1;
            padding: 25px;
            /* Reduzido */
            background: var(--content-bg);
        }

        /* Cards de Estatísticas Escolares - Reduzidos */
        .school-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            /* Reduzido */
            gap: 20px;
            /* Reduzido */
            margin-bottom: 25px;
            /* Reduzido */
        }

        .stat-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            padding: 25px;
            /* Reduzido */
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            /* Reduzido */
            background: var(--primary);
        }

        .stat-card.students::before {
            background: var(--primary);
        }

        .stat-card.teachers::before {
            background: var(--accent);
        }

        .stat-card.payments::before {
            background: var(--success);
        }

        .stat-card.events::before {
            background: var(--warning);
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        .stat-icon {
            width: 60px;
            /* Reduzido */
            height: 60px;
            /* Reduzido */
            border-radius: 15px;
            /* Reduzido */
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            /* Reduzido */
            font-size: 24px;
            /* Reduzido */
            color: white;
            flex-shrink: 0;
        }

        .stat-icon.students {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        }

        .stat-icon.teachers {
            background: linear-gradient(135deg, var(--accent), #c53030);
        }

        .stat-icon.payments {
            background: linear-gradient(135deg, var(--success), #2f855a);
        }

        .stat-icon.events {
            background: linear-gradient(135deg, var(--warning), #c05621);
        }

        .stat-content {
            flex: 1;
        }

        .stat-value {
            font-size: 28px;
            /* Reduzido */
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 3px;
            line-height: 1;
            font-family: 'Poppins', sans-serif;
        }

        .stat-label {
            font-size: 14px;
            /* Reduzido */
            color: var(--text-secondary);
            font-weight: 500;
            margin-bottom: 8px;
            /* Reduzido */
        }

        .stat-change {
            font-size: 12px;
            /* Reduzido */
            font-weight: 600;
            padding: 3px 8px;
            /* Reduzido */
            border-radius: 15px;
            /* Reduzido */
            display: inline-flex;
            align-items: center;
        }

        .stat-change.positive {
            background: rgba(56, 161, 105, 0.1);
            color: var(--success);
        }

        .stat-change.negative {
            background: rgba(229, 62, 62, 0.1);
            color: var(--danger);
        }

        .stat-change i {
            margin-right: 3px;
            /* Reduzido */
            font-size: 11px;
            /* Reduzido */
        }

        /* Breadcrumb Escolar - Reduzido */
        .school-breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 20px;
            /* Reduzido */
        }

        .breadcrumb {
            background: var(--card-bg);
            border-radius: var(--border-radius);
            padding: 12px 18px;
            /* Reduzido */
            box-shadow: var(--shadow);
        }

        .breadcrumb-item a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .breadcrumb-item a:hover {
            color: var(--primary-dark);
        }

        .breadcrumb-item.active {
            color: var(--text-secondary);
        }

        /* Cards Escolares - Reduzidos */
        .school-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .school-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .school-card-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 18px 22px;
            /* Reduzido */
            font-weight: 600;
            font-size: 16px;
            /* Reduzido */
            display: flex;
            align-items: center;
        }

        .school-card-header i {
            margin-right: 8px;
            /* Reduzido */
            font-size: 18px;
            /* Reduzido */
        }

        .school-card-body {
            padding: 22px;
            /* Reduzido */
        }

        /* Botões Escolares - Reduzidos */
        .btn-school {
            border-radius: var(--border-radius);
            font-weight: 600;
            transition: all 0.3s ease;
            padding: 10px 20px;
            /* Reduzido */
            font-size: 13px;
            /* Reduzido */
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }

        .btn-school i {
            margin-right: 6px;
            /* Reduzido */
        }

        .btn-school:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-primary-school {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
        }

        .btn-primary-school:hover {
            background: linear-gradient(135deg, var(--primary-dark), #0a1a2d);
            color: white;
        }

        .btn-secondary-school {
            background: linear-gradient(135deg, var(--secondary), var(--secondary-dark));
            color: white;
        }

        .btn-secondary-school:hover {
            background: linear-gradient(135deg, var(--secondary-dark), #9c7a1a);
            color: white;
        }

        .btn-success-school {
            background: linear-gradient(135deg, var(--success), #2f855a);
            color: white;
        }

        .btn-warning-school {
            background: linear-gradient(135deg, var(--warning), #c05621);
            color: white;
        }

        /* Tabelas Escolares - Reduzidas */
        .school-table-container {
            background: var(--card-bg);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            margin-bottom: 20px;
            /* Reduzido */
        }

        .school-table-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 18px 22px;
            /* Reduzido */
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .school-table-title {
            font-size: 16px;
            /* Reduzido */
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
        }

        .school-table-title i {
            margin-right: 8px;
            /* Reduzido */
        }

        .table-school {
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-school th {
            background: var(--content-bg);
            color: var(--text-primary);
            font-weight: 600;
            border-bottom: 2px solid var(--border-color);
            padding: 15px 18px;
            /* Reduzido */
            font-size: 13px;
            /* Reduzido */
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table-school td {
            padding: 15px 18px;
            /* Reduzido */
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color);
            font-size: 13px;
            /* Reduzido */
        }

        .table-school tbody tr:hover {
            background: var(--content-bg);
        }

        /* Alertas Escolares - Reduzidos */
        .alert-school {
            border: none;
            border-radius: var(--border-radius);
            padding: 15px 22px;
            /* Reduzido */
            margin-bottom: 20px;
            /* Reduzido */
            border-left: 4px solid;
            /* Reduzido */
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .alert-school i {
            margin-right: 10px;
            /* Reduzido */
            font-size: 16px;
            /* Reduzido */
        }

        .alert-success-school {
            background: rgba(56, 161, 105, 0.1);
            color: var(--success);
            border-left-color: var(--success);
        }

        .alert-warning-school {
            background: rgba(221, 107, 32, 0.1);
            color: var(--warning);
            border-left-color: var(--warning);
        }

        .alert-danger-school {
            background: rgba(229, 62, 62, 0.1);
            color: var(--danger);
            border-left-color: var(--danger);
        }

        .alert-info-school {
            background: rgba(49, 130, 206, 0.1);
            color: var(--info);
            border-left-color: var(--info);
        }

        /* Footer Escolar - Reduzido */
        .school-footer {
            background: var(--card-bg);
            border-top: 1px solid var(--border-color);
            padding: 20px 0;
            /* Reduzido */
            margin-top: auto;
            box-shadow: 0 -1px 8px rgba(0, 0, 0, 0.05);
        }

        /* Responsividade */
        @media (max-width: 1199.98px) {
            .main-content {
                margin-left: 0 !important;
            }

            .school-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .school-sidebar.mobile-visible {
                transform: translateX(0);
            }

            .sidebar-toggle-btn {
                display: none !important;
            }

            .mobile-menu-btn {
                display: flex !important;
            }

            .search-input {
                width: 220px;
                /* Reduzido */
            }
        }

        @media (max-width: 991.98px) {
            .content-area {
                padding: 18px;
                /* Reduzido */
            }

            .main-header {
                padding: 0 18px;
                /* Reduzido */
            }

            .search-input {
                width: 180px;
                /* Reduzido */
            }

            .page-title {
                font-size: 18px;
                /* Reduzido */
            }

            .sidebar-toggle-btn {
                display: none !important;
            }
        }

        @media (max-width: 767.98px) {
            .header-search {
                display: none;
            }

            .sidebar-toggle-btn {
                display: none !important;
            }

            .page-title {
                font-size: 16px;
                /* Reduzido */
            }

            .stat-card {
                padding: 18px;
                /* Reduzido */
            }

            .stat-icon {
                width: 50px;
                /* Reduzido */
                height: 50px;
                /* Reduzido */
                font-size: 20px;
                /* Reduzido */
            }

            .stat-value {
                font-size: 24px;
                /* Reduzido */
            }
        }

        /* Overlay para mobile */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1035;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        /* Sistema de Toasts - Reduzido */
        .toast-container {
            position: fixed;
            top: 15px;
            /* Reduzido */
            right: 15px;
            /* Reduzido */
            z-index: 9999;
        }

        .toast-school {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
            background: var(--card-bg);
            margin-bottom: 8px;
            /* Reduzido */
            min-width: 300px;
            /* Reduzido */
            overflow: hidden;
        }

        .toast-school .toast-header {
            background: var(--primary);
            color: white;
            font-weight: 600;
        }

        /* Loading Animation - Reduzido */
        .loading-school {
            position: relative;
            color: transparent !important;
        }

        .loading-school::after {
            content: "";
            position: absolute;
            width: 16px;
            /* Reduzido */
            height: 16px;
            /* Reduzido */
            top: 50%;
            left: 50%;
            margin-left: -8px;
            /* Reduzido */
            margin-top: -8px;
            /* Reduzido */
            border: 2px solid var(--primary);
            /* Reduzido */
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin-school 1s ease-in-out infinite;
        }

        @keyframes spin-school {
            to {
                transform: rotate(360deg);
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Toast Container -->
    <div class="toast-container" id="toast-container"></div>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <!-- Sidebar Escolar -->
    <nav class="school-sidebar" id="sidebar">
        <div class="school-header">
            <div class="school-logo" style="margin: -10px;">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="school-brand" style="margin: 10% ">
                <div class="school-name">VISIONÁRIOS</div>
                <div class="school-subtitle">Sistema Escolar</div>
            </div>
        </div>

        <div class="school-nav">
            <!-- Dashboard Principal -->
            <div class="nav-section">
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}"
                            class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <span class="nav-icon">
                                <i class="fas fa-tachometer-alt"></i>
                            </span>
                            <span class="nav-text">Dashboard</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- ========== PORTAL DO PROFESSOR (apenas para professores) ========== -->
            @if (auth()->user()->role === 'teacher')
                <div class="nav-section">
                    <div class="nav-section-title">Meu Portal</div>
                    <ul class="nav-list">
                        <!-- Dashboard -->
                        <li class="nav-item">
                            <a href="{{ route('teacher.dashboard') }}"
                                class="nav-link {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">
                                <span class="nav-icon">
                                    <i class="fas fa-home"></i>
                                </span>
                                <span class="nav-text">Início</span>
                            </a>
                        </li>

                        <!-- Minhas Turmas -->
                        <li class="nav-item">
                            <a href="{{ route('teacher.classes.index') }}"
                                class="nav-link {{ request()->routeIs('teacher.classes.*') ? 'active' : '' }}">
                                <span class="nav-icon">
                                    <i class="fas fa-chalkboard"></i>
                                </span>
                                <span class="nav-text">Minhas Turmas</span>
                                @php
                                    $classCount =
                                        auth()->user()->teacher?->classes()->active()->currentYear()->count() ?? 0;
                                @endphp
                                @if ($classCount > 0)
                                    <span class="nav-badge badge-success">{{ $classCount }}</span>
                                @endif
                            </a>
                        </li>

                        <!-- Presenças -->
                        @php
                            $teacher = auth()->user()->teacher ?? null;
                            $classes = $teacher?->classes()->active()->currentYear()->get();
                            $firstClass = $classes?->first();
                        @endphp

                        <li class="nav-item">
                            @if ($firstClass)
                                <a href="{{ route('teacher.attendance.class', $firstClass->id) }}"
                                    class="nav-link {{ request()->routeIs('teacher.attendance.*') ? 'active' : '' }}">
                                    <span class="nav-icon">
                                        <i class="fas fa-calendar-check"></i>
                                    </span>
                                    <span class="nav-text">Presenças</span>
                                    @if ($classes->count() > 1)
                                        <span class="nav-badge badge-info">{{ $classes->count() }}</span>
                                    @else
                                        <span class="nav-badge badge-primary">Hoje</span>
                                    @endif
                                </a>
                            @else
                                <a href="#" class="nav-link disabled">
                                    <span class="nav-icon">
                                        <i class="fas fa-calendar-check"></i>
                                    </span>
                                    <span class="nav-text">Presenças</span>
                                    <span class="nav-badge badge-secondary">N/A</span>
                                </a>
                            @endif
                        </li>
                        <!-- Avaliações Pendentes -->
                        <li class="nav-item">
                            <a href="{{ route('teacher.grades.pending') }}"
                                class="nav-link {{ request()->routeIs('teacher.grades.pending') ? 'active' : '' }}">
                                <span class="nav-icon">
                                    <i class="fas fa-clock"></i>
                                </span>
                                <span class="nav-text">Avaliações Pendentes</span>
                                @php
                                    $pendingCount = auth()->user()->teacher?->getPendingAssessmentsCount() ?? 0;
                                @endphp
                                @if ($pendingCount > 0)
                                    <span class="nav-badge badge-warning">{{ $pendingCount }}</span>
                                @endif
                            </a>
                        </li>

                        <!-- Caderno de Notas -->
                        <li class="nav-item">
                            @if ($firstClass)
                                <a href="{{ route('teacher.gradebook', $firstClass->id) }}"
                                    class="nav-link {{ request()->routeIs('teacher.gradebook') ? 'active' : '' }}">
                                    <span class="nav-icon">
                                        <i class="fas fa-medal"></i>
                                    </span>
                                    <span class="nav-text">Caderno de Notas</span>
                                </a>
                            @else
                                <a href="#" class="nav-link" style="opacity: 0.5; cursor: not-allowed;">
                                    <span class="nav-icon">
                                        <i class="fas fa-medal"></i>
                                    </span>
                                    <span class="nav-text">Caderno de Notas</span>
                                    <span class="nav-badge badge-danger" style="font-size: 9px;">Sem turma</span>
                                </a>
                            @endif
                        </li>

                        <!-- Comunicados -->
                        <li class="nav-item">
                            <a href="{{ route('teacher.communications.index') }}"
                                class="nav-link {{ request()->routeIs('teacher.communications.*') ? 'active' : '' }}">
                                <span class="nav-icon">
                                    <i class="fas fa-bullhorn"></i>
                                </span>
                                <span class="nav-text">Comunicados</span>
                                @php
                                    $newCommsCount = \App\Models\Communication::forTeachers()
                                        ->published()
                                        ->recent(7)
                                        ->count();
                                @endphp
                                @if ($newCommsCount > 0)
                                    <span class="nav-badge badge-warning">{{ $newCommsCount }}</span>
                                @endif
                            </a>
                        </li>

                        <!-- Licenças -->
                        <li class="nav-item">
                            <a href="{{ route('teacher.leave-requests.index') }}"
                                class="nav-link {{ request()->routeIs('teacher.leave-requests.*') ? 'active' : '' }}">
                                <span class="nav-icon">
                                    <i class="fas fa-calendar-times"></i>
                                </span>
                                <span class="nav-text">Minhas Licenças</span>
                            </a>
                        </li>
                    </ul>
                </div>
            @endif

            <!-- ========== MENUS DE GESTÃO (com permissões) ========== -->
            @canany(['manage_students', 'view_students'])
                @if (auth()->user()->role !== 'teacher')
                    <div class="nav-section">
                        <div class="nav-section-title">Gestão de Alunos</div>
                        <ul class="nav-list">
                            <li class="nav-item">
                                <a href="{{ route('students.index') }}"
                                    class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
                                    <span class="nav-icon">
                                        <i class="fas fa-user-graduate"></i>
                                    </span>
                                    <span class="nav-text">Alunos</span>
                                    @can('create_students')
                                        <span class="nav-badge badge-primary">Gerir</span>
                                    @else
                                        <span class="nav-badge badge-warning">Ver</span>
                                    @endcan
                                </a>
                            </li>
                            @can('manage_enrollments')
                                <li class="nav-item">
                                    <a href="{{ route('enrollments.index') }}"
                                        class="nav-link {{ request()->routeIs('enrollments.*') ? 'active' : '' }}">
                                        <span class="nav-icon">
                                            <i class="fas fa-clipboard-list"></i>
                                        </span>
                                        <span class="nav-text">Matrículas</span>
                                        @php
                                            $pendingEnrollments = \App\Models\Enrollment::where(
                                                'status',
                                                'pending',
                                            )->count();
                                        @endphp
                                        @if ($pendingEnrollments > 0)
                                            <span class="nav-badge badge-danger">{{ $pendingEnrollments }}</span>
                                        @endif
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                @endif
            @endcanany

            @canany(['manage_classes', 'view_classes', 'manage_subjects'])
                <div class="nav-section">
                    <div class="nav-section-title">Gestão Acadêmica</div>
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a href="{{ route('classes.index') }}"
                                class="nav-link {{ request()->routeIs('classes.*') ? 'active' : '' }}">
                                <span class="nav-icon">
                                    <i class="fas fa-chalkboard"></i>
                                </span>
                                <span class="nav-text">Turmas</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('subjects.index') }}"
                                class="nav-link {{ request()->routeIs('subjects.*') ? 'active' : '' }}">
                                <span class="nav-icon">
                                    <i class="fas fa-book"></i>
                                </span>
                                <span class="nav-text">Disciplinas</span>
                            </a>
                        </li>
                        @can('manage_attendances')
                            <li class="nav-item">
                                <a href="{{ route('attendances.index') }}"
                                    class="nav-link {{ request()->routeIs('attendances.*') ? 'active' : '' }}">
                                    <span class="nav-icon">
                                        <i class="fas fa-calendar-check"></i>
                                    </span>
                                    <span class="nav-text">Presenças</span>
                                </a>
                            </li>
                        @endcan
                        @can('manage_grades')
                            <li class="nav-item">
                                <a href="{{ route('grades.index') }}"
                                    class="nav-link {{ request()->routeIs('grades.*') ? 'active' : '' }}">
                                    <span class="nav-icon">
                                        <i class="fas fa-medal"></i>
                                    </span>
                                    <span class="nav-text">Avaliações</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </div>
            @endcanany

            @canany(['manage_payments', 'view_payments'])
                <div class="nav-section">
                    <div class="nav-section-title">Gestão Financeira</div>
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a href="{{ route('payments.index') }}"
                                class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}">
                                <span class="nav-icon">
                                    <i class="fas fa-money-bill-wave"></i>
                                </span>
                                <span class="nav-text">Mensalidades</span>
                                @php
                                    $overduePayments = \App\Models\Payment::where('status', 'overdue')->count();
                                @endphp
                                @if ($overduePayments > 0)
                                    <span class="nav-badge badge-danger">{{ $overduePayments }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('payments.references') }}"
                                class="nav-link {{ request()->routeIs('payment-references.*') ? 'active' : '' }}">
                                <span class="nav-icon">
                                    <i class="fas fa-receipt"></i>
                                </span>
                                <span class="nav-text">Referências</span>
                            </a>
                        </li>
                    </ul>
                </div>
            @endcanany

            @canany(['manage_teachers', 'view_teachers'])
                <div class="nav-section">
                    <div class="nav-section-title">Gestão de Pessoal</div>
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a href="{{ route('teachers.index') }}"
                                class="nav-link {{ request()->routeIs('teachers.*') ? 'active' : '' }}">
                                <span class="nav-icon">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </span>
                                <span class="nav-text">Professores</span>
                            </a>
                        </li>
                        @can('manage_leave_requests')
                            <li class="nav-item">
                                <a href="{{ route('teacher.leave-requests.index') }}"
                                    class="nav-link {{ request()->routeIs('leave-requests.*') ? 'active' : '' }}">
                                    <span class="nav-icon">
                                        <i class="fas fa-calendar-times"></i>
                                    </span>
                                    <span class="nav-text">Licenças</span>
                                    @php
                                        $pendingRequests = \App\Models\StaffLeaveRequest::where(
                                            'status',
                                            'pending',
                                        )->count();
                                    @endphp
                                    @if ($pendingRequests > 0)
                                        <span class="nav-badge badge-warning">{{ $pendingRequests }}</span>
                                    @endif
                                </a>
                            </li>
                        @endcan
                    </ul>
                </div>
            @endcanany

            @can('manage_events')
                <div class="nav-section">
                    <div class="nav-section-title">Comunicação</div>
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a href="{{ route('events.index') }}"
                                class="nav-link {{ request()->routeIs('events.*') ? 'active' : '' }}">
                                <span class="nav-icon">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                                <span class="nav-text">Eventos</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('communications.index') }}"
                                class="nav-link {{ request()->routeIs('communications.*') ? 'active' : '' }}">
                                <span class="nav-icon">
                                    <i class="fas fa-bullhorn"></i>
                                </span>
                                <span class="nav-text">Comunicados</span>
                            </a>
                        </li>
                    </ul>
                </div>
            @endcan

            @canany(['view_reports', 'export_reports'])
                <div class="nav-section">
                    <div class="nav-section-title">Relatórios</div>
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a href="{{ route('reports.index') }}"
                                class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                                <span class="nav-icon">
                                    <i class="fas fa-chart-bar"></i>
                                </span>
                                <span class="nav-text">Relatórios</span>
                                @can('export_reports')
                                    <span class="nav-badge badge-success">Export</span>
                                @endcan
                            </a>
                        </li>
                    </ul>
                </div>
            @endcanany

            @can('manage_users')
                <div class="nav-section">
                    <div class="nav-section-title">Administração</div>
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a href="{{ route('admin.users.index') }}"
                                class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                <span class="nav-icon">
                                    <i class="fas fa-users-cog"></i>
                                </span>
                                <span class="nav-text">Usuários</span>
                                <span class="nav-badge badge-danger">Admin</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.settings.index') }}"
                                class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                                <span class="nav-icon">
                                    <i class="fas fa-cog"></i>
                                </span>
                                <span class="nav-text">Configurações</span>
                            </a>
                        </li>
                    </ul>
                </div>
            @endcan

            <!-- Minha Conta (sempre visível) -->
            <div class="nav-section">
                <div class="nav-section-title">Minha Conta</div>
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="{{ route('profile.edit') }}"
                            class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                            <span class="nav-icon">
                                <i class="fas fa-user-circle"></i>
                            </span>
                            <span class="nav-text">Meu Perfil</span>
                        </a>
                    </li>
                    @if (auth()->user()->role === 'teacher')
                        <li class="nav-item">
                            <a href="{{ route('teacher.leave-requests.index') }}"
                                class="nav-link {{ request()->routeIs('teacher.leave-requests') ? 'active' : '' }}">
                                <span class="nav-icon">
                                    <i class="fas fa-calendar-times"></i>
                                </span>
                                <span class="nav-text">Minhas Licenças</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="user-area">
            <div class="user-profile">
                <div class="user-avatar">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="user-info">
                    <div class="user-name">{{ explode(' ', auth()->user()->name)[0] }}</div>
                    <div class="user-role">
                        @switch(auth()->user()->role)
                            @case('admin')
                                Administrador
                            @break

                            @case('secretary')
                                Secretaria
                            @break

                            @case('pedagogy')
                                Seção Pedagógica
                            @break

                            @case('teacher')
                                Professor(a)
                            @break

                            @case('parent')
                                Encarregado
                            @break

                            @default
                                Usuário
                        @endswitch
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="logout-text">Sair</span>
                </button>
            </form>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <div class="main-content" id="main-content">
        <!-- Header Principal -->
        <header class="main-header">
            <div class="header-left">
                <button class="sidebar-toggle sidebar-toggle-btn" onclick="toggleSidebar()" id="sidebar-toggle-btn"
                    style="backdrop-filter: blur(10px); background: var(--secondary); border: 1px solid var(--border-color); margin: 0px 15px 0 -15px; color:white;">
                    <i class="fas fa-chevron-left" id="toggle-icon"></i>
                </button>
                <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="page-title">
                    <i class="{{ $titleIcon ?? 'fas fa-tachometer-alt' }}"></i>
                    @yield('page-title', 'Dashboard')
                </h1>
            </div>

            <div class="header-right">
                <div class="header-search">
                    <input type="text" class="search-input"
                        placeholder="Pesquisar alunos, professores, turmas...">
                    <i class="fas fa-search search-icon"></i>
                </div>

                <button class="header-btn" id="notification-btn" data-bs-toggle="dropdown" title="Notificações">
                    <i class="fas fa-bell"></i>
                    @if (auth()->user()->unreadNotifications->count() > 0)
                        <span class="notification-badge">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    @endif
                </button>

                <ul class="dropdown-menu dropdown-menu-end shadow-lg" style="width: 350px;">
                    <li class="dropdown-header d-flex justify-content-between align-items-center p-3">
                        <strong>Notificações</strong>
                        <a href="#" class="text-decoration-none text-primary small"
                            onclick="markAllAsRead(event)">
                            Marcar todas como lidas
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider m-0">
                    </li>

                    @forelse(auth()->user()->notifications->take(5) as $notification)
                        <li>
                            <a class="dropdown-item d-flex align-items-start py-3 {{ $notification->read_at ? '' : 'bg-light' }}"
                                href="{{ $notification->data['action_url'] ?? '#' }}"
                                onclick="markAsRead('{{ $notification->id }}', event)">
                                <div class="flex-shrink-0 me-3">
                                    <i
                                        class="{{ $notification->data['icon'] ?? 'fas fa-bell' }} text-{{ $notification->data['type'] ?? 'info' }}"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold mb-1">{{ $notification->data['title'] ?? 'Notificação' }}
                                    </div>
                                    <div class="text-muted small mb-1">
                                        {{ $notification->data['message'] ?? 'Nova notificação' }}</div>
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                                @if (!$notification->read_at)
                                    <div class="flex-shrink-0">
                                        <span class="badge bg-primary">NOVO</span>
                                    </div>
                                @endif
                            </a>
                        </li>
                    @empty
                        <li class="dropdown-item-text text-center text-muted py-4">
                            <i class="fas fa-bell-slash fs-3 mb-2 d-block"></i>
                            Nenhuma notificação
                        </li>
                    @endforelse

                    <li>
                        <hr class="dropdown-divider m-0">
                    </li>
                    <li class="text-center p-2">
                        <a href="{{ route('notifications.index') }}" class="small text-decoration-none">
                            Ver todas as notificações
                        </a>
                    </li>
                </ul>

                <button class="header-btn" onclick="toggleTheme()" title="Alternar Tema">
                    <i class="fas fa-moon" id="theme-icon"></i>
                </button>

                <div class="dropdown">
                    <button class="header-btn" data-bs-toggle="dropdown" title="Menu do Usuário">
                        <div class="user-avatar"
                            style="width: 32px; height: 32px; font-size: 13px; margin: 0; background: var(--primary);">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li class="dropdown-header">
                            <strong>{{ explode(' ', auth()->user()->name)[0] }}</strong>
                            <small class="d-block text-muted">
                                @switch(auth()->user()->role)
                                    @case('admin')
                                        Administrador do Sistema
                                    @break

                                    @case('secretary')
                                        Secretaria Escolar
                                    @break

                                    @case('pedagogy')
                                        Seção Pedagógica
                                    @break

                                    @case('teacher')
                                        Professor(a)
                                    @break

                                    @case('parent')
                                        Encarregado de Educação
                                    @break

                                    @default
                                        Usuário do Sistema
                                @endswitch
                            </small>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="fas fa-user me-3"></i>Meu Perfil
                            </a>
                        </li>
                        @if (auth()->user()->role === 'parent')
                            <li>
                                <a class="dropdown-item" href="{{ route('parent.dashboard') }}">
                                    <i class="fas fa-child me-3"></i>Portal dos Pais
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->role === 'teacher')
                            <li>
                                <a class="dropdown-item" href="{{ route('teacher.dashboard') }}">
                                    <i class="fas fa-chalkboard-teacher me-3"></i>Portal do Professor
                                </a>
                            </li>
                        @endif
                        <li>
                            <a class="dropdown-item" href="#" onclick="toggleTheme()">
                                <i class="fas fa-moon me-3" id="theme-icon-dropdown"></i>
                                <span id="theme-text">Modo Escuro</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-3"></i>Sair do Sistema
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Área de Conteúdo -->
        <div class="content-area">
            <!-- Breadcrumb -->
            <nav class="school-breadcrumb" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">
                            <i class="fas fa-home"></i> Início
                        </a>
                    </li>
                    @yield('breadcrumbs')
                    @if (!View::hasSection('breadcrumbs'))
                        <li class="breadcrumb-item active">Dashboard</li>
                    @endif
                </ol>
            </nav>

            <!-- Alertas do Sistema -->
            @if (session('success'))
                <div class="alert-school alert-success-school alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i>
                    <div>
                        <strong>Sucesso!</strong> {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert-school alert-danger-school alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        <strong>Erro!</strong> {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('warning'))
                <div class="alert-school alert-warning-school alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        <strong>Atenção!</strong> {{ session('warning') }}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('info'))
                <div class="alert-school alert-info-school alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <strong>Informação!</strong> {{ session('info') }}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert-school alert-danger-school alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        <strong>Erros encontrados:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Conteúdo da Página -->
            @yield('content')
        </div>

        <!-- Footer Escolar -->
        <footer class="school-footer">
            <div class="content-area">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center py-3">
                    <div class="text-center text-sm-start mb-2 mb-sm-0">
                        <small class="text-muted">
                            © {{ date('Y') }} <strong class="text-primary">Escola dos Visionários</strong> -
                            Sistema de Gestão Escolar
                        </small>
                        <br class="d-block d-sm-none">
                        <small class="text-muted">
                            Quelimane, Província da Zambézia - Moçambique
                        </small>
                    </div>
                    <div class="text-center text-sm-end">
                        <small class="text-muted">
                            <span class="badge" style="background: var(--success);">v1.0.0</span>
                            <a href="mailto:suporte@visionarios.co.mz" class="text-decoration-none me-2">Suporte
                                Técnico</a>
                            <a href="#" class="text-decoration-none" onclick="showHelpModal()">Manual do
                                Sistema</a>
                        </small>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.3.0/chart.min.js"></script>

    <script>
        // ===== VARIÁVEIS GLOBAIS =====
        let sidebarCollapsed = localStorage.getItem('school-sidebar-collapsed') === 'true';
        let mobileMenuOpen = false;

        // ===== GERENCIADOR DE SIDEBAR =====
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const toggleIcon = document.getElementById('toggle-icon');

            if (window.innerWidth >= 1200) {
                sidebarCollapsed = !sidebarCollapsed;
                localStorage.setItem('school-sidebar-collapsed', sidebarCollapsed);

                if (sidebarCollapsed) {
                    sidebar.classList.add('collapsed');
                    mainContent.classList.add('collapsed');
                    toggleIcon.className = 'fas fa-chevron-right';
                } else {
                    sidebar.classList.remove('collapsed');
                    mainContent.classList.remove('collapsed');
                    toggleIcon.className = 'fas fa-chevron-left';
                }
            }
        }

        function toggleMobileMenu() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            mobileMenuOpen = !mobileMenuOpen;

            if (mobileMenuOpen) {
                sidebar.classList.add('mobile-visible');
                overlay.classList.add('show');
                document.body.style.overflow = 'hidden';
            } else {
                sidebar.classList.remove('mobile-visible');
                overlay.classList.remove('show');
                document.body.style.overflow = '';
            }
        }

        // ===== GERENCIADOR DE TEMA =====
        function toggleTheme() {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';

            document.documentElement.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('school-theme', newTheme);

            const icons = document.querySelectorAll('#theme-icon, #theme-icon-dropdown');
            const text = document.getElementById('theme-text');

            icons.forEach(icon => {
                icon.className = newTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
            });

            if (text) {
                text.textContent = newTheme === 'dark' ? 'Modo Claro' : 'Modo Escuro';
            }
        }

        // ===== SISTEMA DE NOTIFICAÇÕES =====
        async function markAsRead(notificationId, event) {
            if (event) event.preventDefault();

            try {
                const response = await fetch(`/notifications/${notificationId}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                });

                if (response.ok) {
                    const badge = document.querySelector('.notification-badge');
                    if (badge) {
                        const count = parseInt(badge.textContent) - 1;
                        if (count <= 0) {
                            badge.remove();
                        } else {
                            badge.textContent = count;
                        }
                    }

                    const item = event.target.closest('.dropdown-item');
                    if (item) {
                        item.classList.remove('bg-light');
                        const newBadge = item.querySelector('.badge.bg-primary');
                        if (newBadge) newBadge.remove();
                    }
                }
            } catch (error) {
                console.error('Erro ao marcar notificação:', error);
            }
        }

        async function markAllAsRead(event) {
            if (event) event.preventDefault();

            try {
                const response = await fetch('/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const badge = document.querySelector('.notification-badge');
                    if (badge) badge.remove();

                    document.querySelectorAll('.dropdown-item.bg-light').forEach(item => {
                        item.classList.remove('bg-light');
                    });
                    document.querySelectorAll('.badge.bg-primary').forEach(badge => badge.remove());

                    showToast('Todas as notificações foram marcadas como lidas', 'success');
                }
            } catch (error) {
                console.error('Erro:', error);
                showToast('Erro ao marcar notificações', 'error');
            }
        }

        // ===== SISTEMA DE TOAST =====
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            if (!container) return;

            const iconMap = {
                success: 'check-circle',
                error: 'exclamation-circle',
                warning: 'exclamation-triangle',
                info: 'info-circle'
            };

            const colorMap = {
                success: 'text-bg-success',
                error: 'text-bg-danger',
                warning: 'text-bg-warning',
                info: 'text-bg-primary'
            };

            const toastId = 'toast-' + Date.now();
            const toastHtml = `
                <div class="toast ${colorMap[type]} toast-school" role="alert" id="${toastId}">
                    <div class="toast-body d-flex align-items-center">
                        <i class="fas fa-${iconMap[type]} me-2"></i>
                        <span class="flex-grow-1">${message}</span>
                        <button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', toastHtml);

            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement);
            toast.show();

            toastElement.addEventListener('hidden.bs.toast', () => {
                toastElement.remove();
            });
        }

        // ===== MODAL DE AJUDA =====
        function showHelpModal() {
            const modalHtml = `
                <div class="modal fade" id="helpModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header" style="background: var(--primary); color: white;">
                                <h5 class="modal-title">
                                    <i class="fas fa-question-circle me-2"></i>
                                    Manual do Sistema Visionários
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6><i class="fas fa-user-graduate text-primary me-2"></i>Para Encarregados</h6>
                                        <ul class="list-unstyled">
                                            <li>• Acompanhar notas dos filhos</li>
                                            <li>• Visualizar presenças</li>
                                            <li>• Efetuar pagamentos</li>
                                            <li>• Receber comunicados</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6><i class="fas fa-chalkboard-teacher text-primary me-2"></i>Para Professores</h6>
                                        <ul class="list-unstyled">
                                            <li>• Marcar presenças</li>
                                            <li>• Lançar avaliações</li>
                                            <li>• Comunicar com pais</li>
                                            <li>• Solicitar licenças</li>
                                        </ul>
                                    </div>
                                </div>
                                <hr>
                                <div class="text-center">
                                    <p class="text-muted">
                                        Para mais informações, entre em contato com o suporte técnico:
                                        <br><strong>suporte@visionarios.co.mz</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            const existingModal = document.getElementById('helpModal');
            if (existingModal) existingModal.remove();

            document.body.insertAdjacentHTML('beforeend', modalHtml);
            const modal = new bootstrap.Modal(document.getElementById('helpModal'));
            modal.show();
        }

        // ===== PESQUISA GLOBAL =====
        document.querySelector('.search-input')?.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const query = this.value.trim();
                if (query.length > 2) {
                    window.location.href = `/search?q=${encodeURIComponent(query)}`;
                }
            }
        });

        // ===== INICIALIZAÇÃO =====
        document.addEventListener('DOMContentLoaded', function() {
            // Aplicar tema salvo
            const savedTheme = localStorage.getItem('school-theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);

            const icons = document.querySelectorAll('#theme-icon, #theme-icon-dropdown');
            const text = document.getElementById('theme-text');

            icons.forEach(icon => {
                icon.className = savedTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
            });

            if (text) {
                text.textContent = savedTheme === 'dark' ? 'Modo Claro' : 'Modo Escuro';
            }

            // Aplicar estado do sidebar
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const toggleIcon = document.getElementById('toggle-icon');

            if (window.innerWidth >= 1200 && sidebarCollapsed) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('collapsed');
                toggleIcon.className = 'fas fa-chevron-right';
            }

            // Event listeners
            document.getElementById('sidebar-overlay')?.addEventListener('click', () => {
                toggleMobileMenu();
            });

            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1200) {
                    if (mobileMenuOpen) toggleMobileMenu();
                    if (mainContent) mainContent.classList.remove('expanded');
                } else {
                    if (mainContent) mainContent.classList.add('expanded');
                }
            });

            // Auto-hide alerts
            setTimeout(() => {
                document.querySelectorAll('.alert-school').forEach(alert => {
                    const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                    bsAlert?.close();
                });
            }, 8000);

            console.log('✅ Sistema Escolar Visionários iniciado com sucesso!');
        });

        // ===== API GLOBAL =====
        window.VisionariosSchool = {
            showToast,
            toggleSidebar,
            toggleTheme,
            markAsRead,
            markAllAsRead,
            showHelpModal,
            version: '1.0.0'
        };
    </script>

    @stack('scripts')
</body>

</html>
