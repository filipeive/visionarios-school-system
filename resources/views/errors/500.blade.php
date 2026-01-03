@extends('errors.layout')

@section('title', 'Erro Interno')

@section('content')
    <div class="error-icon">
        <i class="fas fa-bug"></i>
    </div>
    <div class="error-code">500</div>
    <div class="error-title">Erro Interno do Servidor</div>
    <p class="error-message">
        Ocorreu um problema inesperado em nossos servidores.<br>
        Nossa equipe técnica já foi notificada e está trabalhando para resolver.
    </p>
@endsection