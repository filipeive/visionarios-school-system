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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            /* Cores do Logo Visionários */
            --primary-green: #7CB342;
            --primary-blue: #0f60b0;
            --primary-orange: #FF9800;
            --accent-yellow: #FDD835;

            /* Sistema de Cores */
            --success: #7CB342;
            --info: #2E5C8A;
            --warning: #FF9800;
            --danger: #E53935;

            /* Neutrals */
            --gray-50: #FAFAFA;
            --gray-100: #F5F5F5;
            --gray-200: #EEEEEE;
            --gray-300: #E0E0E0;
            --gray-400: #BDBDBD;
            --gray-500: #9E9E9E;
            --gray-600: #757575;
            --gray-700: #616161;
            --gray-800: #424242;
            --gray-900: #212121;

            /* Layout */
            --header-height: 56px;
            --sidebar-width: 240px;
            --sidebar-collapsed: 60px;

            /* Backgrounds */
            --bg-app: #F8F9FA;
            --bg-card: #FFFFFF;
            --bg-sidebar: #2E5C8A;
            --bg-header: #FFFFFF;

            /* Text */
            --text-primary: #212121;
            --text-secondary: #616161;
            --text-muted: #9E9E9E;
            --text-inverse: #FFFFFF;

            /* Shadows */
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 2px 8px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 4px 16px rgba(0, 0, 0, 0.12);

            /* Border */
            --border-radius: 8px;
            --border-color: #E0E0E0;
        }

        [data-theme="dark"] {
            --bg-app: #121212;
            --bg-card: #1E1E1E;
            --bg-header: #1E1E1E;
            --bg-sidebar: #1A3A52;
            --text-primary: #FFFFFF;
            --text-secondary: #B0B0B0;
            --text-muted: #707070;
            --border-color: #333333;
            --gray-100: #2A2A2A;
            --gray-50: #1A1A1A;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            background: var(--bg-app);
            color: var(--text-primary);
            overflow-x: hidden;
        }

        /* ===== HEADER ===== */
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
            padding: 0 16px;
            gap: 16px;
            z-index: 1000;
            box-shadow: var(--shadow-sm);
        }

        .header-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: var(--sidebar-width);
            padding-right: 16px;
        }

        .page-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
            display: flex;
            align-items: center;
        }

        .page-title i {
            margin-right: 10px;
            color: var(--primary-blue);
            font-size: 18px;
        }

        .logo-container {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-green));
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .logo-container i {
            color: white;
            font-size: 18px;
        }

        .logo-text-container {
            display: flex;
            flex-direction: column;
        }

        .logo-text {
            font-weight: 700;
            font-size: 14px;
            color: var(--primary-blue);
            line-height: 1;
            letter-spacing: 0.5px;
        }

        .logo-subtitle {
            font-size: 10px;
            color: var(--text-muted);
            font-weight: 500;
        }

        .sidebar-toggle-desktop {
            width: 32px;
            height: 32px;
            border: 1px solid var(--border-color);
            background: var(--bg-card);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            margin-left: auto;
        }

        .sidebar-toggle-desktop:hover {
            background: var(--gray-100);
            border-color: var(--primary-blue);
        }

        .mobile-toggle {
            display: none;
            width: 32px;
            height: 32px;
            background: transparent;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--text-secondary);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: auto;
        }

        .header-search {
            position: relative;
            width: 320px;
        }

        .search-input {
            width: 100%;
            height: 36px;
            padding: 0 36px;
            border: 1px solid var(--border-color);
            border-radius: 18px;
            font-size: 13px;
            background: var(--gray-50);
            color: var(--text-primary);
            transition: all 0.2s;
        }

        .search-input:focus {
            outline: none;
            background: var(--bg-card);
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(46, 92, 138, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 14px;
        }

        .action-btn {
            width: 36px;
            height: 36px;
            border: none;
            background: transparent;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }

        .action-btn:hover {
            background: var(--gray-100);
            color: var(--text-primary);
        }

        .action-btn .badge {
            position: absolute;
            top: 2px;
            right: 2px;
            min-width: 16px;
            height: 16px;
            background: var(--danger);
            color: white;
            border-radius: 50%;
            font-size: 10px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--bg-header);
            padding: 0 4px;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 4px 8px 4px 4px;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid transparent;
        }

        .user-menu:hover {
            background: var(--gray-100);
            border-color: var(--border-color);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-green));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 12px;
        }

        .user-name {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-primary);
        }

        /* ===== SIDEBAR ===== */
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
            padding: 12px 8px;
            flex: 1;
        }

        .nav-section {
            margin-bottom: 20px;
        }

        .section-title {
            padding: 8px 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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
            gap: 12px;
            padding: 10px 12px;
            margin-bottom: 2px;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s;
            cursor: pointer;
            position: relative;
        }

        .sidebar-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .sidebar-item.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
        }

        .sidebar-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: var(--primary-orange);
            border-radius: 0 3px 3px 0;
        }

        .sidebar-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
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
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: 700;
            min-width: 18px;
            text-align: center;
            transition: all 0.3s;
        }

        .app-sidebar.collapsed .sidebar-badge {
            opacity: 0;
            transform: scale(0);
            width: 0;
        }

        .badge-orange {
            background: var(--primary-orange);
            color: white;
        }

        .badge-green {
            background: var(--primary-green);
            color: white;
        }

        .badge-red {
            background: var(--danger);
            color: white;
        }

        .badge-blue {
            background: var(--info);
            color: white;
        }

        /* Área do Usuário na Sidebar */
        .sidebar-user {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 16px;
            background: rgba(0, 0, 0, 0.1);
        }

        .sidebar-user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
            transition: all 0.3s;
        }

        .app-sidebar.collapsed .sidebar-user-profile {
            justify-content: center;
        }

        .sidebar-user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-orange), #F57C00);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
            flex-shrink: 0;
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
            font-size: 13px;
            font-weight: 600;
            color: white;
            margin-bottom: 2px;
        }

        .sidebar-user-role {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.6);
        }

        .logout-btn {
            width: 100%;
            padding: 10px 12px;
            border: none;
            background: rgba(229, 57, 53, 0.9);
            color: white;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .logout-btn:hover {
            background: var(--danger);
            transform: translateY(-1px);
        }

        .app-sidebar.collapsed .logout-btn {
            padding: 10px;
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
            padding: 24px;
            min-height: calc(100vh - var(--header-height));
            transition: all 0.3s;
        }

        .app-main.collapsed {
            margin-left: var(--sidebar-collapsed);
        }

        /* ===== BREADCRUMB ===== */
        .app-breadcrumb {
            margin-bottom: 20px;
        }

        .breadcrumb {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            padding: 12px 16px;
            margin-bottom: 0;
            box-shadow: var(--shadow-sm);
        }

        .breadcrumb-item a {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 500;
        }

        .breadcrumb-item.active {
            color: var(--text-secondary);
        }

        /* ===== PAGE HEADER ===== */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .page-title {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 24px;
            font-weight: 700;
            color: var(--text-primary);
        }

        .title-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-green));
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
        }

        /* ===== ALERTS ===== */
        .alert-visionarios {
            border: none;
            border-radius: var(--border-radius);
            padding: 14px 18px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: var(--shadow-sm);
        }

        .alert-visionarios i {
            font-size: 18px;
        }

        .alert-success-visionarios {
            background: rgba(124, 179, 66, 0.1);
            color: var(--success);
            border-left: 4px solid var(--success);
        }

        .alert-danger-visionarios {
            background: rgba(229, 57, 53, 0.1);
            color: var(--danger);
            border-left: 4px solid var(--danger);
        }

        .alert-warning-visionarios {
            background: rgba(255, 152, 0, 0.1);
            color: var(--warning);
            border-left: 4px solid var(--warning);
        }

        .alert-info-visionarios {
            background: rgba(46, 92, 138, 0.1);
            color: var(--info);
            border-left: 4px solid var(--info);
        }

        /* ===== STATS CARDS ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.2s;
            box-shadow: var(--shadow-sm);
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
            flex-shrink: 0;
        }

        .icon-blue {
            background: linear-gradient(135deg, #2E5C8A, #1E3A52);
        }

        .icon-green {
            background: linear-gradient(135deg, #7CB342, #558B2F);
        }

        .icon-orange {
            background: linear-gradient(135deg, #FF9800, #F57C00);
        }

        .icon-red {
            background: linear-gradient(135deg, #E53935, #C62828);
        }

        .stat-content {
            flex: 1;
        }

        .stat-label {
            font-size: 12px;
            font-weight: 500;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1;
        }

        .stat-change {
            font-size: 12px;
            font-weight: 600;
            margin-top: 4px;
        }

        .stat-change.positive {
            color: var(--success);
        }

        .stat-change.negative {
            color: var(--danger);
        }

        /* ===== SCHOOL CARDS ===== */
        .school-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            margin-bottom: 24px;
            transition: all 0.2s;
        }

        .school-card:hover {
            box-shadow: var(--shadow-md);
        }

        .school-card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-color);
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .school-card-body {
            padding: 20px;
        }

        /* ===== BUTTONS ===== */
        .btn-primary-visionarios {
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-green));
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary-visionarios:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
            color: white;
        }

        .btn-success-visionarios {
            background: var(--success);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-warning-visionarios {
            background: var(--warning);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        /* ===== TABLES ===== */
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
            border-bottom: 1px solid var(--border-color);
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-secondary);
            padding: 12px 16px;
        }

        .school-table .table tbody td {
            padding: 12px 16px;
            vertical-align: middle;
            border-color: var(--border-color);
        }

        .school-table .table tbody tr:hover {
            background: var(--gray-50);
        }

        /* ===== SIDEBAR OVERLAY ===== */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 998;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
        }

        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        /* ===== FOOTER ===== */
        .app-footer {
            background: var(--bg-card);
            border-top: 1px solid var(--border-color);
            padding: 20px 24px;
            margin-top: auto;
            box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.05);
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

            .mobile-toggle {
                display: flex;
            }

            .header-search {
                width: 200px;
            }

            .logo-subtitle {
                display: none;
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
                padding: 16px;
            }

            .page-title {
                font-size: 20px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ===== SCROLLBAR ===== */
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

        /* ===== TOAST NOTIFICATIONS ===== */
        .toast-visionarios {
            min-width: 350px;
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
        }

        .toast-visionarios .toast-header {
            border-bottom: none;
            padding: 12px 16px;
            font-weight: 600;
            font-size: 13px;
        }

        .toast-visionarios .toast-body {
            padding: 12px 16px;
            font-size: 13px;
        }

        .toast-success {
            background: var(--success);
            color: white;
        }

        .toast-success .toast-header {
            background: var(--success);
            color: white;
        }

        .toast-success .btn-close {
            filter: brightness(0) invert(1);
        }

        .toast-error {
            background: var(--danger);
            color: white;
        }

        .toast-error .toast-header {
            background: var(--danger);
            color: white;
        }

        .toast-error .btn-close {
            filter: brightness(0) invert(1);
        }

        .toast-warning {
            background: var(--warning);
            color: white;
        }

        .toast-warning .toast-header {
            background: var(--warning);
            color: white;
        }

        .toast-warning .btn-close {
            filter: brightness(0) invert(1);
        }

        .toast-info {
            background: var(--info);
            color: white;
        }

        .toast-info .toast-header {
            background: var(--info);
            color: white;
        }

        .toast-info .btn-close {
            filter: brightness(0) invert(1);
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;" id="toast-container"></div>

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

            <ul class="dropdown-menu dropdown-menu-end shadow"
                style="width: 380px; max-height: 500px; overflow-y: auto;">
                <li class="dropdown-header d-flex justify-content-between align-items-center p-3">
                    <strong>Notificações</strong>
                    <a href="#" class="text-decoration-none small" style="color: var(--primary-blue);"
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

                <li>
                    <hr class="dropdown-divider m-0">
                </li>
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
                    <a class="dropdown-item" href="#" onclick="toggleTheme(); return false;">
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
                            @case('admin')
                                Administrador
                            @break

                            @case('secretary')
                                Secretaria
                            @break

                            @case('pedagogy')
                                Pedagógico
                            @break

                            @case('teacher')
                                Professor
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

        <!-- Alerts -->
        @if (session('success'))
            <div class="alert-visionarios alert-success-visionarios alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i>
                <div>
                    <strong>Sucesso!</strong> {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert-visionarios alert-danger-visionarios alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    <strong>Erro!</strong> {{ session('error') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert-visionarios alert-warning-visionarios alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                    <strong>Atenção!</strong> {{ session('warning') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('info'))
            <div class="alert-visionarios alert-info-visionarios alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle"></i>
                <div>
                    <strong>Informação!</strong> {{ session('info') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-visionarios alert-danger-visionarios alert-dismissible fade show" role="alert">
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

        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                <div class="title-icon">
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
        </div>

        <!-- Page Content -->
        @yield('content')

        <!-- Footer -->
        <footer class="app-footer">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center">
                <div class="text-center text-sm-start mb-2 mb-sm-0">
                    <small class="text-muted">
                        © {{ date('Y') }} <strong style="color: var(--primary-blue);">Escola dos
                            Visionários</strong> - Sistema de Gestão Escolar
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
                        <a href="#" class="text-decoration-none" style="color: var(--primary-blue);">Manual do
                            Sistema</a>
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

        // Mark notification as read
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
                    const badge = document.querySelector('.action-btn .badge');
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
                        const newBadge = item.querySelector('.badge');
                        if (newBadge) newBadge.remove();
                    }
                }
            } catch (error) {
                console.error('Erro ao marcar notificação:', error);
            }
        }

        // Mark all notifications as read
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
                    const badge = document.querySelector('.action-btn .badge');
                    if (badge) badge.remove();

                    document.querySelectorAll('.dropdown-item.bg-light').forEach(item => {
                        item.classList.remove('bg-light');
                    });
                    document.querySelectorAll('.dropdown-menu .badge').forEach(badge => badge.remove());
                }
            } catch (error) {
                console.error('Erro:', error);
            }
        }

        // Search functionality
        document.querySelector('.search-input')?.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const query = this.value.trim();
                if (query.length > 2) {
                    window.location.href = `/search?q=${encodeURIComponent(query)}`;
                }
            }
        });

        // Initialize on load
        document.addEventListener('DOMContentLoaded', function() {
            // Apply saved theme
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

            // Apply saved sidebar state
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const toggleIcon = document.getElementById('toggle-icon');

            if (window.innerWidth >= 1024 && sidebarCollapsed) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('collapsed');
                toggleIcon.className = 'fas fa-chevron-right';
            }

            // Auto-hide alerts
            setTimeout(() => {
                document.querySelectorAll('.alert-visionarios').forEach(alert => {
                    const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                    bsAlert?.close();
                });
            }, 8000);

            console.log('✅ Sistema Visionários iniciado com sucesso!');
        });

        // Window resize handler
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                if (mobileSidebarOpen) toggleMobileSidebar();
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
