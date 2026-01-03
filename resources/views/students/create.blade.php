
@extends('layouts.app')

@section('title', 'Novo Aluno')
@section('page-title', 'Cadastrar Novo Aluno')
@section('title-icon', 'fas fa-user-plus')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Alunos</a></li>
    <li class="breadcrumb-item active">Novo Aluno</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="school-card">
            <div class="school-card-header">
                <i class="fas fa-user-plus"></i>
                Cadastrar Novo Aluno
            </div>

            <div class="school-card-body">
                <form method="POST" action="{{ route('students.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="mb-3 text-primary">
                                <i class="fas fa-user me-2"></i>Informações Pessoais
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="first_name" class="form-label">Nome *</label>
                                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                               id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                        @error('first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="last_name" class="form-label">Sobrenome *</label>
                                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                               id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                        @error('last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="gender" class="form-label">Gênero *</label>
                                        <select class="form-select @error('gender') is-invalid @enderror" 
                                                id="gender" name="gender" required>
                                            <option value="">Selecione...</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Masculino</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Feminino</option>
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="birthdate" class="form-label">Data de Nascimento *</label>
                                        <input type="date" class="form-control @error('birthdate') is-invalid @enderror" 
                                               id="birthdate" name="birthdate" value="{{ old('birthdate') }}" required>
                                        @error('birthdate')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="birth_place" class="form-label">Local de Nascimento *</label>
                                <input type="text" class="form-control @error('birth_place') is-invalid @enderror" 
                                       id="birth_place" name="birth_place" value="{{ old('birth_place') }}" 
                                       placeholder="Cidade, Província" required>
                                @error('birth_place')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="passport_photo" class="form-label">Foto do Aluno</label>
                                <input type="file" class="form-control @error('passport_photo') is-invalid @enderror" 
                                       id="passport_photo" name="passport_photo" accept="image/*">
                                @error('passport_photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Formatos: JPG, PNG, GIF. Máx: 2MB</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h5 class="mb-3 text-primary">
                                <i class="fas fa-address-card me-2"></i>Contactos e Morada
                            </h5>

                            <div class="mb-3">
                                <label for="address" class="form-label">Morada Completa *</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="2" required>{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Telefone</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                               id="phone" name="phone" value="{{ old('phone') }}"
                                               placeholder="+258 84 000 0000">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" name="email" value="{{ old('email') }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="parent_id" class="form-label">Encarregado de Educação *</label>
                                <select class="form-select @error('parent_id') is-invalid @enderror" 
                                        id="parent_id" name="parent_id" required>
                                    <option value="">Selecione o encarregado...</option>
                                    @foreach($parents as $parent)
                                        <option value="{{ $parent->user_id }}" {{ old('parent_id') == $parent->user_id ? 'selected' : '' }}>
                                            {{ $parent->first_name }} {{ $parent->last_name }} 
                                            ({{ $parent->user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('parent_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    <a href="#" class="text-decoration-none">Cadastrar novo encarregado</a>
                                </small>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="mb-3 text-primary">
                                <i class="fas fa-shield-alt me-2"></i>Informações de Emergência
                            </h5>

                            <div class="mb-3">
                                <label for="emergency_contact" class="form-label">Contacto de Emergência *</label>
                                <input type="text" class="form-control @error('emergency_contact') is-invalid @enderror" 
                                       id="emergency_contact" name="emergency_contact" 
                                       value="{{ old('emergency_contact') }}" required>
                                @error('emergency_contact')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="emergency_phone" class="form-label">Telefone de Emergência *</label>
                                <input type="text" class="form-control @error('emergency_phone') is-invalid @enderror" 
                                       id="emergency_phone" name="emergency_phone" 
                                       value="{{ old('emergency_phone') }}" required>
                                @error('emergency_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h5 class="mb-3 text-primary">
                                <i class="fas fa-file-medical me-2"></i>Informações Médicas & Financeiras
                            </h5>

                            <div class="mb-3">
                                <label for="medical_info" class="form-label">Informações Médicas</label>
                                <textarea class="form-control @error('medical_info') is-invalid @enderror" 
                                          id="medical_info" name="medical_info" rows="3"
                                          placeholder="Alergias, condições médicas, medicamentos...">{{ old('medical_info') }}</textarea>
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="has_special_needs" name="has_special_needs" value="1" {{ old('has_special_needs') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="has_special_needs">Necessidades Especiais?</label>
                                </div>
                            </div>

                            <div class="mb-3" id="special_needs_div" style="display: {{ old('has_special_needs') ? 'block' : 'none' }};">
                                <label for="special_needs_description" class="form-label">Descrição das Necessidades</label>
                                <textarea class="form-control @error('special_needs_description') is-invalid @enderror" 
                                          id="special_needs_description" name="special_needs_description" rows="2">{{ old('special_needs_description') }}</textarea>
                                @error('special_needs_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="monthly_fee" class="form-label">Mensalidade (MT) *</label>
                                <input type="number" class="form-control @error('monthly_fee') is-invalid @enderror" 
                                       id="monthly_fee" name="monthly_fee" value="{{ old('monthly_fee', 2000) }}" 
                                       min="0" step="0.01" required>
                                @error('monthly_fee')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="class_id" class="form-label">Turma (Opcional)</label>
                                <select class="form-select @error('class_id') is-invalid @enderror" 
                                        id="class_id" name="class_id">
                                    <option value="">Selecione a turma...</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }} ({{ $class->grade_level }}º Ano)
                                        </option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('students.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-school btn-primary-school">
                            <i class="fas fa-save me-2"></i> Cadastrar Aluno
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const hasSpecialNeedsCheckbox = document.getElementById('has_special_needs');
    const specialNeedsDiv = document.getElementById('special_needs_div');

    if (hasSpecialNeedsCheckbox && specialNeedsDiv) {
        hasSpecialNeedsCheckbox.addEventListener('change', function() {
            specialNeedsDiv.style.display = this.checked ? 'block' : 'none';
        });
    }
});
</script>
@endpush
@endsection