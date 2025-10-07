@extends('layouts.app')

@section('title', 'Nova Turma')
@section('page-title', 'Nova Turma')
@section('page-title-icon', 'fas fa-plus-circle')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('classes.index') }}">Turmas</a></li>
    <li class="breadcrumb-item active">Nova Turma</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="school-card">
            <div class="school-card-header">
                <h3 class="school-card-title">
                    <i class="fas fa-chalkboard"></i>
                    Criar Nova Turma
                </h3>
            </div>
            <div class="school-card-body">
                <form action="{{ route('classes.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nome da Turma *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="grade_level" class="form-label">Nível de Ensino *</label>
                                <select class="form-select @error('grade_level') is-invalid @enderror" 
                                        id="grade_level" name="grade_level" required>
                                    <option value="">Selecione o nível...</option>
                                    @foreach($gradeLevels as $key => $level)
                                        <option value="{{ $key }}" {{ old('grade_level') == $key ? 'selected' : '' }}>
                                            {{ $level }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('grade_level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="teacher_id" class="form-label">Professor Responsável</label>
                                <select class="form-select @error('teacher_id') is-invalid @enderror" 
                                        id="teacher_id" name="teacher_id">
                                    <option value="">Selecione o professor...</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->first_name }} {{ $teacher->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('teacher_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="max_students" class="form-label">Capacidade Máxima *</label>
                                <input type="number" class="form-control @error('max_students') is-invalid @enderror" 
                                       id="max_students" name="max_students" value="{{ old('max_students', 35) }}" 
                                       min="1" max="50" required>
                                @error('max_students')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="classroom" class="form-label">Sala de Aula</label>
                                <input type="text" class="form-control @error('classroom') is-invalid @enderror" 
                                       id="classroom" name="classroom" value="{{ old('classroom') }}">
                                @error('classroom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="school_year" class="form-label">Ano Letivo *</label>
                                <input type="number" class="form-control @error('school_year') is-invalid @enderror" 
                                       id="school_year" name="school_year" value="{{ old('school_year', $currentYear) }}" 
                                       min="2020" max="2030" required>
                                @error('school_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Disciplinas</label>
                        <div class="row">
                            @foreach($subjects as $subject)
                                <div class="col-md-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               name="subjects[]" value="{{ $subject->id }}" 
                                               id="subject_{{ $subject->id }}"
                                               {{ in_array($subject->id, old('subjects', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="subject_{{ $subject->id }}">
                                            {{ $subject->name }} ({{ $subject->code }})
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('subjects')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">Turma Ativa</label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('classes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary-school">
                            <i class="fas fa-save"></i> Criar Turma
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection