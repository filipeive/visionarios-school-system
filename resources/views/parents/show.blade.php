@extends('layouts.app')

@section('title', 'Perfil do Encarregado - ' . $parent->full_name)
@section('page-title', $parent->full_name)
@section('title-icon', 'fas fa-user-shield')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('parents.index') }}">Encarregados</a></li>
    <li class="breadcrumb-item active">Perfil</li>
@endsection

@section('content')
<div class="container-fluid p-0">
    <div class="row g-4">
        <!-- Perfil do Encarregado -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="avatar-circle mx-auto mb-3 bg-primary text-white font-weight-bold d-flex align-items-center justify-content-center rounded-circle" style="width: 70px; height: 70px; font-size: 1.6rem;">
                        {{ strtoupper(substr($parent->first_name, 0, 1) . substr($parent->last_name, 0, 1)) }}
                    </div>
                    <h5 class="mb-1 font-weight-bold text-dark">{{ $parent->full_name }}</h5>
                    <p class="text-muted small mb-3">Encarregado de Educação</p>

                    <div class="text-start border-top pt-3">
                        <p class="mb-2"><strong><i class="fas fa-phone text-muted me-2"></i> Telefone:</strong> {{ $parent->phone }}</p>
                        <p class="mb-2"><strong><i class="fas fa-envelope text-muted me-2"></i> E-mail:</strong> {{ $parent->email }}</p>
                        <p class="mb-2"><strong><i class="fas fa-id-card text-muted me-2"></i> Documento:</strong> {{ $parent->bi_number ?? 'Não informado' }}</p>
                        <p class="mb-2"><strong><i class="fas fa-briefcase text-muted me-2"></i> Profissão:</strong> {{ $parent->profession ?? 'N/A' }}</p>
                        <p class="mb-0"><strong><i class="fas fa-map-marker-alt text-muted me-2"></i> Endereço:</strong> {{ $parent->address ?? 'N/A' }}</p>
                    </div>

                    @can('edit_students')
                        <div class="mt-4 pt-3 border-top">
                            <a href="{{ route('parents.edit', $parent->user_id) }}" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-edit me-1"></i> Editar Dados
                            </a>
                        </div>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Lista de Educandos (Alunos) -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 font-weight-bold text-dark">
                        <i class="fas fa-user-graduate text-success me-2"></i> Educando(s) sob Responsabilidade ({{ count($parent->students) }})
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Aluno</th>
                                    <th>N.º de Estudante</th>
                                    <th>Turma Atual</th>
                                    <th class="text-end">Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($parent->students as $student)
                                    <tr>
                                        <td class="font-weight-bold text-dark">
                                            {{ $student->first_name }} {{ $student->last_name }}
                                        </td>
                                        <td>{{ $student->student_number }}</td>
                                        <td>
                                            @if($student->currentEnrollment?->class)
                                                <span class="badge bg-success">
                                                    {{ $student->currentEnrollment->class->name }}
                                                </span>
                                            @else
                                                <span class="text-muted small">Sem turma ativa</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('students.show', $student->id) }}" class="btn btn-outline-secondary btn-sm">
                                                <i class="fas fa-eye me-1"></i> Ver Ficha do Aluno
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">
                                            Nenhum educando associado a este encarregado.
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
</div>
@endsection
