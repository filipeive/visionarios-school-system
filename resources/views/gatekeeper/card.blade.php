@extends('layouts.app')

@section('title', 'Cartão de Estudante — ' . $student->full_name)

@push('styles')
<style>
    @media print {
        body * { visibility: hidden; }
        .print-area, .print-area * { visibility: visible; }
        .print-area { position: absolute; top: 0; left: 0; width: 100%; }
        .no-print { display: none !important; }
        .card-wrapper { break-inside: avoid; page-break-inside: avoid; }
    }

    .card-container-flex {
        display: flex;
        flex-wrap: wrap;
        gap: 2rem;
        justify-content: center;
        align-items: center;
        padding: 1.5rem 0;
    }

    /* Standard CR80 Card Proportions (85.6mm x 53.9mm scaled) */
    .id-card {
        width: 340px;
        height: 520px;
        background: #ffffff;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 12px 35px rgba(0,0,0,0.12);
        border: 1px solid #e2e8f0;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
    }

    /* Front Card Styling */
    .id-card-front .card-header-bg {
        background: linear-gradient(135deg, #0f5132 0%, #198754 60%, #20c997 100%);
        padding: 1.25rem 1rem;
        color: white;
        text-align: center;
        position: relative;
    }
    .id-card-front .school-title {
        font-size: 0.95rem;
        font-weight: 800;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-bottom: 2px;
    }
    .id-card-front .card-subtitle {
        font-size: 0.62rem;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        opacity: 0.85;
    }

    .id-card-front .photo-wrapper {
        text-align: center;
        margin-top: 1rem;
        margin-bottom: 0.5rem;
    }
    .id-card-front .student-photo {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #198754;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }
    .id-card-front .student-photo-placeholder {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, #d4edda, #a3cfbb);
        color: #0f5132;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
        font-weight: 800;
        margin: 0 auto;
        border: 4px solid #198754;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }

    .id-card-front .student-info {
        text-align: center;
        padding: 0 1.25rem;
    }
    .id-card-front .student-name {
        font-size: 1.1rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
        margin-bottom: 0.35rem;
    }
    .id-card-front .student-num-badge {
        display: inline-block;
        background: #f1f5f9;
        color: #0f5132;
        font-family: 'Courier New', monospace;
        font-weight: 700;
        font-size: 0.85rem;
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        letter-spacing: 2px;
        border: 1px solid #cbd5e1;
        margin-bottom: 0.75rem;
    }

    .id-card-front .details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.5rem;
        text-align: left;
        background: #f8fafc;
        padding: 0.75rem 1rem;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        margin: 0 1rem;
        font-size: 0.72rem;
    }
    .id-card-front .details-grid .label {
        color: #64748b;
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .id-card-front .details-grid .value {
        font-weight: 700;
        color: #1e293b;
    }

    .id-card-footer {
        background: #0f5132;
        color: rgba(255,255,255,0.85);
        text-align: center;
        padding: 0.5rem 1rem;
        font-size: 0.6rem;
        letter-spacing: 1px;
        text-transform: uppercase;
        font-weight: 600;
    }

    /* Back Card Styling */
    .id-card-back .card-header-bg {
        background: #0f5132;
        padding: 0.85rem 1rem;
        color: white;
        text-align: center;
    }
    .id-card-back .back-title {
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .id-card-back .qr-center-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        background: #ffffff;
        text-align: center;
    }
    .id-card-back .qr-frame {
        background: #ffffff;
        padding: 0.5rem;
        border-radius: 12px;
        border: 2px solid #198754;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        display: inline-block;
        margin: 0 auto;
    }
    .id-card-back .qr-frame img {
        width: 150px;
        height: 150px;
        display: block;
        margin: 0 auto;
    }
    .id-card-back .qr-caption {
        font-size: 0.62rem;
        color: #64748b;
        margin-top: 0.4rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .id-card-back .emergency-info {
        padding: 0.75rem 1.25rem;
        font-size: 0.7rem;
        color: #334155;
        border-top: 1px dashed #cbd5e1;
        background: #f8fafc;
    }
    .id-card-back .emergency-info .item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.35rem;
    }
    .id-card-back .emergency-info .item-label {
        color: #64748b;
        font-weight: 600;
    }
    .id-card-back .emergency-info .item-val {
        font-weight: 700;
        color: #0f172a;
    }

    .id-card-back .disclaimer {
        padding: 0.5rem 1rem;
        font-size: 0.58rem;
        color: #94a3b8;
        text-align: center;
        line-height: 1.3;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <!-- Top Action Toolbar -->
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <div>
            <a href="{{ route('gatekeeper.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
                <i class="fas fa-arrow-left me-1"></i> Voltar à Portaria
            </a>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-success btn-sm rounded-pill px-4">
                <i class="fas fa-print me-1"></i> Imprimir Cartão (Frente & Verso)
            </button>
        </div>
    </div>

    <!-- Cards Layout (Front & Back side by side for display/print) -->
    <div class="print-area">
        <div class="card-container-flex">
            <!-- ════ FRENTE DO CARTÃO ════ -->
            <div class="card-wrapper">
                <div class="text-center text-muted text-xs mb-2 fw-bold no-print">FRENTE DO CARTÃO</div>
                <div class="id-card id-card-front">
                    <div>
                        <div class="card-header-bg">
                            <div class="school-title">{{ setting('school_name', config('app.name', 'ZamEdu SIGE')) }}</div>
                            <div class="card-subtitle">Cartão de Identificação Escolar</div>
                        </div>

                        <div class="photo-wrapper">
                            @if($student->photo)
                                <img src="{{ asset('storage/' . $student->photo) }}" class="student-photo" alt="Foto">
                            @else
                                <div class="student-photo-placeholder">
                                    {{ strtoupper(substr($student->first_name, 0, 1) . substr($student->last_name ?? '', 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <div class="student-info">
                            <div class="student-name">{{ $student->full_name }}</div>
                            <div class="student-num-badge">{{ $student->student_number }}</div>
                        </div>

                        <div class="details-grid">
                            <div>
                                <div class="label">Turma / Nível</div>
                                <div class="value">{{ $student->currentEnrollment->class->name ?? 'Sem Turma' }}</div>
                            </div>
                            <div>
                                <div class="label">Ano Lectivo</div>
                                <div class="value">{{ setting('academic_year', date('Y')) }}</div>
                            </div>
                            <div>
                                <div class="label">Data Nasc.</div>
                                <div class="value">{{ $student->dob ? \Carbon\Carbon::parse($student->dob)->format('d/m/Y') : 'N/D' }}</div>
                            </div>
                            <div>
                                <div class="label">Gênero</div>
                                <div class="value">{{ $student->gender === 'M' ? 'Masculino' : ($student->gender === 'F' ? 'Feminino' : 'N/D') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="id-card-footer">
                        Validade: 31/12/{{ setting('academic_year', date('Y')) }} &bull; {{ setting('school_short_name', 'ZamEdu') }}
                    </div>
                </div>
            </div>

            <!-- ════ VERSO DO CARTÃO ════ -->
            <div class="card-wrapper">
                <div class="text-center text-muted text-xs mb-2 fw-bold no-print">VERSO DO CARTÃO</div>
                <div class="id-card id-card-back">
                    <div>
                        <div class="card-header-bg">
                            <div class="back-title"><i class="fas fa-qrcode me-1"></i> Verificação de Segurança</div>
                        </div>

                        <!-- QR CODE CENTRALIZADO -->
                        <div class="qr-center-container">
                            <div class="qr-frame">
                                @if($qrBase64)
                                    <img src="data:image/png;base64,{{ $qrBase64 }}" alt="QR Code">
                                @else
                                    <div class="p-3 text-muted text-xs">QR Indisponível</div>
                                @endif
                            </div>
                            <div class="qr-caption">Acesso Portaria &bull; Validação de Presença</div>
                        </div>

                        <!-- INFORMAÇÕES DE EMERGÊNCIA E CONTACTO -->
                        <div class="emergency-info">
                            <div class="item">
                                <span class="item-label">Encarregado:</span>
                                <span class="item-val">{{ $student->parent->name ?? 'N/D' }}</span>
                            </div>
                            <div class="item">
                                <span class="item-label">Contacto Emergência:</span>
                                <span class="item-val">{{ $student->parent->phone ?? $student->phone ?? 'N/D' }}</span>
                            </div>
                            <div class="item">
                                <span class="item-label">Contacto Escola:</span>
                                <span class="item-val">{{ setting('school_phone', '+258 84 000 0000') }}</span>
                            </div>
                        </div>

                        <div class="disclaimer">
                            Este cartão é pessoal e intransmissível. Em caso de perda ou achado, favor entregar na Secretaria da Escola.
                        </div>
                    </div>

                    <div class="id-card-footer">
                        Assinatura Autorizada da Direção
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
