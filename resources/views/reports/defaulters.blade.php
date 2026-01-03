@extends('layouts.app')

@section('title', 'Relatório de Inadimplentes')
@section('page-title', 'Relatório de Inadimplentes')
@section('page-title-icon', 'fas fa-user-times')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Relatórios</a></li>
    <li class="breadcrumb-item"><a href="{{ route('reports.financial') }}">Financeiro</a></li>
    <li class="breadcrumb-item active">Inadimplentes</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="alert alert-warning mb-4">
                <i class="fas fa-info-circle me-2"></i>
                Este relatório mostra alunos matriculados que ainda não efetuaram o pagamento da mensalidade do mês atual
                ({{ now()->format('F Y') }}).
            </div>

            <div class="school-card">
                <div class="school-card-header d-flex justify-content-between align-items-center">
                    <h3 class="school-card-title">
                        <i class="fas fa-users-slash"></i> Alunos Inadimplentes ({{ $defaulters->count() }})
                    </h3>
                    <button class="btn btn-sm btn-secondary-school">
                        <i class="fas fa-envelope"></i> Notificar Todos
                    </button>
                </div>
                <div class="school-card-body">
                    <div class="table-responsive">
                        <table class="table table-school">
                            <thead>
                                <tr>
                                    <th>Aluno</th>
                                    <th>Turma</th>
                                    <th>Encarregado</th>
                                    <th>Telefone</th>
                                    <th>Mensalidade</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($defaulters as $enrollment)
                                    <tr>
                                        <td>
                                            <strong>{{ $enrollment->student->first_name }}
                                                {{ $enrollment->student->last_name }}</strong>
                                            <br><small class="text-muted">{{ $enrollment->student->student_number }}</small>
                                        </td>
                                        <td>{{ $enrollment->class->name ?? 'N/A' }}</td>
                                        <td>{{ $enrollment->student->parent->first_name ?? 'N/A' }}</td>
                                        <td>{{ $enrollment->student->parent->phone ?? 'N/A' }}</td>
                                        <td>{{ number_format($enrollment->monthly_fee, 2, ',', '.') }} MT</td>
                                        <td>
                                            <a href="{{ route('students.show', $enrollment->student_id) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-bell"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                            <p class="text-muted">Parabéns! Não existem alunos inadimplentes para este mês.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection