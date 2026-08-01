<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demonstração 1-Click — {{ setting('school_name', config('app.name', 'ZamEdu')) }}</title>
    @if(file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col justify-between">

    <!-- Header Navigation -->
    <header class="border-b border-slate-800 bg-slate-950/80 backdrop-blur sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="{{ route('welcome') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-400 flex items-center justify-center text-white shadow-lg shadow-emerald-500/20 font-bold text-xl">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <span class="font-extrabold text-xl tracking-tight text-white">ZamEdu <span class="text-xs px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-400 font-semibold border border-emerald-500/30">SIGE</span></span>
            </a>

            <div class="flex items-center gap-4">
                <a href="{{ route('sobre') }}" class="text-sm font-medium text-slate-300 hover:text-white transition">Sobre o Sistema</a>
                <a href="{{ route('contacto') }}" class="text-sm font-medium text-slate-300 hover:text-white transition">Contacto</a>
                <a href="{{ route('login') }}" class="text-sm font-semibold px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white transition border border-slate-700">
                    <i class="fas fa-sign-in-alt mr-2"></i>Entrar
                </a>
            </div>
        </div>
    </header>

    <!-- Main Section -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 flex-1 flex flex-col justify-center">

        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-xs font-semibold uppercase tracking-wider mb-4">
                <i class="fas fa-bolt"></i> Acesso Instantâneo Sem Palavra-passe
            </span>
            <h1 class="text-3xl sm:text-5xl font-black text-white tracking-tight mb-4">
                Experimente o ZamEdu no Perfil Desejado
            </h1>
            <p class="text-slate-400 text-lg">
                Selecione abaixo o perfil que pretende testar. A autenticação é imediata e dá acesso a um ambiente seguro com dados reais de demonstração.
            </p>
        </div>

        <!-- Role Selector Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($profiles as $profile)
                <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-6 hover:border-emerald-500/50 hover:shadow-xl hover:shadow-emerald-500/10 transition-all group flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 text-xl group-hover:scale-110 transition-transform">
                                <i class="{{ $profile['icon'] }}"></i>
                            </div>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-700 text-slate-300">1-Click Demo</span>
                        </div>

                        <h3 class="text-xl font-bold text-white mb-2 group-hover:text-emerald-400 transition-colors">
                            {{ $profile['name'] }}
                        </h3>
                        <p class="text-slate-400 text-sm mb-6 leading-relaxed">
                            {{ $profile['description'] }}
                        </p>
                    </div>

                    <div>
                        <a href="{{ route('demo.role', $profile['role']) }}" class="w-full inline-flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold shadow-lg shadow-emerald-600/20 transition-all text-sm">
                            <i class="fas fa-play text-xs"></i> Testar como {{ explode('/', $profile['name'])[0] }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Info Banner -->
        <div class="mt-12 bg-slate-950/60 border border-slate-800 rounded-2xl p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <h4 class="text-white font-bold text-sm">Ambiente de Demonstração Seguro</h4>
                    <p class="text-slate-400 text-xs">Os dados inseridos no ambiente de demonstração são reiniciados periodicamente. Nenhuma cobrança é efetuada.</p>
                </div>
            </div>
            <a href="{{ route('contacto') }}" class="text-xs font-semibold text-emerald-400 hover:text-emerald-300 underline whitespace-nowrap">
                Solicitar Demonstração Privada da Minha Escola &rarr;
            </a>
        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-800 bg-slate-950 py-6">
        <div class="max-w-7xl mx-auto px-4 text-center text-xs text-slate-500">
            &copy; {{ date('Y') }} {{ setting('school_name', config('app.name', 'ZamEdu')) }}. Todos os direitos reservados.
        </div>
    </footer>
</body>
</html>
