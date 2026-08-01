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
        body { font-family: 'Inter', sans-serif; background: #F4F6FA; color: #0F172A; margin: 0; padding: 0; }
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
                <a href="{{ route('public.about') }}" class="hover:text-emerald-700 transition">Sobre o Sistema</a>
                <a href="{{ route('public.pre-enrollment') }}" class="hover:text-emerald-700 transition">Pré-Matrícula</a>
                <a href="{{ route('public.contact') }}" class="text-emerald-700 font-bold border-b-2 border-emerald-700 pb-1">Contacto</a>
            </nav>

            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="bg-emerald-700 hover:bg-emerald-800 text-white px-5 py-2.5 rounded-full text-xs font-bold transition shadow-xs">
                    <i class="fas fa-right-to-bracket me-1.5"></i> Entrar
                </a>
            </div>
        </div>
    </header>

    <!-- Content -->
    <section class="py-12 px-4 max-w-6xl mx-auto">
        <div class="text-center max-w-2xl mx-auto mb-10">
            <span class="text-xs uppercase font-extrabold tracking-wider text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">
                Fale com a nossa equipa
            </span>
            <h1 class="text-3xl font-extrabold text-slate-900 mt-2 heading-font">
                Contacto & Solicitação de Demonstração
            </h1>
            <p class="text-slate-600 text-sm mt-1">
                Estamos disponíveis para responder a dúvidas operacionais, agendar uma demonstração orientada ou preparar a proposta comercial da sua escola.
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Form -->
            <div class="md:col-span-2 bg-white p-8 rounded-3xl border border-slate-200 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900 mb-4 heading-font">Envie a sua Mensagem</h3>
                
                <form action="https://wa.me/258840000000" method="GET" target="_blank" class="space-y-4">
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Seu Nome Completo</label>
                            <input type="text" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-semibold focus:ring-2 focus:ring-emerald-600 focus:outline-none" placeholder="Ex.: Dr. António Silva">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Cargo na Escola</label>
                            <input type="text" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-semibold focus:ring-2 focus:ring-emerald-600 focus:outline-none" placeholder="Ex.: Director(a) / Tesoureiro(a)">
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nome da Escola / Instituição</label>
                            <input type="text" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-semibold focus:ring-2 focus:ring-emerald-600 focus:outline-none" placeholder="Ex.: Colégio Esperança de Maputo">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Telefone / WhatsApp</label>
                            <input type="tel" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-semibold focus:ring-2 focus:ring-emerald-600 focus:outline-none" placeholder="+258 84 000 0000">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Motivo do Contacto</label>
                        <select class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-semibold focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                            <option>Solicitar Demonstração do Sistema</option>
                            <option>Solicitar Proposta Comercial em PDF</option>
                            <option>Suporte Técnico a Utilizadores</option>
                            <option>Outras Informações</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Mensagem ou Observação</label>
                        <textarea rows="4" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-semibold focus:ring-2 focus:ring-emerald-600 focus:outline-none" placeholder="Indique o número aproximado de alunos ou outras informações relevantes..."></textarea>
                    </div>

                    <button type="submit" class="w-full bg-emerald-700 hover:bg-emerald-800 text-white font-extrabold py-3 rounded-xl text-sm shadow-md transition">
                        <i class="fab fa-whatsapp me-2"></i> Enviar por WhatsApp / Mensagem
                    </button>
                </form>
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-6">
                <div class="bg-gradient-to-br from-emerald-900 to-teal-900 text-white p-6 rounded-3xl shadow-md">
                    <h4 class="text-base font-bold text-amber-300 mb-4 heading-font">Contactos Diretos</h4>
                    <div class="space-y-4 text-sm">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-phone text-emerald-300"></i>
                            <span>{{ setting('phone', '+258 84 000 0000') }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-envelope text-emerald-300"></i>
                            <span>{{ setting('email', 'contacto@zamedu.co.mz') }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-location-dot text-emerald-300"></i>
                            <span>Maputo, Moçambique</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs space-y-3">
                    <h4 class="text-sm font-bold text-slate-900 heading-font">Horário de Atendimento</h4>
                    <p class="text-xs text-slate-600">Segunda a Sexta-feira: 07:30 às 17:00</p>
                    <p class="text-xs text-slate-600">Sábados: 08:00 às 12:00</p>
                    <hr class="border-slate-100 my-2">
                    <span class="text-[11px] text-emerald-700 font-bold uppercase block">Suporte Local em Moçambique</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-white py-8 px-4 border-t border-slate-800 mt-12">
        <div class="max-w-6xl mx-auto text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} {{ setting('school_name', config('app.name', 'ZamEdu')) }}. Todos os direitos reservados.
        </div>
    </footer>

</body>
</html>
