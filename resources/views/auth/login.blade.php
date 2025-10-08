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
    <style>
        :root {
            /* Cores do Logo - Extraídas da imagem */
            --primary-blue: #2B5A8A;
            --secondary-blue: #4A90C8;
            --accent-green: #7CB342;
            --accent-orange: #FF9800;
            --sun-yellow: #FDB913;
            --earth-green: #8BC34A;
            
            /* Gradientes do Logo */
            --gradient-sky: linear-gradient(135deg, #FDB913 0%, #FF9800 100%);
            --gradient-earth: linear-gradient(135deg, #7CB342 0%, #8BC34A 100%);
            --gradient-book: linear-gradient(135deg, #2B5A8A 0%, #4A90C8 100%);
            --gradient-main: linear-gradient(135deg, #2B5A8A 0%, #4A90C8 50%, #7CB342 100%);
            
            /* UI Colors */
            --bg-primary: #F8F9FD;
            --bg-card: #FFFFFF;
            --text-primary: #1A202C;
            --text-secondary: #64748B;
            --border-color: #E2E8F0;
            --shadow: 0 10px 40px rgba(43, 90, 138, 0.1);
            --shadow-hover: 0 20px 60px rgba(43, 90, 138, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--bg-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated Background */
        body::before {
            content: '';
            position: fixed;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(124, 179, 66, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(43, 90, 138, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(255, 152, 0, 0.03) 0%, transparent 50%);
            animation: backgroundFloat 20s ease-in-out infinite;
            pointer-events: none;
            z-index: 0;
        }

        @keyframes backgroundFloat {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -50px) rotate(5deg); }
            66% { transform: translate(-20px, 20px) rotate(-5deg); }
        }

        /* Login Container */
        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 450px;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Logo Section */
        .logo-section {
            text-align: center;
            margin-bottom: 32px;
            animation: fadeIn 0.8s ease-out 0.2s both;
        }

        .logo-wrapper {
            display: inline-block;
            position: relative;
            margin-bottom: 20px;
        }

        .logo-image {
            width: 120px;
            height: auto;
            filter: drop-shadow(0 8px 16px rgba(43, 90, 138, 0.15));
            transition: all 0.3s ease;
            animation: floatLogo 3s ease-in-out infinite;
        }

        @keyframes floatLogo {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .logo-wrapper:hover .logo-image {
            transform: scale(1.05);
            filter: drop-shadow(0 12px 24px rgba(43, 90, 138, 0.2));
        }

        .logo-title {
            font-size: 28px;
            font-weight: 800;
            background: var(--gradient-main);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .logo-subtitle {
            font-size: 15px;
            color: var(--text-secondary);
            font-weight: 500;
        }

        /* Auth Card */
        .auth-card {
            background: var(--bg-card);
            border-radius: 24px;
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .auth-card:hover {
            box-shadow: var(--shadow-hover);
        }

        .auth-header {
            background: var(--gradient-main);
            padding: 32px 32px 80px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .auth-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: ripple 4s ease-out infinite;
        }

        @keyframes ripple {
            0% { transform: scale(0.8); opacity: 0; }
            50% { opacity: 0.5; }
            100% { transform: scale(1.5); opacity: 0; }
        }

        .auth-header h1 {
            color: white;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }

        .auth-header p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
            font-weight: 500;
            position: relative;
            z-index: 1;
        }

        /* Auth Body */
        .auth-body {
            padding: 0 32px 32px;
            margin-top: -50px;
            position: relative;
            z-index: 2;
        }

        .welcome-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 24px;
            border: 1px solid var(--border-color);
        }

        .welcome-text {
            font-size: 16px;
            color: var(--text-primary);
            font-weight: 600;
            margin-bottom: 4px;
        }

        .welcome-subtext {
            font-size: 13px;
            color: var(--text-secondary);
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .form-label i {
            color: var(--primary-blue);
            font-size: 16px;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px 14px 44px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: var(--bg-primary);
            color: var(--text-primary);
            position: relative;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 16px;
            transition: all 0.3s ease;
            pointer-events: none;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-blue);
            background: white;
            box-shadow: 0 0 0 4px rgba(43, 90, 138, 0.1);
        }

        .form-control:focus + .input-icon {
            color: var(--primary-blue);
        }

        .form-control.is-invalid {
            border-color: #E53935;
        }

        .invalid-feedback {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #E53935;
            font-size: 13px;
            margin-top: 6px;
        }

        /* Checkbox */
        .form-check {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            border: 2px solid var(--border-color);
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .form-check-input:checked {
            background: var(--gradient-main);
            border-color: var(--primary-blue);
        }

        .form-check-label {
            font-size: 14px;
            color: var(--text-secondary);
            cursor: pointer;
            user-select: none;
        }

        /* Button */
        .btn-login {
            width: 100%;
            padding: 14px;
            background: var(--gradient-main);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 16px rgba(43, 90, 138, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-login:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(43, 90, 138, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login span {
            position: relative;
            z-index: 1;
        }

        /* Links */
        .forgot-password {
            text-align: center;
            margin-top: 20px;
        }

        .forgot-password a {
            color: var(--primary-blue);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .forgot-password a:hover {
            color: var(--accent-orange);
            gap: 8px;
        }

        /* Alert */
        .alert {
            padding: 14px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            border: none;
            animation: slideDown 0.4s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: rgba(124, 179, 66, 0.1);
            color: var(--accent-green);
            border-left: 4px solid var(--accent-green);
        }

        .alert-danger {
            background: rgba(229, 57, 53, 0.1);
            color: #E53935;
            border-left: 4px solid #E53935;
        }

        .alert i {
            font-size: 18px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .alert ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .alert li {
            margin-bottom: 4px;
        }

        .alert li:last-child {
            margin-bottom: 0;
        }

        /* Footer */
        .auth-footer {
            text-align: center;
            margin-top: 24px;
            padding: 20px;
            color: var(--text-secondary);
            font-size: 13px;
        }

        .auth-footer strong {
            background: var(--gradient-main);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
        }

        /* Loading State */
        .btn-login.loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .btn-login.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 576px) {
            .login-container {
                max-width: 100%;
            }

            .auth-body {
                padding: 0 24px 24px;
            }

            .logo-title {
                font-size: 24px;
            }

            .auth-header {
                padding: 24px 24px 60px;
            }

            .auth-header h1 {
                font-size: 20px;
            }
        }

        /* Toast Notifications */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }

        .toast-notification {
            min-width: 320px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
            margin-bottom: 12px;
            overflow: hidden;
            animation: slideInRight 0.4s ease-out;
            border-left: 4px solid;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .toast-notification.success {
            border-left-color: var(--accent-green);
        }

        .toast-notification.error {
            border-left-color: #E53935;
        }

        .toast-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-color);
        }

        .toast-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            font-size: 14px;
        }

        .toast-body {
            padding: 12px 16px;
            font-size: 13px;
            color: var(--text-secondary);
        }

        .toast-close {
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 4px;
            transition: all 0.2s ease;
        }

        .toast-close:hover {
            color: var(--text-primary);
            transform: scale(1.1);
        }
    </style>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <div class="login-container">
        <!-- Logo Section -->
        <div class="logo-section">
            <div class="logo-wrapper">
                <img src="/images/logo.png" alt="Escola dos Visionários" class="logo-image" 
                     onerror="this.style.display='none'">
            </div>
            <h2 class="logo-title">ESCOLA DOS VISIONÁRIOS</h2>
            <p class="logo-subtitle">Sistema de Gestão Escolar</p>
        </div>

        <!-- Auth Card -->
        <div class="auth-card">
            <div class="auth-header">
                <h1>Bem-vindo de volta!</h1>
                <p>Entre com suas credenciais para acessar o sistema</p>
            </div>

            <div class="auth-body">
                <div class="welcome-card">
                    <div class="welcome-text">Portal Administrativo</div>
                    <div class="welcome-subtext">Acesso seguro ao sistema de gestão</div>
                </div>

                @if (session('status'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            @if ($errors->count() === 1)
                                {{ $errors->first() }}
                            @else
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i>
                            <span>Endereço de Email</span>
                        </label>
                        <div class="input-wrapper">
                            <input 
                                id="email" 
                                type="email" 
                                class="form-control @error('email') is-invalid @enderror"
                                name="email" 
                                value="{{ old('email') }}" 
                                required 
                                autofocus
                                autocomplete="email"
                                placeholder="seu@email.com">
                            <i class="fas fa-envelope input-icon"></i>
                        </div>
                        @error('email')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock"></i>
                            <span>Senha</span>
                        </label>
                        <div class="input-wrapper">
                            <input 
                                id="password" 
                                type="password" 
                                class="form-control @error('password') is-invalid @enderror"
                                name="password" 
                                required
                                autocomplete="current-password"
                                placeholder="Digite sua senha">
                            <i class="fas fa-lock input-icon"></i>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="form-check">
                        <input 
                            type="checkbox" 
                            class="form-check-input" 
                            id="remember" 
                            name="remember"
                            {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            Manter-me conectado
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-login" id="loginBtn">
                        <span>
                            <i class="fas fa-sign-in-alt"></i>
                            Entrar no Sistema
                        </span>
                    </button>

                    <!-- Forgot Password -->
                    @if (Route::has('password.request'))
                        <div class="forgot-password">
                            <a href="{{ route('password.request') }}">
                                <i class="fas fa-key"></i>
                                Esqueceu sua senha?
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        <!-- Footer -->
        <div class="auth-footer">
            <p>© {{ date('Y') }} <strong>Escola dos Visionários</strong></p>
            <p>Quelimane, Província da Zambézia - Moçambique</p>
        </div>
    </div>

    <script>
        // Toast Notification System
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast-notification ${type}`;
            
            const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
            const title = type === 'success' ? 'Sucesso!' : 'Erro!';
            
            toast.innerHTML = `
                <div class="toast-header">
                    <div class="toast-title">
                        <i class="fas ${icon}"></i>
                        <span>${title}</span>
                    </div>
                    <button class="toast-close" onclick="this.parentElement.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="toast-body">${message}</div>
            `;
            
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.style.animation = 'slideOutRight 0.4s ease-out';
                setTimeout(() => toast.remove(), 400);
            }, 5000);
        }

        // Form Submission Handler
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('loginBtn');
            btn.classList.add('loading');
            btn.querySelector('span').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Entrando...';
        });

        // Auto-hide alerts
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.style.animation = 'slideUp 0.4s ease-out reverse';
                setTimeout(() => alert.remove(), 400);
            });
        }, 5000);

        // Input animations
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        console.log('🎓 Sistema Visionários - Login carregado com sucesso!');
    </script>
</x-guest-layout>