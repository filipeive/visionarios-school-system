@extends('layouts.app')

@section('title', 'Portaria Digital — Controlo de Acesso')
@section('page-title', 'Portaria Digital')
@section('page-title-icon', 'fas fa-id-card-clip')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Início</a></li>
    <li class="breadcrumb-item active">Portaria Digital</li>
@endsection

@push('styles')
<style>
    .scan-card-container {
        border: 2px dashed var(--primary);
        border-radius: 1rem;
        padding: 1.5rem;
        background: rgba(25, 67, 124, 0.03);
        transition: all 0.3s;
    }
    #qr-reader {
        width: 100%;
        max-width: 380px;
        margin: 0 auto;
        border-radius: 1rem;
        overflow: hidden;
        border: 3px solid var(--primary);
    }
    #qr-reader video {
        border-radius: 0.75rem;
    }
    .scan-success-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(25, 67, 124, 0.9);
        z-index: 9999;
        display: none;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        color: white;
    }
    .scan-success-overlay.show { display: flex; }
    .live-indicator {
        display: inline-block;
        width: 8px; height: 8px;
        background: #10b981;
        border-radius: 50%;
        margin-right: 6px;
        animation: pulse-live 1.5s infinite;
    }
    @keyframes pulse-live {
        0%, 100% { box-shadow: 0 0 0 0 rgba(16,185,129,0.5); }
        50% { box-shadow: 0 0 0 6px rgba(16,185,129,0); }
    }

    /* Trail Timeline */
    .trail-timeline {
        position: relative;
        padding-left: 2rem;
    }
    .trail-timeline::before {
        content: '';
        position: absolute;
        left: 9px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, var(--primary), #e2e8f0);
    }
    .trail-item {
        position: relative;
        padding-bottom: 1.25rem;
        padding-left: 1rem;
    }
    .trail-item:last-child { padding-bottom: 0; }
    .trail-dot {
        position: absolute;
        left: -1.65rem;
        top: 2px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.6rem;
        color: white;
        border: 2px solid white;
        box-shadow: 0 0 0 2px rgba(0,0,0,0.05);
    }
    .trail-dot.entry { background: #10b981; }
    .trail-dot.exit { background: #ef4444; }
    .trail-date-separator {
        font-weight: 700;
        font-size: 0.7rem;
        text-transform: uppercase;
        color: var(--primary);
        letter-spacing: 0.05em;
        padding: 0.5rem 0 0.25rem 0;
        border-bottom: 1px dashed #e2e8f0;
        margin-bottom: 0.75rem;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Stat KPI Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-primary">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-primary-subtle text-primary rounded-circle p-3 me-3">
                            <i class="fas fa-clock-rotate-left fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Passagens</div>
                            <h4 class="mb-0 text-primary font-weight-bold">{{ $stats['total'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-success">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-success-subtle text-success rounded-circle p-3 me-3">
                            <i class="fas fa-right-to-bracket fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Entradas</div>
                            <h4 class="mb-0 text-success font-weight-bold">{{ $stats['entries'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-danger">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-danger-subtle text-danger rounded-circle p-3 me-3">
                            <i class="fas fa-right-from-bracket fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Saídas</div>
                            <h4 class="mb-0 text-danger font-weight-bold">{{ $stats['exits'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-info">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-info-subtle text-info rounded-circle p-3 me-3">
                            <i class="fas fa-shield-halved fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase font-weight-bold">Status do Leitor</div>
                            <h4 class="mb-0 text-info font-weight-bold text-xs mt-1">
                                <span class="live-indicator"></span> ATIVO
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Coluna Esquerda: Leitor / Identificação -->
            <div class="col-lg-5">
                <div class="school-card mb-4">
                    <div class="school-card-header d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-qrcode text-primary me-2"></i> Identificação do Aluno
                        </div>
                        <div class="text-muted small" id="liveClock" style="font-family: monospace; font-weight: bold;"></div>
                    </div>
                    <div class="school-card-body">
                        <!-- Toggle Modo de Entrada -->
                        <div class="btn-group w-100 mb-4" role="group">
                            <button type="button" class="btn btn-primary-school active" id="btnModeBarcode" onclick="switchMode('barcode')">
                                <i class="fas fa-barcode me-1"></i> Leitor USB / Manual
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="btnModeCamera" onclick="switchMode('camera')">
                                <i class="fas fa-camera me-1"></i> Câmera QR Code
                            </button>
                        </div>

                        <!-- Modo Leitor Barcode / Manual -->
                        <div id="barcodeMode">
                            <form action="{{ route('gatekeeper.index') }}" method="GET" id="searchForm">
                                <div class="mb-3">
                                    <label class="form-label text-xs font-bold text-slate-700 uppercase tracking-wider">Ler Cartão ou Digitar Nome / Nº</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-slate-200 text-primary"><i class="fas fa-barcode"></i></span>
                                        <input type="text"
                                               name="search"
                                               id="barcodeInput"
                                               class="form-control form-control-lg rounded-end-xl border-slate-200"
                                               placeholder="Nº Matrícula ou Nome..."
                                               value="{{ request('search') }}"
                                               autofocus
                                               autocomplete="off">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary-school w-100 rounded-xl py-2.5">
                                    <i class="fas fa-search me-2"></i> Verificar Aluno
                                </button>
                            </form>
                            <div class="text-center mt-3">
                                <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Use o leitor óptico USB — a leitura do código submete automaticamente.</small>
                            </div>
                        </div>

                        <!-- Modo Câmera QR -->
                        <div id="cameraMode" style="display:none;">
                            <div id="qr-reader" class="mb-3"></div>
                            <div class="text-center">
                                <button class="btn btn-outline-success btn-sm rounded-xl" id="startCameraBtn" onclick="startCamera()">
                                    <i class="fas fa-play me-1"></i> Iniciar Câmera
                                </button>
                                <button class="btn btn-outline-danger btn-sm rounded-xl" id="stopCameraBtn" onclick="stopCamera()" style="display:none;">
                                    <i class="fas fa-stop me-1"></i> Parar Câmera
                                </button>
                            </div>
                            <form id="qrScanForm" action="{{ route('gatekeeper.scan') }}" method="POST" style="display:none;">
                                @csrf
                                <input type="hidden" name="qr_data" id="qrDataInput">
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Resultado do Aluno Pesquisado -->
                <div class="school-card">
                    <div class="school-card-header">
                        <i class="fas fa-user-check text-primary me-2"></i> Dados da Validação
                    </div>
                    <div class="school-card-body">
                        @if($searchedStudent)
                            <div class="p-3 bg-light rounded-xl border border-slate-200 mb-3">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    @if($searchedStudent->photo)
                                        <img src="{{ asset('storage/' . $searchedStudent->photo) }}" class="rounded-circle shadow-sm" width="56" height="56" alt="Foto" style="object-fit:cover; border: 2px solid var(--primary);">
                                    @else
                                        <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center shadow-sm fw-bold" style="width:56px; height:56px; font-size:1.2rem;">
                                            {{ strtoupper(substr($searchedStudent->first_name, 0, 1) . substr($searchedStudent->last_name ?? '', 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <h5 class="fw-bold text-slate-800 mb-0">{{ $searchedStudent->full_name }}</h5>
                                        <code>{{ $searchedStudent->student_number }}</code>
                                    </div>
                                </div>

                                <div class="row g-2 text-sm mb-3">
                                    <div class="col-6">
                                        <span class="text-muted text-xs d-block">Turma</span>
                                        <span class="fw-bold text-slate-800">{{ $searchedStudent->currentEnrollment->class->name ?? 'Sem turma' }}</span>
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted text-xs d-block">Encarregado</span>
                                        <span class="fw-bold text-slate-800">{{ $searchedStudent->parent->name ?? 'N/D' }}</span>
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted text-xs d-block">Contacto Encarregado</span>
                                        <span class="fw-bold text-slate-800">{{ $searchedStudent->parent->phone ?? 'N/D' }}</span>
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted text-xs d-block">Estado Matrícula</span>
                                        <span class="badge {{ $searchedStudent->currentEnrollment ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-warning-subtle text-warning border border-warning-subtle' }} rounded-pill">
                                            {{ $searchedStudent->currentEnrollment ? 'Ativa' : 'Inativa' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Ações de Entrada / Saída -->
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <form action="{{ route('gatekeeper.log', $searchedStudent) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="action" value="entry">
                                        <button type="submit" class="btn btn-success rounded-xl">
                                            <i class="fas fa-right-to-bracket me-1"></i> Registar Entrada
                                        </button>
                                    </form>
                                    <form action="{{ route('gatekeeper.log', $searchedStudent) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="action" value="exit">
                                        <button type="submit" class="btn btn-danger rounded-xl">
                                            <i class="fas fa-right-from-bracket me-1"></i> Registar Saída
                                        </button>
                                    </form>
                                    <a href="{{ route('gatekeeper.card', $searchedStudent) }}" class="btn btn-outline-primary rounded-xl">
                                        <i class="fas fa-id-card me-1"></i> Cartão
                                    </a>
                                    @if (auth()->user()?->can('view_students'))
                                        <a href="{{ route('students.show', $searchedStudent->id) }}" class="btn btn-outline-secondary rounded-xl">
                                            <i class="fas fa-eye me-1"></i> Detalhes
                                        </a>
                                    @endif
                                </div>

                                <!-- Botão para Ver Rastreio Completo -->
                                <button type="button" class="btn btn-outline-primary w-100 rounded-xl"
                                    data-bs-toggle="modal" data-bs-target="#trailModal"
                                    onclick="loadTrail({{ $searchedStudent->id }})">
                                    <i class="fas fa-route me-2"></i> Ver Rastreio Completo (Trail)
                                </button>
                            </div>

                            <!-- Mini Trail (Hoje) -->
                            @if($studentTrail && $studentTrail->count() > 0)
                                <div class="mt-3">
                                    <h6 class="fw-bold text-slate-700 text-xs text-uppercase mb-2">
                                        <i class="fas fa-timeline me-1"></i> Movimentações Recentes
                                    </h6>
                                    <div class="trail-timeline">
                                        @foreach($studentTrail->take(6) as $trail)
                                            <div class="trail-item">
                                                <div class="trail-dot {{ $trail->action }}">
                                                    <i class="fas fa-{{ $trail->isEntry() ? 'arrow-right' : 'arrow-left' }}"></i>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <span class="fw-bold text-{{ $trail->isEntry() ? 'success' : 'danger' }}">{{ $trail->action_label }}</span>
                                                        <span class="text-muted text-xs ms-2">{{ $trail->method_label }}</span>
                                                    </div>
                                                    <small class="text-muted fw-bold font-monospace">
                                                        {{ $trail->logged_at->format('d/m H:i') }}
                                                    </small>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-shield-halved fa-3x mb-3 text-slate-300"></i>
                                <h5>Aguardando Identificação</h5>
                                <p class="text-xs mb-0">Pesquise por nome, nº de estudante ou leia o cartão no leitor óptico USB.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Coluna Direita: Histórico do Dia -->
            <div class="col-lg-7">
                <!-- Filtro de Data -->
                <div class="school-card mb-3">
                    <div class="school-card-body py-2">
                        <form action="{{ route('gatekeeper.index') }}" method="GET" class="d-flex align-items-center gap-3">
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            <label class="form-label mb-0 text-xs font-bold text-slate-700 text-uppercase text-nowrap">
                                <i class="fas fa-calendar-day me-1"></i> Data:
                            </label>
                            <input type="date" name="date" class="form-control form-control-sm rounded-xl border-slate-200" style="max-width: 180px;"
                                value="{{ $filterDate }}" onchange="this.form.submit()">
                            @if($filterDate !== now()->toDateString())
                                <a href="{{ route('gatekeeper.index', request('search') ? ['search' => request('search')] : []) }}" class="btn btn-sm btn-outline-primary rounded-xl text-nowrap">
                                    <i class="fas fa-calendar-check me-1"></i> Hoje
                                </a>
                            @endif
                        </form>
                    </div>
                </div>

                <div class="school-table-container">
                    <div class="school-table-header">
                        <h3 class="school-table-title">
                            <i class="fas fa-history text-primary me-2"></i>
                            Registo de Acessos
                            @if($filterDate === now()->toDateString())
                                <span class="badge bg-success ms-2" style="font-size: 0.5em;"><span class="live-indicator"></span> Hoje</span>
                            @else
                                <span class="badge bg-secondary ms-2" style="font-size: 0.5em;">{{ \Carbon\Carbon::parse($filterDate)->format('d/m/Y') }}</span>
                            @endif
                        </h3>
                        <span class="badge bg-primary" style="font-size: 0.55em;">{{ $todayLogs->total() }} registos</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-school">
                            <thead>
                                <tr>
                                    <th style="width: 70px;">Hora</th>
                                    <th>Aluno</th>
                                    <th>Turma</th>
                                    <th>Tipo</th>
                                    <th>Método</th>
                                    <th>Operador</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($todayLogs as $log)
                                    <tr>
                                        <td>
                                            <strong class="text-primary font-monospace">{{ $log->logged_at->format('H:i') }}</strong>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-{{ $log->isEntry() ? 'success' : 'danger' }}-subtle text-{{ $log->isEntry() ? 'success' : 'danger' }} fw-bold me-2 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; font-size: 11px;">
                                                    {{ strtoupper(substr($log->student->first_name ?? 'N', 0, 1) . substr($log->student->last_name ?? 'A', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-slate-800" style="font-size: 0.85rem;">{{ $log->student->full_name ?? 'N/A' }}</div>
                                                    <small class="text-muted"><code>{{ $log->student->student_number ?? '' }}</code></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                                {{ $log->class->name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($log->isEntry())
                                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">
                                                    <i class="fas fa-arrow-right-to-bracket me-1"></i> Entrada
                                                </span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1">
                                                    <i class="fas fa-arrow-right-from-bracket me-1"></i> Saída
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($log->method === 'qr')
                                                <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2.5 py-1">
                                                    <i class="fas fa-qrcode me-1"></i> QR
                                                </span>
                                            @elseif($log->method === 'barcode' || $log->method === 'usb')
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2.5 py-1">
                                                    <i class="fas fa-barcode me-1"></i> USB
                                                </span>
                                            @else
                                                <span class="badge bg-light text-dark border rounded-pill px-2.5 py-1">
                                                    <i class="fas fa-keyboard me-1"></i> Manual
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $log->loggedBy->name ?? 'Sistema' }}</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="fas fa-history fa-3x mb-3 text-slate-300"></i>
                                            <h5>Nenhuma passagem registada</h5>
                                            <p class="text-xs mb-0">Nenhum registo de entrada ou saída para esta data.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($todayLogs->hasPages())
                        <div class="p-3 border-top">
                            {{ $todayLogs->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Overlay Feedback QR -->
<div class="scan-success-overlay" id="scanOverlay">
    <div class="display-1 text-success mb-3"><i class="fas fa-check-circle"></i></div>
    <h3 class="fw-bold text-white mb-2">QR Code Lido com Sucesso!</h3>
    <p class="text-light" id="scanOverlayText">A processar registo...</p>
</div>

<!-- Trail Modal (Rastreio Completo) -->
<div class="modal fade" id="trailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-light py-3 border-bottom">
                <h5 class="modal-title fw-bold text-slate-800">
                    <i class="fas fa-route me-2 text-primary"></i>
                    Rastreio Completo — <span id="trailStudentName">Aluno</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="trailModalBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="text-muted mt-2">A carregar rastreio...</p>
                </div>
            </div>
            <div class="modal-footer bg-light py-2 px-4 d-flex justify-content-between">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-primary rounded-xl trail-days-btn" data-days="7" onclick="reloadTrail(7)">7 dias</button>
                    <button type="button" class="btn btn-sm btn-outline-primary rounded-xl trail-days-btn" data-days="15" onclick="reloadTrail(15)">15 dias</button>
                    <button type="button" class="btn btn-sm btn-outline-primary rounded-xl trail-days-btn" data-days="30" onclick="reloadTrail(30)">30 dias</button>
                </div>
                <button type="button" class="btn btn-outline-secondary btn-sm rounded-xl" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    function updateClock() {
        const now = new Date();
        const time = now.toLocaleTimeString('pt-PT', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        const clockEl = document.getElementById('liveClock');
        if (clockEl) clockEl.textContent = time;
    }
    updateClock();
    setInterval(updateClock, 1000);

    const barcodeInput = document.getElementById('barcodeInput');
    if (barcodeInput) {
        barcodeInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                if (this.value.trim().length > 0) {
                    document.getElementById('searchForm').submit();
                }
            }
        });
        barcodeInput.focus();
    }
});

let html5QrCode = null;
let cameraRunning = false;
let currentTrailStudentId = null;

function switchMode(mode) {
    document.getElementById('btnModeBarcode').classList.toggle('btn-primary-school', mode === 'barcode');
    document.getElementById('btnModeBarcode').classList.toggle('btn-outline-secondary', mode !== 'barcode');
    document.getElementById('btnModeCamera').classList.toggle('btn-primary-school', mode === 'camera');
    document.getElementById('btnModeCamera').classList.toggle('btn-outline-secondary', mode !== 'camera');

    document.getElementById('barcodeMode').style.display = mode === 'barcode' ? 'block' : 'none';
    document.getElementById('cameraMode').style.display = mode === 'camera' ? 'block' : 'none';

    if (mode === 'barcode') {
        stopCamera();
        document.getElementById('barcodeInput')?.focus();
    }
}

function startCamera() {
    const qrReaderEl = document.getElementById('qr-reader');
    if (!qrReaderEl) return;

    document.getElementById('startCameraBtn').style.display = 'none';
    document.getElementById('stopCameraBtn').style.display = 'inline-block';

    html5QrCode = new Html5Qrcode('qr-reader');
    cameraRunning = true;

    html5QrCode.start(
        { facingMode: 'environment' },
        { fps: 10, qrbox: { width: 250, height: 250 }, aspectRatio: 1.0 },
        (decodedText) => {
            stopCamera();
            showScanOverlay(decodedText);
            document.getElementById('qrDataInput').value = decodedText;
            document.getElementById('qrScanForm').submit();
        },
        (errorMessage) => {}
    ).catch(err => {
        console.error('Camera error:', err);
        alert('Não foi possível aceder à câmera.');
        document.getElementById('startCameraBtn').style.display = 'inline-block';
        document.getElementById('stopCameraBtn').style.display = 'none';
    });
}

function stopCamera() {
    if (html5QrCode && cameraRunning) {
        html5QrCode.stop().then(() => {
            cameraRunning = false;
            document.getElementById('startCameraBtn').style.display = 'inline-block';
            document.getElementById('stopCameraBtn').style.display = 'none';
        }).catch(err => console.warn(err));
    }
}

function showScanOverlay(data) {
    const overlay = document.getElementById('scanOverlay');
    const text = document.getElementById('scanOverlayText');
    if (overlay) {
        text.textContent = 'Código lido: ' + data.substring(0, 40) + '...';
        overlay.classList.add('show');
        setTimeout(() => overlay.classList.remove('show'), 2500);
    }
}

// ── Trail Modal Logic ──────────────────────────────────────
function loadTrail(studentId, days = 7) {
    currentTrailStudentId = studentId;
    const body = document.getElementById('trailModalBody');
    body.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div><p class="text-muted mt-2">A carregar rastreio...</p></div>';

    fetch(`/gatekeeper/${studentId}/history?days=${days}`)
        .then(r => r.json())
        .then(data => {
            document.getElementById('trailStudentName').textContent = data.student.full_name + ' (' + data.student.student_number + ')';
            renderTrail(data.logs);
        })
        .catch(err => {
            body.innerHTML = '<div class="text-center py-4 text-danger"><i class="fas fa-exclamation-triangle fa-2x mb-2"></i><p>Erro ao carregar rastreio.</p></div>';
        });
}

function reloadTrail(days) {
    if (currentTrailStudentId) {
        document.querySelectorAll('.trail-days-btn').forEach(b => b.classList.remove('active'));
        document.querySelector(`.trail-days-btn[data-days="${days}"]`)?.classList.add('active');
        loadTrail(currentTrailStudentId, days);
    }
}

function renderTrail(logs) {
    const body = document.getElementById('trailModalBody');

    if (!logs || logs.length === 0) {
        body.innerHTML = '<div class="text-center py-4 text-muted"><i class="fas fa-route fa-3x mb-3 text-slate-300"></i><h5>Sem movimentações</h5><p class="text-xs">Nenhum registo de entrada ou saída encontrado para o período seleccionado.</p></div>';
        return;
    }

    let html = '<div class="trail-timeline">';
    let lastDate = '';

    logs.forEach(log => {
        // Date separator
        if (log.date !== lastDate) {
            html += `<div class="trail-date-separator"><i class="fas fa-calendar-day me-1"></i> ${log.date}</div>`;
            lastDate = log.date;
        }

        const isEntry = log.action === 'entry';
        const dotClass = isEntry ? 'entry' : 'exit';
        const icon = isEntry ? 'fa-arrow-right' : 'fa-arrow-left';
        const color = isEntry ? 'success' : 'danger';
        const badgeClass = isEntry
            ? 'bg-success-subtle text-success border border-success-subtle'
            : 'bg-danger-subtle text-danger border border-danger-subtle';

        html += `
            <div class="trail-item">
                <div class="trail-dot ${dotClass}"><i class="fas ${icon}"></i></div>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge ${badgeClass} rounded-pill px-2 py-1 me-2">${log.label}</span>
                        <span class="text-muted text-xs">${log.method}</span>
                        <span class="text-muted text-xs ms-2">• Turma: ${log.class}</span>
                        <span class="text-muted text-xs ms-2">• Por: ${log.logged_by}</span>
                    </div>
                    <span class="fw-bold font-monospace text-${color}" style="font-size: 0.85rem;">${log.time}</span>
                </div>
            </div>
        `;
    });

    html += '</div>';

    // Summary
    const entries = logs.filter(l => l.action === 'entry').length;
    const exits = logs.filter(l => l.action === 'exit').length;
    html += `
        <div class="mt-3 p-2 bg-light rounded-xl border text-center">
            <small class="text-muted">
                <strong class="text-success">${entries}</strong> entradas •
                <strong class="text-danger">${exits}</strong> saídas •
                <strong class="text-primary">${logs.length}</strong> movimentações totais
            </small>
        </div>
    `;

    body.innerHTML = html;
}
</script>
@endpush
