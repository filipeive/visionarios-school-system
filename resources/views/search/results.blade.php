@extends('layouts.app')

@section('title', 'Resultados da Pesquisa')
@section('page-title', 'Resultados da Pesquisa')

@php
    $titleIcon = 'fas fa-search';
@endphp

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Pesquisa: "{{ $query }}"</li>
@endsection

@section('page-actions')
    <div class="btn-group">
        <button class="btn btn-primary-visionarios" onclick="window.history.back()">
            <i class="fas fa-arrow-left"></i> Voltar
        </button>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
            <i class="fas fa-home"></i> Dashboard
        </a>
    </div>
@endsection

@section('content')
<!-- Estatísticas da Pesquisa -->
<div class="row mb-4">
    <div class="col-12">
        <div class="school-card">
            <div class="school-card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="mb-1">Resultados para: <strong>"{{ $query }}"</strong></h5>
                        <p class="text-muted mb-0">Encontrados {{ $totalResults }} resultados</p>
                    </div>
                    <div class="text-end">
                        <form action="{{ route('search') }}" method="GET" class="d-flex">
                            <input type="text" name="q" value="{{ $query }}" 
                                   class="form-control me-2" placeholder="Nova pesquisa...">
                            <button type="submit" class="btn btn-primary-visionarios">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alunos -->
@if($results['students']->count() > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="school-card">
            <div class="school-card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-user-graduate"></i>
                    Alunos ({{ $results['students']->count() }})
                </div>
                <span class="badge bg-primary">Encontrados</span>
            </div>
            <div class="school-card-body p-0">
                <div class="school-table">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="50">Foto</th>
                                <th>Nome</th>
                                <th>Nº Estudante</th>
                                <th>Turma</th>
                                <th>Email</th>
                                <th width="100">Status</th>
                                <th width="80">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results['students'] as $student)
                                <tr>
                                    <td>
                                        <img src="{{ $student->photo_url ?? '/images/avatar-default.png' }}" 
                                             class="rounded-circle" 
                                             style="width: 40px; height: 40px; object-fit: cover;"
                                             alt="{{ $student->full_name }}"
                                             onerror="this.src='/images/avatar-default.png'">
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $student->full_name }}</div>
                                        <small class="text-muted">{{ $student->gender }} • {{ $student->age }} anos</small>
                                    </td>
                                    <td>
                                        <code>{{ $student->student_number }}</code>
                                    </td>
                                    <td>
                                        @if($student->currentClass)
                                            <span class="badge bg-info">{{ $student->currentClass->name }}</span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $student->email ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $student->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ $student->status === 'active' ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('students.show', $student) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Ver perfil">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Professores -->
@if($results['teachers']->count() > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="school-card">
            <div class="school-card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-chalkboard-teacher"></i>
                    Professores ({{ $results['teachers']->count() }})
                </div>
                <span class="badge bg-success">Encontrados</span>
            </div>
            <div class="school-card-body p-0">
                <div class="school-table">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Especialização</th>
                                <th>Email</th>
                                <th>Telefone</th>
                                <th width="100">Status</th>
                                <th width="80">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results['teachers'] as $teacher)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $teacher->full_name }}</div>
                                        <small class="text-muted">{{ $teacher->gender ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $teacher->specialization ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <small>{{ $teacher->email }}</small>
                                    </td>
                                    <td>
                                        <small>{{ $teacher->phone ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $teacher->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ $teacher->status === 'active' ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('teachers.show', $teacher) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Ver perfil">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Turmas -->
@if($results['classes']->count() > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="school-card">
            <div class="school-card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-chalkboard"></i>
                    Turmas ({{ $results['classes']->count() }})
                </div>
                <span class="badge bg-warning">Encontradas</span>
            </div>
            <div class="school-card-body">
                <div class="row">
                    @foreach($results['classes'] as $class)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <h6 class="card-title">{{ $class->name }}</h6>
                                    <p class="card-text text-muted small mb-2">
                                        <i class="fas fa-users me-1"></i>
                                        {{ $class->students_count ?? 0 }} alunos
                                    </p>
                                    <p class="card-text text-muted small mb-2">
                                        <i class="fas fa-user-tie me-1"></i>
                                        {{ $class->teacher->full_name ?? 'Sem professor' }}
                                    </p>
                                    <p class="card-text text-muted small">
                                        <i class="fas fa-calendar me-1"></i>
                                        Ano: {{ $class->academic_year }}
                                    </p>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <a href="{{ route('classes.show', $class) }}" 
                                       class="btn btn-sm btn-outline-primary w-100">
                                        Ver Turma
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Disciplinas -->
@if($results['subjects']->count() > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="school-card">
            <div class="school-card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-book"></i>
                    Disciplinas ({{ $results['subjects']->count() }})
                </div>
                <span class="badge bg-info">Encontradas</span>
            </div>
            <div class="school-card-body">
                <div class="list-group">
                    @foreach($results['subjects'] as $subject)
                        <div class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $subject->name }}</h6>
                                    <small class="text-muted">
                                        Código: {{ $subject->code }} | 
                                        Professor: {{ $subject->teacher->full_name ?? 'N/A' }}
                                    </small>
                                </div>
                                <div>
                                    <span class="badge bg-{{ $subject->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ $subject->status === 'active' ? 'Ativa' : 'Inativa' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Pagamentos -->
@if($results['payments']->count() > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="school-card">
            <div class="school-card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-money-bill-wave"></i>
                    Pagamentos ({{ $results['payments']->count() }})
                </div>
                <span class="badge bg-success">Encontrados</span>
            </div>
            <div class="school-card-body p-0">
                <div class="school-table">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Referência</th>
                                <th>Aluno</th>
                                <th>Valor</th>
                                <th>Data</th>
                                <th>Status</th>
                                <th width="80">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results['payments'] as $payment)
                                <tr>
                                    <td>
                                        <code>{{ $payment->reference_number }}</code>
                                    </td>
                                    <td>
                                        {{ $payment->student->full_name }}
                                    </td>
                                    <td>
                                        <strong>{{ number_format($payment->amount, 2, ',', '.') }} MT</strong>
                                    </td>
                                    <td>
                                        {{ $payment->payment_date->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $payment->status === 'paid' ? 'success' : 
                                                              ($payment->status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ $payment->status === 'paid' ? 'Pago' : 
                                              ($payment->status === 'pending' ? 'Pendente' : 'Atrasado') }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('payments.show', $payment) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Ver detalhes">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Nenhum Resultado -->
@if($totalResults == 0)
<div class="row">
    <div class="col-12">
        <div class="school-card">
            <div class="school-card-body text-center py-5">
                <i class="fas fa-search fs-1 text-muted mb-3"></i>
                <h4 class="text-muted">Nenhum resultado encontrado</h4>
                <p class="text-muted mb-4">Não foram encontrados resultados para "{{ $query }}"</p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ route('dashboard') }}" class="btn btn-primary-visionarios">
                        <i class="fas fa-home me-2"></i>Voltar ao Dashboard
                    </a>
                    <button onclick="window.history.back()" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Voltar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
// Função para destacar os termos pesquisados
function highlightText(text, query) {
    if (!query) return text;
    
    const regex = new RegExp(`(${query})`, 'gi');
    return text.replace(regex, '<mark>$1</mark>');
}

// Aplicar destaque aos textos
document.addEventListener('DOMContentLoaded', function() {
    const query = "{{ $query }}";
    
    // Destacar textos nas tabelas
    document.querySelectorAll('.school-table td').forEach(td => {
        td.innerHTML = highlightText(td.textContent, query);
    });
    
    // Destacar textos nos cards
    document.querySelectorAll('.card-title, .card-text').forEach(el => {
        el.innerHTML = highlightText(el.textContent, query);
    });
});
</script>
@endpush