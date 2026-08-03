@extends('layouts.app')

@section('title', 'Emissão de Certidões & Certificados')
@section('page-title', 'Certidões & Certificados')
@section('title-icon', 'fas fa-certificate')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Certidões & Certificados</li>
@endsection

@section('content')
<div class="container-fluid p-0">
    <!-- Stat Cards Uniformizados -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-primary">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-primary-subtle text-primary rounded-circle p-3 me-3">
                        <i class="fas fa-graduation-cap fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase font-weight-bold">Alunos Graduados</div>
                        <h4 class="mb-0 text-primary font-weight-bold">{{ \App\Models\Student::where('status', 'graduated')->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-success">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-success-subtle text-success rounded-circle p-3 me-3">
                        <i class="fas fa-certificate fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase font-weight-bold">Certidões Elegíveis</div>
                        <h4 class="mb-0 text-success font-weight-bold">{{ \App\Models\Student::count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-info">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-info-subtle text-info rounded-circle p-3 me-3">
                        <i class="fas fa-file-invoice fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase font-weight-bold">Padrão MINEDH</div>
                        <h4 class="mb-0 text-info font-weight-bold">Oficial Moçambique</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Alunos -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <form action="{{ route('certificates.index') }}" method="GET" class="row g-3">
                <div class="col-md-7">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Pesquisar aluno por nome ou N.º de estudante...">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Todos os Estados</option>
                        <option value="graduated" {{ request('status') === 'graduated' ? 'selected' : '' }}>Graduados (6ª / 12ª)</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Ativos</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">Filtrar</button>
                    @if(request()->has('search') || request()->has('status'))
                        <a href="{{ route('certificates.index') }}" class="btn btn-light btn-sm border">Limpar</a>
                    @endif
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Aluno</th>
                            <th>N.º Estudante</th>
                            <th>Turma / Ciclo</th>
                            <th>Estado</th>
                            <th class="text-end">Documentos Oficiais</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $student->first_name }} {{ $student->last_name }}</div>
                                    <div class="text-muted small">NIB/BI: {{ $student->bi_number ?? 'Não informado' }}</div>
                                </td>
                                <td><span class="badge bg-light text-dark border">{{ $student->student_number }}</span></td>
                                <td>
                                    @if($student->currentEnrollment?->class)
                                        {{ $student->currentEnrollment->class->name }} ({{ $student->currentEnrollment->class->education_level_name }})
                                    @else
                                        <span class="text-muted small">Concluído / Graduado</span>
                                    @endif
                                </td>
                                <td>
                                    @if($student->status === 'graduated')
                                        <span class="badge bg-success"><i class="fas fa-graduation-cap me-1"></i> Graduado</span>
                                    @elseif($student->status === 'active')
                                        <span class="badge bg-primary">Ativo</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($student->status) }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('certificates.certidao', $student->id) }}" class="btn btn-outline-primary" title="Emitir Certidão de Habilitações (MINEDH)">
                                            <i class="fas fa-file-pdf me-1"></i> Certidão de Habilitações
                                        </a>
                                        <a href="{{ route('certificates.certificado', $student->id) }}" class="btn btn-outline-success" title="Emitir Certificado de Conclusão de Ciclo">
                                            <i class="fas fa-award me-1"></i> Certificado de Conclusão
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fas fa-certificate fa-3x text-secondary mb-3"></i>
                                    <p class="mb-0">Nenhum aluno encontrado para emissão de certificados.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($students->hasPages())
                <div class="p-3 border-top">{{ $students->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
