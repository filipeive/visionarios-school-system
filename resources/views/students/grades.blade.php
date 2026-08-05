@extends('layouts.app')

@section('title', 'Pautas & Notas — ' . $student->full_name)

@push('styles')
<style>
    .grades-hero {
        background: linear-gradient(135deg, #1b5e20 0%, #2e7d32 100%);
        border-radius: 1rem;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
    }
    .term-btn {
        border-radius: 0.5rem;
        font-weight: 600;
        transition: all 0.2s;
    }
    .term-btn.active {
        background-color: #1b5e20 !important;
        color: white !important;
        border-color: #1b5e20 !important;
    }
    .grade-badge {
        font-size: 0.9rem;
        padding: 0.35rem 0.75rem;
        border-radius: 0.5rem;
        font-weight: 700;
    }
    .matrix-card {
        border-radius: 1rem;
        border: none;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }
    .share-btn {
        border: none;
        border-radius: 0.5rem;
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
        font-weight: 600;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header Banner -->
    <div class="grades-hero">
        <div class="row align-items-center">
            <div class="col-md-8">
                <span class="badge bg-white/20 text-white mb-2 px-3 py-2 text-xs text-uppercase">Boletim Individual de Rendimento</span>
                <h3 class="fw-bold mb-1">{{ $student->full_name }}</h3>
                <p class="mb-0 opacity-75 text-sm">
                    <strong>Matrícula:</strong> {{ $student->student_number }} |
                    <strong>Turma:</strong> {{ $currentEnrollment->class->name ?? 'N/A' }} |
                    <strong>Ano Lectivo:</strong> {{ $currentEnrollment->school_year ?? date('Y') }}
                </p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="{{ route('students.show', $student) }}" class="btn btn-light btn-sm rounded-pill px-3">
                    <i class="fas fa-arrow-left me-1"></i> Voltar ao Perfil
                </a>
            </div>
        </div>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Term Selection / Navigation Tabs -->
    <div class="d-flex flex-wrap gap-2 mb-4 justify-content-between align-items-center">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-outline-success term-btn active" data-term="1" onclick="switchTerm('1')">1º Trimestre</button>
            <button type="button" class="btn btn-outline-success term-btn" data-term="2" onclick="switchTerm('2')">2º Trimestre</button>
            <button type="button" class="btn btn-outline-success term-btn" data-term="3" onclick="switchTerm('3')">3º Trimestre</button>
            <button type="button" class="btn btn-outline-success term-btn" data-term="annual" onclick="switchTerm('annual')">Ficha Anual</button>
        </div>

        <div class="d-flex gap-2">
            <!-- Download PDF Button -->
            <a href="{{ route('students.grades.pdf', ['student' => $student->id, 'term' => 1]) }}" id="pdfDownloadBtn" class="btn btn-success btn-sm rounded-pill px-3">
                <i class="fas fa-file-pdf me-1"></i> Extrair Boletim PDF
            </a>

            <!-- Share Email Button -->
            <button type="button" class="btn btn-outline-success btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#shareMailModal">
                <i class="fas fa-envelope me-1"></i> Enviar por E-mail
            </button>

            <!-- Share WhatsApp Button -->
            <a href="#" id="whatsappShareBtn" target="_blank" class="btn btn-outline-success btn-sm rounded-pill px-3" style="border-color: #25D366; color: #25D366;">
                <i class="fab fa-whatsapp me-1"></i> Partilhar no WhatsApp
            </a>
        </div>
    </div>

    <!-- Grades Matrix View -->
    <div class="card matrix-card">
        <div class="card-body p-0">
            <!-- ── 1º TRIMESTRE TABLE ── -->
            <div class="term-table-wrapper" id="termTable-1">
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-sm mb-0">
                        <thead class="table-light text-xs text-uppercase" style="letter-spacing:0.5px;">
                            <tr>
                                <th class="ps-4">Disciplina</th>
                                <th class="text-center">ACS 1</th>
                                <th class="text-center">ACS 2</th>
                                <th class="text-center">ACS 3</th>
                                <th class="text-center">Média ACS (MACS)</th>
                                <th class="text-center">ACP</th>
                                <th class="text-center">Média Trimestre (MT)</th>
                                <th class="text-center">Aproveitamento</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($matrix as $subjectId => $data)
                                @php
                                    $tData = $data['terms'][1];
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-slate-800">{{ $data['subject']->name }}</div>
                                        <small class="text-xs text-muted">{{ $data['subject']->code }}</small>
                                    </td>
                                    <td class="text-center">{{ $tData['acs1'] !== null ? number_format($tData['acs1'], 1) : '-' }}</td>
                                    <td class="text-center">{{ $tData['acs2'] !== null ? number_format($tData['acs2'], 1) : '-' }}</td>
                                    <td class="text-center">{{ $tData['acs3'] !== null ? number_format($tData['acs3'], 1) : '-' }}</td>
                                    <td class="text-center fw-bold">{{ $tData['macs'] !== null ? number_format($tData['macs'], 1) : '-' }}</td>
                                    <td class="text-center">{{ $tData['acp'] !== null ? number_format($tData['acp'], 1) : '-' }}</td>
                                    <td class="text-center">
                                        @if($tData['mt'] !== null)
                                            <span class="badge bg-{{ $tData['mt'] >= 10 ? 'success' : 'danger' }} grade-badge">
                                                {{ number_format($tData['mt'], 1) }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($tData['mt'] !== null)
                                            <span class="text-{{ $tData['mt'] >= 10 ? 'success' : 'danger' }} fw-bold">
                                                {{ $tData['mt'] >= 10 ? 'Positiva' : 'Negativa' }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">Não há disciplinas associadas a esta turma.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ── 2º TRIMESTRE TABLE ── -->
            <div class="term-table-wrapper" id="termTable-2" style="display:none;">
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-sm mb-0">
                        <thead class="table-light text-xs text-uppercase" style="letter-spacing:0.5px;">
                            <tr>
                                <th class="ps-4">Disciplina</th>
                                <th class="text-center">ACS 1</th>
                                <th class="text-center">ACS 2</th>
                                <th class="text-center">ACS 3</th>
                                <th class="text-center">Média ACS (MACS)</th>
                                <th class="text-center">ACP</th>
                                <th class="text-center">Média Trimestre (MT)</th>
                                <th class="text-center">Aproveitamento</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($matrix as $subjectId => $data)
                                @php
                                    $tData = $data['terms'][2];
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-slate-800">{{ $data['subject']->name }}</div>
                                        <small class="text-xs text-muted">{{ $data['subject']->code }}</small>
                                    </td>
                                    <td class="text-center">{{ $tData['acs1'] !== null ? number_format($tData['acs1'], 1) : '-' }}</td>
                                    <td class="text-center">{{ $tData['acs2'] !== null ? number_format($tData['acs2'], 1) : '-' }}</td>
                                    <td class="text-center">{{ $tData['acs3'] !== null ? number_format($tData['acs3'], 1) : '-' }}</td>
                                    <td class="text-center fw-bold">{{ $tData['macs'] !== null ? number_format($tData['macs'], 1) : '-' }}</td>
                                    <td class="text-center">{{ $tData['acp'] !== null ? number_format($tData['acp'], 1) : '-' }}</td>
                                    <td class="text-center">
                                        @if($tData['mt'] !== null)
                                            <span class="badge bg-{{ $tData['mt'] >= 10 ? 'success' : 'danger' }} grade-badge">
                                                {{ number_format($tData['mt'], 1) }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($tData['mt'] !== null)
                                            <span class="text-{{ $tData['mt'] >= 10 ? 'success' : 'danger' }} fw-bold">
                                                {{ $tData['mt'] >= 10 ? 'Positiva' : 'Negativa' }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">Não há disciplinas associadas a esta turma.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ── 3º TRIMESTRE TABLE ── -->
            <div class="term-table-wrapper" id="termTable-3" style="display:none;">
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-sm mb-0">
                        <thead class="table-light text-xs text-uppercase" style="letter-spacing:0.5px;">
                            <tr>
                                <th class="ps-4">Disciplina</th>
                                <th class="text-center">ACS 1</th>
                                <th class="text-center">ACS 2</th>
                                <th class="text-center">ACS 3</th>
                                <th class="text-center">Média ACS (MACS)</th>
                                <th class="text-center">ACP</th>
                                <th class="text-center">Média Trimestre (MT)</th>
                                <th class="text-center">Aproveitamento</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($matrix as $subjectId => $data)
                                @php
                                    $tData = $data['terms'][3];
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-slate-800">{{ $data['subject']->name }}</div>
                                        <small class="text-xs text-muted">{{ $data['subject']->code }}</small>
                                    </td>
                                    <td class="text-center">{{ $tData['acs1'] !== null ? number_format($tData['acs1'], 1) : '-' }}</td>
                                    <td class="text-center">{{ $tData['acs2'] !== null ? number_format($tData['acs2'], 1) : '-' }}</td>
                                    <td class="text-center">{{ $tData['acs3'] !== null ? number_format($tData['acs3'], 1) : '-' }}</td>
                                    <td class="text-center fw-bold">{{ $tData['macs'] !== null ? number_format($tData['macs'], 1) : '-' }}</td>
                                    <td class="text-center">{{ $tData['acp'] !== null ? number_format($tData['acp'], 1) : '-' }}</td>
                                    <td class="text-center">
                                        @if($tData['mt'] !== null)
                                            <span class="badge bg-{{ $tData['mt'] >= 10 ? 'success' : 'danger' }} grade-badge">
                                                {{ number_format($tData['mt'], 1) }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($tData['mt'] !== null)
                                            <span class="text-{{ $tData['mt'] >= 10 ? 'success' : 'danger' }} fw-bold">
                                                {{ $tData['mt'] >= 10 ? 'Positiva' : 'Negativa' }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">Não há disciplinas associadas a esta turma.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ── ANUAL TABLE ── -->
            <div class="term-table-wrapper" id="termTable-annual" style="display:none;">
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-sm mb-0">
                        <thead class="table-light text-xs text-uppercase" style="letter-spacing:0.5px;">
                            <tr>
                                <th class="ps-4">Disciplina</th>
                                <th class="text-center">MT1</th>
                                <th class="text-center">MT2</th>
                                <th class="text-center">MT3</th>
                                <th class="text-center">Média Anual (MF)</th>
                                <th class="text-center">Exame</th>
                                <th class="text-center">Média Geral (MGD)</th>
                                <th class="text-center">Situação Final</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($matrix as $subjectId => $data)
                                @php
                                    $annual = $data['annual'];
                                    $isPositive = $annual['mfd'] !== null && $annual['mfd'] >= 10;
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-slate-800">{{ $data['subject']->name }}</div>
                                        <small class="text-xs text-muted">{{ $data['subject']->code }}</small>
                                    </td>
                                    <td class="text-center">{{ $annual['mt1'] !== null ? number_format($annual['mt1'], 1) : '-' }}</td>
                                    <td class="text-center">{{ $annual['mt2'] !== null ? number_format($annual['mt2'], 1) : '-' }}</td>
                                    <td class="text-center">{{ $annual['mt3'] !== null ? number_format($annual['mt3'], 1) : '-' }}</td>
                                    <td class="text-center fw-bold">{{ $annual['mf'] !== null ? number_format($annual['mf'], 1) : '-' }}</td>
                                    <td class="text-center">{{ $annual['exam'] !== null ? number_format($annual['exam'], 1) : '-' }}</td>
                                    <td class="text-center">
                                        @if($annual['mfd'] !== null)
                                            <span class="badge bg-{{ $isPositive ? 'success' : 'danger' }} grade-badge">
                                                {{ number_format($annual['mfd'], 1) }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($annual['status'] === 'Aprovado')
                                            <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1 font-bold">Aprovado</span>
                                        @elseif($annual['status'] === 'Reprovado')
                                            <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-1 font-bold">Reprovado</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-1 font-bold">Em Curso</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">Não há disciplinas associadas a esta turma.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Share via Mail Modal -->
<div class="modal fade" id="shareMailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:1rem;">
            <div class="modal-header bg-success text-white" style="border-radius:1rem 1rem 0 0;">
                <h5 class="modal-title fw-bold"><i class="fas fa-envelope-open-text me-2"></i> Enviar Boletim por E-mail</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('students.grades.share-email', $student) }}" method="POST">
                @csrf
                <input type="hidden" name="term" id="modalTermInput" value="1">
                <div class="modal-body p-4">
                    <p class="text-xs text-muted mb-3">O boletim oficial será gerado em formato PDF e anexado diretamente ao e-mail enviado ao encarregado.</p>
                    
                    <div class="mb-3">
                        <label class="form-label text-slate-700 fw-bold">E-mail do Encarregado</label>
                        <input type="email" name="email" class="form-control" value="{{ $student->parent->email ?? '' }}" placeholder="exemplo@email.com" required>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 bg-light/50" style="border-radius:0 0 1rem 1rem;">
                    <button type="button" class="btn btn-secondary btn-sm rounded-pill" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success btn-sm rounded-pill px-3">
                        <i class="fas fa-paper-plane me-1"></i> Enviar Agora
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let currentActiveTerm = '1';

    function switchTerm(term) {
        currentActiveTerm = term;

        // Update active class on buttons
        document.querySelectorAll('.term-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.term === term);
        });

        // Toggle visibility of table wrappers
        document.querySelectorAll('.term-table-wrapper').forEach(wrapper => {
            wrapper.style.display = wrapper.id === `termTable-${term}` ? 'block' : 'none';
        });

        // Update PDF export link with current selected term
        const pdfBtn = document.getElementById('pdfDownloadBtn');
        if (pdfBtn) {
            let url = new URL(pdfBtn.href);
            url.searchParams.set('term', term);
            pdfBtn.href = url.toString();
        }

        // Update modal form hidden input
        const modalTermInput = document.getElementById('modalTermInput');
        if (modalTermInput) {
            modalTermInput.value = term;
        }

        updateWhatsAppShareLink();
    }

    function updateWhatsAppShareLink() {
        const shareBtn = document.getElementById('whatsappShareBtn');
        if (!shareBtn) return;

        const parentPhone = "{{ $student->parent->phone ?? '' }}".replace(/\s+/g, '');
        const studentName = "{{ $student->full_name }}";
        const termLabel = currentActiveTerm === 'annual' ? 'Ano Lectivo' : `${currentActiveTerm}º Trimestre`;
        
        let message = `Olá! Segue o aproveitamento escolar de *${studentName}* relativo ao *${termLabel}*:\n\n`;

        // Parse corresponding visible table rows to construct WhatsApp message text
        const activeTable = document.querySelector(`#termTable-${currentActiveTerm} tbody`);
        if (activeTable) {
            const rows = activeTable.querySelectorAll('tr');
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                if (cells.length >= 2) {
                    const subjectName = cells[0].querySelector('.fw-bold').textContent.trim();
                    let grade = '-';
                    if (currentActiveTerm === 'annual') {
                        grade = cells[6].textContent.trim(); // Media Geral (MGD)
                    } else {
                        grade = cells[6].textContent.trim(); // Media Trimestre (MT)
                    }
                    message += `• *${subjectName}*: Média ${grade}\n`;
                }
            });
        }

        message += `\nPara ver detalhes completos, consulte o portal do encarregado.`;
        
        shareBtn.href = `https://api.whatsapp.com/send?phone=${parentPhone}&text=${encodeURIComponent(message)}`;
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateWhatsAppShareLink();
    });
</script>
@endpush