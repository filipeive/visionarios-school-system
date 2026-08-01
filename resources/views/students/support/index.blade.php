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
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-5 space-y-4">
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-4 py-3">
                    <h3 class="text-sm font-semibold text-slate-700"><i class="fas fa-user-graduate mr-2"></i>Dados do Aluno</h3>
                </div>
                <div class="p-4 text-sm space-y-2">
                    <h4 class="text-lg font-bold text-slate-900">{{ $student->full_name }}</h4>
                    <div class="text-slate-500">{{ $student->student_number }}</div>
                    <div><strong class="text-slate-700">Turma atual:</strong> {{ $student->currentEnrollment?->class?->name ?? 'Sem turma ativa' }}</div>
                    <div><strong class="text-slate-700">Encarregado:</strong>
                        {{ $student->parent ? $student->parent->first_name . ' ' . $student->parent->last_name : 'Não informado' }}
                    </div>
                </div>
            </div>

            @can('create_observations')
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-4 py-3">
                        <h3 class="text-sm font-semibold text-slate-700"><i class="fas fa-comment-medical mr-2"></i>Nova Observação</h3>
                    </div>
                    <div class="p-4">
                        <form method="POST" action="{{ route('students.support.observations.store', $student) }}">
                            @csrf
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Observação</label>
                                <textarea class="w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('observations') border-rose-400 @enderror" name="observations" rows="4"
                                    placeholder="Registre observações pedagógicas, comportamentais ou de acompanhamento...">{{ old('observations') }}</textarea>
                                @error('observations')
                                    <div class="text-xs text-rose-600 mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="flex items-center gap-2 mb-3">
                                <input class="rounded border-slate-300 text-blue-600 focus:ring-blue-500" type="checkbox" value="1" name="special_needs"
                                    id="special_needs" {{ old('special_needs') ? 'checked' : '' }}>
                                <label class="text-sm text-slate-700" for="special_needs">
                                    Envolve necessidade especial
                                </label>
                            </div>
                            <button class="inline-flex w-full items-center justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                                <i class="fas fa-save mr-2"></i>Guardar Observação
                            </button>
                        </form>
                    </div>
                </div>
            @endcan

            @can('manage_student_records')
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-4 py-3">
                        <h3 class="text-sm font-semibold text-slate-700"><i class="fas fa-folder-plus mr-2"></i>Novo Registo</h3>
                    </div>
                    <div class="p-4">
                        <form method="POST" action="{{ route('students.support.records.store', $student) }}">
                            @csrf
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Tipo</label>
                                <select class="w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('record_type') border-rose-400 @enderror" name="record_type">
                                    <option value="">Selecionar...</option>
                                    <option value="academic" @selected(old('record_type') === 'academic')>Acadêmico</option>
                                    <option value="disciplinary" @selected(old('record_type') === 'disciplinary')>Disciplinar</option>
                                    <option value="health" @selected(old('record_type') === 'health')>Saúde</option>
                                    <option value="achievement" @selected(old('record_type') === 'achievement')>Conquista</option>
                                    <option value="other" @selected(old('record_type') === 'other')>Outro</option>
                                </select>
                                @error('record_type')
                                    <div class="text-xs text-rose-600 mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Data do registo</label>
                                <input type="date" class="w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('record_date') border-rose-400 @enderror"
                                    name="record_date" value="{{ old('record_date', now()->toDateString()) }}">
                                @error('record_date')
                                    <div class="text-xs text-rose-600 mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Detalhes</label>
                                <textarea class="w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('record_details') border-rose-400 @enderror" name="record_details" rows="4"
                                    placeholder="Descrição do evento/registo...">{{ old('record_details') }}</textarea>
                                @error('record_details')
                                    <div class="text-xs text-rose-600 mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <button class="inline-flex w-full items-center justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                                <i class="fas fa-save mr-2"></i>Guardar Registo
                            </button>
                        </form>
                    </div>
                </div>
            @endcan
        </div>

        <div class="lg:col-span-7 space-y-4">
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-4 py-3 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-slate-700"><i class="fas fa-comments mr-2"></i>Observações</h3>
                    <span class="inline-flex rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-700">{{ $observations->total() }}</span>
                </div>
                <div class="p-4">
                    @forelse ($observations as $observation)
                        <div class="rounded-lg border border-slate-200 p-3 mb-3">
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <small class="text-slate-500">
                                    {{ $observation->created_at->format('d/m/Y H:i') }} por
                                    {{ $observation->creator->name ?? 'Sistema' }}
                                </small>
                                @if ($observation->special_needs)
                                    <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700">Nec. especial</span>
                                @endif
                            </div>
                            <p class="mb-2 text-sm text-slate-700">{{ $observation->observations }}</p>
                            @can('manage_observations')
                                <form method="POST"
                                    action="{{ route('students.support.observations.destroy', [$student, $observation]) }}"
                                    data-confirm="Remover esta observação?">
                                    @csrf
                                    @method('DELETE')
                                    <button class="inline-flex items-center rounded-md border border-rose-300 px-2.5 py-1 text-xs font-semibold text-rose-700 hover:bg-rose-50">
                                        <i class="fas fa-trash mr-1"></i>Remover
                                    </button>
                                </form>
                            @endcan
                        </div>
                    @empty
                        <p class="text-sm text-slate-500 mb-0">Nenhuma observação registada.</p>
                    @endforelse
                    <div class="mt-3">{{ $observations->links() }}</div>
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-4 py-3 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-slate-700"><i class="fas fa-folder-open mr-2"></i>Registos</h3>
                    <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">{{ $records->total() }}</span>
                </div>
                <div class="p-4">
                    @forelse ($records as $record)
                        <div class="rounded-lg border border-slate-200 p-3 mb-3">
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <small class="text-slate-500">
                                    {{ \Carbon\Carbon::parse($record->record_date)->format('d/m/Y') }} por
                                    {{ $record->creator->name ?? 'Sistema' }}
                                </small>
                                <span class="inline-flex rounded-full bg-cyan-100 px-2.5 py-1 text-xs font-semibold text-cyan-700">{{ $record->record_type_name }}</span>
                            </div>
                            <p class="mb-2 text-sm text-slate-700">{{ $record->record_details }}</p>
                            @can('manage_student_records')
                                <form method="POST" action="{{ route('students.support.records.destroy', [$student, $record]) }}"
                                    data-confirm="Remover este registo?">
                                    @csrf
                                    @method('DELETE')
                                    <button class="inline-flex items-center rounded-md border border-rose-300 px-2.5 py-1 text-xs font-semibold text-rose-700 hover:bg-rose-50">
                                        <i class="fas fa-trash mr-1"></i>Remover
                                    </button>
                                </form>
                            @endcan
                        </div>
                    @empty
                        <p class="text-sm text-slate-500 mb-0">Nenhum registo encontrado.</p>
                    @endforelse
                    <div class="mt-3">{{ $records->links() }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
