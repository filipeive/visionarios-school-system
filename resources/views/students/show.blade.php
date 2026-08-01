@extends('layouts.app')

@section('title', 'Ficha 360º - ' . $student->full_name)
@section('page-title', 'Perfil 360º do Aluno')
@section('page-title-icon', 'fas fa-user-graduate')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Alunos</a></li>
    <li class="breadcrumb-item active">{{ $student->full_name }}</li>
@endsection

@section('content')
<div class="container-fluid px-0">
    <div class="row g-4">
        <!-- Coluna Esquerda: Cartão do Aluno & Contactos -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-gradient-to-r from-emerald-800 to-teal-800 p-4 text-white text-center position-relative">
                    <div class="mb-3 position-relative d-inline-block">
                        @if ($student->passport_photo)
                            <img src="{{ Storage::url($student->passport_photo) }}" alt="{{ $student->full_name }}"
                                class="rounded-circle shadow-md border border-4 border-white"
                                style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <div class="bg-white text-emerald-800 rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-md border border-4 border-white"
                                style="width: 120px; height: 120px; font-size: 40px; font-weight: 800;">
                                {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <h4 class="fw-bold mb-1 text-white">{{ $student->full_name }}</h4>
                    <p class="text-emerald-100 font-mono small mb-2">{{ $student->student_number }}</p>
                    
                    @php
                        $statusColors = [
                            'active' => 'bg-emerald-500 text-white',
                            'inactive' => 'bg-rose-500 text-white',
                            'transferred' => 'bg-sky-500 text-white',
                            'graduated' => 'bg-emerald-600 text-white',
                            'pending_renewal' => 'bg-amber-500 text-white',
                        ];
                        $statusLabels = [
                            'active' => 'Ativo',
                            'inactive' => 'Inativo',
                            'transferred' => 'Transferido',
                            'graduated' => 'Formado',
                            'pending_renewal' => 'Pendente Renovação',
                        ];
                    @endphp
                    <span class="badge {{ $statusColors[$student->status] ?? 'bg-secondary' }} px-3 py-1.5 rounded-pill text-xs font-semibold">
                        <i class="fas fa-circle text-[8px] me-1"></i> {{ $statusLabels[$student->status] ?? ucfirst($student->status) }}
                    </span>
                </div>

                <div class="card-body p-4">
                    @if ($currentEnrollment)
                        <div class="p-3 bg-emerald-50/70 border border-emerald-100 rounded-3 text-emerald-900 mb-4 d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-emerald-100 p-2.5 text-emerald-700">
                                <i class="fas fa-chalkboard"></i>
                            </div>
                            <div>
                                <small class="text-xs uppercase font-bold text-emerald-700 tracking-wider">Turma Atual</small>
                                <h6 class="fw-bold mb-0 text-emerald-950">{{ $currentEnrollment->class->name }}</h6>
                            </div>
                        </div>
                    @endif

                    <div class="d-grid gap-2 mb-4">
                        @can('edit_students')
                            <a href="{{ route('students.edit', $student) }}" class="btn btn-warning rounded-pill py-2 text-xs font-bold shadow-xs">
                                <i class="fas fa-edit me-1.5"></i> Editar Perfil
                            </a>
                        @endcan

                        <div class="btn-group w-100 rounded-pill overflow-hidden shadow-xs">
                            <a href="{{ route('students.grades', $student) }}" class="btn btn-outline-primary btn-sm" title="Notas">
                                <i class="fas fa-medal"></i> Pautas
                            </a>
                            <a href="{{ route('students.attendance', $student) }}" class="btn btn-outline-info btn-sm" title="Presenças">
                                <i class="fas fa-calendar-check"></i> Frequência
                            </a>
                            @can('manage_payments')
                                <a href="{{ route('students.payments', $student) }}" class="btn btn-outline-success btn-sm" title="Financeiro">
                                    <i class="fas fa-money-bill-wave"></i> Propinas
                                </a>
                            @endcan
                        </div>
                    </div>

                    <div class="border-top pt-3 space-y-3 text-sm">
                        <div class="d-flex align-items-start gap-2.5">
                            <i class="fas fa-map-marker-alt text-primary mt-1"></i>
                            <div>
                                <strong class="d-block text-xs text-muted">Morada</strong>
                                <span>{{ $student->address }}</span>
                            </div>
                        </div>

                        @if ($student->parent)
                            <div class="d-flex align-items-start gap-2.5">
                                <i class="fas fa-user-friends text-success mt-1"></i>
                                <div>
                                    <strong class="d-block text-xs text-muted">Encarregado de Educação</strong>
                                    <span>{{ $student->parent->first_name }} {{ $student->parent->last_name }}</span>
                                    <span class="d-block text-xs text-muted font-mono">{{ $student->parent->phone }}</span>
                                </div>
                            </div>
                        @endif

                        <div class="d-flex align-items-start gap-2.5">
                            <i class="fas fa-phone text-danger mt-1"></i>
                            <div>
                                <strong class="d-block text-xs text-muted">Contacto de Emergência</strong>
                                <span>{{ $student->emergency_contact }}</span>
                                <span class="d-block text-xs text-muted font-mono">{{ $student->emergency_phone }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coluna Direita: Dashboard 360º com Separadores -->
        <div class="col-lg-8">
            <!-- Stat Cards Superiores -->
            <div class="row g-3 mb-4">
                <div class="col-sm-3">
                    <div class="card border-0 shadow-xs rounded-4 p-3 bg-white">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 bg-emerald-100 p-2.5 text-emerald-700 text-lg">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div>
                                <small class="text-xs text-muted uppercase font-bold">Presença</small>
                                <h5 class="fw-black mb-0 text-slate-800">{{ $attendanceStats['attendance_rate'] }}%</h5>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="card border-0 shadow-xs rounded-4 p-3 bg-white">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 bg-blue-100 p-2.5 text-blue-700 text-lg">
                                <i class="fas fa-medal"></i>
                            </div>
                            <div>
                                <small class="text-xs text-muted uppercase font-bold">Média Geral</small>
                                <h5 class="fw-black mb-0 text-slate-800">{{ number_format($student->grades->avg('grade') ?? 0, 1) }}</h5>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="card border-0 shadow-xs rounded-4 p-3 bg-white">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 bg-purple-100 p-2.5 text-purple-700 text-lg">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <div>
                                <small class="text-xs text-muted uppercase font-bold">Pagamentos</small>
                                <h5 class="fw-black mb-0 text-slate-800">{{ $student->payments->where('status', 'paid')->count() }}</h5>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="card border-0 shadow-xs rounded-4 p-3 bg-white">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 bg-amber-100 p-2.5 text-amber-700 text-lg">
                                <i class="fas fa-birthday-cake"></i>
                            </div>
                            <div>
                                <small class="text-xs text-muted uppercase font-bold">Idade</small>
                                <h5 class="fw-black mb-0 text-slate-800">{{ $student->age ?? 'N/A' }} anos</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Separadores 360º -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-bottom p-2">
                    <ul class="nav nav-pills nav-justified gap-1" id="student360Tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-3 py-2 text-xs font-bold" id="resumo-tab" data-bs-toggle="tab" data-bs-target="#resumo" type="button" role="tab">
                                <i class="fas fa-chart-pie me-1"></i> Resumo 360º
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-3 py-2 text-xs font-bold" id="academico-tab" data-bs-toggle="tab" data-bs-target="#academico" type="button" role="tab">
                                <i class="fas fa-graduation-cap me-1"></i> Pautas & Notas
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-3 py-2 text-xs font-bold" id="frequencia-tab" data-bs-toggle="tab" data-bs-target="#frequencia" type="button" role="tab">
                                <i class="fas fa-calendar-check me-1"></i> Assiduidade
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-3 py-2 text-xs font-bold" id="financeiro-tab" data-bs-toggle="tab" data-bs-target="#financeiro" type="button" role="tab">
                                <i class="fas fa-receipt me-1"></i> Financeiro
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-3 py-2 text-xs font-bold" id="timeline-tab" data-bs-toggle="tab" data-bs-target="#timeline" type="button" role="tab">
                                <i class="fas fa-stream me-1"></i> Timeline
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-4">
                    <div class="tab-content" id="student360TabContent">
                        
                        <!-- TAB 1: RESUMO 360º -->
                        <div class="tab-pane fade show active" id="resumo" role="tabpanel">
                            <div class="row g-4">
                                <div class="col-md-7">
                                    <div class="p-3 border rounded-3 bg-light/50">
                                        <h6 class="fw-bold mb-3 text-slate-800"><i class="fas fa-chart-bar text-emerald-600 me-2"></i>Desempenho por Disciplina</h6>
                                        <div style="height: 220px;">
                                            <canvas id="studentSubjectChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="p-3 border rounded-3 bg-light/50">
                                        <h6 class="fw-bold mb-3 text-slate-800"><i class="fas fa-pie-chart text-teal-600 me-2"></i>Distribuição de Frequência</h6>
                                        <div style="height: 220px;">
                                            <canvas id="studentAttendancePieChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 2: PAUTAS & NOTAS -->
                        <div class="tab-pane fade" id="academico" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle text-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Disciplina</th>
                                            <th>Avaliação</th>
                                            <th>Trimestre</th>
                                            <th>Nota</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($student->grades as $grade)
                                            <tr>
                                                <td class="fw-bold">{{ $grade->subject->name ?? 'Geral' }}</td>
                                                <td>{{ $grade->assessment_type }}</td>
                                                <td>{{ $grade->term }}º Trimestre</td>
                                                <td class="fw-black text-{{ $grade->grade >= 10 ? 'emerald' : 'rose' }}-600">
                                                    {{ number_format($grade->grade, 1) }}
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $grade->grade >= 10 ? 'success' : 'danger' }} rounded-pill">
                                                        {{ $grade->grade_status }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4 text-muted">
                                                    <i class="fas fa-medal fa-2x mb-2 text-slate-300"></i>
                                                    <p class="mb-0">Nenhuma nota lançada para este aluno ainda.</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- TAB 3: ASSIDUIDADE -->
                        <div class="tab-pane fade" id="frequencia" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle text-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Data</th>
                                            <th>Turma</th>
                                            <th>Status</th>
                                            <th>Observações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($student->attendances as $att)
                                            <tr>
                                                <td>{{ $att->attendance_date?->format('d/m/Y') ?? 'N/A' }}</td>
                                                <td>{{ $att->class->name ?? 'N/A' }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $att->status === 'present' ? 'success' : ($att->status === 'absent' ? 'danger' : 'warning') }}">
                                                        {{ $att->status === 'present' ? 'Presente' : ($att->status === 'absent' ? 'Ausente' : 'Atrasado') }}
                                                    </span>
                                                </td>
                                                <td>{{ $att->notes ?? '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-4 text-muted">
                                                    <i class="fas fa-calendar-times fa-2x mb-2 text-slate-300"></i>
                                                    <p class="mb-0">Nenhum registo de assiduidade encontrado.</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- TAB 4: FINANCEIRO -->
                        <div class="tab-pane fade" id="financeiro" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle text-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Referência</th>
                                            <th>Tipo</th>
                                            <th>Mês/Ano</th>
                                            <th>Valor</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($student->payments as $pay)
                                            <tr>
                                                <td class="font-mono"><code>{{ $pay->reference_number }}</code></td>
                                                <td class="fw-bold">{{ ucfirst($pay->type) }}</td>
                                                <td>{{ $pay->month ? $pay->month.'/'.$pay->year : '-' }}</td>
                                                <td class="fw-bold text-success">{{ number_format($pay->amount, 2, ',', '.') }} MT</td>
                                                <td>
                                                    <span class="badge bg-{{ $pay->status === 'paid' ? 'success' : ($pay->status === 'pending' ? 'warning' : 'danger') }}">
                                                        {{ $pay->status === 'paid' ? 'Pago' : ($pay->status === 'pending' ? 'Pendente' : 'Atrasado') }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4 text-muted">
                                                    <i class="fas fa-receipt fa-2x mb-2 text-slate-300"></i>
                                                    <p class="mb-0">Nenhum registo financeiro encontrado.</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- TAB 5: TIMELINE DE EVENTOS -->
                        <div class="tab-pane fade" id="timeline" role="tabpanel">
                            <div class="position-relative ps-4 border-start border-2 border-emerald-200 ms-3 space-y-4">
                                @forelse($timelineEvents as $event)
                                    <div class="position-relative">
                                        <div class="position-absolute top-0 start-0 translate-middle rounded-circle bg-{{ $event['color'] }} text-white d-flex align-items-center justify-content-center"
                                             style="width: 28px; height: 28px; left: -17px !important;">
                                            <i class="{{ $event['icon'] }} text-xs"></i>
                                        </div>
                                        <div class="ps-3 p-3 bg-light rounded-3 border">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <h6 class="fw-bold mb-0 text-slate-800">{{ $event['title'] }}</h6>
                                                <small class="text-xs text-muted font-mono">{{ $event['date']?->format('d/m/Y H:i') }}</small>
                                            </div>
                                            <p class="text-xs text-slate-600 mb-0">{{ $event['description'] }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted text-center py-4">Sem eventos na linha do tempo.</p>
                                @endforelse
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Gráfico de Média por Disciplina
    const perfData = @json($subjectPerformance);
    const labels = perfData.length > 0 ? perfData.map(p => p.subject) : ['Sem Notas'];
    const averages = perfData.length > 0 ? perfData.map(p => p.average) : [0];

    new Chart(document.getElementById('studentSubjectChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Média (0-20)',
                data: averages,
                backgroundColor: '#047857',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { min: 0, max: 20 } },
            plugins: { legend: { display: false } }
        }
    });

    // 2. Gráfico de Assiduidade
    const attPres = {{ $attendanceStats['present'] }};
    const attAbs = {{ $attendanceStats['absent'] }};
    const attLate = {{ $attendanceStats['late'] }};
    const attTot = attPres + attAbs + attLate;

    new Chart(document.getElementById('studentAttendancePieChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: attTot > 0 ? ['Presente', 'Ausente', 'Atrasado'] : ['Sem Registos'],
            datasets: [{
                data: attTot > 0 ? [attPres, attAbs, attLate] : [1],
                backgroundColor: attTot > 0 ? ['#10b981', '#ef4444', '#f59e0b'] : ['#cbd5e1']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });
});
</script>
@endpush
