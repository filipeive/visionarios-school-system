<div class="nav-section">
    <div class="nav-section-title">Meu Portal</div>
    <ul class="nav-list">
        <li class="nav-item">
            <a href="{{ route('teacher-portal.dashboard') }}" 
               class="nav-link {{ request()->routeIs('teacher-portal.dashboard') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-tachometer-alt"></i></span>
                <span class="nav-text">Painel</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('teacher-portal.classes') }}" 
               class="nav-link {{ request()->routeIs('teacher-portal.classes') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-chalkboard"></i></span>
                <span class="nav-text">Minhas Turmas</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('teacher.leave-requests') }}" 
               class="nav-link {{ request()->routeIs('leave-requests.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-calendar-times"></i></span>
                <span class="nav-text">Licenças</span>
            </a>
        </li>
    </ul>
</div>
