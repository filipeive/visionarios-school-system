<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registar Escola — Testar 15 Dias Grátis — {{ setting('school_name', config('app.name', 'ZamEdu')) }}</title>
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
                <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg bg-emerald-800 text-white text-sm font-semibold hover:bg-emerald-700 transition shadow-sm">
                    <i class="fas fa-right-to-bracket mr-2"></i>Já tenho Conta
                </a>
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
                <li><a href="{{ route('contacto') }}" class="hover:text-amber-400 transition flex items-center gap-2"><i class="fas fa-envelope"></i> Contactos & Proposta</a></li>
                <li><a href="{{ route('public.trial-register') }}" class="text-amber-400 font-bold border-b-2 border-amber-400 pb-1 flex items-center gap-2"><i class="fas fa-school"></i> Testar 15 Dias Grátis</a></li>
            </ul>
        </div>
    </nav>

    <!-- ====== HERO BANNER ====== -->
    <section class="bg-gradient-to-r from-emerald-900 via-emerald-800 to-emerald-900 text-white py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center max-w-2xl">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-400 text-gray-900 text-xs font-bold uppercase tracking-wider mb-3">
                <i class="fas fa-rocket"></i> Ativação Instantânea Sem Cartão de Crédito
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight mb-2">
                Crie a Sua Conta de Teste (15 Dias)
            </h1>
            <p class="text-emerald-100 text-sm leading-relaxed">
                Registe os dados da sua escola abaixo para aceder imediatamente ao painel completo de administração, secretaria e tesouraria.
            </p>
        </div>
    </section>

    <!-- ====== MAIN CONTENT ====== -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 flex-1">

        <div class="bg-white border border-gray-200 rounded-2xl p-8 shadow-sm">

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                    <div class="font-bold mb-1"><i class="fas fa-exclamation-circle me-2"></i>Por favor, verifique os erros abaixo:</div>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('public.trial-register.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <h3 class="text-lg font-bold text-emerald-900 border-b pb-2 mb-4 flex items-center gap-2">
                        <i class="fas fa-school text-emerald-700"></i> Dados da Instituição Escolar
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Nome Completo da Escola *</label>
                            <input type="text" name="school_name" value="{{ old('school_name') }}" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 outline-none text-sm" placeholder="Ex.: Escola Secundária Visionários de Maputo">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Província *</label>
                            <select name="province" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 outline-none text-sm">
                                <option value="Maputo Cidade" {{ old('province') == 'Maputo Cidade' ? 'selected' : '' }}>Maputo Cidade</option>
                                <option value="Maputo Província" {{ old('province') == 'Maputo Província' ? 'selected' : '' }}>Maputo Província</option>
                                <option value="Gaza" {{ old('province') == 'Gaza' ? 'selected' : '' }}>Gaza</option>
                                <option value="Inhambane" {{ old('province') == 'Inhambane' ? 'selected' : '' }}>Inhambane</option>
                                <option value="Sofala" {{ old('province') == 'Sofala' ? 'selected' : '' }}>Sofala</option>
                                <option value="Manica" {{ old('province') == 'Manica' ? 'selected' : '' }}>Manica</option>
                                <option value="Tete" {{ old('province') == 'Tete' ? 'selected' : '' }}>Tete</option>
                                <option value="Zambézia" {{ old('province') == 'Zambézia' ? 'selected' : '' }}>Zambézia</option>
                                <option value="Nampula" {{ old('province') == 'Nampula' ? 'selected' : '' }}>Nampula</option>
                                <option value="Cabo Delgado" {{ old('province') == 'Cabo Delgado' ? 'selected' : '' }}>Cabo Delgado</option>
                                <option value="Niassa" {{ old('province') == 'Niassa' ? 'selected' : '' }}>Niassa</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Distrito / Bairro</label>
                            <input type="text" name="district" value="{{ old('district') }}" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 outline-none text-sm" placeholder="Ex.: KaMpfumo / Central">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">N.º Estimado de Alunos</label>
                            <select name="estimated_students" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 outline-none text-sm">
                                <option value="150" {{ old('estimated_students') == '150' ? 'selected' : '' }}>Até 150 alunos</option>
                                <option value="400" {{ old('estimated_students') == '400' ? 'selected' : '' }}>151 a 400 alunos</option>
                                <option value="800" {{ old('estimated_students') == '800' ? 'selected' : '' }}>401 a 800 alunos</option>
                                <option value="1500" {{ old('estimated_students') == '1500' ? 'selected' : '' }}>Mais de 800 alunos</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-emerald-900 border-b pb-2 mb-4 flex items-center gap-2">
                        <i class="fas fa-user-shield text-emerald-700"></i> Conta do Administrador da Escola
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Nome do Responsável *</label>
                            <input type="text" name="director_name" value="{{ old('director_name') }}" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 outline-none text-sm" placeholder="Ex.: Prof. Filipe Domingos">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Telefone / WhatsApp *</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 outline-none text-sm" placeholder="+258 84 000 0000">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">E-mail do Administrador (Login) *</label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 outline-none text-sm" placeholder="direcao@suaescola.co.mz">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Palavra-passe *</label>
                            <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 outline-none text-sm" placeholder="Mínimo de 6 caracteres">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Confirmar Palavra-passe *</label>
                            <input type="password" name="password_confirmation" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 outline-none text-sm" placeholder="Repita a palavra-passe">
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-4 rounded-xl bg-emerald-800 hover:bg-emerald-700 text-white font-bold shadow-lg transition text-base flex items-center justify-center gap-2">
                        <i class="fas fa-rocket text-amber-400"></i> Criar Conta de Teste (15 Dias Grátis)
                    </button>
                    <p class="text-center text-xs text-gray-500 mt-3">
                        <i class="fas fa-lock me-1"></i> Sem obrigatoriedade de contratação. O período de teste expira automaticamente após 15 dias.
                    </p>
                </div>
            </form>
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
