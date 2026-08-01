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
            --green-800: #047857;
            --green-700: #059669;
            --green-100: #D1FAE5;
            --amber-500: #F59E0B;
            --gray-900: #0F172A;
            --gray-700: #334155;
            --gray-100: #F8FAFC;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #F4F6FA;
            color: var(--gray-900);
            margin: 0;
            padding: 0;
        }

        .heading-font { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body>

    <!-- Header Navigation -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <a href="{{ route('welcome') }}" class="flex items-center gap-3 decoration-none">
                <div class="w-10 h-10 bg-emerald-700 text-white rounded-xl flex items-center justify-center font-bold text-xl shadow-sm">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-slate-900 leading-tight heading-font mb-0">{{ setting('school_name', config('app.name', 'ZamEdu')) }}</h1>
                    <span class="text-xs text-slate-500 font-medium">Gestão Escolar Integrada</span>
                </div>
            </a>

            <nav class="hidden md:flex items-center gap-6 text-sm font-semibold text-slate-600">
                <a href="{{ route('welcome') }}" class="hover:text-emerald-700 transition">Início</a>
                <a href="{{ route('public.about') }}" class="text-emerald-700 font-bold border-b-2 border-emerald-700 pb-1">Sobre o Sistema</a>
                <a href="{{ route('public.pre-enrollment') }}" class="hover:text-emerald-700 transition">Pré-Matrícula</a>
                <a href="{{ route('public.contact') }}" class="hover:text-emerald-700 transition">Contacto</a>
            </nav>

            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="bg-emerald-700 hover:bg-emerald-800 text-white px-5 py-2.5 rounded-full text-xs font-bold transition shadow-xs">
                    <i class="fas fa-right-to-bracket me-1.5"></i> Entrar
                </a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-emerald-900 via-emerald-800 to-teal-900 text-white py-20 px-4">
        <div class="max-w-5xl mx-auto text-center space-y-6">
            <span class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md px-4 py-1.5 rounded-full text-xs font-bold text-amber-300 border border-white/20">
                <i class="fas fa-award"></i> Criado para a Realidade de Moçambique
            </span>
            <h1 class="text-3xl sm:text-5xl font-black heading-font text-white leading-tight">
                Tecnologia Escolar que Conecta a Secretaria, Tesouraria e Sala de Aula
            </h1>
            <p class="text-emerald-100 text-base sm:text-lg max-w-3xl mx-auto leading-relaxed">
                O {{ setting('school_name', 'ZamEdu') }} foi desenvolvido para simplificar a gestão pedagógica e financeira das escolas moçambicanas, garantindo o cumprimento integral do Diploma Ministerial nº 59/2015 e otimizando a operação diária da instituição.
            </p>
            <div class="pt-4 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('public.pre-enrollment') }}" class="bg-amber-500 hover:bg-amber-600 text-slate-900 px-7 py-3 rounded-full text-sm font-extrabold shadow-lg transition">
                    <i class="fas fa-user-plus me-1.5"></i> Fazer Pré-Matrícula Online
                </a>
                <a href="{{ route('login') }}" class="bg-white/15 hover:bg-white/25 text-white px-6 py-3 rounded-full text-sm font-bold backdrop-blur-md border border-white/30 transition">
                    <i class="fas fa-desktop me-1.5"></i> Aceder ao Sistema
                </a>
            </div>
        </div>
    </section>

    <!-- Ecossistema Integrado ZAMEDU -->
    <section class="py-16 px-4 max-w-6xl mx-auto">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="text-xs uppercase font-extrabold tracking-wider text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">
                Um só ecossistema
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2 heading-font">
                A Informação Certa para Cada Responsável
            </h2>
            <p class="text-slate-600 text-sm mt-1">
                Com controlo granular de permissões, cada perfil de utilizador acede exatamente ao que precisa.
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs hover:shadow-md transition">
                <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl mb-4">
                    <i class="fas fa-file-signature"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 heading-font mb-2">1. Secretaria Digital</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Controlo total sobre cadastro de estudantes, encarregados de educação, emissão de documentos oficiais e transferências.
                </p>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs hover:shadow-md transition">
                <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center text-xl mb-4">
                    <i class="fas fa-wallet"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 heading-font mb-2">2. Tesouraria & M-Pesa</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Gestão transparente de mensalidades, descontos, emissão de recibos, conciliação e integração com pagamentos móveis em Meticais.
                </p>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs hover:shadow-md transition">
                <div class="w-12 h-12 rounded-2xl bg-purple-100 text-purple-700 flex items-center justify-center text-xl mb-4">
                    <i class="fas fa-award"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 heading-font mb-2">3. Académico & Pautas</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Lançamento de avaliações ACS, ACP, ACF, aprovações pedagógicas e geração automática de pautas e boletins de notas.
                </p>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs hover:shadow-md transition">
                <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center text-xl mb-4">
                    <i class="fas fa-id-card-clip"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 heading-font mb-2">4. Portaria Digital</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Controlo de acessos físicos com validação instantânea do estado de matrícula e registo de entrada/saída de alunos.
                </p>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs hover:shadow-md transition">
                <div class="w-12 h-12 rounded-2xl bg-teal-100 text-teal-700 flex items-center justify-center text-xl mb-4">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 heading-font mb-2">5. Painel da Direção</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Relatórios estratégicos com gráficos interativos Chart.js cobrindo assiduidade, arrecadação mensal e demografia escolar.
                </p>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs hover:shadow-md transition">
                <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-700 flex items-center justify-center text-xl mb-4">
                    <i class="fas fa-users-viewfinder"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 heading-font mb-2">6. Portal dos Pais</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Acompanhamento em tempo real pelas famílias das notas, assiduidade, avisos escolares e situação financeira dos educandos.
                </p>
            </div>
        </div>
    </section>

    <!-- 4 Desafios Reais Resolvidos -->
    <section class="bg-slate-900 text-white py-16 px-4">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-12">
                <span class="text-xs uppercase font-extrabold tracking-wider text-amber-400 bg-white/10 px-3 py-1 rounded-full border border-white/20">
                    Soluções Práticas
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-white mt-2 heading-font">
                    Desafios Reais das Escolas que o ZAMEDU Resolve
                </h2>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-white/5 border border-white/10 p-6 rounded-3xl">
                    <div class="flex items-start gap-4">
                        <span class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center font-bold text-base shrink-0">01</span>
                        <div>
                            <h4 class="text-base font-bold text-white mb-1">Informação Dispersa em Papéis</h4>
                            <p class="text-slate-400 text-sm leading-relaxed">Centralizamos o percurso do aluno na Ficha 360º, eliminando ficheiros perdidos ou cadernos rasurados.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white/5 border border-white/10 p-6 rounded-3xl">
                    <div class="flex items-start gap-4">
                        <span class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center font-bold text-base shrink-0">02</span>
                        <div>
                            <h4 class="text-base font-bold text-white mb-1">Cálculos Manuais de Pautas</h4>
                            <p class="text-slate-400 text-sm leading-relaxed">Automação completa dos cálculos de MACS, MT e MFD conforme o programa oficial do MINEDH em Moçambique.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white/5 border border-white/10 p-6 rounded-3xl">
                    <div class="flex items-start gap-4">
                        <span class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center font-bold text-base shrink-0">03</span>
                        <div>
                            <h4 class="text-base font-bold text-white mb-1">Filas e Inadimplência</h4>
                            <p class="text-slate-400 text-sm leading-relaxed">Emissão imediata de recibos, relatórios automáticos de alunos devedores e pagamento facilitado.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white/5 border border-white/10 p-6 rounded-3xl">
                    <div class="flex items-start gap-4">
                        <span class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center font-bold text-base shrink-0">04</span>
                        <div>
                            <h4 class="text-base font-bold text-white mb-1">Sistemas Estrangeiros Descontextualizados</h4>
                            <p class="text-slate-400 text-sm leading-relaxed">Desenvolvido em Moçambique, com suporte local direto, linguagem clara e aderência às regras nacionais.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Metodologia de Implementação -->
    <section class="py-16 px-4 max-w-6xl mx-auto">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 heading-font">
                Metodologia de Implementação Sem Complicação
            </h2>
            <p class="text-slate-600 text-sm mt-1">Acompanhamos a instituição escolar em todas as fases da migração digital.</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white p-5 rounded-2xl border border-slate-200">
                <span class="text-xs font-bold text-emerald-700 uppercase">Passo 01</span>
                <h4 class="font-bold text-slate-900 mt-1 mb-1">Diagnóstico Operacional</h4>
                <p class="text-xs text-slate-600">Levantamento das turmas, disciplinas e estrutura da escola.</p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200">
                <span class="text-xs font-bold text-emerald-700 uppercase">Passo 02</span>
                <h4 class="font-bold text-slate-900 mt-1 mb-1">Configuração do Sistema</h4>
                <p class="text-xs text-slate-600">Parametrização das regras financeiras, turmas e permissões de utilizadores.</p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200">
                <span class="text-xs font-bold text-emerald-700 uppercase">Passo 03</span>
                <h4 class="font-bold text-slate-900 mt-1 mb-1">Carga Inicial de Dados</h4>
                <p class="text-xs text-slate-600">Importação orientada da lista de alunos e encarregados.</p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200">
                <span class="text-xs font-bold text-emerald-700 uppercase">Passo 04</span>
                <h4 class="font-bold text-slate-900 mt-1 mb-1">Capacitação das Equipas</h4>
                <p class="text-xs text-slate-600">Formação prática para secretários, tesoureiros e professores.</p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200">
                <span class="text-xs font-bold text-emerald-700 uppercase">Passo 05</span>
                <h4 class="font-bold text-slate-900 mt-1 mb-1">Entrada em Funcionamento</h4>
                <p class="text-xs text-slate-600">Lançamento oficial com acompanhamento técnico presencial/remoto.</p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200">
                <span class="text-xs font-bold text-emerald-700 uppercase">Passo 06</span>
                <h4 class="font-bold text-slate-900 mt-1 mb-1">Suporte Técnico Contínuo</h4>
                <p class="text-xs text-slate-600">Acompanhamento contínuo e atualizações de produto incluídas.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-white py-10 px-4 border-t border-slate-800">
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-slate-400">
            <div>
                <strong class="text-white font-bold">{{ setting('school_name', config('app.name', 'ZamEdu')) }}</strong> — Sistema Integrado de Gestão Escolar &copy; {{ date('Y') }}.
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('public.pre-enrollment') }}" class="hover:text-white">Pré-Matrícula</a>
                <a href="{{ route('public.payment-check') }}" class="hover:text-white">Verificar Pagamento</a>
                <a href="{{ route('login') }}" class="hover:text-white">Área Restrita</a>
            </div>
        </div>
    </footer>

</body>
</html>
