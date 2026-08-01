<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre o {{ setting('school_name', config('app.name', 'ZamEdu')) }} — Sistema de Gestão Escolar</title>
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
                <li><a href="{{ route('sobre') }}" class="text-amber-400 font-bold border-b-2 border-amber-400 pb-1 flex items-center gap-2"><i class="fas fa-info-circle"></i> Sobre o Sistema</a></li>
                <li><a href="{{ route('demo.access') }}" class="hover:text-amber-400 transition flex items-center gap-2"><i class="fas fa-bolt text-amber-400"></i> 1-Click Demo</a></li>
                <li><a href="{{ route('contacto') }}" class="hover:text-amber-400 transition flex items-center gap-2"><i class="fas fa-envelope"></i> Contactos & Proposta</a></li>
                <li><a href="{{ route('public.pre-enrollment') }}" class="hover:text-amber-400 transition flex items-center gap-2"><i class="fas fa-file-pen"></i> Pré-Matrícula</a></li>
            </ul>
        </div>
    </nav>

    <!-- ====== HERO BANNER ====== -->
    <section class="bg-gradient-to-r from-emerald-900 via-emerald-800 to-emerald-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center max-w-3xl">
            <span class="inline-block px-3 py-1 rounded-full bg-amber-400 text-gray-900 text-xs font-bold uppercase tracking-wider mb-4">
                Criado para a Realidade de Moçambique
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight mb-4">
                Tecnologia Escolar que Conecta a Secretaria, Tesouraria e Sala de Aula
            </h1>
            <p class="text-emerald-100 text-lg leading-relaxed">
                O {{ setting('school_name', config('app.name', 'ZamEdu')) }} foi desenvolvido para simplificar a gestão pedagógica e financeira das escolas moçambicanas, garantindo o cumprimento integral do Diploma Ministerial nº 59/2015.
            </p>
        </div>
    </section>

    <!-- ====== MAIN CONTENT ====== -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 flex-1">

        <!-- 6 Módulos do Ecossistema -->
        <div class="mb-16">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="text-emerald-800 font-bold text-xs uppercase tracking-wider">Um só Ecossistema</span>
                <h2 class="text-3xl font-extrabold text-emerald-900 mt-1">A Informação Certa para Cada Responsável</h2>
                <p class="text-gray-600 text-sm mt-2">Com controlo granular de permissões, cada perfil acede exatamente ao que precisa.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-800 flex items-center justify-center text-xl font-bold mb-4">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <h3 class="text-lg font-bold text-emerald-900 mb-2">1. Secretaria Digital</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">Cadastro completo de estudantes, encarregados, pré-matrículas online e emissão de documentos oficiais.</p>
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-800 flex items-center justify-center text-xl font-bold mb-4">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <h3 class="text-lg font-bold text-emerald-900 mb-2">2. Tesouraria & M-Pesa</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">Gestão transparente de mensalidades em Meticais, multas por atraso, emissão de recibos e conciliação.</p>
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-800 flex items-center justify-center text-xl font-bold mb-4">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3 class="text-lg font-bold text-emerald-900 mb-2">3. Académico & Pautas</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">Lançamento de avaliações ACS, ACP, ACF, aprovações pedagógicas e geração automática de pautas.</p>
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-800 flex items-center justify-center text-xl font-bold mb-4">
                        <i class="fas fa-id-badge"></i>
                    </div>
                    <h3 class="text-lg font-bold text-emerald-900 mb-2">4. Portaria Digital</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">Controlo de acessos físicos com validação instantânea do estado da matrícula e histórico diário.</p>
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-800 flex items-center justify-center text-xl font-bold mb-4">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="text-lg font-bold text-emerald-900 mb-2">5. Painel da Direção</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">Relatórios estratégicos com gráficos Chart.js cobrindo assiduidade, arrecadação e demografia.</p>
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-800 flex items-center justify-center text-xl font-bold mb-4">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3 class="text-lg font-bold text-emerald-900 mb-2">6. Portal dos Pais</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">Acompanhamento em tempo real pelas famílias das notas, assiduidade, avisos e propinas.</p>
                </div>
            </div>
        </div>

        <!-- Metodologia de Implementação -->
        <div class="bg-white border border-gray-200 rounded-2xl p-8 shadow-sm mb-16">
            <div class="text-center max-w-2xl mx-auto mb-10">
                <span class="text-emerald-800 font-bold text-xs uppercase tracking-wider">Passo a Passo</span>
                <h2 class="text-2xl font-extrabold text-emerald-900 mt-1">Metodologia de Implementação Sem Complicação</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-4 border-l-4 border-emerald-700 bg-gray-50 rounded-r-xl">
                    <span class="text-xs font-bold text-emerald-700">PASSO 01</span>
                    <h4 class="font-bold text-gray-900 text-base mt-1">Diagnóstico Operacional</h4>
                    <p class="text-xs text-gray-600 mt-1">Levantamento das turmas, disciplinas e estrutura da escola.</p>
                </div>
                <div class="p-4 border-l-4 border-emerald-700 bg-gray-50 rounded-r-xl">
                    <span class="text-xs font-bold text-emerald-700">PASSO 02</span>
                    <h4 class="font-bold text-gray-900 text-base mt-1">Configuração do Sistema</h4>
                    <p class="text-xs text-gray-600 mt-1">Parametrização das regras financeiras, turmas e utilizadores.</p>
                </div>
                <div class="p-4 border-l-4 border-emerald-700 bg-gray-50 rounded-r-xl">
                    <span class="text-xs font-bold text-emerald-700">PASSO 03</span>
                    <h4 class="font-bold text-gray-900 text-base mt-1">Carga Inicial de Dados</h4>
                    <p class="text-xs text-gray-600 mt-1">Importação orientada da lista de alunos e encarregados.</p>
                </div>
                <div class="p-4 border-l-4 border-amber-500 bg-gray-50 rounded-r-xl">
                    <span class="text-xs font-bold text-amber-600">PASSO 04</span>
                    <h4 class="font-bold text-gray-900 text-base mt-1">Capacitação das Equipas</h4>
                    <p class="text-xs text-gray-600 mt-1">Formação prática para secretários, tesoureiros e professores.</p>
                </div>
                <div class="p-4 border-l-4 border-amber-500 bg-gray-50 rounded-r-xl">
                    <span class="text-xs font-bold text-amber-600">PASSO 05</span>
                    <h4 class="font-bold text-gray-900 text-base mt-1">Entrada em Funcionamento</h4>
                    <p class="text-xs text-gray-600 mt-1">Lançamento oficial com acompanhamento técnico presencial/remoto.</p>
                </div>
                <div class="p-4 border-l-4 border-amber-500 bg-gray-50 rounded-r-xl">
                    <span class="text-xs font-bold text-amber-600">PASSO 06</span>
                    <h4 class="font-bold text-gray-900 text-base mt-1">Suporte Técnico Contínuo</h4>
                    <p class="text-xs text-gray-600 mt-1">Acompanhamento contínuo e atualizações de produto incluídas.</p>
                </div>
            </div>
        </div>

        <!-- Call to action -->
        <div class="text-center bg-emerald-900 text-white rounded-2xl p-10 shadow-lg">
            <h3 class="text-2xl font-bold mb-3">Pronto para Modernizar a Gestão da Sua Escola?</h3>
            <p class="text-emerald-100 text-sm max-w-xl mx-auto mb-6">Solicite uma proposta personalizada para o número de alunos da sua instituição.</p>
            <div class="flex items-center justify-center gap-4">
                <a href="{{ route('demo.access') }}" class="px-6 py-3 rounded-xl bg-amber-400 hover:bg-amber-300 text-gray-900 font-bold text-sm transition shadow">
                    <i class="fas fa-bolt mr-2"></i>Testar 1-Click Demo
                </a>
                <a href="{{ route('contacto') }}" class="px-6 py-3 rounded-xl bg-white hover:bg-gray-100 text-emerald-900 font-bold text-sm transition shadow">
                    <i class="fas fa-envelope mr-2"></i>Falar com Consultor
                </a>
            </div>
        </div>
    </main>

    <!-- ====== FOOTER ====== -->
    <footer class="bg-gray-900 text-gray-400 py-8 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 text-center text-xs">
            &copy; {{ date('Y') }} {{ setting('school_name', config('app.name', 'ZamEdu')) }}. Todos os direitos reservados.
        </div>
    </footer>
</body>
</html>
