@extends('layouts.app')

@section('title', 'Dashboard Professor')
@section('page-title', 'Dashboard Professor')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">
        <div class="alert alert-info shadow-sm rounded-3 mb-4 d-flex align-items-center">
            <i class="fas fa-info-circle fa-lg me-2"></i>
            <div>
                <strong>Portal do Professor:</strong> 
                <a href="{{ route('teacher.dashboard') }}" class="alert-link">
                    Clique aqui para acessar o portal completo do professor.
                </a>
            </div>
        </div>

        <div class="card border-0 shadow-lg rounded-4 text-center py-5">
            <div class="card-body">
                <i class="fas fa-chalkboard-teacher fa-4x text-primary mb-3"></i>
                <h3 class="fw-bold mb-3">Portal do Professor</h3>
                <p class="text-muted mb-4">Você está logado como professor.</p>
                <a href="{{ route('teacher.dashboard') }}" class="btn btn-primary btn-lg px-5">
                    <i class="fas fa-external-link-alt me-1"></i> Acessar Portal do Professor
                </a>
                <p class="text-muted mt-4 mb-0 small">
                    Você será redirecionado automaticamente em alguns segundos...
                </p>
            </div>
        </div>
    </div>
</div>

<script>
// Redirecionamento automático após 3 segundos
setTimeout(function() {
    window.location.href = "{{ route('teacher.dashboard') }}";
}, 3000);
</script>

<style>
    .card {
        background: #ffffff;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    }
    .btn-primary {
        background-color: #0078d4;
        border-color: #0078d4;
    }
    .btn-primary:hover {
        background-color: #005a9e;
        border-color: #005a9e;
    }
</style>
@endsection
