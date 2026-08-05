@extends('layouts.app')

@section('title', 'Minhas Notificações')
@section('page-title', 'Minhas Notificações')
@section('page-title-icon', 'fas fa-bell')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Início</a></li>
    <li class="breadcrumb-item active">Notificações</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">

        <!-- Stat KPI Cards -->
        @php
            $userNotifs = auth()->user()->notifications;
            $unreadCount = auth()->user()->unreadNotifications->count();
            $totalCount = $userNotifs->count();
            $recentCount = $userNotifs->where('created_at', '>=', now()->subDays(7))->count();
        @endphp

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-primary">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-primary-subtle text-primary rounded-circle p-3 me-3">
                            <i class="fas fa-bell fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Total de Notificações</div>
                            <h4 class="mb-0 text-primary font-weight-bold">{{ $totalCount }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-warning">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-warning-subtle text-warning rounded-circle p-3 me-3">
                            <i class="fas fa-envelope-open-text fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Não Lidas</div>
                            <h4 class="mb-0 text-warning font-weight-bold">{{ $unreadCount }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-info">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-info-subtle text-info rounded-circle p-3 me-3">
                            <i class="fas fa-calendar-week fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Últimos 7 Dias</div>
                            <h4 class="mb-0 text-info font-weight-bold">{{ $recentCount }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card de Notificações -->
        <div class="school-card">
            <div class="school-card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-list text-primary"></i>
                    <span class="fw-bold">Histórico de Notificações</span>
                    <span class="badge bg-primary rounded-pill">{{ $notifications->total() }}</span>
                </div>
                <div class="d-flex gap-2">
                    @if($unreadCount > 0)
                        <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-primary rounded-xl">
                                <i class="fas fa-check-double me-1"></i> Marcar todas como lidas
                            </button>
                        </form>
                    @endif
                    @if($totalCount > 0)
                        <form action="{{ route('notifications.clear-all') }}" method="POST"
                            onsubmit="return confirm('Tem certeza que deseja remover todas as notificações?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-xl">
                                <i class="fas fa-trash-alt me-1"></i> Limpar tudo
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            <div class="school-card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($notifications as $notification)
                        @php
                            $isUnread = !$notification->read_at;
                            $iconClass = match($notification->data['type'] ?? 'default') {
                                'payment' => 'fas fa-money-bill-wave text-success bg-success-subtle',
                                'warning' => 'fas fa-exclamation-triangle text-warning bg-warning-subtle',
                                'danger' => 'fas fa-circle-xmark text-danger bg-danger-subtle',
                                default => 'fas fa-bell text-primary bg-primary-subtle'
                            };
                        @endphp
                        <div class="list-group-item p-3 border-bottom transition {{ $isUnread ? 'bg-light border-start border-4 border-primary' : 'opacity-75' }}">
                            <div class="d-flex align-items-start justify-content-between gap-3">
                                <div class="d-flex align-items-start gap-3 flex-grow-1">
                                    <div class="rounded-circle p-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                                        <i class="{{ $iconClass }} fa-lg"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <h6 class="fw-bold mb-0 {{ $isUnread ? 'text-primary' : 'text-slate-700' }}">
                                                {{ $notification->data['title'] ?? 'Notificação' }}
                                            </h6>
                                            @if($isUnread)
                                                <span class="badge bg-primary-subtle text-primary rounded-pill px-2 py-0.5 text-xs">Nova</span>
                                            @endif
                                        </div>
                                        <p class="mb-1 text-secondary text-sm">{{ $notification->data['message'] ?? '' }}</p>
                                        <div class="d-flex align-items-center gap-3 mt-2">
                                            <small class="text-muted text-xs">
                                                <i class="far fa-clock me-1"></i>
                                                {{ $notification->created_at->diffForHumans() }}
                                            </small>
                                            @if(isset($notification->data['action_url']) && $notification->data['action_url'] !== '#')
                                                <a href="{{ $notification->data['action_url'] }}"
                                                    class="btn btn-sm btn-link p-0 text-decoration-none fw-semibold text-xs">
                                                    Ver detalhes <i class="fas fa-arrow-right ms-1"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @if($isUnread)
                                    <form action="{{ route('notifications.mark-read', $notification->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-light border rounded-circle"
                                            title="Marcar como lida" style="width: 34px; height: 34px;">
                                            <i class="fas fa-check text-success"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <div class="mb-3">
                                <i class="fas fa-bell-slash fa-3x text-slate-300"></i>
                            </div>
                            <h5>Nenhuma notificação encontrada</h5>
                            <p class="text-xs">Você está em dia com todas as suas notificações.</p>
                        </div>
                    @endforelse
                </div>

                @if($notifications->hasPages())
                    <div class="p-3 border-top">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection