@extends('layouts.app')

@section('title', 'Caderno de Notas')
@section('page-title', 'Caderno de Notas')
@section('page-title-icon', 'fas fa-tasks')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('teacher-portal.dashboard') }}">Portal</a></li>
    <li class="breadcrumb-item"><a href="{{ route('teacher-portal.classes') }}">Minhas Turmas</a></li>
    <li class="breadcrumb-item"><a href="{{ route('teacher-portal.class-detail', $class->id) }}">{{ $class->name }}</a></li>
    <li class="breadcrumb-item active">Notas</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Cabeçalho -->
        <div class="school-card mb-4">
            <div class="school-card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h3>Caderno de Notas</h3>
                        <p class="text-muted mb-0">
                            Turma: <strong>{{ $class->name }}</strong> | 
                            {{ $class->grade_level_name }} | 
                            {{ $class->students_count }} alunos
                        </p>
                    </div>
                    <div class="col-md-6">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <select name="subject_id" class="form-select" onchange="updateSubject(this.value)">
                                    <option value="">Selecionar Disciplina</option>
                                    @foreach($class->subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ $selectedSubject == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <select name="term" class="form-select" onchange="updateTerm(this.value)">
                                    <option value="1" {{ $selectedTerm == 1 ? 'selected' : '' }}>1º Trimestre</option>
                                    <option value="2" {{ $selectedTerm == 2 ? 'selected' : '' }}>2º Trimestre</option>
                                    <option value="3" {{ $selectedTerm == 3 ? 'selected' : '' }}>3º Trimestre</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($selectedSubject)
            <!-- Formulário de Notas -->
            <div class="school-card">
                <div class="school-card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-book"></i>
                        Lançamento de Notas - {{ $class->subjects->find($selectedSubject)->name }}
                    </div>
                    <button type="button" class="btn btn-primary-school btn-sm" data-bs-toggle="modal" data-bs-target="#addGradeModal">
                        <i class="fas fa-plus"></i> Nova Nota
                    </button>
                </div>
                <div class="school-card-body">
                    @if($class->students->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-grades">
                                <thead>
                                    <tr>
                                        <th width="30%">Aluno</th>
                                        <th width="15%">Testes</th>
                                        <th width="15%">Trabalhos</th>
                                        <th width="15%">Projetos</th>
                                        <th width="15%">Participação</th>
                                        <th width="10%">Média</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($class->students as $student)
                                        @php
                                            $studentGrades = $grades[$student->id] ?? [];
                                            $testGrade = $studentGrades['test']->first()->grade ?? null;
                                            $homeworkGrade = $studentGrades['homework']->first()->grade ?? null;
                                            $projectGrade = $studentGrades['project']->first()->grade ?? null;
                                            $participationGrade = $studentGrades['participation']->first()->grade ?? null;
                                            
                                            // Calcular média (pesos: teste 40%, trabalho 25%, projeto 25%, participação 10%)
                                            $average = null;
                                            if ($testGrade || $homeworkGrade || $projectGrade || $participationGrade) {
                                                $sum = 0;
                                                $weights = 0;
                                                
                                                if ($testGrade) { $sum += $testGrade * 0.4; $weights += 0.4; }
                                                if ($homeworkGrade) { $sum += $homeworkGrade * 0.25; $weights += 0.25; }
                                                if ($projectGrade) { $sum += $projectGrade * 0.25; $weights += 0.25; }
                                                if ($participationGrade) { $sum += $participationGrade * 0.1; $weights += 0.1; }
                                                
                                                $average = $weights > 0 ? round($sum / $weights, 1) : null;
                                            }
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="user-avatar-sm me-3">
                                                        {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <strong>{{ $student->first_name }} {{ $student->last_name }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $student->student_number }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="grade-badge {{ $testGrade ? 'has-grade' : 'no-grade' }}" 
                                                      onclick="editGrade({{ $student->id }}, 'test', {{ $testGrade }})">
                                                    {{ $testGrade ?? '-' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="grade-badge {{ $homeworkGrade ? 'has-grade' : 'no-grade' }}"
                                                      onclick="editGrade({{ $student->id }}, 'homework', {{ $homeworkGrade }})">
                                                    {{ $homeworkGrade ?? '-' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="grade-badge {{ $projectGrade ? 'has-grade' : 'no-grade' }}"
                                                      onclick="editGrade({{ $student->id }}, 'project', {{ $projectGrade }})">
                                                    {{ $projectGrade ?? '-' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="grade-badge {{ $participationGrade ? 'has-grade' : 'no-grade' }}"
                                                      onclick="editGrade({{ $student->id }}, 'participation', {{ $participationGrade }})">
                                                    {{ $participationGrade ?? '-' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($average)
                                                    <span class="average-badge grade-{{ $average >= 10 ? 'success' : 'danger' }}">
                                                        {{ $average }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-4">
                            <i class="fas fa-user-graduate fa-2x mb-2"></i><br>
                            Nenhum aluno matriculado nesta turma
                        </p>
                    @endif
                </div>
            </div>

            <!-- Resumo das Notas -->
            <div class="school-card mt-4">
                <div class="school-card-header">
                    <i class="fas fa-chart-bar"></i>
                    Estatísticas das Notas
                </div>
                <div class="school-card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="grade-stat">
                                <div class="stat-number text-primary" id="total-grades">0</div>
                                <div class="stat-label">Notas Lançadas</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="grade-stat">
                                <div class="stat-number text-success" id="approved-count">0</div>
                                <div class="stat-label">Aprovados</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="grade-stat">
                                <div class="stat-number text-warning" id="recovery-count">0</div>
                                <div class="stat-label">Recuperação</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="grade-stat">
                                <div class="stat-number text-danger" id="failed-count">0</div>
                                <div class="stat-label">Reprovados</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="school-card">
                <div class="school-card-body text-center py-5">
                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">Selecione uma Disciplina</h4>
                    <p class="text-muted">Escolha uma disciplina para visualizar e lançar notas.</p>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal para Adicionar/Editar Nota -->
<div class="modal fade" id="addGradeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gradeModalTitle">Lançar Nova Nota</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('teacher-portal.store-grade') }}" method="POST" id="grade-form">
                @csrf
                <input type="hidden" name="class_id" value="{{ $class->id }}">
                <input type="hidden" name="subject_id" value="{{ $selectedSubject }}">
                <input type="hidden" name="term" value="{{ $selectedTerm }}">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Aluno</label>
                        <select name="student_id" class="form-select" required id="student-select">
                            <option value="">Selecione o aluno</option>
                            @foreach($class->students as $student)
                                <option value="{{ $student->id }}">{{ $student->first_name }} {{ $student->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipo de Avaliação</label>
                        <select name="assessment_type" class="form-select" required id="assessment-type">
                            <option value="test">Teste</option>
                            <option value="homework">Trabalho de Casa</option>
                            <option value="project">Projeto</option>
                            <option value="participation">Participação</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nota (0-20)</label>
                        <input type="number" name="grade" class="form-control" min="0" max="20" step="0.1" required id="grade-input">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Comentários (Opcional)</label>
                        <textarea name="comments" class="form-control" rows="3" id="grade-comments"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary-school">Salvar Nota</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.table-grades th {
    background: var(--content-bg);
    font-weight: 600;
    text-align: center;
}

.grade-badge {
    display: inline-block;
    padding: 8px 12px;
    border-radius: 6px;
    font-weight: 600;
    text-align: center;
    min-width: 50px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.grade-badge.has-grade {
    background: rgba(var(--success-rgb), 0.1);
    border: 2px solid var(--success);
    color: var(--success);
}

.grade-badge.no-grade {
    background: rgba(var(--secondary-rgb), 0.1);
    border: 2px dashed var(--border-color);
    color: var(--text-muted);
}

.grade-badge:hover {
    transform: scale(1.05);
}

.average-badge {
    display: inline-block;
    padding: 6px 10px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 14px;
}

.grade-success {
    background: var(--success);
    color: white;
}

.grade-danger {
    background: var(--danger);
    color: white;
}

.grade-stat {
    padding: 15px;
}

.grade-stat .stat-number {
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 5px;
}

.grade-stat .stat-label {
    font-size: 12px;
    color: var(--text-muted);
    text-transform: uppercase;
    font-weight: 600;
}

.user-avatar-sm {
    width: 35px;
    height: 35px;
    background: var(--accent);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 12px;
    font-weight: 600;
}
</style>

<script>
function updateSubject(subjectId) {
    const url = new URL(window.location.href);
    url.searchParams.set('subject_id', subjectId);
    window.location.href = url.toString();
}

function updateTerm(term) {
    const url = new URL(window.location.href);
    url.searchParams.set('term', term);
    window.location.href = url.toString();
}

function editGrade(studentId, assessmentType, currentGrade) {
    document.getElementById('student-select').value = studentId;
    document.getElementById('assessment-type').value = assessmentType;
    document.getElementById('grade-input').value = currentGrade || '';
    document.getElementById('grade-comments').value = '';
    document.getElementById('gradeModalTitle').textContent = currentGrade ? 'Editar Nota' : 'Lançar Nova Nota';
    
    const modal = new bootstrap.Modal(document.getElementById('addGradeModal'));
    modal.show();
}

// Calcular estatísticas
document.addEventListener('DOMContentLoaded', function() {
    function calculateStatistics() {
        let totalGrades = 0;
        let approved = 0;
        let recovery = 0;
        let failed = 0;

        document.querySelectorAll('.average-badge').forEach(badge => {
            const grade = parseFloat(badge.textContent);
            if (!isNaN(grade)) {
                totalGrades++;
                if (grade >= 10) approved++;
                else if (grade >= 8) recovery++;
                else failed++;
            }
        });

        document.getElementById('total-grades').textContent = totalGrades;
        document.getElementById('approved-count').textContent = approved;
        document.getElementById('recovery-count').textContent = recovery;
        document.getElementById('failed-count').textContent = failed;
    }

    calculateStatistics();
});
</script>
@endsection