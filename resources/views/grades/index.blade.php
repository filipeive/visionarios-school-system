{{-- resources/views/grades/index.blade.php --}}

@extends('layouts.school')

@section('title', 'Gestão de Notas')
@section('page-title', 'Gestão de Notas')
@section('title-icon', 'fas fa-medal')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Notas e Avaliações</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Estatísticas Rápidas -->
        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-icon primary">
                    <i class="fas fa-medal"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ \App\Models\Grade::currentYear()->count() }}</div>
                    <div class="stat-label">Notas Registradas</div>
                    <span class="stat-change positive">
                        <i class="fas fa-calendar"></i> Este ano
                    </span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon success">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ number_format(\App\Models\Grade::currentYear()->avg('grade') ?? 0, 1) }}</div>
                    <div class="stat-label">Média Geral</div>
                    <span class="stat-change positive">
                        <i class="fas fa-trend-up"></i> Desempenho
                    </span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon info">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ \App\Models\Subject::active()->count() }}</div>
                    <div class="stat-label">Disciplinas</div>
                    <span class="stat-change positive">
                        <i class="fas fa-graduation-cap"></i> Ativas
                    </span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon warning">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ \App\Models\Student::active()->count() }}</div>
                    <div class="stat-label">Alunos Ativos</div>
                    <span class="stat-change positive">
                        <i class="fas fa-users"></i> Com notas
                    </span>
                </div>
            </div>
        </div>

        <!-- Card Principal -->
        <div class="vision-card">
            <div class="vision-card-header d-flex align-items-center justify-content-between">
                <h3 class="vision-card-title">
                    <i class="fas fa-medal"></i>
                    Lista de Notas
                </h3>
                <div class="d-flex gap-2">
                    @can('create_grades')
                    <a href="{{ route('grades.batch-create') }}" class="btn-vision btn-vision-warning">
                        <i class="fas fa-layer-group"></i> Notas em Lote
                    </a>
                    <a href="{{ route('grades.create') }}" class="btn-vision btn-vision-primary">
                        <i class="fas fa-plus"></i> Nova Nota
                    </a>
                    @endcan
                </div>
            </div>

            <div class="vision-card-body">
                <!-- Filtros -->
                <form method="GET" class="row g-3 mb-4">
                    <div class="col-md-2">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Aluno..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="subject_id" class="form-select">
                            <option value="">Todas Disciplinas</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="class_id" class="form-select">
                            <option value="">Todas Turmas</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="term" class="form-select">
                            <option value="">Todos Trimestres</option>
                            <option value="1" {{ request('term') == '1' ? 'selected' : '' }}>1º Trimestre</option>
                            <option value="2" {{ request('term') == '2' ? 'selected' : '' }}>2º Trimestre</option>
                            <option value="3" {{ request('term') == '3' ? 'selected' : '' }}>3º Trimestre</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="assessment_type" class="form-select">
                            <option value="">Todos Tipos</option>
                            <option value="test" {{ request('assessment_type') == 'test' ? 'selected' : '' }}>Teste</option>
                            <option value="assignment" {{ request('assessment_type') == 'assignment' ? 'selected' : '' }}>Trabalho</option>
                            <option value="exam" {{ request('assessment_type') == 'exam' ? 'selected' : '' }}>Exame</option>
                            <option value="project" {{ request('assessment_type') == 'project' ? 'selected' : '' }}>Projeto</option>
                            <option value="participation" {{ request('assessment_type') == 'participation' ? 'selected' : '' }}>Participação</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn-vision btn-vision-primary w-100">
                            <i class="fas fa-filter"></i> Filtrar
                        </button>
                    </div>
                </form>

                <!-- Tabela de Notas -->
                <div class="table-responsive">
                    <table class="table table-vision table-hover">
                        <thead>
                            <tr>
                                <th>Aluno</th>
                                <th>Disciplina</th>
                                <th>Nota</th>
                                <th>Tipo</th>
                                <th>Trimestre</th>
                                <th>Ano</th>
                                <th>Data</th>
                                <th>Professor</th>
                                <th width="120">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($grades as $grade)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="student-avatar me-3">
                                                @if($grade->student->passport_photo)
                                                    <img src="{{ Storage::url($grade->student->passport_photo) }}" 
                                                         alt="{{ $grade->student->full_name }}" 
                                                         class="rounded-circle" 
                                                         style="width: 35px; height: 35px; object-fit: cover;">
                                                @else
                                                    <div class="user-avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                                         style="width: 35px; height: 35px; font-size: 12px;">
                                                        {{ substr($grade->student->first_name, 0, 1) }}{{ substr($grade->student->last_name, 0, 1) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <strong>{{ $grade->student->first_name }} {{ $grade->student->last_name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $grade->student->student_number }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-vision" style="background: var(--primary-ocean); color: white;">
                                            {{ $grade->subject->name }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <strong class="me-2 {{ $grade->grade >= 10 ? 'text-success' : 'text-danger' }}">
                                                {{ number_format($grade->grade, 1) }}
                                            </strong>
                                            <span class="badge-vision {{ $grade->grade >= 10 ? 'bg-success' : 'bg-danger' }}">
                                                {{ $grade->grade_status }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        @switch($grade->assessment_type)
                                            @case('test')
                                                <span class="badge-vision bg-info">Teste</span>
                                                @break
                                            @case('assignment')
                                                <span class="badge-vision bg-warning text-dark">Trabalho</span>
                                                @break
                                            @case('exam')
                                                <span class="badge-vision bg-danger">Exame</span>
                                                @break
                                            @case('project')
                                                <span class="badge-vision bg-purple">Projeto</span>
                                                @break
                                            @case('participation')
                                                <span class="badge-vision bg-secondary">Participação</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <strong>{{ $grade->term }}º Trimestre</strong>
                                    </td>
                                    <td>
                                        <code>{{ $grade->year }}</code>
                                    </td>
                                    <td>
                                        <small>{{ $grade->date_recorded->format('d/m/Y') }}</small>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $grade->teacher->first_name ?? 'N/A' }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            @can('edit_grades')
                                            <a href="{{ route('grades.edit', $grade) }}" 
                                               class="btn btn-outline-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endcan
                                            @can('delete_grades')
                                            <button type="button" class="btn btn-outline-danger" 
                                                    title="Excluir" 
                                                    onclick="confirmDelete({{ $grade->id }}, 'Nota de {{ $grade->student->first_name }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-medal fa-3x mb-3"></i>
                                            <h5>Nenhuma nota encontrada</h5>
                                            <p class="mb-3">Não foram encontradas notas com os filtros aplicados.</p>
                                            @can('create_grades')
                                            <a href="{{ route('grades.create') }}" class="btn-vision btn-vision-primary">
                                                <i class="fas fa-plus me-2"></i> Cadastrar Primeira Nota
                                            </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                @if($grades->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Mostrando {{ $grades->firstItem() }} a {{ $grades->lastItem() }} de {{ $grades->total() }} notas
                    </div>
                    <nav>
                        {{ $grades->links() }}
                    </nav>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Exclusão -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir a nota de <strong id="gradeName"></strong>?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <small>Esta ação não pode ser desfeita.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-vision btn-vision-danger">Excluir Nota</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(gradeId, gradeName) {
    document.getElementById('gradeName').textContent = gradeName;
    document.getElementById('deleteForm').action = `/grades/${gradeId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush