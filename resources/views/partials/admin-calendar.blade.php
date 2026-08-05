@php
    $month = (int) ($calendarMonth ?? now()->month);
    $year = (int) ($calendarYear ?? now()->year);
    $startOfMonth = \Carbon\Carbon::create($year, $month, 1);
    $endOfMonth = $startOfMonth->copy()->endOfMonth();
    $daysInMonth = $endOfMonth->day;
    $firstDayOfWeek = $startOfMonth->dayOfWeek; // 0=Dom
    $today = now();

    $eventsByDay = $monthEvents ?? collect();
    $birthdaysByDay = $monthBirthdays ?? collect();

    $selectedDay = request('calendar_day');
    $selectedDate = $selectedDay ? \Carbon\Carbon::create($year, $month, (int) $selectedDay) : null;
    $selectedEvents = $selectedDate && $eventsByDay->has($selectedDay) ? $eventsByDay[$selectedDay]['events'] : collect();
    $selectedBirthdays = $selectedDate && $birthdaysByDay->has($selectedDay) ? $birthdaysByDay[$selectedDay]['students'] : collect();
@endphp

<div class="dash-card-flat h-100">
    <div class="dash-section">
        <div>
            <p class="dash-section-title">Calendário</p>
            <p class="dash-section-subtitle">Eventos e aniversariantes do mês</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="?calendar_month={{ $startOfMonth->copy()->subMonth()->month }}&calendar_year={{ $startOfMonth->copy()->subMonth()->year }}" class="dash-badge" style="background:#f1f5f9; color:#334155; text-decoration:none;">
                <i class="fas fa-chevron-left"></i>
            </a>
            <span class="fw-bold" style="font-size:0.85rem; min-width:120px; text-align:center;">{{ $startOfMonth->translatedFormat('F Y') }}</span>
            <a href="?calendar_month={{ $startOfMonth->copy()->addMonth()->month }}&calendar_year={{ $startOfMonth->copy()->addMonth()->year }}" class="dash-badge" style="background:#f1f5f9; color:#334155; text-decoration:none;">
                <i class="fas fa-chevron-right"></i>
            </a>
        </div>
    </div>

    <div class="dash-collapse-content">
        <div class="row g-3">
            <div class="col-lg-7">
                <div class="rounded-xl border" style="border-color:#f1f5f9; background:#ffffff; padding:0.75rem;">
                    <table class="table table-bordered mb-0" style="border-color:#f1f5f9;">
                        <thead>
                            <tr>
                                <th class="text-center py-1" style="font-size:0.7rem; color:#64748b; font-weight:700; border-color:#f1f5f9; background:#f8fafc;">Dom</th>
                                <th class="text-center py-1" style="font-size:0.7rem; color:#64748b; font-weight:700; border-color:#f1f5f9; background:#f8fafc;">Seg</th>
                                <th class="text-center py-1" style="font-size:0.7rem; color:#64748b; font-weight:700; border-color:#f1f5f9; background:#f8fafc;">Ter</th>
                                <th class="text-center py-1" style="font-size:0.7rem; color:#64748b; font-weight:700; border-color:#f1f5f9; background:#f8fafc;">Qua</th>
                                <th class="text-center py-1" style="font-size:0.7rem; color:#64748b; font-weight:700; border-color:#f1f5f9; background:#f8fafc;">Qui</th>
                                <th class="text-center py-1" style="font-size:0.7rem; color:#64748b; font-weight:700; border-color:#f1f5f9; background:#f8fafc;">Sex</th>
                                <th class="text-center py-1" style="font-size:0.7rem; color:#64748b; font-weight:700; border-color:#f1f5f9; background:#f8fafc;">Sáb</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $day = 1;
                                $week = 0;
                            @endphp
                            @while($day <= $daysInMonth)
                                <tr>
                                    @for($col = 0; $col < 7; $col++)
                                        @php
                                            if ($week == 0 && $col < $firstDayOfWeek) {
                                                echo '<td style="border-color:#f1f5f9; background:#f8fafc;"></td>';
                                            } elseif ($day > $daysInMonth) {
                                                echo '<td style="border-color:#f1f5f9; background:#f8fafc;"></td>';
                                            } else {
                                                $date = \Carbon\Carbon::create($year, $month, $day);
                                                $isToday = $date->isToday();
                                                $isSelected = $selectedDate && $date->eq($selectedDate);
                                                $hasEvent = $eventsByDay->has($day);
                                                $hasBirthday = $birthdaysByDay->has($day);

                                                if ($isToday) {
                                                    $cellStyle = 'background:#059669; color:#ffffff; font-weight:700;';
                                                } elseif ($isSelected) {
                                                    $cellStyle = 'background:#d1fae5; color:#065f46; font-weight:700; box-shadow: inset 0 0 0 2px #059669;';
                                                } elseif ($hasEvent || $hasBirthday) {
                                                    $cellStyle = 'background:#ecfdf5; color:#065f46; font-weight:600;';
                                                } else {
                                                    $cellStyle = 'color:#334155;';
                                                }
                                        @endphp
                                        <td class="text-center py-1 position-relative" style="border-color:#f1f5f9; {{ $cellStyle }} cursor:pointer;">
                                            <a href="?calendar_month={{ $month }}&calendar_year={{ $year }}&calendar_day={{ $day }}" style="color:inherit; text-decoration:none; display:block;">
                                                <span style="font-size:0.8rem;">{{ $day }}</span>
                                                @if($hasEvent || $hasBirthday)
                                                    <span class="d-block" style="width:4px; height:4px; border-radius:50%; margin:1px auto 0; background:{{ ($isToday || $isSelected) ? '#ffffff' : '#059669' }};"></span>
                                                @endif
                                            </a>
                                        </td>
                                        @php
                                                $day++;
                                            }
                                        @endphp
                                    @endfor
                                </tr>
                                @php $week++; @endphp
                            @endwhile
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="rounded-xl border h-100" style="border-color:#f1f5f9; background:#ffffff; padding:1rem;">
                    @if($selectedDate)
                        <p class="fw-bold mb-2" style="font-size:0.85rem;">
                            {{ $selectedDate->translatedFormat('d \\d\\e F') }}
                        </p>
                        @if($selectedEvents->count() > 0)
                            <div class="mb-2">
                                <p class="fw-bold mb-1" style="font-size:0.75rem; color:#64748b; text-transform:uppercase;">Eventos</p>
                                @foreach($selectedEvents as $event)
                                    <a href="{{ route('events.show', $event) }}" class="d-flex align-items-start gap-2 text-decoration-none rounded-lg p-2 mb-1" style="background:#f8fafc; border:1px solid #f1f5f9; color:#334155;">
                                        <div class="text-center" style="min-width:32px;">
                                            <p class="fw-bold mb-0" style="font-size:0.65rem; color:#64748b;">{{ $event->event_date->format('M') }}</p>
                                            <p class="fw-black mb-0" style="font-size:0.9rem; color:#0f172a;">{{ $event->event_date->format('d') }}</p>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="fw-bold mb-0" style="font-size:0.8rem;">{{ $event->title }}</p>
                                            <p class="mb-0" style="font-size:0.7rem; color:#64748b;">
                                                <i class="far fa-clock me-1"></i> {{ $event->start_time?->format('H:i') ?? '' }} {{ $event->start_time && $event->end_time ? '- ' . $event->end_time->format('H:i') : '' }}
                                            </p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                        @if($selectedBirthdays->count() > 0)
                            <div>
                                <p class="fw-bold mb-1" style="font-size:0.75rem; color:#64748b; text-transform:uppercase;">Aniversariantes</p>
                                @foreach($selectedBirthdays as $birthday)
                                    <div class="d-flex align-items-center gap-2 rounded-lg p-2 mb-1" style="background:#fef3c7; border:1px solid #fde68a;">
                                        <div class="rounded-full d-flex align-items-center justify-content-center" style="width:28px; height:28px; background:#fef3c7; color:#d97706; font-weight:800; font-size:0.75rem;">
                                            {{ substr($birthday->first_name, 0, 1) }}
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="fw-bold mb-0" style="font-size:0.8rem;">{{ $birthday->first_name }} {{ $birthday->last_name }}</p>
                                            <p class="mb-0" style="font-size:0.7rem; color:#64748b;">
                                                {{-- podemos colocar a turma em que ele está ou entao a data de nascimento dele --}}    
                                            <i class="far fa-clock me-1"></i> {{ $birthday->birth_date }}    
                                            </p>
                                        </div>
                                        <a href="{{ route('students.show', $birthday->id) }}" class="btn btn-sm btn-warning end-0 me-3">Ver Perfil</a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        @if(!$selectedEvents->count() && !$selectedBirthdays->count())
                            <p class="text-center py-4 mb-0" style="font-size:0.85rem; color:#94a3b8;">Nenhum evento ou aniversariante neste dia.</p>
                        @endif
                    @else
                        <p class="text-center py-4 mb-0" style="font-size:0.85rem; color:#94a3b8;">Selecione um dia para ver os detalhes.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
