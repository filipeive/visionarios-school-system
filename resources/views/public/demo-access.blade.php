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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --green-900: #1B5E20;
            --green-800: #2E7D32;
            --green-700: #388E3C;
            --green-600: #43A047;
            --yellow-400: #FFC107;
            --gray-900: #212121;
            --gray-700: #616161;
            --gray-200: #EEEEEE;
            --gray-50: #FAFAFA;
            --white: #FFFFFF;
        }
        body { font-family: 'Inter', sans-serif; background-color: var(--gray-50); color: var(--gray-900); }
        h1, h2, h3, h4 { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between">

    <!-- ====== TOP BAR ====== -->
    <header class="bg-white border-b border-gray-200 py-3">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <a href="{{ route('welcome') }}" class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-emerald-800 flex items-center justify-center text-white text-2xl font-bold shadow">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div>
                    <h1 class="text-lg font-extrabold text-gray-900 leading-tight">
                        {{ setting('school_name', config('app.name', 'ZamEdu')) }}
                    </h1>
                    <p class="text-xs text-gray-500 font-medium">Sistema de Gestão Escolar Integrado</p>
                </div>
            </a>

            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-4 py-2 rounded-lg bg-emerald-800 text-white text-sm font-semibold hover:bg-emerald-700 transition">
                        <i class="fas fa-gauge-high mr-2"></i>Acessar Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg bg-emerald-800 text-white text-sm font-semibold hover:bg-emerald-700 transition shadow-sm">
                        <i class="fas fa-right-to-bracket mr-2"></i>Entrar na Conta
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- ====== YELLOW ACCENT BAR ====== -->
    <div class="h-1 bg-amber-400"></div>

    <!-- ====== GREEN NAVIGATION ====== -->
    <nav class="bg-emerald-900 text-white sticky top-0 z-50 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-14">
            <ul class="flex items-center gap-6 text-sm font-semibold">
                <li><a href="{{ route('welcome') }}" class="hover:text-amber-400 transition flex items-center gap-2"><i class="fas fa-home"></i> Início</a></li>
                <li><a href="{{ route('sobre') }}" class="hover:text-amber-400 transition flex items-center gap-2"><i class="fas fa-info-circle"></i> Sobre o Sistema</a></li>
                <li><a href="{{ route('demo.access') }}" class="text-amber-400 font-bold border-b-2 border-amber-400 pb-1 flex items-center gap-2"><i class="fas fa-bolt"></i> 1-Click Demo</a></li>
                <li><a href="{{ route('contacto') }}" class="hover:text-amber-400 transition flex items-center gap-2"><i class="fas fa-envelope"></i> Contactos & Proposta</a></li>
                <li><a href="{{ route('public.pre-enrollment') }}" class="hover:text-amber-400 transition flex items-center gap-2"><i class="fas fa-file-pen"></i> Pré-Matrícula</a></li>
            </ul>
        </div>
    </nav>

    <!-- ====== MAIN CONTENT ====== -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 flex-1">

        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider mb-3">
                <i class="fas fa-bolt text-amber-500"></i> Acesso Instantâneo Sem Palavra-passe
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-emerald-900 tracking-tight mb-4">
                Experimente o ZamEdu no Perfil Desejado
            </h1>
            <p class="text-gray-600 text-base leading-relaxed">
                Selecione abaixo o perfil que pretende testar. A autenticação é imediata em 1 clique e dá acesso ao ambiente de demonstração com dados de exemplo.
            </p>
        </div>

        <!-- Role Selector Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($profiles as $profile)
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-lg hover:border-emerald-500 transition-all flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-800 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                                <i class="{{ $profile['icon'] }}"></i>
                            </div>
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 border border-amber-200">
                                1-Click Demo
                            </span>
                        </div>

                        <h3 class="text-xl font-bold text-emerald-900 mb-2 group-hover:text-emerald-700 transition-colors">
                            {{ $profile['name'] }}
                        </h3>
                        <p class="text-gray-600 text-sm mb-6 leading-relaxed">
                            {{ $profile['description'] }}
                        </p>
                    </div>

                    <div>
                        <a href="{{ route('demo.role', $profile['role']) }}" class="w-full inline-flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-emerald-800 hover:bg-emerald-700 text-white font-bold shadow transition text-sm">
                            <i class="fas fa-play text-xs text-amber-400"></i> Testar como {{ explode('/', $profile['name'])[0] }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Info Card -->
        <div class="mt-12 bg-white border border-emerald-200 rounded-2xl p-6 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center shrink-0 text-xl">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <h4 class="text-emerald-900 font-bold text-base">Ambiente de Demonstração Seguro</h4>
                    <p class="text-gray-600 text-xs">Os dados no ambiente de demonstração são reiniciados periodicamente. Nenhuma cobrança é efetuada.</p>
                </div>
            </div>
            <a href="{{ route('contacto') }}" class="px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-gray-900 font-bold text-xs transition shadow-sm whitespace-nowrap">
                Solicitar Demonstração para a Minha Escola &rarr;
            </a>
        </div>
    </main>

    <!-- ====== FOOTER ====== -->
    <footer class="bg-gray-900 text-gray-400 py-8 mt-12 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 text-center text-xs">
            &copy; {{ date('Y') }} {{ setting('school_name', config('app.name', 'ZamEdu')) }}. Todos os direitos reservados.
        </div>
    </footer>
</body>
</html>
