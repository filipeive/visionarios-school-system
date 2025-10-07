@extends('layouts.app')

@section('title', 'Notas - ' . $student->first_name)
@section('page-title', 'Notas do Aluno')
@section('page-title-icon', 'fas fa-medal')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Alunos</a></li>
    <li class="breadcrumb-item"><a href="{{ route('students.show', $student) }}">{{ $student->first_name }}</a></li>
    <li class="breadcrumb-item active">Notas</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Informações do Aluno -->
        <div class="school-card mb-4">
            <div class="school-card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h4>{{ $student->first_name }} {{ $student->last_name }}</h4>
                        <p class="text-muted mb-0">
                            <strong>Número:</strong> {{ $student->student_number }} |
                            <strong>Turma:</strong> {{ $currentEnrollment->class->name ?? 'N/A' }} |
                            <strong>Ano:</strong> {{ $currentEnrollment->school_year ?? now()->year }}
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ route('students.show', $student) }}" class="btn btn-secondary-school">
                            <i class="fas fa-arrow-left"></i> Voltar ao Perfil
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estatísticas de Notas -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon students">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ number_format($student->grades->avg('grade') ?? 0, 1) }}</div>
                        <div class="stat-label">Média Geral</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon teachers">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $student->grades->groupBy('subject_id')->count() }}</div>
                        <div class="stat-label">Disciplinas</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon payments">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $student->grades->count() }}</div>
                        <div class="stat-label">Avaliações</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon events">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">
                            @php
                                $approved = $student->grades->where('grade', '>=', 10)->count();
                                $total = $student->grades->count();
                                $percentage = $total > 0 ? round(($approved / $total) * 100) : 0;
                            @endphp
                            {{ $percentage }}%
                        </div>
                        <div class="stat-label">Aproveitamento</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabela de Notas -->
        <div class="school-table-container">
            <div class="school-table-header">
                <h3 class="school-table-title">
                    <i class="fas fa-list"></i>
                    Histórico de Notas
                </h3>
            </div>

            <div class="table-responsive">
                <table class="table table-school">
                    <thead>
                        <tr>
                            <th>Disciplina</th>
                            <th>Nota</th>
                            <th>Tipo</th>
                            <th>Período</th>
                            <th>Ano</th>
                            <th>Data</th>
                            <th>Professor</th>
                            <th>Observações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($student->grades as $grade)
                            <tr>
                                <td>
                                    <strong>{{ $grade->subject->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $grade->subject->code }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $grade->grade >= 10 ? 'success' : 'danger' }} fs-6">
                                        {{ number_format($grade->grade, 1) }}
                                    </span>
                                </td>
                                <td>
                                    @switch($grade->assessment_type)
                                        @case('continuous') Contínua @break
                                        @case('test') Teste @break
                                        @case('exam') Exame @break
                                        @case('final') Final @break
                                        @default {{ $grade->assessment_type }}
                                    @endswitch
                                </td>
                                <td>{{ $grade->term }}º Período</td>
                                <td>{{ $grade->year }}</td>
                                <td>{{ $grade->date_recorded->format('d/m/Y') }}</td>
                                <td>
                                    @if($grade->teacher)
                                        {{ $grade->teacher->first_name }} {{ $grade->teacher->last_name }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>{{ $grade->comments ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-medal fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Nenhuma nota registrada para este aluno.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Resumo por Disciplina -->
        @if($student->grades->count() > 0)
        <div class="school-card mt-4">
            <div class="school-card-header">
                <h3 class="school-card-title">
                    <i class="fas fa-chart-bar"></i>
                    Resumo por Disciplina
                </h3>
            </div>
            <div class="school-card-body">
                <div class="row">
                    @foreach($student->grades->groupBy('subject_id') as $subjectId => $grades)
                        @php
                            $subject = $grades->first()->subject;
                            $average = $grades->avg('grade');
                            $count = $grades->count();
                        @endphp
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">{{ $subject->name }}</h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-{{ $average >= 10 ? 'success' : 'danger' }}">
                                            Média: {{ number_format($average, 1) }}
                                        </span>
                                        <small class="text-muted">{{ $count }} avaliações</small>
                                    </div>
                                    <div class="progress mt-2" style="height: 8px;">
                                        <div class="progress-bar bg-{{ $average >= 10 ? 'success' : 'danger' }}" 
                                             style="width: {{ min($average * 2.5, 100) }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection