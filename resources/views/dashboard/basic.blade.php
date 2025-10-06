@extends('layouts.school')

@section('title', 'Dashboard')
@section('page-title', 'Bem-vindo')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="school-card">
            <div class="school-card-body text-center py-5">
                <i class="fas fa-graduation-cap text-primary" style="font-size: 80px;"></i>
                <h2 class="mt-4">Bem-vindo ao Sistema Visionários</h2>
                <p class="text-muted">Sistema de Gestão Escolar</p>
                <p>Seu perfil ainda não foi completamente configurado.</p>
                <a href="{{ route('profile.edit') }}" class="btn-school btn-primary-school mt-3">
                    <i class="fas fa-user-cog"></i>
                    Completar Perfil
                </a>
            </div>
        </div>
    </div>
</div>
@endsection