@php
    $upcoming = \App\Models\Event::where('event_date', '>=', today())
        ->orderBy('event_date')
        ->limit(5)
        ->get();
    $typeColors = [
        'meeting' => 'bg-info',
        'celebration' => 'bg-warning text-dark',
        'exam' => 'bg-danger',
        'activity' => 'bg-success',
    ];
@endphp

<div class="dash-card-flat h-100">
    <div class="dash-section">
        <div>
            <p class="dash-section-title">Próximos Eventos</p>
            <p class="dash-section-subtitle">Agenda dos próximos dias</p>
        </div>
        <a href="{{ route('events.index') }}" class="dash-badge" style="background:#f1f5f9; color:#334155; text-decoration:none;">
            <i class="fas fa-calendar-alt"></i> Calendário
        </a>
    </div>
    <div class="dash-collapse-content">
        @forelse($upcoming as $event)
            <a href="{{ route('events.show', $event) }}" class="d-flex align-items-start gap-3 text-decoration-none rounded-xl p-3 mb-2" style="background:#f8fafc; border:1px solid #f1f5f9; color:#334155;">
                <div class="text-center" style="min-width:42px;">
                    <p class="fw-bold mb-0" style="font-size:0.7rem; color:#64748b; text-transform:uppercase;">{{ $event->event_date->format('M') }}</p>
                    <p class="fw-black mb-0" style="font-size:1.1rem; color:#0f172a;">{{ $event->event_date->format('d') }}</p>
                </div>
                <div class="flex-grow-1">
                    <p class="fw-bold mb-0" style="font-size:0.85rem;">{{ $event->title }}</p>
                    <p class="mb-0" style="font-size:0.75rem; color:#64748b;">
                        <i class="far fa-clock me-1"></i> {{ $event->start_time?->format('H:i') ?? '' }} {{ $event->start_time && $event->end_time ? '- ' . $event->end_time->format('H:i') : '' }}
                    </p>
                </div>
                <span class="dash-badge" style="background:#f1f5f9; color:#475569;">{{ ucfirst($event->type) }}</span>
            </a>
        @empty
            <p class="text-center py-4 mb-0" style="font-size:0.85rem; color:#94a3b8;">Nenhum evento agendado.</p>
        @endforelse
    </div>
</div>
