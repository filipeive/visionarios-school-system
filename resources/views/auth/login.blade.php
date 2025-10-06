{{-- <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Entrar - {{ config('app.name', 'Visionaries School') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Styles -->
    <style>
        .bg-pattern {
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(59, 130, 246, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(139, 92, 246, 0.1) 0%, transparent 50%);
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
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
            50% { transform: translateY(-10px); }
        }
        
        .input-group {
            position: relative;
        }
        
        .input-group input:focus + label,
        .input-group input:not(:placeholder-shown) + label {
            transform: translateY(-1.5rem) scale(0.85);
            color: #1e40af;
        }
        
        .floating-label {
            position: absolute;
            left: 3rem;
            top: 50%;
            transform: translateY(-50%);
            transition: all 0.3s ease;
            pointer-events: none;
            color: #6b7280;
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 bg-pattern">
    <!-- Header -->
    <header class="bg-white/80 backdrop-blur-sm shadow-sm">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <a href="{{ route('welcome') }}" class="flex items-center group">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl flex items-center justify-center text-white font-bold text-lg mr-3 shadow-lg group-hover:scale-105 transition duration-300">
                        VS
                    </div>
                    <h1 class="text-xl font-bold gradient-text">{{ config('app.name', 'Visionaries School') }}</h1>
                </a>
                
                <!-- Back to Home -->
                <a href="{{ route('welcome') }}" 
                   class="text-gray-600 hover:text-blue-800 transition duration-300 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar ao Início
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="flex items-center justify-center min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <!-- Background Elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-blue-200/20 rounded-full animate-float"></div>
            <div class="absolute bottom-1/4 right-1/4 w-48 h-48 bg-purple-200/20 rounded-full animate-float" style="animation-delay: 3s;"></div>
        </div>
        
        <div class="max-w-md w-full space-y-8 relative z-10">
            <!-- Header -->
            <div class="text-center">
                <div class="mx-auto w-20 h-20 bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl flex items-center justify-center text-white mb-6 shadow-2xl">
                    <i class="fas fa-graduation-cap text-3xl"></i>
                </div>
                <h2 class="text-4xl font-bold gradient-text mb-2">
                    Bem-vindo de volta!
                </h2>
                <p class="text-gray-600 text-lg">
                    Entre na sua conta para continuar
                </p>
            </div>

            <!-- Login Form -->
            <div class="glass-effect rounded-2xl shadow-2xl">
                <div class="px-8 py-10">
                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <!-- Email Field -->
                        <div class="input-group">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input id="email" 
                                       name="email" 
                                       type="email" 
                                       autocomplete="email" 
                                       required 
                                       placeholder=" "
                                       value="{{ old('email') }}"
                                       class="block w-full pl-12 pr-4 py-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300 bg-white/70 @error('email') border-red-500 @enderror">
                                <label for="email" class="floating-label">Endereço de Email</label>
                            </div>
                            @error('email')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div class="input-group">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input id="password" 
                                       name="password" 
                                       type="password" 
                                       autocomplete="current-password" 
                                       required 
                                       placeholder=" "
                                       class="block w-full pl-12 pr-12 py-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300 bg-white/70 @error('password') border-red-500 @enderror">
                                <label for="password" class="floating-label">Senha</label>
                                <button type="button" 
                                        onclick="togglePassword()" 
                                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600">
                                    <i id="password-toggle" class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember" 
                                       name="remember" 
                                       type="checkbox" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition duration-300">
                                <label for="remember" class="ml-3 text-sm text-gray-700">
                                    Lembrar-me
                                </label>
                            </div>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" 
                                   class="text-sm text-blue-600 hover:text-blue-800 transition duration-300 font-medium">
                                    Esqueceu a senha?
                                </a>
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button type="submit" 
                                    class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-lg font-semibold rounded-xl text-white bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <span class="absolute left-0 inset-y-0 flex items-center pl-4">
                                    <i class="fas fa-sign-in-alt text-blue-300 group-hover:text-blue-200 transition duration-300"></i>
                                </span>
                                Entrar
                            </button>
                        </div>

                        <!-- Divider -->
                        <div class="relative my-6">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-4 bg-white text-gray-500">ou</span>
                            </div>
                        </div>

                        <!-- Register Link -->
                        <div class="text-center">
                            <p class="text-gray-600">
                                Não tem uma conta?
                                <a href="{{ route('register') }}" 
                                   class="font-semibold text-blue-600 hover:text-blue-800 transition duration-300 ml-1">
                                    Criar conta gratuita
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="text-center">
                <p class="text-sm text-gray-500">
                    Ao entrar, você concorda com nossos
                    <a href="#" class="text-blue-600 hover:text-blue-800 transition duration-300">Termos de Uso</a>
                    e
                    <a href="#" class="text-blue-600 hover:text-blue-800 transition duration-300">Política de Privacidade</a>
                </p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-800">{{ session('status') }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordToggle = document.getElementById('password-toggle');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordToggle.classList.remove('fa-eye');
                passwordToggle.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordToggle.classList.remove('fa-eye-slash');
                passwordToggle.classList.add('fa-eye');
            }
        }

        // Auto-focus on first input
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('email').focus();
        });
    </script>
</body>
</html> --}}
{{-- resources/views/auth/login.blade.php --}}
<x-guest-layout>
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-logo">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h2 class="mb-0">VISIONÁRIOS</h2>
            <p class="mb-0">Sistema de Gestão Escolar</p>
        </div>

        <div class="auth-body">
            <h4 class="text-center mb-4">Entrar no Sistema</h4>

            @if (session('status'))
                <div class="alert alert-success mb-4">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope me-2"></i>Email
                    </label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                           name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock me-2"></i>Senha
                    </label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                           name="password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Lembrar-me</label>
                </div>

                <button type="submit" class="btn btn-primary-auth">
                    <i class="fas fa-sign-in-alt me-2"></i>Entrar
                </button>

                @if (Route::has('password.request'))
                    <div class="text-center mt-3">
                        <a href="{{ route('password.request') }}" class="text-decoration-none">
                            Esqueceu a senha?
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="text-center mt-4 text-white">
        <small>© {{ date('Y') }} Escola dos Visionários - Quelimane, Zambézia</small>
    </div>
</x-guest-layout>