@extends('layouts.app')

@section('title', 'Cadastrar Professor')
@section('page-title', 'Cadastrar Professor')
@section('page-title-icon', 'fas fa-user-plus')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('teachers.index') }}">Professores</a></li>
    <li class="breadcrumb-item active">Cadastrar</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="school-card">
            <div class="school-card-header">
                <i class="fas fa-user-plus"></i>
                Dados do Professor
            </div>
            <div class="school-card-body">
                <form action="{{ route('teachers.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Primeiro Nome *</label>
                            <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" 
                                   value="{{ old('first_name') }}" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Último Nome *</label>
                            <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" 
                                   value="{{ old('last_name') }}" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Telefone *</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                   value="{{ old('phone') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Número do BI *</label>
                            <input type="text" name="bi_number" class="form-control @error('bi_number') is-invalid @enderror" 
                                   value="{{ old('bi_number') }}" required>
                            @error('bi_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gênero *</label>
                            <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                                <option value="">Selecione</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Masculino</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Feminino</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Data de Nascimento *</label>
                            <input type="date" name="birth_date" class="form-control @error('birth_date') is-invalid @enderror" 
                                   value="{{ old('birth_date') }}" required>
                            @error('birth_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Data de Contratação *</label>
                            <input type="date" name="hire_date" class="form-control @error('hire_date') is-invalid @enderror" 
                                   value="{{ old('hire_date', date('Y-m-d')) }}" required>
                            @error('hire_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Qualificação *</label>
                            <input type="text" name="qualification" class="form-control @error('qualification') is-invalid @enderror" 
                                   value="{{ old('qualification') }}" placeholder="Ex: Licenciatura, Mestrado..." required>
                            @error('qualification')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Especialização *</label>
                            <input type="text" name="specialization" class="form-control @error('specialization') is-invalid @enderror" 
                                   value="{{ old('specialization') }}" placeholder="Ex: Matemática, Português..." required>
                            @error('specialization')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Salário (MZN) *</label>
                            <input type="number" step="0.01" name="salary" class="form-control @error('salary') is-invalid @enderror" 
                                   value="{{ old('salary', 15000.00) }}" required>
                            @error('salary')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Endereço *</label>
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                                      rows="3" required>{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="create_user_account" id="create_user_account" value="1" {{ old('create_user_account') ? 'checked' : '' }}>
                                <label class="form-check-label" for="create_user_account">
                                    <strong>Criar conta de usuário para o professor</strong>
                                </label>
                                <small class="text-muted d-block">
                                    O professor receberá acesso ao sistema com email e senha padrão "password123"
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('teachers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-primary-school">
                            <i class="fas fa-save"></i> Cadastrar Professor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection