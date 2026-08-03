@extends('layouts.app')

@section('title', 'Editar Encarregado')
@section('page-title', 'Editar Encarregado')
@section('title-icon', 'fas fa-edit')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('parents.index') }}">Encarregados</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
<div class="container-fluid p-0">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 font-weight-bold text-dark">
                <i class="fas fa-edit text-primary me-2"></i> Editar Dados de {{ $parent->full_name }}
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('parents.update', $parent->user_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Primeiro Nome <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $parent->first_name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Apelido / Sobrenome <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $parent->last_name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Telefone / WhatsApp <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $parent->phone) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">N.º do Documento de Identificação (BI / Passaporte)</label>
                        <input type="text" name="bi_number" class="form-control" value="{{ old('bi_number', $parent->bi_number) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Profissão</label>
                        <input type="text" name="profession" class="form-control" value="{{ old('profession', $parent->profession) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Local de Trabalho</label>
                        <input type="text" name="workplace" class="form-control" value="{{ old('workplace', $parent->workplace) }}">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label font-weight-bold">Endereço Residencial</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address', $parent->address) }}">
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <a href="{{ route('parents.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary px-4 font-weight-bold">
                        <i class="fas fa-save me-1"></i> Atualizar Dados
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
