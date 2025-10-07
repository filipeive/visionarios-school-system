@extends('layouts.app')

@section('title', 'Portal do Professor')
@section('page-title', 'Meu Portal')
@section('page-title-icon', 'fas fa-chalkboard-teacher')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Boas-vindas -->
        <div class="school-card mb-4">
            <div class="school-card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3>Bem-vindo(a), {{ $teacher->first_name }}!</h3>
                        <p class="text-muted mb-0">
                            <i class="fas fa-graduation-cap"></i> {{ $teacher->qualification }} em {{ $teacher->specialization }} | 
                            <i class="fas fa-clock"></i> {{ $teacher->years_experience }} anos de experiência |
                            <i class="fas fa-chalkboard"></i> {{ $stats['total_classes'] }} turmas
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="user-avatar mx-auto" style="width: 80px; height: 80px; font-size: 24px; background: linear-gradient(135deg, var(--accent), #0097A7);">
                            {{ substr($teacher->first_name, 0, 1) }}{{ substr($teacher->last_name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estatísticas Rápidas -->
        <div class="school-stats mb-4">
            <div class="stat-card students">
                <div class="stat-icon students">
                    <i class="fas fa-chalkboard"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $stats['total_classes'] }}</div>
                    <div class="stat-label">Minhas Turmas</div>
                    <a href="{{-- route('teacher-portal.classes') --}}" class="text-white small">Ver todas</a>
                </div>
            </div>

            <div class="stat-card teachers">
                <div class="stat-icon teachers">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $stats['total_students'] }}</div>
                    <div class="stat-label">Total de Alunos</div>
                    <span class="text-white small">Sob minha orientação</span>
                </div>
            </div>

            <div class="stat-card payments">
                <div class="stat-icon payments">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $stats['today_attendance'] }}</div>
                    <div class="stat-label">Presenças Hoje</div>
                    <span class="text-white small">Alunos presentes</span>
                </div>
            </div>

            <div class="stat-card events">
                <div class="stat-icon events">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $stats['upcoming_events'] }}</div>
                    <div class="stat-label">Próximos Eventos</div>
                    <span class="text-white small">Agendados</span>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Minhas Turmas -->
            <div class="col-md-6 mb-4">
                <div class="school-card h-100">
                    <div class="school-card-header d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-chalkboard"></i>
                            Minhas Turmas
                        </div>
                        <a href="{{-- route('teacher-portal.classes') --}}" class="btn btn-sm btn-primary-school">
                            Ver Todas
                        </a>
                    </div>
                    <div class="school-card-body">
                        @if($myClasses->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($myClasses as $class)
                                    <div class="list-group-item px-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">{{ $class->name }}</h6>
                                                <small class="text-muted">
                                                    {{ $class->grade_level_name }} | 
                                                    {{ $class->students_count }} alunos
                                                </small>
                                            </div>
                                            <div class="btn-group">
                                                <a href="{{ route('teacher-portal.attendance', $class->id) }}" 
                                                   class="btn btn-sm btn-success" title="Presenças">
                                                    <i class="fas fa-clipboard-check"></i>
                                                </a>
                                                <a href="{{ route('teacher-portal.grades', $class->id) }}" 
                                                   class="btn btn-sm btn-warning" title="Notas">
                                                    <i class="fas fa-tasks"></i>
                                                </a>
                                                <a href="{{ route('teacher-portal.class-detail', $class->id) }}" 
                                                   class="btn btn-sm btn-primary" title="Detalhes">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted text-center py-3">
                                <i class="fas fa-chalkboard-teacher fa-2x mb-2"></i><br>
                                Nenhuma turma atribuída
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Próximos Eventos -->
            <div class="col-md-6 mb-4">
                <div class="school-card h-100">
                    <div class="school-card-header">
                        <i class="fas fa-calendar-alt"></i>
                        Próximos Eventos
                    </div>
                    <div class="school-card-body">
                        @if($upcomingEvents->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($upcomingEvents as $event)
                                    <div class="list-group-item px-0">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">{{ $event->title }}</h6>
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar"></i>
                                                    {{ $event->event_date->format('d/m/Y') }}
                                                    @if($event->start_time)
                                                        às {{ $event->start_time->format('H:i') }}
                                                    @endif
                                                </small>
                                                @if($event->description)
                                                    <p class="mb-0 mt-1 small">{{ Str::limit($event->description, 100) }}</p>
                                                @endif
                                            </div>
                                            <span class="badge bg-{{ $event->type == 'meeting' ? 'primary' : ($event->type == 'exam' ? 'warning' : 'info') }}">
                                                {{ $event->type }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted text-center py-3">
                                <i class="fas fa-calendar-times fa-2x mb-2"></i><br>
                                Nenhum evento próximo
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Ações Rápidas -->
        <div class="school-card">
            <div class="school-card-header">
                <i class="fas fa-bolt"></i>
                Ações Rápidas
            </div>
            <div class="school-card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <a href="{{-- route('teacher-portal.classes') --}}" class="btn btn-primary-school w-100 h-100 py-3">
                            <i class="fas fa-chalkboard fa-2x mb-2"></i><br>
                            Minhas Turmas
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('teacher-portal.attendance', $myClasses->first()->id ?? '#') }}" 
                           class="btn btn-success-school w-100 h-100 py-3 {{ !$myClasses->count() ? 'disabled' : '' }}">
                            <i class="fas fa-clipboard-check fa-2x mb-2"></i><br>
                            Registrar Presenças
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('teacher-portal.grades', $myClasses->first()->id ?? '#') }}" 
                           class="btn btn-warning-school w-100 h-100 py-3 {{ !$myClasses->count() ? 'disabled' : '' }}">
                            <i class="fas fa-tasks fa-2x mb-2"></i><br>
                            Lançar Notas
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{-- route('teacher-portal.communications') --}}" class="btn btn-info-school w-100 h-100 py-3">
                            <i class="fas fa-bullhorn fa-2x mb-2"></i><br>
                            Comunicados
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection