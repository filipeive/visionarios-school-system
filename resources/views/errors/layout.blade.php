<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Visionários School') }} - @yield('title')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Plus+Jakarta+Sans:wght@700;800&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --primary-navy: #0A2463;
            --primary-ocean: #0077B6;
            --accent-sun: #FFB800;
            --accent-green: #00A878;
            --gray-50: #F8FAFC;
            --gray-600: #475569;
            --gray-900: #0F172A;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--gray-50);
            color: var(--gray-900);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .error-container {
            text-align: center;
            padding: 40px;
            max-width: 600px;
            width: 100%;
        }

        .error-code {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 120px;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 20px;
            background: linear-gradient(135deg, var(--primary-navy) 0%, var(--primary-ocean) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .error-icon {
            font-size: 60px;
            margin-bottom: 30px;
            color: var(--accent-sun);
        }

        .error-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--primary-navy);
        }

        .error-message {
            font-size: 18px;
            color: var(--gray-600);
            margin-bottom: 40px;
        }

        .btn-home {
            background: linear-gradient(135deg, var(--primary-navy) 0%, var(--primary-ocean) 100%);
            color: white;
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(10, 36, 99, 0.2);
        }

        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(10, 36, 99, 0.3);
            color: white;
        }

        .school-logo {
            margin-bottom: 40px;
        }

        .logo-box {
            width: 60px;
            height: 60px;
            background: var(--accent-sun);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            box-shadow: 0 4px 12px rgba(255, 184, 0, 0.3);
        }

        .logo-box i {
            color: var(--primary-navy);
            font-size: 30px;
        }
    </style>
</head>

<body>
    <div class="error-container">
        <div class="school-logo">
            <div class="logo-box">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="mt-2 fw-bold text-uppercase tracking-wider"
                style="color: var(--primary-navy); letter-spacing: 2px; font-size: 12px;">
                Visionários School
            </div>
        </div>

        @yield('content')

        <div class="mt-5">
            <a href="{{ url('/') }}" class="btn-home">
                <i class="fas fa-arrow-left"></i>
                Voltar para o Início
            </a>
        </div>
    </div>
</body>

</html>