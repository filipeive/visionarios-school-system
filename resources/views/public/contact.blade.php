<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto & Demonstração — {{ setting('school_name', config('app.name', 'ZamEdu')) }}</title>
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
                <li><a href="{{ route('demo.access') }}" class="hover:text-amber-400 transition flex items-center gap-2"><i class="fas fa-bolt text-amber-400"></i> 1-Click Demo</a></li>
                <li><a href="{{ route('contacto') }}" class="text-amber-400 font-bold border-b-2 border-amber-400 pb-1 flex items-center gap-2"><i class="fas fa-envelope"></i> Contactos & Proposta</a></li>
                <li><a href="{{ route('public.pre-enrollment') }}" class="hover:text-amber-400 transition flex items-center gap-2"><i class="fas fa-file-pen"></i> Pré-Matrícula</a></li>
            </ul>
        </div>
    </nav>

    <!-- ====== HERO BANNER ====== -->
    <section class="bg-gradient-to-r from-emerald-900 via-emerald-800 to-emerald-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center max-w-2xl">
            <span class="inline-block px-3 py-1 rounded-full bg-amber-400 text-gray-900 text-xs font-bold uppercase tracking-wider mb-3">
                Atendimento Especializado
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight mb-3">
                Fale Connosco & Solicite Proposta
            </h1>
            <p class="text-emerald-100 text-base leading-relaxed">
                Estamos prontos para esclarecer as suas dúvidas, agendar uma demonstração guiada ou enviar uma proposta adaptada à sua escola.
            </p>
        </div>
    </section>

    <!-- ====== MAIN CONTENT ====== -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 flex-1">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Contact Info -->
            <div class="space-y-6">
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-800 flex items-center justify-center text-xl font-bold mb-4">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3 class="text-lg font-bold text-emerald-900 mb-2">Apoio ao Cliente</h3>
                    <p class="text-gray-600 text-sm mb-4">Atendimento presencial e remoto de Segunda a Sexta, das 08h00 às 17h00.</p>
                    <div class="space-y-3 text-sm text-gray-700">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-phone text-emerald-700"></i>
                            <span>+258 84 000 0000 / +258 82 000 0000</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fab fa-whatsapp text-emerald-700 text-base"></i>
                            <span>+258 84 000 0000 (WhatsApp)</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-envelope text-emerald-700"></i>
                            <span>comercial@visionarios.co.mz</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold mb-4">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3 class="text-lg font-bold text-emerald-900 mb-2">Escritório Central</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Av. 24 de Julho, Nº 1200, 4º Andar<br>
                        Maputo — Moçambique
                    </p>
                </div>
            </div>

            <!-- Form -->
            <div class="lg:col-span-2 bg-white border border-gray-200 rounded-2xl p-8 shadow-sm">
                <h3 class="text-2xl font-bold text-emerald-900 mb-2">Solicitar Demonstração ou Proposta</h3>
                <p class="text-gray-600 text-sm mb-6">Preencha os seus dados e a nossa equipa entrará em contacto em menos de 24 horas úteis.</p>

                <form action="#" method="POST" class="space-y-5" onsubmit="event.preventDefault(); alert('Mensagem enviada com sucesso! Entraremos em contacto brevemente.');">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Nome do Responsável *</label>
                            <input type="text" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 outline-none text-sm" placeholder="Ex.: Prof. Patrícia Chissano">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Nome da Escola *</label>
                            <input type="text" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 outline-none text-sm" placeholder="Ex.: Colégio Visionários">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Telefone / WhatsApp *</label>
                            <input type="tel" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 outline-none text-sm" placeholder="+258 8X XXX XXXX">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">E-mail Institucional *</label>
                            <input type="email" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 outline-none text-sm" placeholder="direcao@escola.co.mz">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">N.º Estimado de Alunos</label>
                            <select class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 outline-none text-sm">
                                <option value="150">Até 150 alunos</option>
                                <option value="400">151 a 400 alunos</option>
                                <option value="800">401 a 800 alunos</option>
                                <option value="1500">Mais de 800 alunos</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Cargo / Perfil</label>
                            <select class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 outline-none text-sm">
                                <option value="director">Diretor / Proprietário</option>
                                <option value="secretario">Secretário Escolar</option>
                                <option value="financeiro">Responsável Financeiro</option>
                                <option value="professor">Professor / Coordenador</option>
                                <option value="outros">Outro</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Mensagem / Necessidades Específicas</label>
                        <textarea rows="4" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 outline-none text-sm" placeholder="Descreva brevemente como a sua escola funciona atualmente ou o que pretende automatizar..."></textarea>
                    </div>

                    <button type="submit" class="w-full py-4 rounded-xl bg-emerald-800 hover:bg-emerald-700 text-white font-bold shadow-lg transition text-base flex items-center justify-center gap-2">
                        <i class="fas fa-paper-plane text-amber-400"></i> Enviar Pedido de Proposta
                    </button>
                </form>
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
