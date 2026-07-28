@extends('layouts.app')

@section('title', 'Gestão de Ano Lectivo')
@section('page-title', 'Gestão de Ano Lectivo')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="school-card">
                <div class="school-card-header bg-primary-school text-white">
                    <h3 class="school-card-title"><i class="fas fa-calendar-alt me-2"></i> Configuração do Ano Lectivo</h3>
                </div>
                <div class="school-card-body p-4">
                    <div class="row mb-4 text-center">
                        <div class="col-md-6 border-end">
                            <h6 class="text-muted mb-1">Ano Lectivo Actual</h6>
                            <div class="h2 fw-bold text-primary">{{ $currentYear }}</div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">Próximo Ano (Preparação)</h6>
                            <div class="h2 fw-bold text-success">{{ $nextYear }}</div>
                        </div>
                    </div>

                    <div class="alert alert-info border-0 shadow-sm mb-4">
                        <h5 class="alert-heading"><i class="fas fa-info-circle me-2"></i> Como funciona a transição?</h5>
                        <p class="mb-0">Ao iniciar a transição para o novo ano lectivo:</p>
                        <ul class="mt-2 mb-0">
                            <li>O <strong>Ano Actual</strong> passará a ser <strong>{{ $nextYear }}</strong>.</li>
                            <li>Todas as matrículas activas de <strong>{{ $currentYear }}</strong> serão marcadas como
                                <strong>Concluídas</strong>.</li>
                            <li>Os alunos serão marcados como <strong>Inactivos</strong>, o que activará o botão de
                                <strong>Renovação</strong> no portal dos pais.</li>
                            <li>O sistema estará pronto para receber novas inscrições para <strong>{{ $nextYear }}</strong>.
                            </li>
                        </ul>
                    </div>

                    <div class="card border-warning mb-4">
                        <div class="card-body bg-light">
                            <h5 class="text-warning"><i class="fas fa-exclamation-triangle me-2"></i> Atenção!</h5>
                            <p class="mb-0">Esta acção é irreversível e deve ser feita apenas quando o ano lectivo actual
                                terminar e os resultados finais forem publicados.</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.academic-years.transition') }}" method="POST"
                        onsubmit="return confirm('Tem certeza que deseja iniciar a transição para o novo ano lectivo? Esta acção afectará todas as matrículas activas.')">
                        @csrf
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary-school btn-lg py-3">
                                <i class="fas fa-forward me-2"></i> Iniciar Transição para {{ $nextYear }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection