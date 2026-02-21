@extends('layouts.app')

@section('title', 'Acompanhamento - ' . $student->full_name)
@section('page-title', 'Acompanhamento do Aluno')
@section('title-icon', 'fas fa-notes-medical')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Alunos</a></li>
    <li class="breadcrumb-item"><a href="{{ route('students.show', $student) }}">{{ $student->full_name }}</a></li>
    <li class="breadcrumb-item active">Acompanhamento</li>
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-12 col-lg-5">
            <div class="school-card mb-4">
                <div class="school-card-header">
                    <i class="fas fa-user-graduate"></i>
                    Dados do Aluno
                </div>
                <div class="school-card-body">
                    <h5 class="mb-1">{{ $student->full_name }}</h5>
                    <div class="text-muted mb-3">{{ $student->student_number }}</div>
                    <div><strong>Turma atual:</strong> {{ $student->currentEnrollment?->class?->name ?? 'Sem turma ativa' }}</div>
                    <div><strong>Encarregado:</strong>
                        {{ $student->parent ? $student->parent->first_name . ' ' . $student->parent->last_name : 'Não informado' }}
                    </div>
                </div>
            </div>

            @can('create_observations')
                <div class="school-card mb-4">
                    <div class="school-card-header">
                        <i class="fas fa-comment-medical"></i>
                        Nova Observação
                    </div>
                    <div class="school-card-body">
                        <form method="POST" action="{{ route('students.support.observations.store', $student) }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Observação</label>
                                <textarea class="form-control @error('observations') is-invalid @enderror" name="observations" rows="4"
                                    placeholder="Registre observações pedagógicas, comportamentais ou de acompanhamento...">{{ old('observations') }}</textarea>
                                @error('observations')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" value="1" name="special_needs"
                                    id="special_needs" {{ old('special_needs') ? 'checked' : '' }}>
                                <label class="form-check-label" for="special_needs">
                                    Envolve necessidade especial
                                </label>
                            </div>
                            <button class="btn btn-school btn-primary-school w-100">
                                <i class="fas fa-save me-1"></i> Guardar Observação
                            </button>
                        </form>
                    </div>
                </div>
            @endcan

            @can('manage_student_records')
                <div class="school-card">
                    <div class="school-card-header">
                        <i class="fas fa-folder-plus"></i>
                        Novo Registo
                    </div>
                    <div class="school-card-body">
                        <form method="POST" action="{{ route('students.support.records.store', $student) }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Tipo</label>
                                <select class="form-select @error('record_type') is-invalid @enderror" name="record_type">
                                    <option value="">Selecionar...</option>
                                    <option value="academic" @selected(old('record_type') === 'academic')>Acadêmico</option>
                                    <option value="disciplinary" @selected(old('record_type') === 'disciplinary')>Disciplinar</option>
                                    <option value="health" @selected(old('record_type') === 'health')>Saúde</option>
                                    <option value="achievement" @selected(old('record_type') === 'achievement')>Conquista</option>
                                    <option value="other" @selected(old('record_type') === 'other')>Outro</option>
                                </select>
                                @error('record_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Data do registo</label>
                                <input type="date" class="form-control @error('record_date') is-invalid @enderror"
                                    name="record_date" value="{{ old('record_date', now()->toDateString()) }}">
                                @error('record_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Detalhes</label>
                                <textarea class="form-control @error('record_details') is-invalid @enderror" name="record_details" rows="4"
                                    placeholder="Descrição do evento/registo...">{{ old('record_details') }}</textarea>
                                @error('record_details')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button class="btn btn-school btn-primary-school w-100">
                                <i class="fas fa-save me-1"></i> Guardar Registo
                            </button>
                        </form>
                    </div>
                </div>
            @endcan
        </div>

        <div class="col-12 col-lg-7">
            <div class="school-card mb-4">
                <div class="school-card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-comments"></i> Observações</span>
                    <span class="badge bg-primary">{{ $observations->total() }}</span>
                </div>
                <div class="school-card-body">
                    @forelse ($observations as $observation)
                        <div class="border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <small class="text-muted">
                                    {{ $observation->created_at->format('d/m/Y H:i') }} por
                                    {{ $observation->creator->name ?? 'Sistema' }}
                                </small>
                                @if ($observation->special_needs)
                                    <span class="badge bg-warning text-dark">Nec. especial</span>
                                @endif
                            </div>
                            <p class="mb-2">{{ $observation->observations }}</p>
                            @can('manage_observations')
                                <form method="POST"
                                    action="{{ route('students.support.observations.destroy', [$student, $observation]) }}"
                                    onsubmit="return confirm('Remover esta observação?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash me-1"></i> Remover
                                    </button>
                                </form>
                            @endcan
                        </div>
                    @empty
                        <p class="text-muted mb-0">Nenhuma observação registada.</p>
                    @endforelse
                    <div class="mt-3">{{ $observations->links() }}</div>
                </div>
            </div>

            <div class="school-card">
                <div class="school-card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-folder-open"></i> Registos</span>
                    <span class="badge bg-success">{{ $records->total() }}</span>
                </div>
                <div class="school-card-body">
                    @forelse ($records as $record)
                        <div class="border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($record->record_date)->format('d/m/Y') }} por
                                    {{ $record->creator->name ?? 'Sistema' }}
                                </small>
                                <span class="badge bg-info text-dark">{{ $record->record_type_name }}</span>
                            </div>
                            <p class="mb-2">{{ $record->record_details }}</p>
                            @can('manage_student_records')
                                <form method="POST" action="{{ route('students.support.records.destroy', [$student, $record]) }}"
                                    onsubmit="return confirm('Remover este registo?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash me-1"></i> Remover
                                    </button>
                                </form>
                            @endcan
                        </div>
                    @empty
                        <p class="text-muted mb-0">Nenhum registo encontrado.</p>
                    @endforelse
                    <div class="mt-3">{{ $records->links() }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
