@extends('layouts.school')

@section('title', 'Novo Aluno')
@section('page-title', 'Cadastrar Novo Aluno')

@php
    $titleIcon = 'fas fa-user-plus';
@endphp

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Alunos</a></li>
    <li class="breadcrumb-item active">Novo Aluno</li>
@endsection

@section('content')
<form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="row">
        <!-- Dados Pessoais -->
        <div class="col-lg-8">
            <div class="school-card mb-4">
                <div class="school-card-header">
                    <i class="fas fa-user"></i>
                    Dados Pessoais do Aluno
                </div>
                <div class="school-card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Primeiro Nome <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" 
                                   value="{{ old('first_name') }}" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Último Nome <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" 
                                   value="{{ old('last_name') }}" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Género <span class="text-danger">*</span></label>
                            <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                                <option value="">Selecione...</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Masculino</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Feminino</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Data de Nascimento <span class="text-danger">*</span></label>
                            <input type="date" name="birthdate" class="form-control @error('birthdate') is-invalid @enderror" 
                                   value="{{ old('birthdate') }}" required max="{{ date('Y-m-d') }}">
                            @error('birthdate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Local de Nascimento</label>
                            <input type="text" name="birth_place" class="form-control @error('birth_place') is-invalid @enderror" 
                                   value="{{ old('birth_place') }}">
                            @error('birth_place')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Morada</label>
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                                      rows="2">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dados do Encarregado -->
            <div class="school-card mb-4">
                <div class="school-card-header">
                    <i class="fas fa-users"></i>
                    Encarregado de Educação
                </div>
                <div class="school-card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Encarregado <span class="text-danger">*</span></label>
                            <select name="parent_id" class="form-select @error('parent_id') is-invalid @enderror" required>
                                <option value="">Selecione...</option>
                                @foreach($parents as $parent)
                                    <option value="{{ $parent->user_id }}" {{ old('parent_id') == $parent->user_id ? 'selected' : '' }}>
                                        {{ $parent->full_name }} - {{ $parent->phone }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Se o encarregado não estiver na lista, cadastre-o primeiro</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mensalidade (MT) <span class="text-danger">*</span></label>
                            <input type="number" name="monthly_fee" class="form-control @error('monthly_fee') is-invalid @enderror" 
                                   value="{{ old('monthly_fee', 2500) }}" required min="0" step="0.01">
                            @error('monthly_fee')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Contacto de Emergência</label>
                            <input type="text" name="emergency_contact" class="form-control @error('emergency_contact') is-invalid @enderror" 
                                   value="{{ old('emergency_contact') }}">
                            @error('emergency_contact')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Telefone de Emergência</label>
                            <input type="text" name="emergency_phone" class="form-control @error('emergency_phone') is-invalid @enderror" 
                                   value="{{ old('emergency_phone') }}" placeholder="+258 84 XXX XXXX">
                            @error('emergency_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Observações -->
            <div class="school-card mb-4">
                <div class="school-card-header">
                    <i class="fas fa-notes-medical"></i>
                    Observações e Necessidades Especiais
                </div>
                <div class="school-card-body">
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="has_special_needs" 
                                   id="has_special_needs" value="1" {{ old('has_special_needs') ? 'checked' : '' }}>
                            <label class="form-check-label" for="has_special_needs">
                                O aluno tem necessidades especiais
                            </label>
                        </div>
                    </div>

                    <div class="mb-3" id="special_needs_section" style="display: none;">
                        <label class="form-label">Descrição das Necessidades Especiais</label>
                        <textarea name="special_needs_description" class="form-control @error('special_needs_description') is-invalid @enderror" 
                                  rows="3">{{ old('special_needs_description') }}</textarea>
                        @error('special_needs_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Observações Gerais</label>
                        <textarea name="observations" class="form-control @error('observations') is-invalid @enderror" 
                                  rows="3">{{ old('observations') }}</textarea>
                        @error('observations')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Documentos e Foto -->
        <div class="col-lg-4">
            <div class="school-card mb-4">
                <div class="school-card-header">
                    <i class="fas fa-camera"></i>
                    Foto do Aluno
                </div>
                <div class="school-card-body text-center">
                    <div class="mb-3">
                        <img id="photo-preview" src="https://ui-avatars.com/api/?name=Aluno&background=2E7D32&color=fff&size=200" 
                             class="img-fluid rounded-circle mb-3" style="width: 200px; height: 200px; object-fit: cover;">
                    </div>
                    <input type="file" name="passport_photo" class="form-control @error('passport_photo') is-invalid @enderror" 
                           accept="image/*" id="photo-input">
                    @error('passport_photo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted d-block mt-2">Formatos aceitos: JPG, PNG (máx: 2MB)</small>
                </div>
            </div>

            <div class="school-card mb-4">
                <div class="school-card-header">
                    <i class="fas fa-file-medical"></i>
                    Certificado Médico
                </div>
                <div class="school-card-body">
                    <input type="file" name="medical_certificate" class="form-control @error('medical_certificate') is-invalid @enderror" 
                           accept=".pdf,.jpg,.jpeg,.png">
                    @error('medical_certificate')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted d-block mt-2">Formatos: PDF, JPG, PNG (máx: 5MB)</small>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn-school btn-primary-school">
                    <i class="fas fa-save"></i>
                    Cadastrar Aluno
                </button>
                <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i>
                    Cancelar
                </a>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview de foto
    const photoInput = document.getElementById('photo-input');
    const photoPreview = document.getElementById('photo-preview');
    
    if (photoInput) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    photoPreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Mostrar/ocultar seção de necessidades especiais
    const hasSpecialNeeds = document.getElementById('has_special_needs');
    const specialNeedsSection = document.getElementById('special_needs_section');
    
    if (hasSpecialNeeds && specialNeedsSection) {
        hasSpecialNeeds.addEventListener('change', function() {
            specialNeedsSection.style.display = this.checked ? 'block' : 'none';
        });
        
        // Verificar estado inicial
        if (hasSpecialNeeds.checked) {
            specialNeedsSection.style.display = 'block';
        }
    }
});
</script>
@endpush