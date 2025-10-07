<!-- resources/views/layouts/partials/sidebar.blade.php -->
<nav class="school-sidebar" id="sidebar">
    <div class="school-header">
        <div class="school-logo">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <div class="school-brand">
            <div class="school-name">ESCOLA DOS VISIONÁRIOS</div>
            <div class="school-subtitle">AQUI SE PREPARA A NOVA GERAÇÃO</div>
        </div>
        <button class="sidebar-toggle" onclick="toggleSidebar()">
            <i class="fas fa-chevron-left" id="toggle-icon"></i>
        </button>
    </div>

    <div class="school-nav">
        <!-- Dashboard -->
        <div class="nav-section">
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" 
                       class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="fas fa-home"></i></span>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- ========== PROFESSOR ========== -->
        @if(auth()->user()->hasRole('teacher'))
            @include('layouts.partials.menus.teacher')
        @endif

        <!-- ========== ADMIN / SECRETARIA ========== -->
        @canany(['manage_students', 'view_students', 'manage_teachers', 'manage_classes'])
            @include('layouts.partials.menus.management')
        @endcanany

        <!-- ========== RELATÓRIOS ========== -->
        @canany(['view_reports', 'export_reports'])
            <div class="nav-section">
                <div class="nav-section-title">Relatórios</div>
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="{{ route('reports.index') }}"
                           class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                            <span class="nav-icon"><i class="fas fa-chart-bar"></i></span>
                            <span class="nav-text">Relatórios</span>
                            @can('export_reports')
                                <span class="nav-badge badge-success">Exportar</span>
                            @endcan
                        </a>
                    </li>
                </ul>
            </div>
        @endcanany

        <!-- ========== PERFIL ========== -->
        <div class="nav-section">
            <div class="nav-section-title">Minha Conta</div>
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="{{ route('profile.edit') }}" 
                       class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="fas fa-user-circle"></i></span>
                        <span class="nav-text">Meu Perfil</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Rodapé do menu -->
    <div class="user-area">
        <div class="user-profile">
            <div class="user-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
            <div class="user-info">
                <div class="user-name">{{ explode(' ', auth()->user()->name)[0] }}</div>
                <div class="user-role">
                    @if(auth()->user()->hasRole('teacher')) Professor(a)
                    @elseif(auth()->user()->hasRole('admin')) Administrador
                    @elseif(auth()->user()->hasRole('secretary')) Secretaria
                    @elseif(auth()->user()->hasRole('parent')) Encarregado
                    @else Usuário
                    @endif
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
