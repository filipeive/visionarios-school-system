<nav class="school-breadcrumb mb-3" aria-label="breadcrumb">
    <ol class="breadcrumb mb-0 align-items-center">
        <li class="breadcrumb-item">
            @auth
                <a href="{{ route('dashboard') }}" class="text-decoration-none text-slate-500 hover:text-emerald-600 font-medium transition">
                    <i class="fas fa-home me-1"></i> Início
                </a>
            @else
                <a href="{{ route('welcome') }}" class="text-decoration-none text-slate-500 hover:text-emerald-600 font-medium transition">
                    <i class="fas fa-home me-1"></i> Início
                </a>
            @endauth
        </li>
        @yield('breadcrumbs')
    </ol>
</nav>
