@extends('layouts.app')

@section('title', 'Estudantes Arquivados')
@section('page-title', 'Gestão de Estudantes')
@section('page-title-icon', 'fas fa-archive')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('enrollments.index') }}">Matrículas</a></li>
     <li class="breadcrumb-item"><a href="{{ route('admin.enrollments.renewals') }}">Renovações</a></li>
    <li class="breadcrumb-item active">Arquivados</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Estatísticas -->
            <div class="school-stats mb-4">
                <div class="stat-card secondary">
                    <div class="stat-icon secondary">
                        <i class="fas fa-archive"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $stats['total'] }}</div>
                        <div class="stat-label">Total Arquivados</div>
                    </div>
                </div>
                <div class="stat-card info">
                    <div class="stat-icon info">
                        <i class="fas fa-sign-out-alt"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $stats['transferred'] }}</div>
                        <div class="stat-label">Transferidos</div>
                    </div>
                </div>
                <div class="stat-card success">
                    <div class="stat-icon success">
                        <i class="fas fa-award"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $stats['graduated'] }}</div>
                        <div class="stat-label">Formados</div>
                    </div>
                </div>
                <div class="stat-card danger">
                    <div class="stat-icon danger">
                        <i class="fas fa-user-slash"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $stats['inactive'] }}</div>
                        <div class="stat-label">Desistiram</div>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="school-card mb-4">
                <div class="school-card-body">
                    <form action="{{ route('admin.students-archive.index') }}" method="GET" class="row g-3">
                        <div class="col-md-5">
                            <input type="text" name="search" class="form-control"
                                placeholder="Pesquisar por nome ou nº de estudante..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="exit_status" class="form-select">
                                <option value="">Todos os Status</option>
                                <option value="transferred" {{ request('exit_status') == 'transferred' ? 'selected' : '' }}>
                                    Transferidos
                                </option>
                                <option value="graduated" {{ request('exit_status') == 'graduated' ? 'selected' : '' }}>
                                    Formados
                                </option>
                                <option value="inactive" {{ request('exit_status') == 'inactive' ? 'selected' : '' }}>
                                    Desistiram
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary-school w-100">
                                <i class="fas fa-search"></i> Filtrar
                            </button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('admin.enrollments.renewals') }}" class="btn btn-outline-warning w-100">
                                <i class="fas fa-sync"></i> Renovações
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            @if($students->isEmpty())
                <div class="school-card">
                    <div class="school-card-body text-center py-5">
                        <i class="fas fa-archive fa-4x text-muted mb-3"></i>
                        <h4>Nenhum estudante arquivado</h4>
                        <p class="text-muted">Não há estudantes com status de transferência, formatura ou inatividade.</p>
                    </div>
                </div>
            @else
                <div class="school-card">
                    <div class="school-card-header">
                        <h3 class="school-card-title">
                            <i class="fas fa-list me-2"></i> Lista de Estudantes Arquivados
                        </h3>
                        <span class="badge bg-secondary">{{ $students->total() }}</span>
                    </div>
                    <div class="school-card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-school table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Estudante</th>
                                        <th>Contacto</th>
                                        <th>Status</th>
                                        <th>Última Matrícula</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $student)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="user-avatar me-3" style="width: 40px; height: 40px; font-size: 12px;">
                                                        {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold">{{ $student->full_name }}</div>
                                                        <small class="text-muted">{{ $student->student_number ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div><small>{{ $student->emergency_phone ?? 'N/A' }}</small></div>
                                            </td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'transferred' => 'info',
                                                        'graduated' => 'success',
                                                        'inactive' => 'secondary'
                                                    ];
                                                    $statusLabels = [
                                                        'transferred' => 'Transferido',
                                                        'graduated' => 'Formado',
                                                        'inactive' => 'Desistente'
                                                    ];
                                                @endphp
                                                <span class="badge bg-{{ $statusColors[$student->status] ?? 'secondary' }}">
                                                    {{ $statusLabels[$student->status] ?? ucfirst($student->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $lastEnrollment = $student->enrollments->last();
                                                @endphp
                                                @if($lastEnrollment)
                                                    <div>{{ $lastEnrollment->class->name ?? 'N/A' }}</div>
                                                    <small class="text-muted">Ano {{ $lastEnrollment->school_year }}</small>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.students-archive.show', $student) }}"
                                                        class="btn btn-sm btn-primary" title="Ver Detalhes">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-success"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#reactivateModal{{ $student->id }}"
                                                        title="Reativar">
                                                        <i class="fas fa-redo"></i>
                                                    </button>
                                                </div>

                                                <!-- Modal de Reativação -->
                                                <div class="modal fade" id="reactivateModal{{ $student->id }}" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form action="{{ route('admin.students-archive.reactivate', $student) }}"
                                                                method="POST">
                                                                @csrf
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">
                                                                        <i class="fas fa-redo me-2 text-success"></i>
                                                                        Reativar: {{ $student->full_name }}
                                                                    </h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body text-start">
                                                                    <div class="alert alert-warning">
                                                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                                                        O aluno será reativado para o ano letivo {{ current_school_year() }}.
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label class="form-label">Nova Turma *</label>
                                                                        <select name="class_id" class="form-select" required>
                                                                            <option value="">Selecione...</option>
                                                                            @foreach(\App\Models\ClassRoom::where('school_year', current_school_year())->where('is_active', true)->get() as $class)
                                                                                <option value="{{ $class->id }}">
                                                                                    {{ $class->name }} ({{ $class->grade_level_name }})
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-md-6 mb-3">
                                                                            <label class="form-label">Mensalidade (MZN) *</label>
                                                                            <input type="number" name="monthly_fee" class="form-control"
                                                                                value="{{ $student->monthly_fee ?? 0 }}" step="0.01" required>
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <label class="form-label">Observações</label>
                                                                            <input type="text" name="reason" class="form-control"
                                                                                placeholder="Motivo da reativação">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Cancelar</button>
                                                                    <button type="submit" class="btn btn-success">
                                                                        <i class="fas fa-check me-1"></i> Reativar
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($students->hasPages())
                        <div class="school-card-footer">
                            {{ $students->links() }}
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection