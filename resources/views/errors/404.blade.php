@extends('errors.layout')

@section('title', 'Página Não Encontrada')

@section('content')
    <div class="error-icon">
        <i class="fas fa-search"></i>
    </div>
    <div class="error-code">404</div>
    <div class="error-title">Página Não Encontrada</div>
    <p class="error-message">
        Ops! A página que você está procurando parece ter desaparecido.<br>
        Verifique o endereço ou use o botão abaixo para voltar.
    </p>
@endsection