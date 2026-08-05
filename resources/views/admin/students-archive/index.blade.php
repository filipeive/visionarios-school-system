@extends('layouts.app')

@section('title', 'Estudantes Arquivados')
@section('page-title', 'Alunos Arquivados')
@section('page-title-icon', 'fas fa-archive')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('enrollments.index') }}">Matrículas</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.enrollments.renewals') }}">Renovações</a></li>
    <li class="breadcrumb-item active">Arquivados</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">

        <!-- Stat KPI Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-secondary">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-secondary-subtle text-secondary rounded-circle p-3 me-3">
                            <i class="fas fa-archive fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Total Arquivados</div>
                            <h4 class="mb-0 text-secondary font-weight-bold">{{ $stats['total'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-info">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-info-subtle text-info rounded-circle p-3 me-3">
                            <i class="fas fa-right-from-bracket fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Transferidos</div>
                            <h4 class="mb-0 text-info font-weight-bold">{{ $stats['transferred'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-success">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-success-subtle text-success rounded-circle p-3 me-3">
                            <i class="fas fa-award fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Formados</div>
                            <h4 class="mb-0 text-success font-weight-bold">{{ $stats['graduated'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-danger">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-danger-subtle text-danger rounded-circle p-3 me-3">
                            <i class="fas fa-user-slash fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Desistiram</div>
                            <h4 class="mb-0 text-danger font-weight-bold">{{ $stats['inactive'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros Harmonizados -->
        <div class="school-card mb-4">
            <div class="school-card-body">
                <form action="{{ route('admin.students-archive.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label text-xs font-bold text-slate-700 uppercase tracking-wider">Pesquisar Estudante</label>
                        <input type="text" name="search" class="form-control rounded-xl border-slate-200"
                            placeholder="Pesquisar por nome ou nº de estudante..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-xs font-bold text-slate-700 uppercase tracking-wider">Motivo de Arquivo</label>
                        <select name="exit_status" class="form-select rounded-xl border-slate-200">
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
                        <button type="submit" class="btn btn-primary-school w-100 rounded-xl">
                            <i class="fas fa-filter me-1"></i> Filtrar
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('admin.enrollments.renewals') }}" class="btn btn-outline-warning w-100 rounded-xl">
                            <i class="fas fa-sync me-1"></i> Renovações
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabela de Estudantes Arquivados -->
        @if($students->isEmpty())
            <div class="school-card">
                <div class="school-card-body text-center py-5">
                    <i class="fas fa-archive fa-4x text-slate-300 mb-3"></i>
                    <h5>Nenhum estudante arquivado</h5>
                    <p class="text-muted text-xs">Não foram encontrados registos de transferência, formatura ou inatividade.</p>
                </div>
            </div>
        @else
            <div class="school-table-container">
                <div class="school-table-header">
                    <h3 class="school-table-title">
                        <i class="fas fa-archive text-secondary me-2"></i>
                        Lista de Estudantes Arquivados
                        <span class="badge bg-secondary ms-2" style="font-size: 0.55em;">{{ $students->total() }}</span>
                    </h3>
                </div>

                <div class="table-responsive">
                    <table class="table table-school">
                        <thead>
                            <tr>
                                <th>Estudante</th>
                                <th>Contacto</th>
                                <th>Status</th>
                                <th>Última Matrícula</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-secondary-subtle text-secondary fw-bold me-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; font-size: 13px;">
                                                {{ strtoupper(substr($student->first_name, 0, 1) . substr($student->last_name ?? '', 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-slate-800">{{ $student->full_name }}</div>
                                                <small class="text-muted"><code>{{ $student->student_number ?? 'N/A' }}</code></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted"><i class="fas fa-phone me-1"></i> {{ $student->emergency_phone ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $statusSubtle = [
                                                'transferred' => 'bg-info-subtle text-info border border-info-subtle',
                                                'graduated' => 'bg-success-subtle text-success border border-success-subtle',
                                                'inactive' => 'bg-secondary-subtle text-secondary border'
                                            ];
                                            $statusLabels = [
                                                'transferred' => 'Transferido',
                                                'graduated' => 'Formado',
                                                'inactive' => 'Desistente'
                                            ];
                                        @endphp
                                        <span class="badge rounded-pill px-3 py-1 {{ $statusSubtle[$student->status] ?? 'bg-secondary-subtle text-secondary' }}">
                                            {{ $statusLabels[$student->status] ?? ucfirst($student->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $lastEnrollment = $student->enrollments->last();
                                        @endphp
                                        @if($lastEnrollment)
                                            <div class="fw-semibold text-slate-800">{{ $lastEnrollment->class->name ?? 'N/A' }}</div>
                                            <small class="text-muted">Ano {{ $lastEnrollment->school_year }}</small>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.students-archive.show', $student) }}"
                                                class="btn btn-outline-primary" title="Ver Detalhes">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-success"
                                                data-bs-toggle="modal"
                                                data-bs-target="#reactivateModal{{ $student->id }}"
                                                title="Reativar Estudante">
                                                <i class="fas fa-redo"></i>
                                            </button>
                                        </div>

                                        <!-- Modal de Reativação -->
                                        <div class="modal fade" id="reactivateModal{{ $student->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden text-start">
                                                    <form action="{{ route('admin.students-archive.reactivate', $student) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header bg-light py-3 border-bottom">
                                                            <h5 class="modal-title fw-bold text-slate-800">
                                                                <i class="fas fa-redo me-2 text-success"></i>
                                                                Reativar: {{ $student->full_name }}
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body p-4">
                                                            <div class="alert alert-warning border-0 rounded-xl mb-3">
                                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                                O aluno será reativado no ano letivo <strong>{{ current_school_year() }}</strong>.
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label text-xs font-bold text-slate-700 uppercase tracking-wider">Nova Turma *</label>
                                                                <select name="class_id" class="form-select rounded-xl" required>
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
                                                                    <label class="form-label text-xs font-bold text-slate-700 uppercase tracking-wider">Mensalidade (MT) *</label>
                                                                    <input type="number" name="monthly_fee" class="form-control rounded-xl"
                                                                        value="{{ $student->monthly_fee ?? 0 }}" step="0.01" required>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label text-xs font-bold text-slate-700 uppercase tracking-wider">Observações</label>
                                                                    <input type="text" name="reason" class="form-control rounded-xl"
                                                                        placeholder="Motivo da reativação">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer bg-light py-2 px-4">
                                                            <button type="button" class="btn btn-outline-secondary btn-sm rounded-xl" data-bs-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-success btn-sm rounded-xl font-semibold">
                                                                <i class="fas fa-check me-1"></i> Confirmar Reativação
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

                @if($students->hasPages())
                    <div class="p-3 border-top">
                        {{ $students->links() }}
                    </div>
                @endif
            </div>
        @endif

    </div>
</div>
@endsection