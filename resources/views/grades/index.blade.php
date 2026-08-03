{{-- resources/views/grades/index.blade.php --}}

@extends('layouts.app')

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
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-primary">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-primary-subtle text-primary rounded-circle p-3 me-3">
                            <i class="fas fa-medal fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Notas Registadas</div>
                            <h4 class="mb-0 text-primary font-weight-bold">{{ \App\Models\Grade::currentYear()->count() }}</h4>
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
                            <h4 class="mb-0 text-success font-weight-bold">{{ number_format(\App\Models\Grade::currentYear()->avg('grade') ?? 0, 1) }} / 20</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-info">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-info-subtle text-info rounded-circle p-3 me-3">
                            <i class="fas fa-book fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Disciplinas</div>
                            <h4 class="mb-0 text-info font-weight-bold">{{ \App\Models\Subject::active()->count() }}</h4>
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
                            <div class="text-muted small text-uppercase font-weight-bold">Alunos Ativos</div>
                            <h4 class="mb-0 text-dark font-weight-bold">{{ \App\Models\Student::active()->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Principal -->
        <div class="school-card">
            <div class="school-card-header d-flex align-items-center justify-content-between">
                <h3 class="school-card-title">
                    <i class="fas fa-medal"></i>
                    Lista de Notas
                </h3>
                <div class="d-flex gap-2 flex-wrap">
                    @can('create_grades')
                    <a href="{{ route('grades.batch-create') }}" class="btn btn-school btn-warning-school">
                        <i class="fas fa-layer-group"></i> Lançar Notas em Lote
                    </a>
                    <a href="{{ route('grades.create') }}" class="btn btn-school btn-primary-school">
                        <i class="fas fa-plus"></i> Nova Nota
                    </a>
                    @endcan
                    @if(count($classes) > 0)
                        <div class="dropdown">
                            <button class="btn btn-school btn-secondary-school dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-table me-1"></i> Emitir Pautas
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                <li><h6 class="dropdown-header text-uppercase small font-weight-bold">Selecione uma Turma</h6></li>
                                @foreach($classes as $c)
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center justify-content-between py-2" href="{{ route('pautas.trimestral', $c->id) }}">
                                            <span><i class="fas fa-chalkboard-teacher text-success me-2"></i> {{ $c->name }}</span>
                                            <span class="badge bg-light text-dark ms-2">{{ $c->grade_level_name }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>

            <div class="school-card-body">
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
                                <option value="{{ $class?->id }}" {{ request('class_id') == $class?->id ? 'selected' : '' }}>
                                    {{ $class?->name }}
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
                            <option value="ACS1" {{ request('assessment_type') == 'ACS1' ? 'selected' : '' }}>ACS 1</option>
                            <option value="ACS2" {{ request('assessment_type') == 'ACS2' ? 'selected' : '' }}>ACS 2</option>
                            <option value="ACS3" {{ request('assessment_type') == 'ACS3' ? 'selected' : '' }}>ACS 3</option>
                            <option value="ACP" {{ request('assessment_type') == 'ACP' ? 'selected' : '' }}>ACP</option>
                            <option value="ACF" {{ request('assessment_type') == 'ACF' ? 'selected' : '' }}>ACF</option>
                            <option value="behavioral" {{ request('assessment_type') == 'behavioral' ? 'selected' : '' }}>Comportamento</option>
                            <option value="test" {{ request('assessment_type') == 'test' ? 'selected' : '' }}>Teste</option>
                            <option value="exam" {{ request('assessment_type') == 'exam' ? 'selected' : '' }}>Exame</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-school btn-primary-school w-100">
                            <i class="fas fa-filter"></i> Filtrar
                        </button>
                    </div>
                </form>

                <!-- Tabela de Notas -->
                <div class="table-responsive">
                    <table class="table table-school table-hover">
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
                                        @php
                                            $typeLabels = [
                                                'ACS1' => 'ACS 1',
                                                'ACS2' => 'ACS 2',
                                                'ACS3' => 'ACS 3',
                                                'ACP' => 'ACP',
                                                'ACF' => 'ACF',
                                                'behavioral' => 'Comportamento',
                                                'test' => 'Teste',
                                                'exam' => 'Exame'
                                            ];
                                            $typeColors = [
                                                'ACS1' => 'info',
                                                'ACS2' => 'info',
                                                'ACS3' => 'info',
                                                'ACP' => 'primary',
                                                'ACF' => 'success',
                                                'behavioral' => 'warning',
                                                'test' => 'secondary',
                                                'exam' => 'danger'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $typeColors[$grade->assessment_type] ?? 'secondary' }}">
                                            {{ $typeLabels[$grade->assessment_type] ?? $grade->assessment_type }}
                                        </span>
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
                                                    onclick="confirmDelete({{ $grade->id }}, 'Nota de {{ addslashes($grade->student->first_name) }}')">
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
                    <a href="{{ route('grades.create') }}" class="btn btn-school btn-primary-school">
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
                        <button type="submit" class="btn btn-danger">Excluir Nota</button>
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