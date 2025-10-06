<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registrar - {{ config('app.name', 'Visionaries School') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
                <!-- Back to Login -->
                <a href="{{ route('login') }}" 
                   class="text-gray-600 hover:text-blue-800 transition duration-300 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar ao Login
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
                    <i class="fas fa-user-plus text-3xl"></i>
                </div>
                <h2 class="text-4xl font-bold gradient-text mb-2">
                    Criar Conta
                </h2>
                <p class="text-gray-600 text-lg">
                    Preencha os campos para se registrar
                </p>
            </div>

            <!-- Register Form -->
            <div class="glass-effect rounded-2xl shadow-2xl">
                <div class="px-8 py-10">
                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-red-400"></i>
                                </div>
                                <div class="ml-3">
                                    <ul class="text-sm text-red-800 mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('register') }}" class="space-y-6">
                        @csrf

                        <!-- Name Field -->
                        <div class="input-group">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <input id="name" 
                                       name="name" 
                                       type="text" 
                                       required 
                                       placeholder=" "
                                       value="{{ old('name') }}"
                                       class="block w-full pl-12 pr-4 py-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300 bg-white/70 @error('name') border-red-500 @enderror">
                                <label for="name" class="floating-label">Nome</label>
                            </div>
                        </div>

                        <!-- Email Field -->
                        <div class="input-group">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input id="email" 
                                       name="email" 
                                       type="email" 
                                       required 
                                       placeholder=" "
                                       value="{{ old('email') }}"
                                       class="block w-full pl-12 pr-4 py-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300 bg-white/70 @error('email') border-red-500 @enderror">
                                <label for="email" class="floating-label">Email</label>
                            </div>
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
                        </div>

                        <!-- Password Confirmation Field -->
                        <div class="input-group">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input id="password_confirmation" 
                                       name="password_confirmation" 
                                       type="password" 
                                       required 
                                       placeholder=" "
                                       class="block w-full pl-12 pr-4 py-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300 bg-white/70">
                                <label for="password_confirmation" class="floating-label">Confirmar Senha</label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button type="submit" 
                                    class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-lg font-semibold rounded-xl text-white bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <span class="absolute left-0 inset-y-0 flex items-center pl-4">
                                    <i class="fas fa-user-plus text-blue-300 group-hover:text-blue-200 transition duration-300"></i>
                                </span>
                                Registrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="text-center">
                <p class="text-sm text-gray-500">
                    Ao criar uma conta, você concorda com nossos
                    <a href="#" class="text-blue-600 hover:text-blue-800 transition duration-300">Termos de Uso</a>
                    e
                    <a href="#" class="text-blue-600 hover:text-blue-800 transition duration-300">Política de Privacidade</a>
                </p>
            </div>

            <!-- Login Link -->
            <div class="text-center mt-4">
                <p class="text-gray-600">
                    Já possui uma conta?
                    <a href="{{ route('login') }}" 
                       class="font-semibold text-blue-600 hover:text-blue-800 transition duration-300 ml-1">
                        Entrar
                    </a>
                </p>
            </div>
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
            document.getElementById('name').focus();
        });
    </script>
</body>
</html>