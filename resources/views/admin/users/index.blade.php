@extends('layouts.app')

@section('title', 'Gestão de Usuários')
@section('page-title', 'Gestão de Usuários')
@section('title-icon', 'fas fa-users-cog')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Usuários do Sistema</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Estatísticas Rápidas -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon students">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $totalUsers }}</div>
                        <div class="stat-label">Total de Usuários</div>
                        <span class="stat-change positive">
                            <i class="fas fa-arrow-up"></i> {{ $activeUsers }} ativos
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon teachers">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ \App\Models\Teacher::count() }}</div>
                        <div class="stat-label">Professores</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon payments">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ \App\Models\ParentModel::count() }}</div>
                        <div class="stat-label">Encarregados</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon events">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ \App\Models\User::role(['admin', 'secretary', 'pedagogy'])->count() }}</div>
                        <div class="stat-label">Administrativos</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Principal -->
        <div class="school-card">
            <div class="school-card-header">
                <i class="fas fa-users-cog"></i>
                Lista de Usuários do Sistema
                @can('create_users')
                <a href="{{ route('admin.users.create') }}" class="btn btn-school btn-primary-school ms-auto">
                    <i class="fas fa-plus"></i> Novo Usuário
                </a>
                @endcan
            </div>

            <div class="school-card-body">
                <!-- Filtros -->
                <form method="GET" class="row g-3 mb-4">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Pesquisar por nome ou email..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="role" class="form-select">
                            <option value="">Todos os Perfis</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Todos os Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Ativos</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inativos</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-school btn-primary-school w-100">
                            <i class="fas fa-filter"></i> Filtrar
                        </button>
                    </div>
                </form>

                <!-- Tabela de Usuários -->
                <div class="table-responsive">
                    <table class="table table-school table-hover">
                        <thead>
                            <tr>
                                <th>Usuário</th>
                                <th>Email</th>
                                <th>Perfil</th>
                                <th>Status</th>
                                <th>Último Login</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar me-3" style="width: 40px; height: 40px; font-size: 14px;">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <strong>{{ $user->name }}</strong>
                                                <br>
                                                <small class="text-muted">ID: {{ $user->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @foreach($user->roles as $role)
                                            <span class="badge 
                                                @switch($role->name)
                                                    @case('admin') bg-danger @break
                                                    @case('secretary') bg-warning @break
                                                    @case('pedagogy') bg-info @break
                                                    @case('teacher') bg-primary @break
                                                    @case('parent') bg-success @break
                                                    @default bg-secondary
                                                @endswitch">
                                                {{ ucfirst($role->name) }}
                                            </span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'danger' }}">
                                            {{ $user->status === 'active' ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($user->last_login)
                                            <small>{{ $user->last_login->format('d/m/Y H:i') }}</small>
                                            <br>
                                            <small class="text-muted">{{ $user->last_login->diffForHumans() }}</small>
                                        @else
                                            <span class="text-muted">Nunca</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.users.show', $user) }}" 
                                               class="btn btn-sm btn-outline-primary" title="Ver Detalhes">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @can('edit_users')
                                            <a href="{{ route('admin.users.edit', $user) }}" 
                                               class="btn btn-sm btn-outline-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endcan
                                            @can('delete_users')
                                            @if(!$user->hasRole('super_admin') && $user->id !== auth()->id())
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        title="Excluir" 
                                                        onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Nenhum usuário encontrado.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Mostrando {{ $users->firstItem() }} a {{ $users->lastItem() }} de {{ $users->total() }} registros
                    </div>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Exclusão -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir o usuário <strong id="userName"></strong>?</p>
                <p class="text-danger"><small>Esta ação não pode ser desfeita.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Excluir Usuário</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(userId, userName) {
    document.getElementById('userName').textContent = userName;
    document.getElementById('deleteForm').action = `/admin/users/${userId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

// Toggle status via AJAX
function toggleStatus(userId) {
    if (confirm('Deseja alterar o status deste usuário?')) {
        fetch(`/admin/users/${userId}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                showToast(data.error, 'error');
            }
        })
        .catch(error => {
            showToast('Erro ao alterar status', 'error');
        });
    }
}
</script>
@endpush