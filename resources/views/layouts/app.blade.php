<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Visionários') }} - @yield('title', 'Dashboard')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            /* Cores do Logo Visionários */
            --primary-blue: #0f60b0;
            --primary-green: #7CB342;
            --primary-orange: #FF9800;
            --accent-yellow: #FDD835;

            /* Gradientes */
            --gradient-primary: linear-gradient(135deg, #0f60b0 0%, #1976D2 100%);
            --gradient-success: linear-gradient(135deg, #7CB342 0%, #558B2F 100%);
            --gradient-warning: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);
            --gradient-danger: linear-gradient(135deg, #E53935 0%, #C62828 100%);

            /* Sistema de Cores */
            --success: #7CB342;
            --info: #0f60b0;
            --warning: #FF9800;
            --danger: #E53935;

            /* Neutrals Melhorados */
            --gray-50: #FAFBFC;
            --gray-100: #F5F7FA;
            --gray-200: #E8ECF1;
            --gray-300: #D1D9E2;
            --gray-400: #B0BCC9;
            --gray-500: #8896A6;
            --gray-600: #677486;
            --gray-700: #4A5568;
            --gray-800: #2D3748;
            --gray-900: #1A202C;

            /* Layout */
            --header-height: 64px;
            --sidebar-width: 260px;
            --sidebar-collapsed: 72px;

            /* Backgrounds */
            --bg-app: linear-gradient(180deg, #F5F7FA 0%, #E8ECF1 100%);
            --bg-card: #FFFFFF;
            --bg-sidebar: #0c378d;
            --bg-header: #FFFFFF;

            /* Text */
            --text-primary: #1A202C;
            --text-secondary: #4A5568;
            --text-muted: #8896A6;
            --text-inverse: #FFFFFF;

            /* Shadows Melhoradas */
            --shadow-xs: 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.12);
            --shadow-xl: 0 16px 40px rgba(0, 0, 0, 0.16);

            /* Border */
            --border-radius: 12px;
            --border-radius-sm: 8px;
            --border-color: #E8ECF1;
        }

        [data-theme="dark"] {
            --bg-app: linear-gradient(180deg, #1A202C 0%, #0D1117 100%);
            --bg-card: #2D3748;
            --bg-header: #1A202C;
            --bg-sidebar: #0D1117;
            --text-primary: #FFFFFF;
            --text-secondary: #B0BCC9;
            --text-muted: #8896A6;
            --border-color: #4A5568;
            --gray-100: #2D3748;
            --gray-50: #1A202C;
            --gray-200: #4A5568;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            background: var(--bg-app);
            color: var(--text-primary);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* ===== HEADER MELHORADO ===== */
        .app-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--header-height);
            background: var(--bg-header);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            padding: 0 24px;
            gap: 20px;
            z-index: 1000;
            box-shadow: var(--shadow-sm);
            backdrop-filter: blur(10px);
        }

        .header-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: var(--sidebar-width);
            padding-right: 24px;
        }

        .logo-container {
            width: 44px;
            height: 44px;
            background: var(--gradient-primary);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: var(--shadow-md);
            transition: transform 0.3s ease;
        }

        .logo-container:hover {
            transform: scale(1.05) rotate(-5deg);
        }

        .logo-container i {
            color: white;
            font-size: 20px;
        }

        .logo-text-container {
            display: flex;
            flex-direction: column;
        }

        .logo-text {
            font-weight: 800;
            font-size: 16px;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            letter-spacing: 0.5px;
        }

        .logo-subtitle {
            font-size: 11px;
            color: var(--text-muted);
            font-weight: 500;
        }

        .page-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-title i {
            color: var(--primary-blue);
            font-size: 20px;
        }

        .sidebar-toggle-desktop {
            width: 36px;
            height: 36px;
            border: 1px solid var(--border-color);
            background: var(--bg-card);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            margin-left: auto;
        }

        .sidebar-toggle-desktop:hover {
            background: var(--gray-100);
            border-color: var(--primary-blue);
            transform: scale(1.1);
        }

        .mobile-toggle {
            display: none;
        }

        /* ===== HEADER ACTIONS MELHORADAS ===== */
        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-left: auto;
        }

        .header-search {
            position: relative;
            width: 360px;
        }

        .search-input {
            width: 100%;
            height: 40px;
            padding: 0 40px 0 40px;
            border: 1px solid var(--border-color);
            border-radius: 20px;
            font-size: 14px;
            background: var(--gray-50);
            color: var(--text-primary);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .search-input:focus {
            outline: none;
            background: var(--bg-card);
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 4px rgba(15, 96, 176, 0.1);
            transform: translateY(-1px);
        }

        .search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 16px;
        }

        .action-btn {
            width: 40px;
            height: 40px;
            border: none;
            background: transparent;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }

        .action-btn:hover {
            background: var(--gray-100);
            color: var(--primary-blue);
            transform: scale(1.1);
        }

        .action-btn .badge {
            position: absolute;
            top: 4px;
            right: 4px;
            min-width: 18px;
            height: 18px;
            background: var(--gradient-danger);
            color: white;
            border-radius: 50%;
            font-size: 10px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--bg-header);
            padding: 0 4px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 12px 6px 6px;
            border-radius: 24px;
            cursor: pointer;
            transition: all 0.3s;
            border: 1px solid transparent;
        }

        .user-menu:hover {
            background: var(--gray-100);
            border-color: var(--border-color);
            transform: translateY(-1px);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--gradient-primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            box-shadow: var(--shadow-sm);
        }

        .user-name {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
        }

        /* ===== SIDEBAR MELHORADA ===== */
        .app-sidebar {
            position: fixed;
            top: var(--header-height);
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - var(--header-height));
            background: var(--bg-sidebar);
            overflow-y: auto;
            overflow-x: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 999;
            display: flex;
            flex-direction: column;
        }

        .app-sidebar.collapsed {
            width: var(--sidebar-collapsed);
        }

        .sidebar-nav {
            padding: 16px 12px;
            flex: 1;
        }

        .nav-section {
            margin-bottom: 24px;
        }

        .section-title {
            padding: 8px 16px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255, 255, 255, 0.5);
            transition: all 0.3s;
            white-space: nowrap;
        }

        .app-sidebar.collapsed .section-title {
            opacity: 0;
            height: 0;
            padding: 0;
            margin: 0;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 16px;
            margin-bottom: 4px;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
        }

        .sidebar-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(4px);
        }

        .sidebar-item.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            box-shadow: var(--shadow-md);
        }

        .sidebar-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--gradient-warning);
            border-radius: 0 4px 4px 0;
        }

        .sidebar-icon {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .sidebar-text {
            flex: 1;
            white-space: nowrap;
            transition: all 0.3s;
            overflow: hidden;
        }

        .app-sidebar.collapsed .sidebar-text {
            opacity: 0;
            width: 0;
        }

        .sidebar-badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 700;
            min-width: 20px;
            text-align: center;
            transition: all 0.3s;
        }

        .app-sidebar.collapsed .sidebar-badge {
            opacity: 0;
            transform: scale(0);
            width: 0;
        }

        .badge-orange {
            background: var(--gradient-warning);
            color: white;
            box-shadow: 0 2px 8px rgba(255, 152, 0, 0.3);
        }

        .badge-green {
            background: var(--gradient-success);
            color: white;
            box-shadow: 0 2px 8px rgba(124, 179, 66, 0.3);
        }

        .badge-red {
            background: var(--gradient-danger);
            color: white;
            box-shadow: 0 2px 8px rgba(229, 57, 53, 0.3);
        }

        .badge-blue {
            background: var(--gradient-primary);
            color: white;
            box-shadow: 0 2px 8px rgba(15, 96, 176, 0.3);
        }

        /* ===== SIDEBAR USER AREA ===== */
        .sidebar-user {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 20px 16px;
            background: rgba(0, 0, 0, 0.2);
        }

        .sidebar-user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
            transition: all 0.3s;
        }

        .app-sidebar.collapsed .sidebar-user-profile {
            justify-content: center;
        }

        .sidebar-user-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: var(--gradient-warning);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 18px;
            flex-shrink: 0;
            box-shadow: var(--shadow-md);
        }

        .sidebar-user-info {
            flex: 1;
            min-width: 0;
            transition: all 0.3s;
        }

        .app-sidebar.collapsed .sidebar-user-info {
            opacity: 0;
            width: 0;
        }

        .sidebar-user-name {
            font-size: 14px;
            font-weight: 700;
            color: white;
            margin-bottom: 2px;
        }

        .sidebar-user-role {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.6);
        }

        .logout-btn {
            width: 100%;
            padding: 12px 16px;
            border: none;
            background: var(--gradient-danger);
            color: white;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: var(--shadow-md);
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .app-sidebar.collapsed .logout-btn {
            padding: 12px;
        }

        .logout-text {
            transition: all 0.3s;
        }

        .app-sidebar.collapsed .logout-text {
            opacity: 0;
            width: 0;
        }

        /* ===== MAIN CONTENT ===== */
        .app-main {
            margin-left: var(--sidebar-width);
            margin-top: var(--header-height);
            padding: 32px;
            min-height: calc(100vh - var(--header-height));
            transition: all 0.3s;
        }

        .app-main.collapsed {
            margin-left: var(--sidebar-collapsed);
        }

        /* ===== BREADCRUMB MELHORADO ===== */
        .app-breadcrumb {
            margin-bottom: 24px;
        }

        .breadcrumb {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius-sm);
            padding: 14px 20px;
            margin-bottom: 0;
            box-shadow: var(--shadow-xs);
        }

        .breadcrumb-item a {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
        }

        .breadcrumb-item a:hover {
            color: var(--blue-light);
        }

        .breadcrumb-item.active {
            color: var(--text-secondary);
        }

        /* ===== PAGE HEADER MELHORADO ===== */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 32px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .title-icon {
            width: 48px;
            height: 48px;
            background: var(--gradient-primary);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgb(56, 85, 214);
            font-size: 20px;
            box-shadow: var(--shadow-md);
        }

        /* ===== TOAST NOTIFICATIONS (NOVO) ===== */
        .toast-container-custom {
            position: fixed;
            top: 80px;
            right: 24px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 12px;
            max-width: 420px;
        }

        .toast-custom {
            background: var(--bg-card);
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: var(--shadow-xl);
            display: flex;
            align-items: flex-start;
            gap: 16px;
            min-width: 360px;
            border-left: 4px solid;
            animation: slideInRight 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .toast-custom.hiding {
            animation: slideOutRight 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        @keyframes slideOutRight {
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }

        .toast-custom::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: currentColor;
            animation: toastProgress 5s linear forwards;
        }

        @keyframes toastProgress {
            from { width: 100%; }
            to { width: 0%; }
        }

        .toast-custom.success {
            border-left-color: var(--success);
        }

        .toast-custom.success::before {
            background: var(--success);
        }

        .toast-custom.error {
            border-left-color: var(--danger);
        }

        .toast-custom.error::before {
            background: var(--danger);
        }

        .toast-custom.warning {
            border-left-color: var(--warning);
        }

        .toast-custom.warning::before {
            background: var(--warning);
        }

        .toast-custom.info {
            border-left-color: var(--info);
        }

        .toast-custom.info::before {
            background: var(--info);
        }

        .toast-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .toast-custom.success .toast-icon {
            background: rgba(124, 179, 66, 0.15);
            color: var(--success);
        }

        .toast-custom.error .toast-icon {
            background: rgba(229, 57, 53, 0.15);
            color: var(--danger);
        }

        .toast-custom.warning .toast-icon {
            background: rgba(255, 152, 0, 0.15);
            color: var(--warning);
        }

        .toast-custom.info .toast-icon {
            background: rgba(15, 96, 176, 0.15);
            color: var(--info);
        }

        .toast-content {
            flex: 1;
        }

        .toast-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .toast-message {
            font-size: 13px;
            color: var(--text-secondary);
            line-height: 1.5;
        }

        .toast-close {
            width: 24px;
            height: 24px;
            border: none;
            background: transparent;
            color: var(--text-muted);
            cursor: pointer;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .toast-close:hover {
            background: var(--gray-100);
            color: var(--text-primary);
        }

        /* ===== STATS CARDS MELHORADOS ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 20px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-sm);
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
            background: var(--gradient-primary);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: transparent;
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            flex-shrink: 0;
            box-shadow: var(--shadow-md);
        }

        .icon-blue {
            background: var(--gradient-primary);
        }

        .icon-green {
            background: var(--gradient-success);
        }

        .icon-orange {
            background: var(--gradient-warning);
        }

        .icon-red {
            background: var(--gradient-danger);
        }

        .stat-content {
            flex: 1;
        }

        .stat-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 800;
            color: var(--text-primary);
            line-height: 1;
            margin-bottom: 8px;
        }

        .stat-change {
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .stat-change.positive {
            color: var(--success);
        }

        .stat-change.negative {
            color: var(--danger);
        }

        /* ===== SCHOOL CARDS MELHORADOS ===== */
        .school-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            margin-bottom: 32px;
            transition: all 0.3s;
            overflow: hidden;
        }

        .school-card:hover {
            box-shadow: var(--shadow-md);
        }

        .school-card-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-color);
            font-weight: 700;
            font-size: 16px;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 12px;
            background: var(--gray-50);
        }

        .school-card-body {
            padding: 24px;
        }

        /* ===== BUTTONS MELHORADOS ===== */
        .btn-primary-visionarios {
            background: var(--gradient-primary);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: var(--shadow-md);
        }

        .btn-primary-visionarios:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            color: white;
        }

        .btn-success-visionarios {
            background: var(--gradient-success);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: var(--shadow-md);
        }

        .btn-success-visionarios:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            color: white;
        }

        .btn-warning-visionarios {
            background: var(--gradient-warning);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: var(--shadow-md);
        }

        .btn-warning-visionarios:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            color: white;
        }

        /* ===== TABLES MELHORADAS ===== */
        .school-table {
            background: var(--bg-card);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .school-table .table {
            margin-bottom: 0;
        }

        .school-table .table thead th {
            background: var(--gray-50);
            border-bottom: 2px solid var(--border-color);
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-secondary);
            padding: 16px 20px;
        }

        .school-table .table tbody td {
            padding: 16px 20px;
            vertical-align: middle;
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .school-table .table tbody tr {
            transition: all 0.2s;
        }

        .school-table .table tbody tr:hover {
            background: var(--gray-50);
            transform: scale(1.01);
        }

        /* ===== SIDEBAR OVERLAY ===== */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 998;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
            backdrop-filter: blur(4px);
        }

        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        /* ===== FOOTER MELHORADO ===== */
        .app-footer {
            background: var(--bg-card);
            border-top: 1px solid var(--border-color);
            padding: 24px 32px;
            margin-top: auto;
            box-shadow: 0 -2px 12px rgba(0, 0, 0, 0.05);
        }

        /* ===== DROPDOWN MELHORADO ===== */
        .dropdown-menu {
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius-sm);
            box-shadow: var(--shadow-lg);
            padding: 8px;
        }

        .dropdown-item {
            border-radius: 6px;
            padding: 10px 14px;
            transition: all 0.2s;
            color: var(--text-primary);
        }

        .dropdown-item:hover {
            background: var(--gray-100);
            color: var(--primary-blue);
            transform: translateX(4px);
        }

        .dropdown-header {
            font-weight: 700;
            color: var(--text-primary);
            padding: 12px 14px;
        }

        .dropdown-divider {
            border-color: var(--border-color);
            margin: 8px 0;
        }

        /* ===== SCROLLBAR MELHORADA ===== */
        .app-sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .app-sidebar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
        }

        .app-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .app-sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .app-sidebar {
                transform: translateX(-100%);
            }

            .app-sidebar.mobile-open {
                transform: translateX(0);
            }

            .app-main {
                margin-left: 0 !important;
            }

            .header-logo {
                min-width: auto;
                padding-right: 0;
            }

            .sidebar-toggle-desktop {
                display: none;
            }
            .page-title{
                display: none;
            }

            .mobile-toggle {
                display: flex;
                width: 36px;
                height: 36px;
                background: transparent;
                border: 1px solid var(--border-color);
                border-radius: 8px;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                color: var(--text-secondary);
            }

            .header-search {
                width: 200px;
            }

            .logo-subtitle {
                display: none;
            }

            .toast-container-custom {
                right: 12px;
                left: 12px;
                max-width: none;
            }

            .toast-custom {
                min-width: auto;
            }
        }

        @media (max-width: 768px) {
            .header-search {
                display: none;
            }

            .user-name {
                display: none;
            }

            .app-main {
                padding: 20px 16px;
            }

            /* .page-title {
                font-size: 18px;
            } */
             .page-title{
                display: none;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .app-header {
                padding: 0 16px;
            }
        }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.5s ease forwards;
        }

        /* ===== UTILITIES ===== */
        .badge {
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Toast Container (NOVO) -->
    <div class="toast-container-custom" id="toastContainer"></div>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebar-overlay" onclick="toggleMobileSidebar()"></div>

    <!-- Header -->
    <header class="app-header">
        <div class="header-logo">
            <button class="mobile-toggle" onclick="toggleMobileSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <div class="logo-container">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="logo-text-container">
                <div class="logo-text">VISIONÁRIOS</div>
                <div class="logo-subtitle">Sistema Escolar</div>
            </div>
            <button class="sidebar-toggle-desktop" onclick="toggleSidebar()" id="sidebar-toggle">
                <i class="fas fa-chevron-left" id="toggle-icon"></i>
            </button>
        </div>
        <h1 class="page-title">
            <i class="{{ $titleIcon ?? 'fas fa-tachometer-alt' }}"></i>
            @yield('page-title', 'Dashboard')
        </h1>
        <div class="header-actions">
            <div class="header-search">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" placeholder="Pesquisar alunos, professores...">
            </div>

            <button class="action-btn" data-bs-toggle="dropdown" title="Notificações">
                <i class="fas fa-bell"></i>
                @if (auth()->user()->unreadNotifications->count() > 0)
                    <span class="badge">{{ auth()->user()->unreadNotifications->count() }}</span>
                @endif
            </button>

            <ul class="dropdown-menu dropdown-menu-end shadow" style="width: 380px; max-height: 500px; overflow-y: auto;">
                <li class="dropdown-header d-flex justify-content-between align-items-center">
                    <strong>Notificações</strong>
                    <a href="#" class="text-decoration-none small" style="color: var(--primary-blue);"
                        onclick="markAllAsRead(event)">
                        Marcar todas como lidas
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>

                @forelse(auth()->user()->notifications->take(5) as $notification)
                    <li>
                        <a class="dropdown-item d-flex align-items-start py-3 {{ $notification->read_at ? '' : 'bg-light' }}"
                            href="{{ $notification->data['action_url'] ?? '#' }}"
                            onclick="markAsRead('{{ $notification->id }}', event)">
                            <div class="flex-shrink-0 me-3">
                                <i class="{{ $notification->data['icon'] ?? 'fas fa-bell' }}"
                                    style="color: var(--primary-blue);"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold mb-1">{{ $notification->data['title'] ?? 'Notificação' }}</div>
                                <div class="text-muted small mb-1">
                                    {{ $notification->data['message'] ?? 'Nova notificação' }}</div>
                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                            @if (!$notification->read_at)
                                <div class="flex-shrink-0">
                                    <span class="badge" style="background: var(--primary-orange);">NOVO</span>
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

                <li><hr class="dropdown-divider"></li>
                <li class="text-center p-2">
                    <a href="{{ route('notifications.index') }}" class="small text-decoration-none"
                        style="color: var(--primary-blue);">
                        Ver todas as notificações
                    </a>
                </li>
            </ul>

            <button class="action-btn" onclick="toggleTheme()" title="Alternar Tema">
                <i class="fas fa-moon" id="theme-icon"></i>
            </button>

            <div class="user-menu" data-bs-toggle="dropdown">
                <div class="user-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
                <span class="user-name">{{ explode(' ', auth()->user()->name)[0] }}</span>
                <i class="fas fa-chevron-down" style="font-size: 10px; color: var(--text-muted);"></i>
            </div>

            <ul class="dropdown-menu dropdown-menu-end shadow">
                <li class="dropdown-header">
                    <strong>{{ explode(' ', auth()->user()->name)[0] }}</strong>
                    <small class="d-block text-muted">
                        @switch(auth()->user()->role)
                            @case('admin') Administrador @break
                            @case('secretary') Secretaria @break
                            @case('pedagogy') Seção Pedagógica @break
                            @case('teacher') Professor(a) @break
                            @case('parent') Encarregado @break
                            @default Usuário
                        @endswitch
                    </small>
                </li>
                <li><hr class="dropdown-divider"></li>
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
                    <a class="dropdown-item" href="#" onclick="toggleTheme(); return false;">
                        <i class="fas fa-moon me-3" id="theme-icon-dropdown"></i>
                        <span id="theme-text">Modo Escuro</span>
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
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
    </header>

    <!-- Sidebar -->
    <aside class="app-sidebar" id="sidebar">
        <nav class="sidebar-nav">
            <!-- Dashboard -->
            <div class="nav-section">
                <a href="{{ route('dashboard') }}"
                    class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="sidebar-icon"><i class="fas fa-th-large"></i></span>
                    <span class="sidebar-text">Dashboard</span>
                </a>
            </div>

            <!-- Gestão de Alunos -->
            @canany(['manage_students', 'view_students'])
                <div class="nav-section">
                    <div class="section-title">Gestão de Alunos</div>
                    <a href="{{ route('students.index') }}"
                        class="sidebar-item {{ request()->routeIs('students.*') ? 'active' : '' }}">
                        <span class="sidebar-icon"><i class="fas fa-user-graduate"></i></span>
                        <span class="sidebar-text">Alunos</span>
                        @can('create_students')
                            <span class="sidebar-badge badge-green">Gerir</span>
                        @else
                            <span class="sidebar-badge badge-orange">Ver</span>
                        @endcan
                    </a>

                    <a href="{{ route('enrollments.index') }}"
                        class="sidebar-item {{ request()->routeIs('enrollments.*') ? 'active' : '' }}">
                        <span class="sidebar-icon"><i class="fas fa-clipboard-list"></i></span>
                        <span class="sidebar-text">Matrículas</span>
                        @php
                            $pendingEnrollments = \App\Models\Enrollment::where('status', 'pending')->count();
                        @endphp
                        @if ($pendingEnrollments > 0)
                            <span class="sidebar-badge badge-orange">{{ $pendingEnrollments }}</span>
                        @endif
                    </a>
                </div>
            @endcanany

            <!-- Gestão Acadêmica -->
            @canany(['manage_classes', 'view_classes', 'manage_subjects'])
                <div class="nav-section">
                    <div class="section-title">Gestão Acadêmica</div>
                    <a href="{{ route('classes.index') }}"
                        class="sidebar-item {{ request()->routeIs('classes.*') ? 'active' : '' }}">
                        <span class="sidebar-icon"><i class="fas fa-chalkboard"></i></span>
                        <span class="sidebar-text">Turmas</span>
                    </a>

                    <a href="{{ route('subjects.index') }}"
                        class="sidebar-item {{ request()->routeIs('subjects.*') ? 'active' : '' }}">
                        <span class="sidebar-icon"><i class="fas fa-book"></i></span>
                        <span class="sidebar-text">Disciplinas</span>
                    </a>

                    @can('manage_attendances')
                        <a href="{{ route('attendances.index') }}"
                            class="sidebar-item {{ request()->routeIs('attendances.*') ? 'active' : '' }}">
                            <span class="sidebar-icon"><i class="fas fa-calendar-check"></i></span>
                            <span class="sidebar-text">Presenças</span>
                        </a>
                    @endcan

                    @can('manage_grades')
                        <a href="{{ route('grades.index') }}"
                            class="sidebar-item {{ request()->routeIs('grades.*') ? 'active' : '' }}">
                            <span class="sidebar-icon"><i class="fas fa-star"></i></span>
                            <span class="sidebar-text">Avaliações</span>
                        </a>
                    @endcan
                </div>
            @endcanany

            <!-- Gestão Financeira -->
            @canany(['manage_payments', 'view_payments'])
                <div class="nav-section">
                    <div class="section-title">Gestão Financeira</div>
                    <a href="{{ route('payments.index') }}"
                        class="sidebar-item {{ request()->routeIs('payments.*') ? 'active' : '' }}">
                        <span class="sidebar-icon"><i class="fas fa-money-bill-wave"></i></span>
                        <span class="sidebar-text">Mensalidades</span>
                        @php
                            $overduePayments = \App\Models\Payment::where('status', 'overdue')->count();
                        @endphp
                        @if ($overduePayments > 0)
                            <span class="sidebar-badge badge-red">{{ $overduePayments }}</span>
                        @endif
                    </a>

                    <a href="{{ route('payments.references') }}" class="sidebar-item">
                        <span class="sidebar-icon"><i class="fas fa-receipt"></i></span>
                        <span class="sidebar-text">Referências</span>
                    </a>
                </div>
            @endcanany

            <!-- Gestão de Pessoal -->
            @canany(['manage_teachers', 'view_teachers'])
                <div class="nav-section">
                    <div class="section-title">Gestão de Pessoal</div>
                    <a href="{{ route('teachers.index') }}"
                        class="sidebar-item {{ request()->routeIs('teachers.*') ? 'active' : '' }}">
                        <span class="sidebar-icon"><i class="fas fa-chalkboard-teacher"></i></span>
                        <span class="sidebar-text">Professores</span>
                    </a>

                    @can('manage_leave_requests')
                        <a href="{{ route('teacher.leave-requests') }}"
                            class="sidebar-item {{ request()->routeIs('leave-requests.*') ? 'active' : '' }}">
                            <span class="sidebar-icon"><i class="fas fa-calendar-times"></i></span>
                            <span class="sidebar-text">Licenças</span>
                            @php
                                $pendingRequests = \App\Models\StaffLeaveRequest::where('status', 'pending')->count();
                            @endphp
                            @if ($pendingRequests > 0)
                                <span class="sidebar-badge badge-orange">{{ $pendingRequests }}</span>
                            @endif
                        </a>
                    @endcan
                </div>
            @endcanany

            <!-- Comunicação -->
            @can('manage_events')
                <div class="nav-section">
                    <div class="section-title">Comunicação</div>
                    <a href="{{ route('events.index') }}"
                        class="sidebar-item {{ request()->routeIs('events.*') ? 'active' : '' }}">
                        <span class="sidebar-icon"><i class="fas fa-calendar-alt"></i></span>
                        <span class="sidebar-text">Eventos</span>
                    </a>

                    <a href="{{ route('communications.index') }}"
                        class="sidebar-item {{ request()->routeIs('communications.*') ? 'active' : '' }}">
                        <span class="sidebar-icon"><i class="fas fa-bullhorn"></i></span>
                        <span class="sidebar-text">Comunicados</span>
                    </a>
                </div>
            @endcan

            <!-- Relatórios -->
            @canany(['view_reports', 'export_reports'])
                <div class="nav-section">
                    <div class="section-title">Relatórios</div>
                    <a href="{{ route('reports.index') }}"
                        class="sidebar-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <span class="sidebar-icon"><i class="fas fa-chart-bar"></i></span>
                        <span class="sidebar-text">Relatórios</span>
                        @can('export_reports')
                            <span class="sidebar-badge badge-blue">Export</span>
                        @endcan
                    </a>
                </div>
            @endcanany

            <!-- Administração -->
            @can('manage_users')
                <div class="nav-section">
                    <div class="section-title">Administração</div>
                    <a href="{{ route('admin.users.index') }}"
                        class="sidebar-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <span class="sidebar-icon"><i class="fas fa-users-cog"></i></span>
                        <span class="sidebar-text">Usuários</span>
                        <span class="sidebar-badge badge-red">Admin</span>
                    </a>

                    <a href="{{ route('admin.settings.index') }}"
                        class="sidebar-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <span class="sidebar-icon"><i class="fas fa-cog"></i></span>
                        <span class="sidebar-text">Configurações</span>
                    </a>
                </div>
            @endcan

            <!-- Minha Conta -->
            <div class="nav-section">
                <div class="section-title">Minha Conta</div>
                <a href="{{ route('profile.edit') }}"
                    class="sidebar-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <span class="sidebar-icon"><i class="fas fa-user-circle"></i></span>
                    <span class="sidebar-text">Meu Perfil</span>
                </a>
            </div>
        </nav>

        <!-- User Area -->
        <div class="sidebar-user">
            <div class="sidebar-user-profile">
                <div class="sidebar-user-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name">{{ explode(' ', auth()->user()->name)[0] }}</div>
                    <div class="sidebar-user-role">
                        @switch(auth()->user()->role)
                            @case('admin') Administrador @break
                            @case('secretary') Secretaria @break
                            @case('pedagogy') Pedagógico @break
                            @case('teacher') Professor @break
                            @case('parent') Encarregado @break
                            @default Usuário
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
    </aside>

    <!-- Main Content -->
    <main class="app-main" id="main-content">
        <!-- Breadcrumb -->
        <nav class="app-breadcrumb" aria-label="breadcrumb">
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

        <!-- Page Header -->
        {{-- <div class="page-header">
            <div class="page-title">
                <div class="title-icon" style="background-color: wheat !important">
                    <i class="@yield('title-icon', 'fas fa-tachometer-alt')"></i>
                </div>
                <div>
                    <h1 class="mb-0">@yield('page-title', 'Dashboard')</h1>
                    @hasSection('page-subtitle')
                        <small class="text-muted">@yield('page-subtitle')</small>
                    @endif
                </div>
            </div>
            <div class="page-actions">
                @yield('page-actions')
            </div>
        </div> --}}

        <!-- Page Content -->
        @yield('content')

        <!-- Footer -->
        <footer class="app-footer">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center">
                <div class="text-center text-sm-start mb-2 mb-sm-0">
                    <small class="text-muted">
                        © {{ date('Y') }} <strong style="color: var(--primary-blue);">Escola dos Visionários</strong> - Sistema de Gestão Escolar
                    </small>
                    <br>
                    <small class="text-muted">
                        Quelimane, Província da Zambézia - Moçambique
                    </small>
                </div>
                <div class="text-center text-sm-end">
                    <small class="text-muted">
                        <span class="badge" style="background: var(--success);">v1.0.0</span>
                        <a href="mailto:suporte@visionarios.co.mz" class="text-decoration-none me-2"
                            style="color: var(--primary-blue);">Suporte Técnico</a>
                        <a href="#" class="text-decoration-none" style="color: var(--primary-blue);">Manual do Sistema</a>
                    </small>
                </div>
            </div>
        </footer>
    </main>
<!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Estado do sidebar
        let sidebarCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
        let mobileSidebarOpen = false;

        // Toggle Sidebar Desktop
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const toggleIcon = document.getElementById('toggle-icon');

            if (window.innerWidth >= 1024) {
                sidebarCollapsed = !sidebarCollapsed;
                localStorage.setItem('sidebar-collapsed', sidebarCollapsed);

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

        // Toggle Sidebar Mobile
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            mobileSidebarOpen = !mobileSidebarOpen;

            if (mobileSidebarOpen) {
                sidebar.classList.add('mobile-open');
                overlay.classList.add('show');
                document.body.style.overflow = 'hidden';
            } else {
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('show');
                document.body.style.overflow = '';
            }
        }

        // Toggle Theme
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme') || 'light';
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';

            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);

            const icons = document.querySelectorAll('#theme-icon, #theme-icon-dropdown');
            const text = document.getElementById('theme-text');

            icons.forEach(icon => {
                icon.className = newTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
            });

            if (text) {
                text.textContent = newTheme === 'dark' ? 'Modo Claro' : 'Modo Escuro';
            }
        }

        // Sistema de Toast Notifications
        function showToast(type, title, message) {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast-custom ${type}`;
            
            const icons = {
                success: 'fa-check-circle',
                error: 'fa-times-circle',
                warning: 'fa-exclamation-triangle',
                info: 'fa-info-circle'
            };
            
            toast.innerHTML = `
                <div class="toast-icon">
                    <i class="fas ${icons[type] || icons.info}"></i>
                </div>
                <div class="toast-content">
                    <div class="toast-title">${title}</div>
                    <div class="toast-message">${message}</div>
                </div>
                <button class="toast-close" onclick="closeToast(this)">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            container.appendChild(toast);
            
            // Auto-remover após 5 segundos
            setTimeout(() => {
                closeToast(toast.querySelector('.toast-close'));
            }, 5000);
        }

        function closeToast(button) {
            const toast = button.closest('.toast-custom');
            if (toast) {
                toast.classList.add('hiding');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }
        }

        // Marcar notificação como lida
        function markAsRead(notificationId, event) {
            event.preventDefault();
            
            fetch(`/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirecionar para a URL da notificação
                    const link = event.target.closest('a');
                    if (link && link.href) {
                        window.location.href = link.href;
                    }
                }
            })
            .catch(error => console.error('Erro ao marcar notificação como lida:', error));
        }

        // Marcar todas as notificações como lidas
        function markAllAsRead(event) {
            event.preventDefault();
            
            if (!confirm('Deseja marcar todas as notificações como lidas?')) {
                return;
            }
            
            fetch('/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('success', 'Sucesso', 'Todas as notificações foram marcadas como lidas');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                }
            })
            .catch(error => {
                console.error('Erro ao marcar todas como lidas:', error);
                showToast('error', 'Erro', 'Não foi possível marcar as notificações como lidas');
            });
        }

        // Inicializar estado do sidebar ao carregar a página
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const toggleIcon = document.getElementById('toggle-icon');

            // Restaurar estado do sidebar
            if (window.innerWidth >= 1024 && sidebarCollapsed) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('collapsed');
                if (toggleIcon) {
                    toggleIcon.className = 'fas fa-chevron-right';
                }
            }

            // Restaurar tema
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
            
            const icons = document.querySelectorAll('#theme-icon, #theme-icon-dropdown');
            const text = document.getElementById('theme-text');
            
            icons.forEach(icon => {
                icon.className = savedTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
            });
            
            if (text) {
                text.textContent = savedTheme === 'dark' ? 'Modo Claro' : 'Modo Escuro';
            }

            // Fechar sidebar mobile ao clicar em um link
            const sidebarLinks = document.querySelectorAll('.sidebar-item');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 1024 && mobileSidebarOpen) {
                        toggleMobileSidebar();
                    }
                });
            });

            // Mostrar mensagens flash como toast
            @if(session('success'))
                showToast('success', 'Sucesso', '{{ session('success') }}');
            @endif

            @if(session('error'))
                showToast('error', 'Erro', '{{ session('error') }}');
            @endif

            @if(session('warning'))
                showToast('warning', 'Atenção', '{{ session('warning') }}');
            @endif

            @if(session('info'))
                showToast('info', 'Informação', '{{ session('info') }}');
            @endif

            // Verificar notificações a cada 60 segundos
            setInterval(updateNotificationBadge, 60000);
        });

        // Atualizar badge de notificações
        function updateNotificationBadge() {
            fetch('/api/dashboard/counters')
                .then(response => response.json())
                .then(data => {
                    const badges = document.querySelectorAll('.action-btn .badge');
                    badges.forEach(badge => {
                        if (data.notifications !== undefined) {
                            badge.textContent = data.notifications;
                            if (data.notifications === 0) {
                                badge.style.display = 'none';
                            } else {
                                badge.style.display = 'flex';
                            }
                        }
                    });
                })
                .catch(error => console.error('Erro ao atualizar badge:', error));
        }

        // Função para confirmar exclusão
        function confirmDelete(message) {
            return confirm(message || 'Tem certeza que deseja excluir este item? Esta ação não pode ser desfeita.');
        }

        // Função para formatar valores monetários
        function formatCurrency(value) {
            return new Intl.NumberFormat('pt-MZ', {
                style: 'decimal',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(value) + ' MT';
        }

        // Função para formatar datas
        function formatDate(date) {
            return new Intl.DateTimeFormat('pt-MZ', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            }).format(new Date(date));
        }

        // Prevenir múltiplos submits de formulários
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                let isSubmitting = false;
                
                form.addEventListener('submit', function(e) {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    
                    if (isSubmitting) {
                        e.preventDefault();
                        return false;
                    }
                    
                    if (submitBtn && !submitBtn.disabled) {
                        isSubmitting = true;
                        
                        // Guardar texto original
                        if (!submitBtn.dataset.originalText) {
                            submitBtn.dataset.originalText = submitBtn.innerHTML;
                        }
                        
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processando...';
                        
                        // Reativar após 10 segundos (caso haja erro de validação)
                        setTimeout(() => {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = submitBtn.dataset.originalText;
                            isSubmitting = false;
                        }, 10000);
                    }
                });
            });
        });

        // Função para busca ao vivo
        let searchTimeout;
        function liveSearch(input) {
            clearTimeout(searchTimeout);
            const query = input.value.trim();
            
            if (query.length < 3) {
                return;
            }
            
            searchTimeout = setTimeout(() => {
                console.log('Buscando:', query);
                // Implementar busca ao vivo aqui se necessário
            }, 500);
        }

        // Adicionar listener para campo de busca
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('.search-input');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    liveSearch(this);
                });
                
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const query = this.value.trim();
                        if (query.length >= 3) {
                            window.location.href = `/search?q=${encodeURIComponent(query)}`;
                        } else {
                            showToast('warning', 'Atenção', 'Digite pelo menos 3 caracteres para pesquisar');
                        }
                    }
                });
            }
        });

        // Função para copiar texto para clipboard
        function copyToClipboard(text, showMessage = true) {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(text).then(() => {
                    if (showMessage) {
                        showToast('success', 'Copiado', 'Texto copiado para a área de transferência');
                    }
                }).catch(err => {
                    console.error('Erro ao copiar:', err);
                    if (showMessage) {
                        showToast('error', 'Erro', 'Não foi possível copiar o texto');
                    }
                });
            } else {
                // Fallback para navegadores antigos
                const textArea = document.createElement('textarea');
                textArea.value = text;
                textArea.style.position = 'fixed';
                textArea.style.left = '-999999px';
                document.body.appendChild(textArea);
                textArea.select();
                try {
                    document.execCommand('copy');
                    if (showMessage) {
                        showToast('success', 'Copiado', 'Texto copiado para a área de transferência');
                    }
                } catch (err) {
                    console.error('Erro ao copiar:', err);
                    if (showMessage) {
                        showToast('error', 'Erro', 'Não foi possível copiar o texto');
                    }
                }
                document.body.removeChild(textArea);
            }
        }

        // Função para imprimir
        function printPage() {
            window.print();
        }

        // Função para exportar tabela para CSV
        function exportTable(tableId, filename) {
            const table = document.getElementById(tableId);
            if (!table) {
                showToast('error', 'Erro', 'Tabela não encontrada');
                return;
            }
            
            let csv = [];
            const rows = table.querySelectorAll('tr');
            
            rows.forEach(row => {
                const cols = row.querySelectorAll('td, th');
                const csvRow = [];
                cols.forEach(col => {
                    // Limpar o texto e escapar vírgulas
                    let text = col.innerText.trim().replace(/"/g, '""');
                    if (text.includes(',') || text.includes('\n')) {
                        text = `"${text}"`;
                    }
                    csvRow.push(text);
                });
                csv.push(csvRow.join(','));
            });
            
            const csvContent = '\uFEFF' + csv.join('\n'); // BOM para UTF-8
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = (filename || 'export') + '.csv';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
            
            showToast('success', 'Sucesso', 'Arquivo CSV exportado com sucesso');
        }

        // Função para fazer scroll suave
        function smoothScrollTo(elementId) {
            const element = document.getElementById(elementId);
            if (element) {
                element.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }

        // Função para loading em botões
        function setButtonLoading(button, loading = true) {
            if (!button) return;
            
            if (loading) {
                if (!button.dataset.originalText) {
                    button.dataset.originalText = button.innerHTML;
                }
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Carregando...';
            } else {
                button.disabled = false;
                button.innerHTML = button.dataset.originalText || 'Enviar';
            }
        }

        // Função para validar email
        function isValidEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        // Função para validar telefone moçambicano
        function isValidMozambiquePhone(phone) {
            // Remove espaços e caracteres especiais
            const cleaned = phone.replace(/[\s\-\(\)]/g, '');
            // Valida formatos: 84XXXXXXX ou +25884XXXXXXX
            const re = /^(\+258)?(8[2-7])\d{7}$/;
            return re.test(cleaned);
        }

        // Função para formatar número de telefone
        function formatPhoneNumber(phone) {
            const cleaned = phone.replace(/[\s\-\(\)]/g, '');
            if (cleaned.length === 9) {
                return `${cleaned.substr(0, 2)} ${cleaned.substr(2, 3)} ${cleaned.substr(5, 4)}`;
            }
            return phone;
        }

        // Função para debounce
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

        // Listener para redimensionamento da janela
        window.addEventListener('resize', debounce(function() {
            // Fechar sidebar mobile se a tela ficar maior
            if (window.innerWidth >= 1024 && mobileSidebarOpen) {
                toggleMobileSidebar();
            }
            
            // Ajustar sidebar desktop
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            
            if (window.innerWidth < 1024) {
                sidebar.classList.remove('collapsed');
                mainContent.classList.remove('collapsed');
            } else if (sidebarCollapsed) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('collapsed');
            }
        }, 250));

        // Log de erros JavaScript para o servidor (opcional)
        window.addEventListener('error', function(e) {
            if (typeof console !== 'undefined' && console.error) {
                console.error('JavaScript Error:', e.message, e.filename, e.lineno);
            }
            
            // Opcional: enviar para o servidor
            // fetch('/api/log-js-error', {
            //     method: 'POST',
            //     headers: {
            //         'Content-Type': 'application/json',
            //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            //     },
            //     body: JSON.stringify({
            //         message: e.message,
            //         filename: e.filename,
            //         lineno: e.lineno,
            //         colno: e.colno,
            //         stack: e.error ? e.error.stack : null
            //     })
            // });
        });
    </script>

    @stack('scripts')
</body>
</html>