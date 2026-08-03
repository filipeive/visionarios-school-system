@extends('layouts.app')

@section('title', 'Gestão de Eventos & Calendário')
@section('page-title', 'Gestão de Eventos')
@section('title-icon', 'fas fa-calendar-alt')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Eventos</li>
@endsection

@section('content')
    @php
        $typeLabels = [
            'meeting' => 'Reunião',
            'celebration' => 'Celebração / Festa',
            'exam' => 'Exame / Avaliação',
            'activity' => 'Atividade Extracurricular',
        ];
        $audienceLabels = [
            'all' => 'Geral (Todos)',
            'students' => 'Alunos',
            'parents' => 'Pais & Encarregados',
            'teachers' => 'Professores',
        ];
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-primary">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-primary-subtle text-primary rounded-circle p-3 me-3">
                        <i class="fas fa-calendar-check fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase font-weight-bold">Próximos Eventos</div>
                        <h4 class="mb-0 text-primary font-weight-bold">{{ \App\Models\Event::upcoming()->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-success">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-success-subtle text-success rounded-circle p-3 me-3">
                        <i class="fas fa-clock fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase font-weight-bold">Eventos Hoje</div>
                        <h4 class="mb-0 text-success font-weight-bold">{{ \App\Models\Event::today()->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-info">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-info-subtle text-info rounded-circle p-3 me-3">
                        <i class="fas fa-calendar-week fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase font-weight-bold">Esta Semana</div>
                        <h4 class="mb-0 text-info font-weight-bold">{{ \App\Models\Event::thisWeek()->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Principal -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0 text-dark font-weight-bold">
                <i class="fas fa-calendar-alt text-success me-2"></i> Calendário & Lista de Eventos
            </h5>
            <div class="d-flex gap-2">
                <a href="{{ route('events.calendar') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-calendar-day me-1"></i> Visualização Calendário
                </a>
                @can('create_events')
                    <a href="{{ route('events.create') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus me-1"></i> Novo Evento
                    </a>
                @endcan
            </div>
        </div>

        <div class="card-body">
            <!-- Filtros -->
            <form action="{{ route('events.index') }}" method="GET" class="row g-3 mb-4">
                <div class="col-md-4">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Título do evento...">
                </div>
                <div class="col-md-3">
                    <select name="type" class="form-select form-select-sm">
                        <option value="">Todos os Tipos</option>
                        @foreach ($typeLabels as $key => $label)
                            <option value="{{ $key }}" @selected(request('type') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="audience" class="form-select form-select-sm">
                        <option value="">Todos os Públicos</option>
                        @foreach ($audienceLabels as $key => $label)
                            <option value="{{ $key }}" @selected(request('audience') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">Filtrar</button>
                    @if (request()->anyFilled(['search', 'type', 'audience']))
                        <a href="{{ route('events.index') }}" class="btn btn-light btn-sm border">Limpar</a>
                    @endif
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Data & Hora</th>
                            <th>Evento</th>
                            <th>Tipo</th>
                            <th>Público-Alvo</th>
                            <th>Organizador</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($events as $event)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $event->event_date->format('d/m/Y') }}</div>
                                    <div class="text-muted small">
                                        <i class="far fa-clock me-1"></i> {{ $event->start_time->format('H:i') }} - {{ $event->end_time->format('H:i') }}
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $event->title }}</div>
                                    <div class="text-muted small text-truncate" style="max-width: 280px;">{{ $event->description }}</div>
                                </td>
                                <td>
                                    @php
                                        $badgeBg = match($event->type) {
                                            'meeting' => 'bg-info',
                                            'celebration' => 'bg-warning text-dark',
                                            'exam' => 'bg-danger',
                                            default => 'bg-success',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeBg }}">{{ $typeLabels[$event->type] ?? ucfirst($event->type) }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $audienceLabels[$event->target_audience] ?? $event->target_audience }}</span>
                                </td>
                                <td class="text-muted small">{{ $event->createdBy->name ?? 'Sistema' }}</td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('events.show', $event) }}" class="btn btn-outline-secondary" title="Ver Detalhes">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @can('edit_events')
                                            <a href="{{ route('events.edit', $event) }}" class="btn btn-outline-primary" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan
                                        @can('delete_events')
                                            <form action="{{ route('events.destroy', $event) }}" method="POST" class="d-inline" data-confirm="Deseja excluir este evento?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Excluir">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-calendar-times fa-3x mb-3 text-secondary"></i>
                                    <p class="mb-0">Nenhum evento encontrado.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($events->hasPages())
                <div class="mt-4">{{ $events->links() }}</div>
            @endif
        </div>
    </div>
@endsection
