<div class="nav-section">
    <div class="nav-section-title">Gestão Escolar</div>
    <ul class="nav-list">
        <li class="nav-item">
            <a href="{{ route('students.index') }}" class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-user-graduate"></i></span>
                <span class="nav-text">Alunos</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('teachers.index') }}" class="nav-link {{ request()->routeIs('teachers.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-chalkboard-teacher"></i></span>
                <span class="nav-text">Professores</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('classes.index') }}" class="nav-link {{ request()->routeIs('classes.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-chalkboard"></i></span>
                <span class="nav-text">Turmas</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('enrollments.index') }}" class="nav-link {{ request()->routeIs('enrollments.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-clipboard-list"></i></span>
                <span class="nav-text">Matrículas</span>
            </a>
        </li>
    </ul>
</div>
