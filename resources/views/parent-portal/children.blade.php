@extends('layouts.app')

@section('title', 'Meus Filhos')
@section('page-title', 'Meus Filhos')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="school-card">
                <div class="school-card-body">
                    <div class="row">
                        @forelse($children as $child)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="school-card h-100 border shadow-sm">
                                    <div class="school-card-body text-center">
                                        <div class="mb-3">
                                            @if($child->photo_url)
                                                <img src="{{ $child->photo_url }}" alt="{{ $child->full_name }}"
                                                    class="rounded-circle border border-3 border-light shadow-sm" width="100"
                                                    height="100" style="object-fit: cover;">
                                            @else
                                                <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center text-secondary border border-3 border-light shadow-sm"
                                                    style="width: 100px; height: 100px;">
                                                    <i class="fas fa-user fa-3x"></i>
                                                </div>
                                            @endif
                                        </div>

                                        <h5 class="fw-bold mb-3">{{ $child->full_name }}</h5>

                                        <div class="text-start bg-light p-3 rounded mb-3">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-muted small">Turma:</span>
                                                <span
                                                    class="fw-bold text-dark">{{ $child->currentEnrollment->class->name ?? 'N/A' }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-muted small">Número:</span>
                                                <span class="fw-bold text-dark">{{ $child->student_number }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted small">Idade:</span>
                                                <span class="fw-bold text-dark">{{ $child->age }} anos</span>
                                            </div>
                                        </div>

                                        <div class="d-grid gap-2">
                                            <a href="{{ route('parent.student-details', $child) }}"
                                                class="btn btn-primary-school">
                                                <i class="fas fa-id-card me-2"></i> Detalhes
                                            </a>
                                            <a href="{{ route('parent.student-payments', $child) }}"
                                                class="btn btn-success-school">
                                                <i class="fas fa-money-bill-wave me-2"></i> Pagamentos
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5">
                                <i class="fas fa-child fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Nenhum filho encontrado.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection