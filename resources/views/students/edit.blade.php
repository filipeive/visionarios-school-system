@extends('layouts.app')

@section('title', 'Editar ' . $student->full_name)
@section('page-title', 'Editar ' . $student->full_name)
@section('title-icon', 'fas fa-user-edit')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Alunos</a></li>
    <li class="breadcrumb-item"><a href="{{ route('students.show', $student) }}">{{ $student->full_name }}</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="school-card">
            <div class="school-card-header">
                <i class="fas fa-user-edit"></i>
                Editar Aluno: {{ $student->full_name }}
            </div>

            <div class="school-card-body">
                <form method="POST" action="{{ route('students.update', $student) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

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
                                               id="first_name" name="first_name" 
                                               value="{{ old('first_name', $student->first_name) }}" required>
                                        @error('first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="last_name" class="form-label">Sobrenome *</label>
                                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                               id="last_name" name="last_name" 
                                               value="{{ old('last_name', $student->last_name) }}" required>
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
                                            <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>Masculino</option>
                                            <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>Feminino</option>
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
                                               id="birthdate" name="birthdate" 
                                               value="{{ old('birthdate', $student->birthdate?->format('Y-m-d')) }}" required>
                                        @error('birthdate')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="birth_place" class="form-label">Local de Nascimento *</label>
                                <input type="text" class="form-control @error('birth_place') is-invalid @enderror" 
                                       id="birth_place" name="birth_place" 
                                       value="{{ old('birth_place', $student->birth_place) }}" required>
                                @error('birth_place')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="passport_photo" class="form-label">Foto do Aluno</label>
                                @if($student->passport_photo)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($student->passport_photo) }}" 
                                         alt="{{ $student->full_name }}" 
                                         class="rounded" 
                                         style="width: 100px; height: 100px; object-fit: cover;">
                                    <br>
                                    <small class="text-muted">Foto atual</small>
                                </div>
                                @endif
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
                                          id="address" name="address" rows="2" required>{{ old('address', $student->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="parent_id" class="form-label">Encarregado de Educação *</label>
                                <select class="form-select @error('parent_id') is-invalid @enderror" 
                                        id="parent_id" name="parent_id" required>
                                    <option value="">Selecione o encarregado...</option>
                                    @foreach($parents as $parent)
                                        <option value="{{ $parent->user_id }}" {{ old('parent_id', $student->parent_id) == $parent->user_id ? 'selected' : '' }}>
                                            {{ $parent->first_name }} {{ $parent->last_name }} 
                                            ({{ $parent->user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('parent_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="emergency_contact" class="form-label">Contacto de Emergência *</label>
                                        <input type="text" class="form-control @error('emergency_contact') is-invalid @enderror" 
                                               id="emergency_contact" name="emergency_contact" 
                                               value="{{ old('emergency_contact', $student->emergency_contact) }}" required>
                                        @error('emergency_contact')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="emergency_phone" class="form-label">Telefone de Emergência *</label>
                                        <input type="text" class="form-control @error('emergency_phone') is-invalid @enderror" 
                                               id="emergency_phone" name="emergency_phone" 
                                               value="{{ old('emergency_phone', $student->emergency_phone) }}" required>
                                        @error('emergency_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="mb-3 text-primary">
                                <i class="fas fa-file-medical me-2"></i>Informações Médicas & Educacionais
                            </h5>

                            <div class="mb-3">
                                <label for="medical_info" class="form-label">Informações Médicas</label>
                                <textarea class="form-control @error('medical_info') is-invalid @enderror" 
                                          id="medical_info" name="medical_info" rows="3"
                                          placeholder="Alergias, condições médicas, medicamentos...">{{ old('medical_info', $student->medical_certificate) }}</textarea>
                                @error('medical_info')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                           id="has_special_needs" name="has_special_needs" value="1"
                                           {{ old('has_special_needs', $student->has_special_needs) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="has_special_needs">
                                        Tem necessidades educacionais especiais
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3" id="special_needs_field" 
                                 style="display: {{ $student->has_special_needs ? 'block' : 'none' }};">
                                <label for="special_needs_description" class="form-label">Descrição das Necessidades Especiais</label>
                                <textarea class="form-control @error('special_needs_description') is-invalid @enderror" 
                                          id="special_needs_description" name="special_needs_description" rows="3">{{ old('special_needs_description', $student->special_needs_description) }}</textarea>
                                @error('special_needs_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h5 class="mb-3 text-primary">
                                <i class="fas fa-cog me-2"></i>Configurações do Aluno
                            </h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="monthly_fee" class="form-label">Mensalidade (MT) *</label>
                                        <input type="number" class="form-control @error('monthly_fee') is-invalid @enderror" 
                                               id="monthly_fee" name="monthly_fee" 
                                               value="{{ old('monthly_fee', $student->monthly_fee) }}" 
                                               min="0" step="0.01" required>
                                        @error('monthly_fee')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status *</label>
                                        <select class="form-select @error('status') is-invalid @enderror" 
                                                id="status" name="status" required>
                                            <option value="active" {{ old('status', $student->status) == 'active' ? 'selected' : '' }}>Ativo</option>
                                            <option value="inactive" {{ old('status', $student->status) == 'inactive' ? 'selected' : '' }}>Inativo</option>
                                            <option value="transferred" {{ old('status', $student->status) == 'transferred' ? 'selected' : '' }}>Transferido</option>
                                            <option value="graduated" {{ old('status', $student->status) == 'graduated' ? 'selected' : '' }}>Formado</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="class_id" class="form-label">Turma Atual</label>
                                <select class="form-select @error('class_id') is-invalid @enderror" 
                                        id="class_id" name="class_id">
                                    <option value="">Selecione a turma...</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" 
                                            {{ old('class_id', $currentEnrollment?->class_id) == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }} ({{ $class->grade_level }}º Ano)
                                        </option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="observations" class="form-label">Observações</label>
                                <textarea class="form-control @error('observations') is-invalid @enderror" 
                                          id="observations" name="observations" rows="3">{{ old('observations', $student->observations) }}</textarea>
                                @error('observations')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('students.show', $student) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Voltar ao Perfil
                        </a>
                        <div>
                            <a href="{{ route('students.index') }}" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-times me-2"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-school btn-primary-school">
                                <i class="fas fa-save me-2"></i> Atualizar Aluno
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('has_special_needs').addEventListener('change', function() {
    const specialNeedsField = document.getElementById('special_needs_field');
    specialNeedsField.style.display = this.checked ? 'block' : 'none';
});
</script>
@endpush