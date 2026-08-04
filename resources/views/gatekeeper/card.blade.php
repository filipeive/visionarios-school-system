@extends('layouts.app')

@section('title', 'Cartão de Aluno — ' . $student->full_name)

@push('styles')
<style>
    @media print {
        body * { visibility: hidden; }
        .student-card-print, .student-card-print * { visibility: visible; }
        .student-card-print { position: absolute; top: 0; left: 0; }
        .no-print { display: none !important; }
    }

    .student-card-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 60vh;
        padding: 2rem;
    }

    .student-card-print {
        width: 340px;
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 40px rgba(0,0,0,0.12);
        border: 1px solid #e9ecef;
    }

    .card-header-strip {
        background: linear-gradient(135deg, #0f5132 0%, #198754 100%);
        padding: 1.2rem 1.5rem;
        color: white;
        text-align: center;
    }
    .card-header-strip h6 {
        margin: 0;
        font-size: 0.85rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
    }
    .card-header-strip small {
        font-size: 0.6rem;
        opacity: 0.8;
        text-transform: uppercase;
        letter-spacing: 1.5px;
    }

    .card-body-content {
        padding: 1.5rem;
        text-align: center;
    }

    .card-photo {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #198754;
        margin-bottom: 0.75rem;
    }
    .card-photo-placeholder {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #d4edda, #b5e2c4);
        color: #0f5132;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        font-weight: 700;
        margin: 0 auto 0.75rem;
        border: 3px solid #198754;
    }

    .card-student-name {
        font-size: 1rem;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 0.25rem;
    }
    .card-student-number {
        font-size: 0.75rem;
        font-family: 'Courier New', monospace;
        color: #6c757d;
        letter-spacing: 2px;
        margin-bottom: 0.75rem;
    }
    .card-student-class {
        font-size: 0.7rem;
        color: #495057;
        background: #f8f9fa;
        border-radius: 6px;
        padding: 0.3rem 0.8rem;
        display: inline-block;
        margin-bottom: 1rem;
    }

    .qr-section {
        padding: 1rem;
        background: #f8fdf9;
        border-top: 1px dashed #dee2e6;
        text-align: center;
    }
    .qr-section img {
        width: 160px;
        height: 160px;
        border-radius: 8px;
    }
    .qr-section small {
        display: block;
        margin-top: 0.5rem;
        font-size: 0.6rem;
        color: #adb5bd;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .card-footer-strip {
        background: #0f5132;
        color: rgba(255,255,255,0.7);
        text-align: center;
        padding: 0.5rem;
        font-size: 0.55rem;
        letter-spacing: 1px;
        text-transform: uppercase;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="text-center mb-4 no-print">
        <a href="{{ route('gatekeeper.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill me-2">
            <i class="fas fa-arrow-left me-1"></i> Voltar à Portaria
        </a>
        <button onclick="window.print()" class="btn btn-success btn-sm rounded-pill">
            <i class="fas fa-print me-1"></i> Imprimir Cartão
        </button>
    </div>

    <div class="student-card-container">
        <div class="student-card-print">
            <div class="card-header-strip">
                <small>Cartão de Identificação Escolar</small>
                <h6>{{ setting('school_name', config('app.name', 'ZamEdu SIGE')) }}</h6>
            </div>

            <div class="card-body-content">
                @if($student->photo)
                    <img src="{{ asset('storage/' . $student->photo) }}" class="card-photo" alt="Foto">
                @else
                    <div class="card-photo-placeholder">
                        {{ strtoupper(substr($student->first_name, 0, 1) . substr($student->last_name ?? '', 0, 1)) }}
                    </div>
                @endif

                <div class="card-student-name">{{ $student->full_name }}</div>
                <div class="card-student-number">{{ $student->student_number }}</div>
                <div class="card-student-class">
                    <i class="fas fa-graduation-cap me-1"></i>
                    {{ $student->currentEnrollment->class->name ?? 'Sem turma' }}
                </div>
            </div>

            <div class="qr-section">
                @if($qrBase64)
                    <img src="data:image/png;base64,{{ $qrBase64 }}" alt="QR Code">
                @else
                    <div class="p-4 text-muted">
                        <i class="fas fa-exclamation-circle me-1"></i> QR indisponível
                    </div>
                @endif
                <small>Escaneie para verificar identidade</small>
            </div>

            <div class="card-footer-strip">
                {{ setting('school_short_name', 'ZamEdu') }} &bull; Ano Lectivo {{ setting('academic_year', date('Y')) }}
            </div>
        </div>
    </div>
</div>
@endsection
