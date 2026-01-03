@extends('layouts.app')

@section('title', 'Minhas Notificações')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="school-card">
                    <div class="school-card-header d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-bell me-2"></i> Minhas Notificações</span>
                        <div class="d-flex gap-2">
                            <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill">
                                    <i class="fas fa-check-double me-1"></i> Marcar todas como lidas
                                </button>
                            </form>
                            <form action="{{ route('notifications.clear-all') }}" method="POST"
                                onsubmit="return confirm('Tem certeza que deseja remover todas as notificações?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">
                                    <i class="fas fa-trash-alt me-1"></i> Limpar tudo
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="school-card-body">
                        @forelse($notifications as $notification)
                            <div
                                class="notification-item p-3 border-bottom {{ $notification->read_at ? 'opacity-75' : 'bg-light border-start border-primary border-4' }}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-1 {{ $notification->read_at ? 'text-muted' : 'text-primary' }}">
                                            {{ $notification->data['title'] ?? 'Notificação' }}
                                        </h6>
                                        <p class="mb-1 text-secondary">{{ $notification->data['message'] ?? '' }}</p>
                                        <div class="d-flex align-items-center gap-3">
                                            <small class="text-muted">
                                                <i class="far fa-clock me-1"></i>
                                                {{ $notification->created_at->diffForHumans() }}
                                            </small>
                                            @if(isset($notification->data['action_url']) && $notification->data['action_url'] !== '#')
                                                <a href="{{ $notification->data['action_url'] }}"
                                                    class="btn btn-link btn-sm p-0 text-decoration-none">
                                                    Ver detalhes <i class="fas fa-external-link-alt ms-1 small"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="ms-3">
                                        @if(!$notification->read_at)
                                            <form action="{{ route('notifications.mark-read', $notification->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-light rounded-circle"
                                                    title="Marcar como lida">
                                                    <i class="fas fa-check text-success"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted">
                                <div class="mb-3">
                                    <i class="fas fa-bell-slash fa-3x opacity-25"></i>
                                </div>
                                <h5>Nenhuma notificação encontrada</h5>
                                <p>Você está em dia com todas as suas notificações.</p>
                            </div>
                        @endforelse

                        <div class="mt-4">
                            {{ $notifications->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .notification-item {
            transition: all 0.2s ease;
        }

        .notification-item:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }
    </style>
@endsection