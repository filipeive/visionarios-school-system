@extends('layouts.app')

@section('title', 'Nova Disciplina')
@section('page-title', 'Nova Disciplina')
@section('page-title-icon', 'fas fa-plus-circle')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('subjects.index') }}">Disciplinas</a></li>
    <li class="breadcrumb-item active">Nova Disciplina</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="school-card">
            <div class="school-card-header">
                <h3 class="school-card-title">
                    <i class="fas fa-plus-circle"></i>
                    Criar Nova Disciplina
                </h3>
            </div>
            <div class="school-card-body">
                <form action="{{ route('subjects.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nome da Disciplina *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="code" class="form-label">Código *</label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                       id="code" name="code" value="{{ old('code') }}" 
                                       placeholder="Ex: MAT, LP, CN" required>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Código único para identificar a disciplina</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descrição</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4">
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
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="weekly_hours" class="form-label">Horas Semanais *</label>
                                <input type="number" class="form-control @error('weekly_hours') is-invalid @enderror" 
                                       id="weekly_hours" name="weekly_hours" value="{{ old('weekly_hours', 2) }}" 
                                       min="1" max="20" required>
                                @error('weekly_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                                    <label class="form-check-label" for="is_active">Disciplina Ativa</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('subjects.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary-school">
                            <i class="fas fa-save"></i> Criar Disciplina
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection