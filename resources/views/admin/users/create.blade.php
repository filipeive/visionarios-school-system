@extends('layouts.app')

@section('title', 'Novo Usuário')
@section('page-title', 'Criar Novo Usuário')
@section('title-icon', 'fas fa-user-plus')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Usuários</a></li>
    <li class="breadcrumb-item active">Novo Usuário</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="school-card">
                <div class="school-card-header">
                    <i class="fas fa-user-plus"></i>
                    Cadastrar Novo Usuário
                </div>

                <div class="school-card-body">
                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nome Completo *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                        name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                        name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Senha *</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirmar Senha *</label>
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="role" class="form-label">Perfil *</label>
                                    <select class="form-select @error('role') is-invalid @enderror" id="role" name="role"
                                        required>
                                        <option value="">Selecione um perfil...</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
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
                                    <select class="form-select @error('status') is-invalid @enderror" id="status"
                                        name="status" required>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Ativo
                                        </option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inativo
                                        </option>
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
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                                        name="phone" value="{{ old('phone') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Campos específicos por perfil -->
                        <div id="teacher-fields" style="display: none;">
                            <hr>
                            <h6><i class="fas fa-chalkboard-teacher text-primary me-2"></i>Informações do Professor</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="qualification" class="form-label">Qualificação</label>
                                        <input type="text" class="form-control" id="qualification" name="qualification"
                                            value="{{ old('qualification') }}" placeholder="Ex: Licenciatura em Matemática">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="specialization" class="form-label">Especialização</label>
                                        <input type="text" class="form-control" id="specialization" name="specialization"
                                            value="{{ old('specialization') }}" placeholder="Ex: Matemática e Física">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="parent-fields" style="display: none;">
                            <hr>
                            <h6><i class="fas fa-user-friends text-primary me-2"></i>Informações do Encarregado</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="relationship" class="form-label">Grau de Parentesco</label>
                                        <select class="form-select" id="relationship" name="relationship">
                                            <option value="Father">Pai</option>
                                            <option value="Mother">Mãe</option>
                                            <option value="Uncle">Tio</option>
                                            <option value="Aunt">Tia</option>
                                            <option value="Grandfather">Avô</option>
                                            <option value="Grandmother">Avó</option>
                                            <option value="Brother">Irmão</option>
                                            <option value="Sister">Irmã</option>
                                            <option value="Other" selected>Outro</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Voltar
                            </a>
                            <button type="submit" class="btn btn-school btn-primary-school">
                                <i class="fas fa-save me-2"></i> Criar Usuário
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('role').addEventListener('change', function () {
            const teacherFields = document.getElementById('teacher-fields');
            const parentFields = document.getElementById('parent-fields');

            teacherFields.style.display = 'none';
            parentFields.style.display = 'none';

            if (this.value === 'teacher') {
                teacherFields.style.display = 'block';
            } else if (this.value === 'parent') {
                parentFields.style.display = 'block';
            }
        });

        // Trigger change on page load
        document.getElementById('role').dispatchEvent(new Event('change'));
    </script>
@endpush