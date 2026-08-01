@extends('layouts.app')

@section('title', 'Portaria Digital — Controlo de Acesso')
@section('page-title', 'Portaria Digital')
@section('page-title-icon', 'fas fa-id-card-clip')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Portaria Digital</li>
@endsection

@section('content')
<div class="container-fluid px-0">
    <div class="row g-4">
        <!-- Coluna Esquerda: Verificação do Aluno -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-gradient-to-r from-emerald-800 to-teal-800 text-white p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="badge bg-amber-400 text-slate-900 font-bold px-3 py-1 rounded-full text-xs">
                                <i class="fas fa-shield-halved me-1"></i> Validação Instantânea
                            </span>
                            <h4 class="fw-black mb-0 mt-2 text-white">Controlo de Acesso Escolar</h4>
                        </div>
                        <i class="fas fa-barcode text-4xl text-emerald-200/50"></i>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Formulário de Pesquisa -->
                    <form action="{{ route('gatekeeper.index') }}" method="GET" class="mb-4">
                        <label for="gateSearch" class="form-label text-xs uppercase font-bold text-muted tracking-wider">
                            Pesquisar Aluno (Número de Matrícula ou Nome)
                        </label>
                        <div class="input-group input-group-lg shadow-xs rounded-3 overflow-hidden">
                            <span class="input-group-text bg-white border-end-0 text-slate-400 ps-3">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" id="gateSearch" name="search" class="form-control border-start-0 text-sm font-semibold"
                                   placeholder="Ex.: AL2026-0001 ou Patrícia Chissano..." 
                                   value="{{ request('search') }}" autofocus required>
                            <button type="submit" class="btn btn-emerald-700 text-white font-bold px-4 text-sm" style="background-color: #047857;">
                                Verificação
                            </button>
                        </div>
                    </form>

                    <!-- Resultado da Verificação -->
                    @if($searchedStudent)
                        <div class="border rounded-4 p-4 text-center position-relative bg-light/60">
                            <div class="mb-3">
                                @if ($searchedStudent->passport_photo)
                                    <img src="{{ Storage::url($searchedStudent->passport_photo) }}" alt="{{ $searchedStudent->full_name }}"
                                        class="rounded-circle shadow-sm border border-4 border-white mx-auto"
                                        style="width: 120px; height: 120px; object-fit: cover;">
                                @else
                                    <div class="bg-emerald-700 text-white rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm border border-4 border-white"
                                        style="width: 120px; height: 120px; font-size: 36px; font-weight: 800;">
                                        {{ substr($searchedStudent->first_name, 0, 1) }}{{ substr($searchedStudent->last_name, 0, 1) }}
                                    </div>
                                @endif
                            </div>

                            <h4 class="fw-bold mb-1 text-slate-900">{{ $searchedStudent->full_name }}</h4>
                            <p class="font-mono text-xs text-muted mb-3">{{ $searchedStudent->student_number }}</p>

                            @if($searchedStudent->status === 'active')
                                <div class="alert alert-success border-0 shadow-xs mb-3 py-2 px-3 d-inline-flex align-items-center gap-2">
                                    <i class="fas fa-check-circle text-lg"></i>
                                    <strong class="text-sm">ACESSO AUTORIZADO — MATRÍCULA ATIVA</strong>
                                </div>
                            @else
                                <div class="alert alert-danger border-0 shadow-xs mb-3 py-2 px-3 d-inline-flex align-items-center gap-2">
                                    <i class="fas fa-exclamation-triangle text-lg"></i>
                                    <strong class="text-sm">ATENÇÃO — ESTADO DA MATRÍCULA: {{ strtoupper($searchedStudent->status) }}</strong>
                                </div>
                            @endif

                            <div class="row g-2 text-start text-xs bg-white p-3 rounded-3 border mb-4">
                                <div class="col-6">
                                    <span class="text-muted d-block">Turma Atual:</span>
                                    <strong class="text-slate-800">{{ $searchedStudent->currentEnrollment->class->name ?? 'N/A' }}</strong>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted d-block">Sala de Aula:</span>
                                    <strong class="text-slate-800">{{ $searchedStudent->currentEnrollment->class->classroom ?? 'N/A' }}</strong>
                                </div>
                                <div class="col-6 mt-2">
                                    <span class="text-muted d-block">Encarregado:</span>
                                    <strong class="text-slate-800">{{ $searchedStudent->parent ? $searchedStudent->parent->first_name . ' ' . $searchedStudent->parent->last_name : 'N/A' }}</strong>
                                </div>
                                <div class="col-6 mt-2">
                                    <span class="text-muted d-block">Contacto Emergência:</span>
                                    <strong class="text-rose-600 font-mono">{{ $searchedStudent->emergency_phone }}</strong>
                                </div>
                            </div>

                            <!-- Botões de Registo de Passagem -->
                            <div class="d-flex gap-2">
                                <form action="{{ route('gatekeeper.log', $searchedStudent->id) }}" method="POST" class="flex-grow-1">
                                    @csrf
                                    <input type="hidden" name="action" value="entry">
                                    <button type="submit" class="btn btn-success w-100 py-2.5 rounded-pill font-bold text-xs shadow-xs">
                                        <i class="fas fa-right-to-bracket me-1"></i> Registar Entrada
                                    </button>
                                </form>
                                <form action="{{ route('gatekeeper.log', $searchedStudent->id) }}" method="POST" class="flex-grow-1">
                                    @csrf
                                    <input type="hidden" name="action" value="exit">
                                    <button type="submit" class="btn btn-outline-secondary w-100 py-2.5 rounded-pill font-bold text-xs shadow-xs">
                                        <i class="fas fa-right-from-bracket me-1"></i> Registar Saída
                                    </button>
                                </form>
                            </div>
                        </div>
                    @elseif(request('search'))
                        <div class="text-center py-5 border rounded-4 bg-light/30">
                            <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                            <h5 class="fw-bold text-slate-700">Aluno não encontrado</h5>
                            <p class="text-xs text-muted">Verifique o número de matrícula ou nome pesquisado e tente novamente.</p>
                        </div>
                    @else
                        <div class="text-center py-5 border rounded-4 bg-light/30">
                            <i class="fas fa-barcode fa-3x text-emerald-600 mb-3"></i>
                            <h5 class="fw-bold text-slate-800">Aguardando Leitura / Pesquisa</h5>
                            <p class="text-xs text-muted max-w-sm mx-auto">Digite o número de matrícula do aluno ou leia o código do cartão com o leitor óptico para validar a passagem na portaria.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Coluna Direita: Histórico Recente de Passagens do Dia -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white p-4 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold text-slate-800 mb-0">
                            <i class="fas fa-clock-rotate-left me-2 text-emerald-600"></i> Histórico do Dia
                        </h5>
                        <small class="text-xs text-muted">Registos de entrada e saída efetuados hoje</small>
                    </div>
                    <span class="badge bg-emerald-100 text-emerald-800 rounded-pill font-bold px-3 py-1 text-xs">
                        {{ $todayLogs->count() }} Passagens
                    </span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle text-sm mb-0">
                            <thead class="table-light text-xs uppercase tracking-wider">
                                <tr>
                                    <th class="ps-4">Hora</th>
                                    <th>Aluno</th>
                                    <th>Turma</th>
                                    <th>Observações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($todayLogs as $log)
                                    <tr>
                                        <td class="ps-4 font-mono fw-bold text-emerald-700">
                                            {{ $log->updated_at->format('H:i') }}
                                        </td>
                                        <td>
                                            <div class="fw-bold text-slate-800">{{ $log->student->full_name ?? 'N/A' }}</div>
                                            <div class="text-xs font-mono text-muted">{{ $log->student->student_number ?? '' }}</div>
                                        </td>
                                        <td>{{ $log->class->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs">
                                                <i class="fas fa-check me-1"></i> {{ $log->notes }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <i class="fas fa-history fa-2x mb-2 text-slate-300"></i>
                                            <p class="mb-0">Nenhuma passagem registada na portaria hoje.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
