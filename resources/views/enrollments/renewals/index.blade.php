@extends('layouts.app')

@section('title', 'Renovação de Matrículas')
@section('page-title', 'Renovação de Matrículas')
@section('page-title-icon', 'fas fa-sync-alt')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('enrollments.index') }}">Matrículas</a></li>
    <li class="breadcrumb-item active">Renovações</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Estatísticas -->
            <div class="school-stats mb-4">
                <div class="stat-card pending">
                    <div class="stat-icon pending">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $students->total() }}</div>
                        <div class="stat-label">Alunos Pendentes</div>
                    </div>
                </div>
                <div class="stat-card success">
                    <div class="stat-icon success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ \App\Models\Enrollment::where('school_year', current_school_year())->where('status', 'active')->count() }}</div>
                        <div class="stat-label">Matriculados {{ current_school_year() }}</div>
                    </div>
                </div>
                <div class="stat-card info">
                    <div class="stat-icon info">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ \App\Models\ClassRoom::where('school_year', current_school_year())->where('is_active', true)->count() }}</div>
                        <div class="stat-label">Turmas Ativas</div>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="school-card mb-4">
                <div class="school-card-body">
                     <form action="{{ route('admin.enrollments.renewals') }}" method="GET" class="row g-3">
                        <div class="col-md-8">
                            <input type="text" name="search" class="form-control"
                                placeholder="Pesquisar por nome ou nº de estudante..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary-school w-100">
                                <i class="fas fa-search"></i> Filtrar
                            </button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('admin.students-archive.index') }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-archive"></i> Arquivados
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            @if($students->isEmpty())
                <div class="school-card">
                    <div class="school-card-body text-center py-5">
                        <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                        <h4>Todos os alunos renovada!</h4>
                        <p class="text-muted">Não há alunos pendentes de renovação para o ano letivo {{ current_school_year() }}.</p>
                    </div>
                </div>
            @else
                <div class="school-card">
                    <div class="school-card-header">
                        <h3 class="school-card-title">
                            <i class="fas fa-user-clock me-2"></i> Alunos Aguardando Renovação
                        </h3>
                        <span class="badge bg-warning">{{ $students->total() }}</span>
                    </div>
                    <div class="school-card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-school table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Estudante</th>
                                        <th>Dados do Encartado</th>
                                        <th>Mensalidade Anterior</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $student)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="user-avatar me-3" style="width: 45px; height: 45px; font-size: 14px;">
                                                        {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold">{{ $student->full_name }}</div>
                                                        <small class="text-muted">
                                                            <i class="fas fa-id-card me-1"></i>{{ $student->student_number ?? 'N/A' }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div><strong>Tel:</strong> {{ $student->emergency_phone ?? 'N/A' }}</div>
                                                <div><strong>Endereço:</strong> {{ Str::limit($student->address, 30) }}</div>
                                            </td>
                                            <td>
                                                <span class="fw-bold">{{ number_format($student->monthly_fee ?? 0, 2, ',', '.') }} MZN</span>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                                    data-bs-target="#renewModal{{ $student->id }}">
                                                    <i class="fas fa-sync me-1"></i> Renovar Matrícula
                                                </button>
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

                <!-- Modais de Renovação (fora da tabela para evitar problemas de z-index) -->
                @foreach($students as $student)
                    <div class="modal fade" id="renewModal{{ $student->id }}" tabindex="-1"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('admin.enrollments.renew', $student) }}"
                                    method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            <i class="fas fa-sync me-2"></i>
                                            Renovar Matrícula: {{ $student->full_name }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-start">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Ano Letivo: <strong>{{ current_school_year() }}</strong>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Nova Turma *</label>
                                            <select name="class_id" class="form-select" required>
                                                <option value="">Selecione a turma...</option>
                                                @foreach($classes as $class)
                                                    <option value="{{ $class->id }}">
                                                        {{ $class->name }} ({{ $class->grade_level_name }})
                                                        - {{ $class->students_count ?? 0 }} alunos
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Nova Mensalidade (MZN) *</label>
                                                <input type="number" name="monthly_fee" class="form-control"
                                                    value="{{ $student->monthly_fee ?? 0 }}" step="0.01" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Dia de Pagamento</label>
                                                <input type="number" name="payment_day" class="form-control"
                                                    value="5" min="1" max="28">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Taxa de Renovação (MZN)</label>
                                            <input type="number" name="enrollment_fee"
                                                class="form-control" value="0" step="0.01">
                                            <small class="text-muted">
                                                Deixe 0 se não houver taxa. 
                                                Se houver taxa, a matrícula ficará pendente até pagamento.
                                            </small>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-check me-1"></i>
                                            Confirmar Renovação
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection