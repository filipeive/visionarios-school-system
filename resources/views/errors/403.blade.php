{{-- resources/views/errors/403.blade.php --}}
@extends('layouts.school')

@section('title', 'Acesso Negado')
@section('page-title', 'Erro 403')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="school-card">
            <div class="school-card-body text-center py-5">
                <i class="fas fa-ban text-danger" style="font-size: 100px;"></i>
                <h1 class="display-1 mt-4">403</h1>
                <h3>Acesso Negado</h3>
                <p class="text-muted">Você não tem permissão para acessar esta página.</p>
                <a href="{{ route('dashboard') }}" class="btn-school btn-primary-school mt-3">
                    <i class="fas fa-home"></i>
                    Voltar ao Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection