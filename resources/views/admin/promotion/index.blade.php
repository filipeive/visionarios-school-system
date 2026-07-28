@extends('layouts.app')

@section('title', 'Passagem de Classe')
@section('page-title', 'Passagem de Classe (Promoção)')
@section('page-title-icon', 'fas fa-graduation-cap')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.academic-years.index') }}">Gestão Acadêmica</a></li>
    <li class="breadcrumb-item active">Passagem de Classe</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="school-card mb-4">
                <div class="school-card-body">
                    <form action="{{ route('admin.promotion.index') }}" method="GET" class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Selecionar Turma</label>
                            <select name="class_id" class="form-select" onchange="this.form.submit()">
                                <option value="">Selecione a turma...</option>
                                @if(is_iterable($classes))
                                @foreach($classes as $class)
                                    <option value="{{ optional($class)->id }}" {{ $classId == optional($class)->id ? 'selected' : '' }}>
                                        {{ optional($class)->name }} ({{ optional($class)->grade_level }}º Ano)
                                    </option>
                                @endforeach
                            @endif
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary-school w-100">
                                <i class="fas fa-search me-2"></i> Listar Alunos
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if($classId)
                <div class="school-card">
                    <div class="school-card-header">
                        <h3 class="school-card-title">
                            <i class="fas fa-users me-2"></i> Alunos da Turma
                        </h3>
                    </div>
                    <div class="school-card-body">
                        <form action="{{ route('admin.promotion.process') }}" method="POST">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-school table-hover">
                                    <thead>
                                        <tr>
                                            <th width="40">
                                                <input type="checkbox" class="form-check-input" id="check-all">
                                            </th>
                                            <th>Nº</th>
                                            <th>Nome do Aluno</th>
                                            <th>Média Final (MF)</th>
                                            <th>Resultado Sugerido</th>
                                            <th>Status Atual</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($students as $student)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="student_ids[]" value="{{ $student->id }}"
                                                        class="form-check-input student-check">
                                                </td>
                                                <td>{{ $student->student_number }}</td>
                                                <td>{{ $student->full_name }}</td>
                                                <td>
                                                    <span
                                                        class="fw-bold {{ $student->is_eligible ? 'text-success' : 'text-danger' }}">
                                                        {{ $student->calculated_mf ?? 'N/A' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($student->is_eligible)
                                                        <span class="badge bg-success">Aprovado</span>
                                                    @elseif($student->calculated_mf === null)
                                                        <span class="badge bg-secondary">Avaliação Incompleta</span>
                                                    @else
                                                        <span class="badge bg-danger">Reprovado</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $student->status == 'active' ? 'success' : 'info' }}">
                                                        {{ ucfirst($student->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4 text-muted">Nenhum aluno encontrado ou
                                                    matriculado nesta turma.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if(!empty($students))
                                <div class="d-flex justify-content-end gap-3 mt-4">
                                    <button type="submit" name="action" value="retain" class="btn btn-outline-danger">
                                        <i class="fas fa-history me-2"></i> Reter Selecionados
                                    </button>
                                    <button type="submit" name="action" value="promote" class="btn btn-success">
                                        <i class="fas fa-arrow-up me-2"></i> Promover Selecionados
                                    </button>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('check-all').addEventListener('change', function () {
            document.querySelectorAll('.student-check').forEach(cb => cb.checked = this.checked);
        });
    </script>
@endpush