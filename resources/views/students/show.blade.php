@extends('layouts.app')

@section('title', $student->full_name)
@section('page-title', $student->full_name)
@section('title-icon', 'fas fa-user-graduate')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Alunos</a></li>
    <li class="breadcrumb-item active">{{ $student->full_name }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <!-- Perfil do Aluno -->
            <div class="school-card mb-4">
                <div class="school-card-header">
                    <i class="fas fa-id-card"></i>
                    Perfil do Aluno
                </div>
                <div class="school-card-body text-center">
                    <div class="mb-3">
                        @if ($student->passport_photo)
                            <img src="{{ Storage::url($student->passport_photo) }}" alt="{{ $student->full_name }}"
                                class="rounded-circle"
                                style="width: 150px; height: 150px; object-fit: cover; border: 4px solid var(--primary);">
                        @else
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                style="width: 150px; height: 150px; font-size: 48px; border: 4px solid var(--primary-dark);">
                                {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                            </div>
                        @endif
                    </div>

                    <h4 class="mb-1">{{ $student->full_name }}</h4>
                    <p class="text-muted mb-2">{{ $student->student_number }}</p>

                    <div class="mb-3">
                        <span
                            class="badge bg-{{ $student->status === 'active' ? 'success' : ($student->status === 'inactive' ? 'danger' : 'warning') }} fs-6">
                            @switch($student->status)
                                @case('active')
                                    Ativo
                                @break

                                @case('inactive')
                                    Inativo
                                @break

                                @case('transferred')
                                    Transferido
                                @break

                                @case('graduated')
                                    Formado
                                @break
                            @endswitch
                        </span>
                    </div>

                    @if ($currentEnrollment)
                        <div class="alert alert-info">
                            <i class="fas fa-chalkboard me-2"></i>
                            <strong>Turma Atual:</strong> {{ $currentEnrollment->class->name }}
                        </div>
                    @endif

                    <div class="d-grid gap-2">
                        @can('edit_students')
                            <a href="{{ route('students.edit', $student) }}" class="btn btn-school btn-warning-school">
                                <i class="fas fa-edit me-2"></i> Editar Perfil
                            </a>
                        @endcan

                        <div class="btn-group w-100">
                            <a href="{{ route('students.grades', $student) }}" class="btn btn-outline-primary">
                                <i class="fas fa-medal"></i>
                            </a>
                            <a href="{{ route('students.attendance', $student) }}" class="btn btn-outline-info">
                                <i class="fas fa-calendar-check"></i>
                            </a>
                            @can('manage_payments')
                                <a href="{{ route('students.payments', $student) }}" class="btn btn-outline-success">
                                    <i class="fas fa-money-bill-wave"></i>
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informações de Contacto -->
            <div class="school-card mb-4">
                <div class="school-card-header">
                    <i class="fas fa-address-book"></i>
                    Informações de Contacto
                </div>
                <div class="school-card-body">
                    <div class="mb-3">
                        <strong><i class="fas fa-map-marker-alt text-primary me-2"></i>Morada:</strong>
                        <p class="mb-0 text-muted">{{ $student->address }}</p>
                    </div>

                    @if ($student->parent)
                        <div class="mb-3">
                            <strong><i class="fas fa-user-friends text-success me-2"></i>Encarregado:</strong>
                            <p class="mb-0">{{ $student->parent->first_name }} {{ $student->parent->last_name }}</p>
                            <small class="text-muted">{{ $student->parent->phone }}</small>
                        </div>
                    @endif

                    <div class="mb-3">
                        <strong><i class="fas fa-phone text-danger me-2"></i>Emergência:</strong>
                        <p class="mb-0">{{ $student->emergency_contact }}</p>
                        <small class="text-muted">{{ $student->emergency_phone }}</small>
                    </div>

                    @if ($student->medical_certificate)
                        <div class="mb-3">
                            <strong><i class="fas fa-file-medical text-warning me-2"></i>Informações Médicas:</strong>
                            <p class="mb-0 text-muted">{{ $student->medical_certificate }}</p>
                        </div>
                    @endif

                    @if ($student->has_special_needs)
                        <div class="alert alert-warning">
                            <i class="fas fa-wheelchair me-2"></i>
                            <strong>Necessidades Especiais:</strong>
                            <p class="mb-0 mt-1">{{ $student->special_needs_description }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Estatísticas Rápidas -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon students">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ $attendanceStats['attendance_rate'] }}%</div>
                            <div class="stat-label">Presença</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon teachers">
                            <i class="fas fa-medal"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ $student->grades->avg('score') ?? 0 }}</div>
                            <div class="stat-label">Média</div>
                        </div>
                    </div>
                </div>
                @can('manage_payments')
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon payments">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">{{ $student->payments->where('status', 'paid')->count() }}</div>
                                <div class="stat-label">Pagamentos</div>
                            </div>
                        </div>
                    </div>
                @endcan

                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon events">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ $student->age }}</div>
                            <div class="stat-label">Idade</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Presenças Recentes -->
            <div class="school-card mb-4">
                <div class="school-card-header">
                    <i class="fas fa-calendar-check"></i>
                    Presenças Recentes
                </div>
                <div class="school-card-body">
                    @if ($student->attendances->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Data</th>
                                        <th>Turma</th>
                                        <th>Status</th>
                                        <th>Observações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($student->attendances->take(10) as $attendance)
                                        <tr>
                                            <td>{{ $attendance->attendance_date?->format('d/m/Y') ?? 'Sem data' }}</td>
                                            <td>{{ $attendance->class->name ?? 'N/A' }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $attendance->status === 'present' ? 'success' : ($attendance->status === 'absent' ? 'danger' : 'warning') }}">
                                                    @switch($attendance->status)
                                                        @case('present')
                                                            Presente
                                                        @break

                                                        @case('absent')
                                                            Ausente
                                                        @break

                                                        @case('late')
                                                            Atrasado
                                                        @break
                                                    @endswitch
                                                </span>
                                            </td>
                                            <td>{{ $attendance->notes ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-calendar-times fa-2x mb-2"></i>
                            <p>Nenhum registro de presença encontrado.</p>
                        </div>
                    @endif

                    <div class="text-center">
                        <a href="{{ route('students.attendance', $student) }}"
                            class="btn btn-school btn-primary-school btn-sm">
                            <i class="fas fa-list me-1"></i> Ver Todas as Presenças
                        </a>
                    </div>
                </div>
            </div>
            @can('manage_payments')
                <!-- Pagamentos Recentes -->
                <div class="school-card mb-4">
                    <div class="school-card-header">
                        <i class="fas fa-money-bill-wave"></i>
                        Pagamentos Recentes
                    </div>
                    <div class="school-card-body">
                        @if ($student->payments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Referência</th>
                                            <th>Descrição</th>
                                            <th>Valor</th>
                                            <th>Status</th>
                                            <th>Data</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($student->payments->take(10) as $payment)
                                            <tr>
                                                <td><code>{{ $payment->reference_number }}</code></td>
                                                <td>
                                                    {{ $payment->type === 'matricula' ? 'Matrícula' : 'Mensalidade' }}
                                                    {{ $payment->month }}/{{ $payment->year }}
                                                </td>
                                                <td>{{ number_format($payment->amount, 2, ',', '.') }} MT</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $payment->status === 'paid' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }}">
                                                        @switch($payment->status)
                                                            @case('paid')
                                                                Pago
                                                            @break

                                                            @case('pending')
                                                                Pendente
                                                            @break

                                                            @case('overdue')
                                                                Em Atraso
                                                            @break
                                                        @endswitch
                                                    </span>
                                                </td>
                                                <td>{{ $payment->payment_date?->format('d/m/Y') ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                                <p>Nenhum registro de pagamento encontrado.</p>
                            </div>
                        @endif

                        <div class="text-center">
                            <a href="{{ route('students.payments', $student) }}"
                                class="btn btn-school btn-primary-school btn-sm">
                                <i class="fas fa-list me-1"></i> Ver Todos os Pagamentos
                            </a>
                        </div>
                    </div>
                </div>
            @endcan

            @can('manage_enrollments')
                <!-- Histórico de Matrículas -->
                <div class="school-card">
                    <div class="school-card-header">
                        <i class="fas fa-history"></i>
                        Histórico de Matrículas
                    </div>
                    <div class="school-card-body">
                        @if ($student->enrollments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Ano Letivo</th>
                                            <th>Turma</th>
                                            <th>Data</th>
                                            <th>Status</th>
                                            <th>Mensalidade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($student->enrollments as $enrollment)
                                            <tr class="{{ $enrollment->status === 'active' ? 'table-success' : '' }}">
                                                <td>{{ $enrollment->school_year }}</td>
                                                <td>{{ $enrollment->class->name }}</td>
                                                <td>{{ $enrollment->enrollment_date->format('d/m/Y') }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $enrollment->status === 'active' ? 'success' : 'secondary' }}">
                                                        {{ $enrollment->status === 'active' ? 'Ativa' : 'Inativa' }}
                                                    </span>
                                                </td>
                                                <td>{{ number_format($enrollment->monthly_fee, 2, ',', '.') }} MT</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-history fa-2x mb-2"></i>
                                <p>Nenhum histórico de matrícula encontrado.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endcan
        </div>
    </div>
@endsection
