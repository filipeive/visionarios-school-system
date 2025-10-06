<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Visionaries School') }} - Shaping tomorrow's leaders today</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Styles -->
    <style>
        .hero-image {
            background-image: linear-gradient(rgba(42, 63, 84, 0.7), rgba(42, 63, 84, 0.7)), 
                            url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80');
            background-size: cover;
            background-position: center;
        }
        
        .feature-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #1e40af, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>

<body class="font-sans antialiased text-gray-800">
    <!-- Header -->
    <header class="bg-white/95 backdrop-blur-sm shadow-lg fixed w-full z-50 transition-all duration-300">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl flex items-center justify-center text-white font-bold text-xl mr-3 shadow-lg">
                        VS
                    </div>
                    <h1 class="text-2xl font-bold gradient-text">{{ config('app.name', 'Visionaries School') }}</h1>
                </div>

                <!-- Navigation -->
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="#about" class="text-blue-800 font-medium hover:text-blue-600 transition duration-300">Sobre</a>
                    <a href="#features" class="text-gray-600 hover:text-blue-800 transition duration-300">Recursos</a>
                    <a href="#contact" class="text-gray-600 hover:text-blue-800 transition duration-300">Contato</a>
                    
                    @auth
                        <a href="{{ url('/dashboard') }}" 
                           class="bg-gradient-to-r from-blue-600 to-blue-800 text-white px-6 py-2 rounded-lg hover:from-blue-700 hover:to-blue-900 transition duration-300 shadow-lg">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="text-gray-600 hover:text-blue-800 transition duration-300">
                            Entrar
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" 
                               class="bg-gradient-to-r from-blue-600 to-blue-800 text-white px-6 py-2 rounded-lg hover:from-blue-700 hover:to-blue-900 transition duration-300 shadow-lg">
                                Registrar
                            </a>
                        @endif
                    @endauth
                </nav>

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="md:hidden text-blue-800 hover:text-blue-600 transition">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
            
            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden mt-4 py-4 border-t border-gray-200">
                <div class="flex flex-col space-y-4">
                    <a href="#about" class="text-blue-800 font-medium hover:text-blue-600 transition">Sobre</a>
                    <a href="#features" class="text-gray-600 hover:text-blue-800 transition">Recursos</a>
                    <a href="#contact" class="text-gray-600 hover:text-blue-800 transition">Contato</a>
                    
                    @auth
                        <a href="{{ url('/dashboard') }}" 
                           class="bg-gradient-to-r from-blue-600 to-blue-800 text-white px-6 py-2 rounded-lg text-center transition shadow-lg">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="text-gray-600 hover:text-blue-800 transition">
                            Entrar
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" 
                               class="bg-gradient-to-r from-blue-600 to-blue-800 text-white px-6 py-2 rounded-lg text-center transition shadow-lg">
                                Registrar
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-image min-h-screen flex items-center justify-center text-white relative overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0">
            <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-blue-500/10 rounded-full animate-float"></div>
            <div class="absolute top-3/4 right-1/4 w-48 h-48 bg-yellow-500/10 rounded-full animate-float" style="animation-delay: 2s;"></div>
        </div>
        
        <div class="container mx-auto px-4 text-center relative z-10">
            <div class="glass-effect rounded-3xl p-8 md:p-12 max-w-4xl mx-auto">
                <h1 class="text-4xl md:text-7xl font-bold mb-6 leading-tight">
                    Moldando os 
                    <span class="text-yellow-400">líderes</span> 
                    de amanhã
                </h1>
                <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto text-blue-100">
                    Uma abordagem moderna à educação com nosso sistema abrangente de gestão escolar
                </p>
                
                <div class="flex flex-col sm:flex-row justify-center gap-4 mt-8">
                    @auth
                        <a href="{{ url('/dashboard') }}" 
                           class="bg-white text-blue-800 px-8 py-4 rounded-xl font-semibold hover:bg-gray-100 transition duration-300 shadow-xl">
                            <i class="fas fa-tachometer-alt mr-2"></i>
                            Acessar Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="bg-white text-blue-800 px-8 py-4 rounded-xl font-semibold hover:bg-gray-100 transition duration-300 shadow-xl">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Entrar no Sistema
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" 
                               class="glass-effect border-2 border-white px-8 py-4 rounded-xl font-semibold hover:bg-white hover:text-blue-800 transition duration-300">
                                <i class="fas fa-user-plus mr-2"></i>
                                Criar Conta
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-gradient-to-br from-gray-50 to-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold gradient-text mb-6">Sobre Nossa Escola</h2>
                <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-yellow-500 mx-auto rounded-full"></div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="order-2 lg:order-1">
                    <img src="https://images.unsplash.com/photo-1588072432836-e10032774350?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1472&q=80" 
                         alt="Edifício da escola" 
                         class="rounded-2xl shadow-2xl w-full transform hover:scale-105 transition duration-500">
                </div>
                
                <div class="order-1 lg:order-2">
                    <h3 class="text-3xl font-bold text-blue-800 mb-6">Nossa Missão</h3>
                    <p class="text-gray-600 mb-8 text-lg leading-relaxed">
                        Na Visionaries School, estamos comprometidos em proporcionar uma experiência educacional transformadora que capacita os estudantes a se tornarem pensadores críticos, solucionadores criativos de problemas e líderes compassivos do amanhã.
                    </p>
                    
                    <h3 class="text-3xl font-bold text-blue-800 mb-6">Nossos Valores</h3>
                    <div class="space-y-4">
                        @php
                            $values = [
                                'Excelência no desenvolvimento acadêmico e pessoal',
                                'Inovação nas metodologias de ensino e aprendizagem',
                                'Integridade e comportamento ético em todas as nossas ações',
                                'Engajamento comunitário e cidadania global'
                            ];
                        @endphp
                        
                        @foreach($values as $value)
                            <div class="flex items-start group">
                                <span class="text-yellow-500 mr-4 group-hover:scale-110 transition duration-300">
                                    <i class="fas fa-check-circle text-xl"></i>
                                </span>
                                <span class="text-gray-600 text-lg">{{ $value }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gradient-to-br from-blue-50 to-indigo-100">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold gradient-text mb-6">Recursos do Sistema</h2>
                <p class="text-gray-600 max-w-3xl mx-auto text-lg">
                    Nosso sistema abrangente de gestão escolar oferece todas as ferramentas necessárias para uma administração eficiente e aprendizagem aprimorada
                </p>
                <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-yellow-500 mx-auto mt-6 rounded-full"></div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @php
                    $features = [
                        [
                            'icon' => 'fas fa-user-graduate',
                            'title' => 'Gestão de Estudantes',
                            'description' => 'Perfis abrangentes de estudantes, acompanhamento de presença e monitoramento de desempenho em um sistema centralizado.',
                            'color' => 'blue'
                        ],
                        [
                            'icon' => 'fas fa-chalkboard-teacher',
                            'title' => 'Portal do Professor',
                            'description' => 'Planejamento de aulas, livro de notas e ferramentas de comunicação desenvolvidas especificamente para educadores.',
                            'color' => 'green'
                        ],
                        [
                            'icon' => 'fas fa-laptop',
                            'title' => 'Aprendizagem Online',
                            'description' => 'Salas de aula virtuais integradas com submissão de tarefas, videoconferência e compartilhamento de recursos.',
                            'color' => 'yellow'
                        ],
                        [
                            'icon' => 'fas fa-chart-line',
                            'title' => 'Dashboard Analytics',
                            'description' => 'Visualização de dados em tempo real e relatórios para tomada de decisões informadas em todos os níveis.',
                            'color' => 'purple'
                        ],
                        [
                            'icon' => 'fas fa-money-bill-wave',
                            'title' => 'Gestão Financeira',
                            'description' => 'Cobrança automatizada de taxas, acompanhamento de despesas e relatórios financeiros para operações transparentes.',
                            'color' => 'red'
                        ],
                        [
                            'icon' => 'fas fa-users',
                            'title' => 'Engajamento dos Pais',
                            'description' => 'Canais de comunicação direta, atualizações de progresso e notificações de eventos para os pais.',
                            'color' => 'teal'
                        ]
                    ];
                @endphp
                
                @foreach($features as $feature)
                    <div class="feature-card bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl">
                        <div class="w-16 h-16 bg-{{ $feature['color'] }}-100 rounded-2xl flex items-center justify-center text-{{ $feature['color'] }}-800 mb-6 mx-auto">
                            <i class="{{ $feature['icon'] }} text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-blue-800 mb-4 text-center">{{ $feature['title'] }}</h3>
                        <p class="text-gray-600 text-center leading-relaxed">{{ $feature['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="bg-gradient-to-br from-blue-800 to-blue-900 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-blue-800 font-bold text-xl mr-4">
                            VS
                        </div>
                        <h3 class="text-2xl font-bold">{{ config('app.name', 'Visionaries School') }}</h3>
                    </div>
                    <p class="text-blue-200 mb-6 text-lg leading-relaxed">
                        Moldando os líderes de amanhã através da educação inovadora e desenvolvimento de caráter.
                    </p>
                    <div class="flex space-x-4">
                        @php
                            $socials = ['facebook-f', 'twitter', 'instagram', 'linkedin-in'];
                        @endphp
                        
                        @foreach($socials as $social)
                            <a href="#" class="w-10 h-10 bg-blue-700 rounded-lg flex items-center justify-center text-white hover:bg-yellow-500 transition duration-300">
                                <i class="fab fa-{{ $social }}"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
                
                <div>
                    <h4 class="text-xl font-bold mb-6">Links Rápidos</h4>
                    <ul class="space-y-3">
                        @php
                            $quickLinks = [
                                'Início' => '#',
                                'Sobre Nós' => '#about',
                                'Admissões' => '#',
                                'Calendário Acadêmico' => '#',
                                'Notícias da Escola' => '#'
                            ];
                        @endphp
                        
                        @foreach($quickLinks as $name => $link)
                            <li>
                                <a href="{{ $link }}" class="text-blue-200 hover:text-white transition duration-300 hover:translate-x-2 inline-block">
                                    {{ $name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-xl font-bold mb-6">Contato</h4>
                    <address class="not-italic text-blue-200 space-y-3">
                        <p class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-3 text-yellow-400"></i> 
                            123 Avenida da Educação, Cidade do Saber
                        </p>
                        <p class="flex items-center">
                            <i class="fas fa-phone-alt mr-3 text-yellow-400"></i> 
                            +258 84 123 4567
                        </p>
                        <p class="flex items-center">
                            <i class="fas fa-envelope mr-3 text-yellow-400"></i> 
                            info@visionariesschool.co.mz
                        </p>
                        <p class="flex items-center">
                            <i class="fas fa-clock mr-3 text-yellow-400"></i> 
                            Seg-Sex: 7:00 - 17:00
                        </p>
                    </address>
                </div>
            </div>
            
            <div class="border-t border-blue-700 mt-12 pt-8 text-center text-blue-200">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'Visionaries School') }}. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            
            mobileMenuBtn.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
            
            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
            
            // Header background on scroll
            window.addEventListener('scroll', function() {
                const header = document.querySelector('header');
                if (window.scrollY > 100) {
                    header.classList.add('bg-white');
                    header.classList.remove('bg-white/95');
                } else {
                    header.classList.add('bg-white/95');
                    header.classList.remove('bg-white');
                }
            });
        });
    </script>
</body>
</html>