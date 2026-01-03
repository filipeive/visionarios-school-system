@extends('layouts.app')

@section('title', 'Novo Comunicado')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="school-card">
                <div class="school-card-header">
                    <i class="fas fa-plus-circle me-2"></i> Criar Novo Comunicado
                </div>
                <div class="school-card-body p-4">
                    <form action="{{ route('communications.send') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label fw-bold">Título do Comunicado <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" 
                                value="{{ old('title') }}" required placeholder="Ex: Reunião de Pais e Encarregados">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="target_audience" class="form-label fw-bold">Público-Alvo <span class="text-danger">*</span></label>
                                <select name="target_audience" id="target_audience" class="form-select @error('target_audience') is-invalid @enderror" required>
                                    <option value="all" {{ old('target_audience') == 'all' ? 'selected' : '' }}>Todos</option>
                                    <option value="parents" {{ old('target_audience') == 'parents' ? 'selected' : '' }}>Encarregados</option>
                                    <option value="teachers" {{ old('target_audience') == 'teachers' ? 'selected' : '' }}>Professores</option>
                                    <option value="students" {{ old('target_audience') == 'students' ? 'selected' : '' }}>Alunos</option>
                                </select>
                                @error('target_audience')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="priority" class="form-label fw-bold">Prioridade <span class="text-danger">*</span></label>
                                <select name="priority" id="priority" class="form-select @error('priority') is-invalid @enderror" required>
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Baixa</option>
                                    <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }} selected>Média</option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Alta</option>
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label fw-bold">Mensagem <span class="text-danger">*</span></label>
                            <textarea name="message" id="message" rows="6" class="form-control @error('message') is-invalid @enderror" 
                                required placeholder="Escreva aqui o conteúdo do comunicado...">{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_public" id="is_public" value="1" {{ old('is_public') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_public">Tornar este comunicado público (visível sem login)</label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('communications.index') }}" class="btn btn-light px-4 rounded-pill">Cancelar</a>
                            <button type="submit" class="btn btn-primary-school px-5 rounded-pill">
                                <i class="fas fa-paper-plane me-2"></i> Enviar Comunicado
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
