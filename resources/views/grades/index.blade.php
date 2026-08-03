@extends('layouts.app')

@section('title', 'Gestão de Notas & Pauta da Turma')
@section('page-title', 'Gestão de Notas')
@section('title-icon', 'fas fa-medal')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Gestão de Notas</li>
@endsection

@section('content')
<div class="container-fluid p-0">
    <!-- Stat Cards Uniformizados -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-primary">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-primary-subtle text-primary rounded-circle p-3 me-3">
                        <i class="fas fa-medal fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase font-weight-bold">Notas Registadas</div>
                        <h4 class="mb-0 text-primary font-weight-bold">{{ \App\Models\Grade::where('year', $currentYear)->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-success">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-success-subtle text-success rounded-circle p-3 me-3">
                        <i class="fas fa-chart-line fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase font-weight-bold">Média Geral</div>
                        <h4 class="mb-0 text-success font-weight-bold">{{ number_format(\App\Models\Grade::where('year', $currentYear)->avg('grade') ?? 0, 1) }} / 20</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-info">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-info-subtle text-info rounded-circle p-3 me-3">
                        <i class="fas fa-chalkboard fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase font-weight-bold">Turmas Ativas</div>
                        <h4 class="mb-0 text-info font-weight-bold">{{ count($classes) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-warning">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-warning-subtle text-warning rounded-circle p-3 me-3">
                        <i class="fas fa-user-graduate fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase font-weight-bold">Alunos na Turma</div>
                        <h4 class="mb-0 text-dark font-weight-bold">{{ count($students) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card de Filtros e Seleção da Turma -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <form method="GET" action="{{ route('grades.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label text-xs font-bold text-muted uppercase">Nível de Ensino</label>
                    <select name="level" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="all" {{ $level === 'all' ? 'selected' : '' }}>Todos os Níveis</option>
                        <option value="preschool" {{ $level === 'preschool' ? 'selected' : '' }}>Pré-Escolar & Infantil (Pré-Infantil & Creche)</option>
                        <option value="primary" {{ $level === 'primary' ? 'selected' : '' }}>Ensino Primário (1ª - 6ª Classe)</option>
                        <option value="secondary" {{ $level === 'secondary' ? 'selected' : '' }}>Ensino Secundário (7ª - 12ª Classe)</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-xs font-bold text-muted uppercase">Turma</label>
                    <select name="class_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        @forelse($classes as $c)
                            <option value="{{ $c->id }}" {{ $selectedClass?->id == $c->id ? 'selected' : '' }}>
                                {{ $c->name }} ({{ $c->grade_level_name }} - {{ $c->education_level_name }})
                            </option>
                        @empty
                            <option value="">Nenhuma turma encontrada</option>
                        @endforelse
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label text-xs font-bold text-muted uppercase">Trimestre</label>
                    <select name="term" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="1" {{ $term == 1 ? 'selected' : '' }}>1º Trimestre</option>
                        <option value="2" {{ $term == 2 ? 'selected' : '' }}>2º Trimestre</option>
                        <option value="3" {{ $term == 3 ? 'selected' : '' }}>3º Trimestre</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    @if($selectedClass)
                        <a href="{{ route('pautas.trimestral', $selectedClass->id) }}" class="btn btn-outline-primary btn-sm w-100">
                            <i class="fas fa-file-pdf me-1"></i> Ver Pauta Completa
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Pauta Editável e Lançamento de Notas -->
    @if($selectedClass)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="mb-0 font-weight-bold text-dark">
                        <i class="fas fa-chalkboard-teacher text-success me-2"></i> Pauta de Notas: {{ $selectedClass->name }}
                    </h5>
                    <p class="text-muted small mb-0">{{ $selectedClass->education_level_name }} • {{ $selectedClass->grade_level_name }} • {{ $term }}º Trimestre</p>
                </div>

                @can('create_grades')
                    <a href="{{ route('grades.batch-create', ['class_id' => $selectedClass->id, 'term' => $term]) }}" class="btn btn-success font-weight-bold text-nowrap shadow-sm">
                        <i class="fas fa-edit me-1"></i> Lançar / Editar Notas da Turma
                    </a>
                @endcan
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0 text-center" style="font-size: 0.88rem;">
                        <thead class="table-dark">
                            <tr>
                                <th rowspan="2" class="align-middle text-start" style="min-width: 240px;">Nome do Aluno</th>
                                @foreach($subjects as $sub)
                                    <th colspan="3" class="text-center border-bottom-0">{{ $sub->code ?? substr($sub->name, 0, 8) }}</th>
                                @endforeach
                                <th rowspan="2" class="align-middle bg-primary text-white">Média Geral</th>
                            </tr>
                            <tr>
                                @foreach($subjects as $sub)
                                    <th class="small fw-normal bg-secondary">ACS</th>
                                    <th class="small fw-normal bg-secondary">ACP</th>
                                    <th class="small fw-bold bg-dark text-warning">MT</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                                @php
                                    $studentGrades = $matrixGrades[$student->id] ?? [];
                                    $allMts = [];
                                @endphp
                                <tr>
                                    <td class="text-start font-weight-bold text-dark">
                                        <a href="{{ route('students.show', $student->id) }}" class="text-dark text-decoration-none hover-underline">
                                            {{ $student->first_name }} {{ $student->last_name }}
                                        </a>
                                        <div class="text-muted small" style="font-size: 0.75rem;">N.º {{ $student->student_number }}</div>
                                    </td>

                                    @foreach($subjects as $sub)
                                        @php
                                            $subGrades = $studentGrades[$sub->id] ?? [];
                                            $acs1 = $subGrades['ACS1'] ?? $subGrades['test'] ?? null;
                                            $acs2 = $subGrades['ACS2'] ?? null;
                                            $acsArray = array_filter([$acs1, $acs2], fn($v) => $v !== null);
                                            $acsAvg = !empty($acsArray) ? array_sum($acsArray) / count($acsArray) : null;
                                            
                                            $acp = $subGrades['ACP'] ?? $subGrades['exam'] ?? null;

                                            if ($acsAvg !== null && $acp !== null) {
                                                $mt = round(($acsAvg * 0.4) + ($acp * 0.6), 1);
                                            } elseif ($acsAvg !== null) {
                                                $mt = round($acsAvg, 1);
                                            } else {
                                                $mt = null;
                                            }

                                            if ($mt !== null) {
                                                $allMts[] = $mt;
                                            }
                                        @endphp
                                        <td class="text-muted">{{ $acsAvg !== null ? number_format($acsAvg, 1) : '-' }}</td>
                                        <td class="text-muted">{{ $acp !== null ? number_format($acp, 1) : '-' }}</td>
                                        <td class="fw-bold {{ $mt !== null ? ($mt >= 10 ? 'text-success bg-success-subtle' : 'text-danger bg-danger-subtle') : '' }}">
                                            {{ $mt !== null ? number_format($mt, 1) : '-' }}
                                        </td>
                                    @endforeach

                                    <td class="fw-bold bg-light text-primary">
                                        @php
                                            $overall = !empty($allMts) ? round(array_sum($allMts) / count($allMts), 1) : 0;
                                        @endphp
                                        {{ $overall > 0 ? number_format($overall, 1) : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($subjects) * 3 + 2 }}" class="py-5 text-center text-muted">
                                        <i class="fas fa-users-slash fa-3x mb-3 text-secondary"></i>
                                        <p class="mb-0">Nenhum aluno matriculado nesta turma.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm py-5 text-center text-muted">
            <i class="fas fa-chalkboard fa-4x text-secondary mb-3"></i>
            <h5>Selecione uma turma para visualizar a pauta e lançar notas</h5>
        </div>
    @endif
</div>
@endsection