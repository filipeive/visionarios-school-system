@extends('layouts.app')

@section('title', 'Nova Solicitação de Licença')
@section('page-title', 'Nova Solicitação de Licença')
@section('page-title-icon', 'fas fa-calendar-plus')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('teacher.leave-requests.index') }}">Licenças</a></li>
    <li class="breadcrumb-item active">Nova Solicitação</li>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="school-card">
                <div class="school-card-header">
                    <i class="fas fa-edit me-2"></i>Preencha os dados da solicitação
                </div>
                <div class="school-card-body">
                    <form action="{{ route('teacher.leave-requests.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Tipo de Licença *</label>
                            <select name="leave_type" class="form-select @error('leave_type') is-invalid @enderror"
                                required>
                                <option value="">Selecione...</option>
                                <option value="sick">Licença Médica</option>
                                <option value="personal">Assuntos Pessoais</option>
                                <option value="vacation">Férias</option>
                                <option value="study">Estudos</option>
                                <option value="other">Outro</option>
                            </select>
                            @error('leave_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Data de Início *</label>
                                <input type="date" name="start_date"
                                    class="form-control @error('start_date') is-invalid @enderror"
                                    value="{{ old('start_date') }}" required min="{{ date('Y-m-d') }}">
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Data de Término *</label>
                                <input type="date" name="end_date"
                                    class="form-control @error('end_date') is-invalid @enderror"
                                    value="{{ old('end_date') }}" required min="{{ date('Y-m-d') }}">
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Motivo / Justificativa *</label>
                            <textarea name="reason" class="form-control @error('reason') is-invalid @enderror" rows="4"
                                required
                                placeholder="Descreva o motivo da sua solicitação...">{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Anexo (Opcional)</label>
                            <input type="file" name="attachment"
                                class="form-control @error('attachment') is-invalid @enderror">
                            <div class="form-text">Formatos aceitos: PDF, JPG, PNG. Máx: 2MB.</div>
                            @error('attachment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('teacher.leave-requests.index') }}" class="btn btn-secondary-school">
                                <i class="fas fa-arrow-left me-2"></i>Voltar
                            </a>
                            <button type="submit" class="btn btn-primary-school">
                                <i class="fas fa-paper-plane me-2"></i>Enviar Solicitação
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection