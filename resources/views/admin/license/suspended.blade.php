<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Licença Suspensa - ZamEdu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8fafc;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .suspension-card {
            max-width: 550px;
            background: white;
            padding: 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="suspension-card">
        <div class="text-danger mb-3">
            <i class="fas fa-exclamation-triangle fa-4x"></i>
        </div>
        <h3 class="fw-bold mb-2">Licença do Sistema Suspensa</h3>
        <p class="text-muted mb-4">
            A subscrição do <strong>ZamEdu</strong> para esta instituição expirou e o período de carência foi ultrapassado.
            <br><small>Nota: Todos os dados de alunos, turmas e notas permanecem salvaguardados na base de dados.</small>
        </p>

        <div class="alert alert-light border text-start mb-4">
            <div class="d-flex justify-content-between mb-1">
                <span>Cliente:</span>
                <strong>{{ $license->client_name ?? 'N/A' }}</strong>
            </div>
            <div class="d-flex justify-content-between mb-1">
                <span>Data de Expiração:</span>
                <strong>{{ $license?->expires_at ? $license->expires_at->format('d/m/Y') : 'N/A' }}</strong>
            </div>
            <div class="d-flex justify-content-between">
                <span>Suporte FDS Software:</span>
                <strong>suporte@fdssoftware.co.mz</strong>
            </div>
        </div>

        <div class="d-flex gap-2 justify-content-center">
            @auth
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary">
                        <i class="fas fa-sign-out-alt me-2"></i>Sair
                    </button>
                </form>
                @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('super-admin'))
                    <a href="{{ route('admin.license') }}" class="btn btn-primary">
                        <i class="fas fa-key me-2"></i>Renovar Licença
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt me-2"></i>Entrar como Administrador
                </a>
            @endauth
        </div>
    </div>
</body>
</html>
