{{-- resources/views/errors/404.blade.php --}}
@extends('layouts.app')

@section('title', 'Página Não Encontrada')
@section('page-title', 'Erro 404')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="school-card">
            <div class="school-card-body text-center py-5">
                <i class="fas fa-exclamation-triangle text-warning" style="font-size: 100px;"></i>
                <h1 class="display-1 mt-4">404</h1>
                <h3>Página Não Encontrada</h3>
                <p class="text-muted">A página que você está procurando não existe.</p>
                <a href="{{ route('dashboard') }}" class="btn-school btn-primary-school mt-3">
                    <i class="fas fa-home"></i>
                    Voltar ao Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection