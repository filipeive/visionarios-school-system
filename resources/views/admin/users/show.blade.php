@extends('layouts.app')

@section('title', 'Detalhes do Usuário')
@section('page-title', 'Detalhes do Usuário')
@section('title-icon', 'fas fa-user')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Usuários</a></li>
    <li class="breadcrumb-item active">{{ $user->name }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <!-- Perfil Card -->
            <div class="school-card mb-4">
                <div class="school-card-body text-center">
                    <div class="mb-3">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}"
                                class="rounded-circle img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto"
                                style="width: 150px; height: 150px;">
                                <span class="display-4 text-secondary">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                    <h4 class="mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-3">{{ $user->email }}</p>

                    <div class="mb-3">
                        @foreach($user->roles as $role)
                            <span class="badge bg-primary">{{ ucfirst($role->name) }}</span>
                        @endforeach
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit me-2"></i> Editar Perfil
                        </a>
                    </div>
                </div>
            </div>

            <!-- Status Card -->
            <div class="school-card mb-4">
                <div class="school-card-header">
                    Status da Conta
                </div>
                <div class="school-card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Status Atual:</span>
                        @if($user->status === 'active')
                            <span class="badge bg-success">Ativo</span>
                        @else
                            <span class="badge bg-danger">Inativo</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Registrado em:</span>
                        <span>{{ $user->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Detalhes Pessoais -->
            <div class="school-card mb-4">
                <div class="school-card-header">
                    <i class="fas fa-info-circle me-2"></i> Informações Pessoais
                </div>
                <div class="school-card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">Nome Completo:</div>
                        <div class="col-sm-8">{{ $user->name }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">Email:</div>
                        <div class="col-sm-8">{{ $user->email }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">Telefone:</div>
                        <div class="col-sm-8">{{ $user->phone ?? 'Não informado' }}</div>
                    </div>

                    @if($user->hasRole('teacher') && $user->teacher)
                        <hr>
                        <h6 class="mb-3 text-primary">Dados do Professor</h6>
                        <div class="row mb-3">
                            <div class="col-sm-4 fw-bold">Qualificação:</div>
                            <div class="col-sm-8">{{ $user->teacher->qualification }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 fw-bold">Especialização:</div>
                            <div class="col-sm-8">{{ $user->teacher->specialization }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 fw-bold">Data de Contratação:</div>
                            <div class="col-sm-8">
                                {{ $user->teacher->hire_date ? $user->teacher->hire_date->format('d/m/Y') : '-' }}</div>
                        </div>
                    @endif

                    @if($user->hasRole('parent') && $user->parent)
                        <hr>
                        <h6 class="mb-3 text-primary">Dados do Encarregado</h6>
                        <div class="row mb-3">
                            <div class="col-sm-4 fw-bold">Grau de Parentesco:</div>
                            <div class="col-sm-8">{{ $user->parent->relationship }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 fw-bold">Filhos Matriculados:</div>
                            <div class="col-sm-8">
                                @if($user->parent->students->count() > 0)
                                    <ul class="list-unstyled mb-0">
                                        @foreach($user->parent->students as $student)
                                            <li>
                                                <a href="#" class="text-decoration-none">
                                                    {{ $student->first_name }} {{ $student->last_name }}
                                                </a>
                                                <small class="text-muted">({{ $student->currentClass->name ?? 'Sem turma' }})</small>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-muted">Nenhum filho associado</span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Histórico de Login (Simulado conforme Controller) -->
            <div class="school-card">
                <div class="school-card-header">
                    <i class="fas fa-history me-2"></i> Histórico de Acesso Recente
                </div>
                <div class="school-card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Data/Hora</th>
                                    <th>IP</th>
                                    <th>Navegador</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($loginHistory as $history)
                                    <tr>
                                        <td>{{ $history['login_at']->format('d/m/Y H:i') }}</td>
                                        <td>{{ $history['ip_address'] }}</td>
                                        <td>
                                            <small class="text-muted" title="{{ $history['user_agent'] }}">
                                                {{ Str::limit($history['user_agent'], 30) }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($history['success'])
                                                <span class="badge bg-success">Sucesso</span>
                                            @else
                                                <span class="badge bg-danger">Falha</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Nenhum registro encontrado.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection