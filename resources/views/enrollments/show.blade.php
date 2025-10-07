@extends('layouts.app')

@section('title', 'Detalhes da Matrícula')
@section('page-title', 'Detalhes da Matrícula')
@section('page-title-icon', 'fas fa-eye')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('enrollments.index') }}">Matrículas</a></li>
    <li class="breadcrumb-item active">Detalhes</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="school-card mb-4">
                <div class="school-card-header">
                    <i class="fas fa-info-circle"></i>
                    Informações da Matrícula
                </div>
                <div class="school-card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Dados do Aluno</h6>
                            <p><strong>Nome Completo:</strong> {{ $enrollment->student->first_name }}
                                {{ $enrollment->student->last_name }}</p>
                            <p><strong>Nº Estudante:</strong> {{ $enrollment->student->student_number ?? 'N/A' }}</p>
                            <p><strong>Gênero:</strong> {{ $enrollment->student->gender ?? 'N/A' }}</p>
                            <p><strong>Idade:</strong>
                                @if ($enrollment->student->birthdate)
                                    {{ $enrollment->student->age }} anos
                                @else
                                    Não informada
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6>Dados da Matrícula</h6>
                            <p><strong>Turma:</strong> {{ $enrollment->class->name }}</p>
                            <p><strong>Ano Letivo:</strong> {{ $enrollment->school_year }}</p>
                            <p><strong>Data Matrícula:</strong> {{ $enrollment->enrollment_date->format('d/m/Y') }}</p>
                            @if ($enrollment->cancellation_date)
                                <p><strong>Data Cancelamento:</strong> {{ $enrollment->cancellation_date->format('d/m/Y') }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h6>Informações Financeiras</h6>
                            <p><strong>Mensalidade:</strong> {{ number_format($enrollment->monthly_fee, 2, ',', '.') }} MZN
                            </p>
                            <p><strong>Dia de Pagamento:</strong> {{ $enrollment->payment_day }}</p>

                            @if ($enrollmentPayment)
                                <p><strong>Taxa de Matrícula:</strong>
                                    {{ number_format($enrollmentPayment->amount, 2, ',', '.') }} MZN
                                    <span
                                        class="badge bg-{{ $enrollmentPayment->status == 'paid' ? 'success' : 'warning' }} ms-2">
                                        {{ $enrollmentPayment->status == 'paid' ? 'Pago' : 'Pendente' }}
                                    </span>
                                </p>
                                @if ($enrollmentPayment->payment_date)
                                    <p><strong>Data Pagamento Matrícula:</strong>
                                        {{ $enrollmentPayment->payment_date->format('d/m/Y') }}</p>
                                @endif
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6>Status</h6>
                            @php
                                $statusColors = [
                                    'active' => 'success',
                                    'inactive' => 'secondary',
                                    'transferred' => 'info',
                                    'pending' => 'warning',
                                    'cancelled' => 'danger',
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$enrollment->status] }} fs-6">
                                {{ ucfirst($enrollment->status) }}
                            </span>
                        </div>
                    </div>

                    @if ($enrollment->observations)
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6>Observações</h6>
                                <p class="text-muted">{{ $enrollment->observations }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Card de Pagamentos Relacionados -->
            @if ($enrollment->payments->count() > 0)
                <div class="school-card">
                    <div class="school-card-header">
                        <i class="fas fa-money-bill-wave"></i>
                        Pagamentos Relacionados
                    </div>
                    <div class="school-card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Referência</th>
                                        <th>Tipo</th>
                                        <th>Valor</th>
                                        <th>Vencimento</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($enrollment->payments as $payment)
                                        <tr>
                                            <td>{{ $payment->reference_number }}</td>
                                            <td>
                                                @if ($payment->type == 'matricula')
                                                    <span class="badge bg-primary">Matrícula</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($payment->type) }}</span>
                                                @endif
                                            </td>
                                            <td>{{ number_format($payment->amount, 2, ',', '.') }} MZN</td>
                                            <td>{{ $payment->due_date->format('d/m/Y') }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $payment->status == 'paid' ? 'success' : ($payment->status == 'overdue' ? 'danger' : 'warning') }}">
                                                    {{ $payment->status == 'paid' ? 'Pago' : ($payment->status == 'overdue' ? 'Atrasado' : 'Pendente') }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('payments.show', $payment->id) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="school-card mb-4">
                <div class="school-card-header">
                    <i class="fas fa-cog"></i>
                    Ações
                </div>
                <div class="school-card-body">
                    <div class="d-grid gap-2">
                        @can('edit_enrollments')
                            <a href="{{ route('enrollments.edit', $enrollment->id) }}" class="btn btn-secondary-school">
                                <i class="fas fa-edit"></i> Editar Matrícula
                            </a>
                        @endcan

                        <a href="{{ route('students.show', $enrollment->student_id) }}" class="btn btn-primary-school">
                            <i class="fas fa-user-graduate"></i> Ver Aluno
                        </a>

                        <a href="{{ route('enrollments.print', $enrollment->id) }}" class="btn btn-info text-white"
                            target="_blank">
                            <i class="fas fa-print"></i> Imprimir Matrícula
                        </a>

                        <!-- Ações de Status -->
                        @if ($enrollment->status == 'active')
                            @can('edit_enrollments')
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#transferModal">
                                    <i class="fas fa-exchange-alt"></i> Transferir
                                </button>

                                <form action="{{ route('enrollments.cancel', $enrollment->id) }}" method="POST"
                                    class="d-grid">
                                    @csrf
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Tem certeza que deseja cancelar esta matrícula?')">
                                        <i class="fas fa-times"></i> Cancelar Matrícula
                                    </button>
                                </form>
                            @endcan
                        @elseif($enrollment->status == 'cancelled')
                            @can('edit_enrollments')
                                <form action="{{ route('enrollments.activate', $enrollment->id) }}" method="POST"
                                    class="d-grid">
                                    @csrf
                                    <button type="submit" class="btn btn-success"
                                        onclick="return confirm('Tem certeza que deseja reativar esta matrícula?')">
                                        <i class="fas fa-check"></i> Reativar Matrícula
                                    </button>
                                </form>
                            @endcan
                        @endif

                        {{-- No card de ações, adicionar botão de confirmar pagamento --}}
                        @if ($enrollment->status == 'pending' && $enrollmentPayment && $enrollmentPayment->status == 'pending')
                            <form action="{{ route('enrollments.confirm-payment', $enrollment->id) }}" method="POST"
                                class="d-grid">
                                @csrf
                                <button type="submit" class="btn btn-success"
                                    onclick="return confirm('Confirmar pagamento da taxa de matrícula e ativar matrícula?')">
                                    <i class="fas fa-check-circle"></i> Confirmar Pagamento
                                </button>
                            </form>
                        @endif

                        @if ($enrollment->status == 'pending' && (!$enrollmentPayment || $enrollmentPayment->status == 'paid'))
                            <form action="{{ route('enrollments.activate', $enrollment->id) }}" method="POST"
                                class="d-grid">
                                @csrf
                                <button type="submit" class="btn btn-success"
                                    onclick="return confirm('Ativar matrícula?')">
                                    <i class="fas fa-check"></i> Ativar Matrícula
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Modal de Transferência -->
            @can('edit_enrollments')
                <div class="modal fade" id="transferModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Transferir Aluno</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('enrollments.transfer', $enrollment->id) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Nova Turma</label>
                                        <select name="new_class_id" class="form-select" required>
                                            <option value="">Selecione a nova turma</option>
                                            @foreach (\App\Models\ClassRoom::all() as $class)
                                                @if ($class->id != $enrollment->class_id)
                                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Data da Transferência</label>
                                        <input type="date" name="transfer_date" class="form-control"
                                            value="{{ date('Y-m-d') }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Taxa de Transferência (MZN)</label>
                                        <input type="number" step="0.01" name="transfer_fee" class="form-control"
                                            value="0.00" min="0">
                                        <small class="text-muted">Deixe 0.00 se não houver taxa</small>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary-school">Confirmar Transferência</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </div>
@endsection
