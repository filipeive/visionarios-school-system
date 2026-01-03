@extends('layouts.app')

@section('title', 'Portal dos Pais')
@section('page-title', 'Portal dos Pais')

@section('content')
    <div class="row mb-4">
        <!-- Resumo Financeiro -->
        <div class="col-md-4 mb-4 mb-md-0">
            <div class="school-card h-100">
                <div class="school-card-body d-flex flex-column justify-content-center align-items-center text-center">
                    <div class="text-muted small mb-2">Total a Pagar</div>
                    <div class="h2 fw-bold text-danger mb-2">
                        {{ number_format($totalPendingPayments, 2, ',', '.') }} MT
                    </div>
                    @if($totalPendingPayments > 0)
                        <a href="{{ route('parent.payments') }}" class="btn btn-sm btn-outline-danger rounded-pill px-4">
                            Ver Detalhes
                        </a>
                    @else
                        <span class="badge bg-success rounded-pill px-3">Em dia</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4 mb-md-0">
            <div class="school-card h-100">
                <div class="school-card-body d-flex flex-column justify-content-center align-items-center text-center">
                    <div class="text-muted small mb-2">Filhos Matriculados</div>
                    <div class="h2 fw-bold text-primary mb-0">
                        {{ $children->count() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="school-card h-100">
                <div class="school-card-body d-flex flex-column justify-content-center align-items-center text-center">
                    <div class="text-muted small mb-2">Novos Comunicados</div>
                    <div class="h2 fw-bold text-info mb-0">
                        {{ $recentCommunications->count() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Meus Filhos -->
        <div class="col-12">
            <div class="school-card">
                <div class="school-card-header">
                    <i class="fas fa-child me-2"></i> Meus Filhos
                </div>
                <div class="school-card-body">
                    <div class="row">
                        @foreach($children as $child)
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center border rounded p-3 hover-shadow transition">
                                    <div class="flex-shrink-0 me-3">
                                        @if($child->photo_url)
                                            <img src="{{ $child->photo_url }}" alt="{{ $child->first_name }}" class="rounded-circle"
                                                width="60" height="60" style="object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-secondary"
                                                style="width: 60px; height: 60px;">
                                                <i class="fas fa-user fa-lg"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1 fw-bold">{{ $child->full_name }}</h5>
                                        <p class="text-muted small mb-2">
                                            {{ $child->currentEnrollment->class->name ?? 'Sem turma' }}
                                        </p>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('parent.student-details', $child) }}"
                                                class="btn btn-sm btn-primary-school">
                                                <i class="fas fa-id-card me-1"></i> Detalhes
                                            </a>
                                            <a href="{{ route('parent.student-payments', $child) }}"
                                                class="btn btn-sm btn-success-school">
                                                <i class="fas fa-money-bill-wave me-1"></i> Pagamentos
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Comunicados Recentes -->
        <div class="col-12">
            <div class="school-card">
                <div class="school-card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-bullhorn me-2"></i> Comunicados Recentes</span>
                    <a href="{{ route('parent.communications') }}" class="text-white text-decoration-none small">
                        Ver todos <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="school-card-body">
                    @forelse($recentCommunications as $comm)
                        <div class="border-bottom pb-3 mb-3 last:border-0 last:pb-0 last:mb-0">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <h6 class="fw-bold mb-0 text-primary">{{ $comm->title }}</h6>
                                <small class="text-muted">{{ $comm->created_at->format('d/m/Y') }}</small>
                            </div>
                            <p class="text-secondary small mb-0 text-truncate">{{ $comm->message }}</p>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                            Nenhum comunicado recente.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection