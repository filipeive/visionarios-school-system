@extends('layouts.app')

@section('title', 'Editar Usuário')
@section('page-title', 'Editar Usuário')
@section('title-icon', 'fas fa-user-edit')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Usuários</a></li>
    <li class="breadcrumb-item active">Editar {{ $user->name }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="school-card">
            <div class="school-card-header">
                <i class="fas fa-user-edit"></i>
                Editar Usuário: {{ $user->name }}
            </div>

            <div class="school-card-body">
                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PATCH')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nome Completo *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">Nova Senha</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" placeholder="Deixe em branco para manter a atual">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Mínimo 8 caracteres</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirmar Nova Senha</label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="role" class="form-label">Perfil *</label>
                                <select class="form-select @error('role') is-invalid @enderror" 
                                        id="role" name="role" required>
                                    <option value="">Selecione um perfil...</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" 
                                            {{ (old('role', $current_role) == $role->name) ? 'selected' : '' }}>
                                            {{ ucfirst($role->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status *</label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Ativo</option>
                                    <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inativo</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Telefone</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Campos específicos para professores -->
                    <div id="teacher-fields" style="display: {{ $current_role == 'teacher' ? 'block' : 'none' }};">
                        <hr>
                        <h6><i class="fas fa-chalkboard-teacher text-primary me-2"></i>Informações do Professor</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="qualification" class="form-label">Qualificação</label>
                                    <input type="text" class="form-control" id="qualification" name="qualification" 
                                           value="{{ old('qualification', $teacher_data->qualification ?? '') }}" 
                                           placeholder="Ex: Licenciatura em Matemática">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="specialization" class="form-label">Especialização</label>
                                    <input type="text" class="form-control" id="specialization" name="specialization" 
                                           value="{{ old('specialization', $teacher_data->specialization ?? '') }}" 
                                           placeholder="Ex: Matemática e Física">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Voltar
                        </a>
                        <div>
                            <button type="submit" class="btn btn-school btn-primary-school">
                                <i class="fas fa-save me-2"></i> Atualizar Usuário
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
document.getElementById('role').addEventListener('change', function() {
    const teacherFields = document.getElementById('teacher-fields');
    if (this.value === 'teacher') {
        teacherFields.style.display = 'block';
    } else {
        teacherFields.style.display = 'none';
    }
});
</script>
@endpush