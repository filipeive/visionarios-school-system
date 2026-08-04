@extends('layouts.app')

@section('title', 'Portaria Digital — Controlo de Acesso')

@push('styles')
<style>
    /* ── Portaria Digital Styles ── */
    .gatekeeper-hero {
        background: linear-gradient(135deg, #0f5132 0%, #198754 40%, #20c997 100%);
        border-radius: 1rem;
        padding: 2rem 2.5rem;
        color: white;
        position: relative;
        overflow: hidden;
    }
    .gatekeeper-hero::before {
        content: '';
        position: absolute;
        top: -50%; right: -20%;
        width: 400px; height: 400px;
        background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
        border-radius: 50%;
    }
    .scan-card {
        border: 2px dashed #20c997;
        border-radius: 1rem;
        padding: 1.5rem;
        background: rgba(32, 201, 151, 0.03);
        transition: all 0.3s;
    }
    .scan-card.active {
        border-color: #0f5132;
        background: rgba(15, 81, 50, 0.05);
        box-shadow: 0 0 20px rgba(15, 81, 50, 0.1);
    }
    .scan-card:hover {
        border-color: #0f5132;
    }
    #qr-reader {
        width: 100%;
        max-width: 400px;
        margin: 0 auto;
        border-radius: 0.75rem;
        overflow: hidden;
        border: 3px solid #0f5132;
    }
    #qr-reader video {
        border-radius: 0.75rem;
    }
    .student-result-card {
        border-left: 4px solid #198754;
        background: linear-gradient(135deg, #f8fdf9, #edf7f0);
        border-radius: 0 1rem 1rem 0;
        padding: 1.5rem;
        animation: slideInRight 0.3s ease;
    }
    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(20px); }
        to { opacity: 1; transform: translateX(0); }
    }
    .action-btn {
        border: none;
        border-radius: 0.75rem;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .action-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
    .btn-entry { background: #198754; color: white; }
    .btn-entry:hover { background: #0f5132; color: white; }
    .btn-exit  { background: #dc3545; color: white; }
    .btn-exit:hover { background: #b02a37; color: white; }
    .btn-qr    { background: #0d6efd; color: white; }
    .btn-qr:hover { background: #0b5ed7; color: white; }
    .log-entry-badge { font-size: 0.7rem; padding: 0.25rem 0.6rem; }
    .live-indicator {
        display: inline-block;
        width: 8px; height: 8px;
        background: #20c997;
        border-radius: 50%;
        margin-right: 6px;
        animation: pulse-live 1.5s infinite;
    }
    @keyframes pulse-live {
        0%, 100% { box-shadow: 0 0 0 0 rgba(32,201,151,0.5); }
        50% { box-shadow: 0 0 0 6px rgba(32,201,151,0); }
    }
    .scanner-toggle-group .btn {
        border-radius: 0.5rem;
        font-size: 0.8rem;
        padding: 0.5rem 1rem;
    }
    .scanner-toggle-group .btn.active {
        background: #0f5132;
        color: white;
        border-color: #0f5132;
    }
    .barcode-input-wrapper {
        position: relative;
    }
    .barcode-input-wrapper .form-control {
        padding-left: 2.8rem;
        border-radius: 0.75rem;
        border: 2px solid #dee2e6;
        font-size: 1.1rem;
        font-family: 'Courier New', monospace;
        letter-spacing: 2px;
    }
    .barcode-input-wrapper .form-control:focus {
        border-color: #198754;
        box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.15);
    }
    .barcode-input-wrapper .input-icon {
        position: absolute;
        left: 0.9rem;
        top: 50%;
        transform: translateY(-50%);
        color: #198754;
        font-size: 1.1rem;
    }
    .scan-success-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15,81,50,0.85);
        z-index: 9999;
        display: none;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        color: white;
    }
    .scan-success-overlay.show { display: flex; }
    .scan-success-overlay .checkmark {
        font-size: 5rem;
        animation: scaleIn 0.3s ease;
    }
    @keyframes scaleIn {
        from { transform: scale(0); }
        to { transform: scale(1); }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- ═══ Hero Banner ═══ -->
    <div class="gatekeeper-hero mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center mb-2">
                    <span class="live-indicator"></span>
                    <small class="text-uppercase fw-bold" style="letter-spacing:2px; opacity:0.8; font-size:0.7rem;">Portaria Digital — Em Tempo Real</small>
                </div>
                <h3 class="fw-bold mb-1"><i class="fas fa-shield-alt me-2"></i> Controlo de Acesso Escolar</h3>
                <p class="mb-0 opacity-75" style="font-size:0.85rem;">Registe entradas e saídas de alunos via scanner QR, leitor óptico de código de barras ou pesquisa manual.</p>
            </div>
            <div class="col-lg-4 text-end d-none d-lg-block">
                <div class="bg-white/10 rounded-3 p-3 d-inline-block">
                    <div class="text-uppercase fw-bold" style="font-size:0.65rem; letter-spacing:1px; opacity:0.7;">DATA E HORA ACTUAL</div>
                    <div class="fw-bold" id="liveClock" style="font-size:1.6rem; font-family: 'Courier New', monospace;"></div>
                    <div class="fw-bold" style="font-size:0.8rem;" id="liveDate"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-sm mb-3" role="alert">
            <i class="fas fa-check-circle me-2"></i> {!! session('success') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 shadow-sm mb-3" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> {!! session('error') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- ═══ Coluna Esquerda: Entrada & Scanner ═══ -->
        <div class="col-lg-6">
            <!-- Card: Métodos de Entrada -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white p-4 border-bottom">
                    <h5 class="fw-bold text-slate-800 mb-1">
                        <i class="fas fa-qrcode me-2 text-emerald-600"></i> Identificação do Aluno
                    </h5>
                    <small class="text-xs text-muted">Escolha o método de leitura para verificar o aluno</small>
                </div>
                <div class="card-body p-4">
                    <!-- Toggle: Método de Entrada -->
                    <div class="btn-group scanner-toggle-group w-100 mb-4" role="group">
                        <button type="button" class="btn btn-outline-secondary active" data-mode="barcode" onclick="switchMode('barcode')">
                            <i class="fas fa-barcode me-1"></i> Leitor / Manual
                        </button>
                        <button type="button" class="btn btn-outline-secondary" data-mode="camera" onclick="switchMode('camera')">
                            <i class="fas fa-camera me-1"></i> Câmera QR
                        </button>
                    </div>

                    <!-- Modo: Leitor de Código de Barras / Manual -->
                    <div id="barcodeMode">
                        <form action="{{ route('gatekeeper.index') }}" method="GET" id="searchForm">
                            <div class="barcode-input-wrapper mb-3">
                                <i class="fas fa-barcode input-icon"></i>
                                <input type="text"
                                       name="search"
                                       id="barcodeInput"
                                       class="form-control form-control-lg"
                                       placeholder="Nº Matrícula ou Nome..."
                                       value="{{ request('search') }}"
                                       autofocus
                                       autocomplete="off">
                            </div>
                            <button type="submit" class="btn btn-success w-100 rounded-3 py-2">
                                <i class="fas fa-search me-2"></i> Pesquisar Aluno
                            </button>
                        </form>
                        <div class="text-center mt-3">
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Leia o código de barras do cartão do aluno com leitor USB — a pesquisa é automática.</small>
                        </div>
                    </div>

                    <!-- Modo: Câmera QR Code -->
                    <div id="cameraMode" style="display:none;">
                        <div id="qr-reader" class="mb-3"></div>
                        <div class="text-center">
                            <button class="btn btn-outline-success btn-sm rounded-pill" id="startCameraBtn" onclick="startCamera()">
                                <i class="fas fa-play me-1"></i> Iniciar Câmera
                            </button>
                            <button class="btn btn-outline-danger btn-sm rounded-pill" id="stopCameraBtn" onclick="stopCamera()" style="display:none;">
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

            <!-- Card: Resultado do Aluno Pesquisado -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    @if($searchedStudent)
                        <div class="student-result-card">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="fw-bold text-slate-800 mb-0">{{ $searchedStudent->full_name }}</h5>
                                    <span class="badge bg-emerald-100 text-emerald-800 rounded-pill text-xs mt-1">
                                        <i class="fas fa-id-badge me-1"></i> {{ $searchedStudent->student_number }}
                                    </span>
                                </div>
                                @if($searchedStudent->photo)
                                    <img src="{{ asset('storage/' . $searchedStudent->photo) }}" class="rounded-circle shadow-sm" width="56" height="56" alt="Foto" style="object-fit:cover; border: 2px solid #198754;">
                                @else
                                    <div class="rounded-circle bg-emerald-100 text-emerald-700 d-flex align-items-center justify-content-center shadow-sm" style="width:56px;height:56px;font-size:1.3rem;font-weight:700;border:2px solid #198754;">
                                        {{ strtoupper(substr($searchedStudent->first_name, 0, 1) . substr($searchedStudent->last_name ?? '', 0, 1)) }}
                                    </div>
                                @endif
                            </div>

                            <div class="row g-2 text-sm mb-3">
                                <div class="col-6">
                                    <div class="text-muted text-xs">Turma</div>
                                    <div class="fw-bold">{{ $searchedStudent->currentEnrollment->class->name ?? 'Sem turma' }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted text-xs">Encarregado</div>
                                    <div class="fw-bold">{{ $searchedStudent->parent->name ?? 'N/D' }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted text-xs">Contacto Encarregado</div>
                                    <div class="fw-bold">{{ $searchedStudent->parent->phone ?? 'N/D' }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted text-xs">Estado Matrícula</div>
                                    <div class="fw-bold">
                                        <span class="badge {{ $searchedStudent->currentEnrollment ? 'bg-success' : 'bg-warning' }} rounded-pill text-xs">
                                            {{ $searchedStudent->currentEnrollment ? 'Activa' : 'Inactiva' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Acções -->
                            <div class="d-flex flex-wrap gap-2">
                                <form action="{{ route('gatekeeper.log', $searchedStudent) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="action" value="entry">
                                    <button type="submit" class="action-btn btn-entry">
                                        <i class="fas fa-sign-in-alt"></i> Entrada
                                    </button>
                                </form>
                                <form action="{{ route('gatekeeper.log', $searchedStudent) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="action" value="exit">
                                    <button type="submit" class="action-btn btn-exit">
                                        <i class="fas fa-sign-out-alt"></i> Saída
                                    </button>
                                </form>
                                <a href="{{ route('gatekeeper.qr', $searchedStudent) }}" class="action-btn btn-qr">
                                    <i class="fas fa-qrcode"></i> Ver QR
                                </a>
                                <a href="{{ route('gatekeeper.card', $searchedStudent) }}" class="action-btn btn-qr" style="background: #6610f2;">
                                    <i class="fas fa-id-card"></i> Cartão
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-user-shield fa-3x" style="color:#c3dfd0;"></i>
                            </div>
                            <h6 class="fw-bold text-slate-600">Aguardando Identificação</h6>
                            <p class="text-xs text-muted mb-0 mx-auto" style="max-width:320px;">
                                Pesquise pelo nome / nº de matrícula, leia o código de barras do cartão ou escaneie o QR Code com a câmera.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- ═══ Coluna Direita: Histórico do Dia ═══ -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white p-4 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold text-slate-800 mb-0">
                            <i class="fas fa-clock-rotate-left me-2 text-emerald-600"></i> Histórico do Dia
                        </h5>
                        <small class="text-xs text-muted">Registos de entrada e saída efectuados hoje</small>
                    </div>
                    <span class="badge bg-emerald-100 text-emerald-800 rounded-pill fw-bold px-3 py-1 text-xs">
                        {{ $todayLogs->count() }} Passagens
                    </span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle text-sm mb-0">
                            <thead class="table-light text-xs text-uppercase" style="letter-spacing:0.5px;">
                                <tr>
                                    <th class="ps-4">Hora</th>
                                    <th>Aluno</th>
                                    <th>Turma</th>
                                    <th>Tipo</th>
                                    <th>Método</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($todayLogs as $log)
                                    @php
                                        $isEntry = str_contains($log->notes ?? '', 'Entrada');
                                        $isQr = str_contains($log->notes ?? '', 'QR');
                                    @endphp
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
                                            @if($isEntry)
                                                <span class="badge bg-success-subtle text-success log-entry-badge rounded-pill">
                                                    <i class="fas fa-arrow-right-to-bracket me-1"></i> Entrada
                                                </span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger log-entry-badge rounded-pill">
                                                    <i class="fas fa-arrow-right-from-bracket me-1"></i> Saída
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($isQr)
                                                <span class="badge bg-info-subtle text-info log-entry-badge rounded-pill">
                                                    <i class="fas fa-qrcode me-1"></i> QR
                                                </span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary log-entry-badge rounded-pill">
                                                    <i class="fas fa-keyboard me-1"></i> Manual
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="fas fa-history fa-2x mb-2 text-slate-300 d-block"></i>
                                            Nenhuma passagem registada na portaria hoje.
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

{{-- QR Code scan success feedback overlay --}}
<div class="scan-success-overlay" id="scanOverlay">
    <div class="checkmark"><i class="fas fa-check-circle"></i></div>
    <h3 class="fw-bold mt-3">QR Lido com Sucesso!</h3>
    <p id="scanOverlayText">A processar...</p>
</div>
@endsection

@push('scripts')
{{-- html5-qrcode library --}}
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ── Live Clock ──
    function updateClock() {
        const now = new Date();
        const time = now.toLocaleTimeString('pt-PT', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        const date = now.toLocaleDateString('pt-PT', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        const clockEl = document.getElementById('liveClock');
        const dateEl = document.getElementById('liveDate');
        if (clockEl) clockEl.textContent = time;
        if (dateEl) dateEl.textContent = date.charAt(0).toUpperCase() + date.slice(1);
    }
    updateClock();
    setInterval(updateClock, 1000);

    // ── Auto-submit for USB barcode reader ──
    const barcodeInput = document.getElementById('barcodeInput');
    if (barcodeInput) {
        let lastKeyTime = 0;
        let inputBuffer = '';

        barcodeInput.addEventListener('keydown', function(e) {
            const now = Date.now();
            if (e.key === 'Enter') {
                e.preventDefault();
                if (this.value.trim().length > 0) {
                    document.getElementById('searchForm').submit();
                }
                return;
            }

            // Barcode readers type very fast (< 50ms between keys)
            if (now - lastKeyTime < 50) {
                // Likely barcode reader input
            }
            lastKeyTime = now;
        });

        // Auto-focus the input whenever the page loads
        barcodeInput.focus();

        // Re-focus on click anywhere (for barcode reader)
        document.addEventListener('click', function(e) {
            if (!e.target.closest('button, a, .btn, form[action]')) {
                barcodeInput.focus();
            }
        });
    }
});

// ── Scanner Mode Toggle ──
let html5QrCode = null;
let cameraRunning = false;

function switchMode(mode) {
    document.querySelectorAll('.scanner-toggle-group .btn').forEach(btn => {
        btn.classList.toggle('active', btn.dataset.mode === mode);
    });

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
        {
            fps: 10,
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0,
        },
        (decodedText) => {
            // Success callback
            stopCamera();
            showScanOverlay(decodedText);

            // Submit the QR data via form
            document.getElementById('qrDataInput').value = decodedText;
            document.getElementById('qrScanForm').submit();
        },
        (errorMessage) => {
            // Ignore scan errors (continuously scanning)
        }
    ).catch(err => {
        console.error('Camera error:', err);
        alert('Não foi possível aceder à câmera. Verifique as permissões do navegador.');
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
        }).catch(err => console.warn('Stop camera error:', err));
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

    // Play a success beep
    try {
        const ctx = new (window.AudioContext || window.webkitAudioContext)();
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.connect(gain);
        gain.connect(ctx.destination);
        osc.frequency.value = 880;
        osc.type = 'sine';
        gain.gain.value = 0.3;
        osc.start();
        osc.stop(ctx.currentTime + 0.15);
    } catch(e) {}
}
</script>
@endpush
