@extends('layouts.app')

@section('title', 'Comunicados')
@section('page-title', 'Comunicados')

@section('content')
    <div class="row">
        <!-- Lista de Comunicados -->
        <div class="col-md-8">
            <div class="school-card mb-4">
                <div class="school-card-header">
                    <i class="fas fa-bullhorn me-2"></i> Últimos Comunicados
                </div>
                <div class="school-card-body">
                    @forelse($communications as $comm)
                        <div class="card mb-3 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title fw-bold text-primary">{{ $comm->title }}</h5>
                                    <small class="text-muted">{{ $comm->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                                <div class="card-text text-secondary">
                                    {!! nl2br(e($comm->message)) !!}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>Nenhum comunicado encontrado.</p>
                        </div>
                    @endforelse

                    <div class="mt-4">
                        {{ $communications->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Enviar Mensagem -->
        <div class="col-md-4">
            <div class="school-card sticky-top" style="top: 100px; z-index: 1;">
                <div class="school-card-header bg-secondary text-white">
                    <i class="fas fa-paper-plane me-2"></i> Fale Conosco
                </div>
                <div class="school-card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('parent.send-message') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="subject" class="form-label fw-bold">Assunto</label>
                            <input type="text" name="subject" id="subject" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label fw-bold">Mensagem</label>
                            <textarea name="message" id="message" rows="5" class="form-control" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary-school w-100">
                            <i class="fas fa-paper-plane me-2"></i> Enviar Mensagem
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection