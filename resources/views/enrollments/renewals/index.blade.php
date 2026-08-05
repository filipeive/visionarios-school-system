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

        <!-- Stat KPI Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-warning">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-warning-subtle text-warning rounded-circle p-3 me-3">
                            <i class="fas fa-clock fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Alunos Pendentes</div>
                            <h4 class="mb-0 text-warning font-weight-bold">{{ $students->total() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-success">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-success-subtle text-success rounded-circle p-3 me-3">
                            <i class="fas fa-check-circle fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Matriculados {{ current_school_year() }}</div>
                            <h4 class="mb-0 text-success font-weight-bold">{{ \App\Models\Enrollment::where('school_year', current_school_year())->where('status', 'active')->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-info">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-info-subtle text-info rounded-circle p-3 me-3">
                            <i class="fas fa-graduation-cap fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Turmas Ativas {{ current_school_year() }}</div>
                            <h4 class="mb-0 text-info font-weight-bold">{{ \App\Models\ClassRoom::where('school_year', current_school_year())->where('is_active', true)->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros Harmonizados -->
        <div class="school-card mb-4">
            <div class="school-card-body">
                <form action="{{ route('admin.enrollments.renewals') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-8">
                        <label class="form-label text-xs font-bold text-slate-700 uppercase tracking-wider">Pesquisar Estudante</label>
                        <input type="text" name="search" class="form-control rounded-xl border-slate-200"
                            placeholder="Pesquisar por nome ou nº de estudante..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary-school w-100 rounded-xl">
                            <i class="fas fa-filter me-1"></i> Filtrar
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('admin.students-archive.index') }}" class="btn btn-outline-secondary w-100 rounded-xl">
                            <i class="fas fa-archive me-1"></i> Arquivados
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabela de Alunos Aguardando Renovação -->
        @if($students->isEmpty())
            <div class="school-card">
                <div class="school-card-body text-center py-5">
                    <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                    <h4 class="text-success">Todas as renovações concluídas!</h4>
                    <p class="text-muted text-xs">Não há alunos pendentes de renovação para o ano letivo {{ current_school_year() }}.</p>
                </div>
            </div>
        @else
            <div class="school-table-container">
                <div class="school-table-header">
                    <h3 class="school-table-title">
                        <i class="fas fa-user-clock text-warning me-2"></i>
                        Alunos Aguardando Renovação
                        <span class="badge bg-warning text-dark ms-2" style="font-size: 0.55em;">{{ $students->total() }}</span>
                    </h3>
                </div>

                <div class="table-responsive">
                    <table class="table table-school">
                        <thead>
                            <tr>
                                <th>Estudante</th>
                                <th>Contacto / Endereço</th>
                                <th>Mensalidade Anterior</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary-subtle text-primary fw-bold me-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; font-size: 13px;">
                                                {{ strtoupper(substr($student->first_name, 0, 1) . substr($student->last_name ?? '', 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-slate-800">{{ $student->full_name }}</div>
                                                <small class="text-muted"><code>{{ $student->student_number ?? 'N/A' }}</code></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-slate-800"><i class="fas fa-phone me-1 text-muted"></i> {{ $student->emergency_phone ?? 'N/A' }}</div>
                                        <small class="text-muted"><i class="fas fa-location-dot me-1"></i> {{ Str::limit($student->address, 30) }}</small>
                                    </td>
                                    <td>
                                        <strong class="text-slate-900 fs-6">{{ number_format($student->monthly_fee ?? 0, 2, ',', '.') }} MT</strong>
                                    </td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-success btn-sm rounded-xl font-semibold" data-bs-toggle="modal"
                                            data-bs-target="#renewModal{{ $student->id }}">
                                            <i class="fas fa-sync me-1"></i> Renovar Matrícula
                                        </button>
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

            <!-- Modais de Renovação -->
            @foreach($students as $student)
                <div class="modal fade" id="renewModal{{ $student->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                            <form action="{{ route('admin.enrollments.renew', $student) }}" method="POST">
                                @csrf
                                <div class="modal-header bg-light py-3 border-bottom">
                                    <h5 class="modal-title fw-bold text-slate-800">
                                        <i class="fas fa-sync text-primary me-2"></i>
                                        Renovar Matrícula: {{ $student->full_name }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4 text-start">
                                    <div class="alert alert-info border-0 rounded-xl mb-3">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Ano Letivo de Destino: <strong>{{ current_school_year() }}</strong>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-xs font-bold text-slate-700 uppercase tracking-wider">Nova Turma *</label>
                                        <select name="class_id" class="form-select rounded-xl" required>
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
                                            <label class="form-label text-xs font-bold text-slate-700 uppercase tracking-wider">Nova Mensalidade (MT) *</label>
                                            <input type="number" name="monthly_fee" class="form-control rounded-xl"
                                                value="{{ $student->monthly_fee ?? 0 }}" step="0.01" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-xs font-bold text-slate-700 uppercase tracking-wider">Dia de Pagamento</label>
                                            <input type="number" name="payment_day" class="form-control rounded-xl"
                                                value="5" min="1" max="28">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-xs font-bold text-slate-700 uppercase tracking-wider">Taxa de Renovação (MT)</label>
                                        <input type="number" name="enrollment_fee"
                                            class="form-control rounded-xl" value="0" step="0.01">
                                        <small class="text-muted">
                                            Deixe 0 se não houver taxa de renovação.
                                        </small>
                                    </div>
                                </div>
                                <div class="modal-footer bg-light py-2 px-4">
                                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-xl" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-success btn-sm rounded-xl font-semibold">
                                        <i class="fas fa-check me-1"></i> Confirmar Renovação
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