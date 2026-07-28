@extends('layouts.app')

@section('title', 'Configurações do Sistema')
@section('page-title', 'Configurações')
@section('page-title-icon', 'fas fa-cog')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Configurações</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="school-card">
            <div class="school-card-header">
                <i class="fas fa-cog me-2"></i>
                Configurações do Sistema
            </div>
            <div class="school-card-body">
                @if(session('success'))
                    <div class="alert alert-success-school">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <!-- Configurações Gerais -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="mb-3"><i class="fas fa-school me-2"></i>Informações da Escola</h5>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nome da Escola</label>
                                <input type="text" name="settings[school_name]" class="form-control" 
                                    value="{{ setting('school_name', 'Escola dos Visionários') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Telefone</label>
                                <input type="text" name="settings[school_phone]" class="form-control" 
                                    value="{{ setting('school_phone', '') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="settings[school_email]" class="form-control" 
                                    value="{{ setting('school_email', '') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Endereço</label>
                                <input type="text" name="settings[school_address]" class="form-control" 
                                    value="{{ setting('school_address', '') }}">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Configurações Académicas -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="mb-3"><i class="fas fa-calendar me-2"></i>Ano Lectivo</h5>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Ano Lectivo Actual</label>
                                <input type="number" name="settings[current_academic_year]" class="form-control" 
                                    value="{{ setting('current_academic_year', date('Y')) }}" min="2020" max="2030">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Data de Início do Ano</label>
                                <input type="date" name="settings[academic_year_start]" class="form-control" 
                                    value="{{ setting('academic_year_start', '2026-01-30') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Data de Fim do Ano</label>
                                <input type="date" name="settings[academic_year_end]" class="form-control" 
                                    value="{{ setting('academic_year_end', '2026-11-20') }}">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Configurações Financeiras -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="mb-3"><i class="fas fa-money-bill-wave me-2"></i>Configurações Financeiras</h5>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Dia de Vencimento</label>
                                <input type="number" name="settings[payment_due_day]" class="form-control" 
                                    value="{{ setting('payment_due_day', 5) }}" min="1" max="28">
                                <small class="text-muted">Dia do mês para vencimento das mensalidades</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Multa por Atraso (%)</label>
                                <input type="number" name="settings[late_payment_penalty]" class="form-control" 
                                    value="{{ setting('late_payment_penalty', 5) }}" min="0" max="100" step="0.1">
                                <small class="text-muted">Percentagem de multa por pagamento em atraso</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Taxa de Matrícula (MZN)</label>
                                <input type="number" name="settings[enrollment_fee]" class="form-control" 
                                    value="{{ setting('enrollment_fee', 0) }}" min="0">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Configurações de Avaliação -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="mb-3"><i class="fas fa-clipboard-check me-2"></i>Sistema de Avaliação</h5>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Nota Mínima para Aprovação</label>
                                <input type="number" name="settings[passing_grade]" class="form-control" 
                                    value="{{ setting('passing_grade', 10) }}" min="0" max="20" step="0.1">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Número de Trimestres</label>
                                <select name="settings[number_of_terms]" class="form-select">
                                    <option value="3" {{ setting('number_of_terms', 3) == 3 ? 'selected' : '' }}>3 Trimestres</option>
                                    <option value="2" {{ setting('number_of_terms', 3) == 2 ? 'selected' : '' }}>2 Semestres</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Peso da ACP (%)</label>
                                <input type="number" name="settings[acp_weight]" class="form-control" 
                                    value="{{ setting('acp_weight', 33) }}" min="0" max="100">
                                <small class="text-muted">Peso da Avaliação Contínua Parcial</small>
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary-school">
                            <i class="fas fa-save me-2"></i>
                            Guardar Configurações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@php
function setting($key, $default = null) {
    return \App\Models\Setting::where('key', $key)->first()?->value ?? $default;
}
@endphp