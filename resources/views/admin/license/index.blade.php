@extends('layouts.app')

@section('title', 'Gestão de Licença - ZamEdu')
@section('page-title', 'Gestão de Licença')
@section('page-title-icon', 'fas fa-key')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Licença do Sistema</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="school-card">
            <div class="school-card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-certificate me-2"></i>Estado da Licença ZamEdu</span>
                @if($license)
                    @if($license->status === 'active')
                        <span class="badge bg-success">Ativa</span>
                    @elseif($license->status === 'grace_period')
                        <span class="badge bg-warning text-dark">Período de Tolerância</span>
                    @else
                        <span class="badge bg-danger">Suspensa</span>
                    @endif
                @else
                    <span class="badge bg-secondary">Não Registada</span>
                @endif
            </div>
            <div class="school-card-body">



                <form action="{{ route('admin.license.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nome da Entidade / Cliente</label>
                        <input type="text" name="client_name" class="form-control" value="{{ old('client_name', $license->client_name ?? '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Chave da Licença</label>
                        <input type="text" name="license_key" class="form-control font-monospace" value="{{ old('license_key', $license->license_key ?? '') }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Data de Expiração</label>
                            <input type="date" name="expires_at" class="form-control" value="{{ old('expires_at', $license?->expires_at ? $license->expires_at->format('Y-m-d') : '') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Plano de Subscrição</label>
                            <select name="plan" class="form-select">
                                <option value="standard" {{ ($license->plan ?? '') === 'standard' ? 'selected' : '' }}>Standard</option>
                                <option value="premium" {{ ($license->plan ?? '') === 'premium' ? 'selected' : '' }}>Premium</option>
                                <option value="enterprise" {{ ($license->plan ?? '') === 'enterprise' ? 'selected' : '' }}>Enterprise</option>
                            </select>
                        </div>
                    </div>

                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-primary-school">
                            <i class="fas fa-save me-2"></i>Guardar Licença
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
