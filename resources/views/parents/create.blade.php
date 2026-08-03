@extends('layouts.app')

@section('title', 'Registar Encarregado de Educação')
@section('page-title', 'Registar Encarregado')
@section('title-icon', 'fas fa-user-plus')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('parents.index') }}">Encarregados</a></li>
    <li class="breadcrumb-item active">Registar</li>
@endsection

@section('content')
<div class="container-fluid p-0">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 font-weight-bold text-dark">
                <i class="fas fa-user-plus text-success me-2"></i> Cadastro de Novo Encarregado de Educação
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('parents.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Primeiro Nome <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Apelido / Sobrenome <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">E-mail (Login do Portal) <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Telefone / WhatsApp <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">N.º do Documento de Identificação (BI / Passaporte)</label>
                        <input type="text" name="bi_number" class="form-control" value="{{ old('bi_number') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Palavra-passe de Acesso ao Portal <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" required minlength="6">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Profissão</label>
                        <input type="text" name="profession" class="form-control" value="{{ old('profession') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Local de Trabalho</label>
                        <input type="text" name="workplace" class="form-control" value="{{ old('workplace') }}">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label font-weight-bold">Endereço Residencial</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address') }}">
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <a href="{{ route('parents.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-success px-4 font-weight-bold">
                        <i class="fas fa-save me-1"></i> Gravar Encarregado
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
