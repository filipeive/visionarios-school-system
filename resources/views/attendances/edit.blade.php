@extends('layouts.app')

@section('title', 'Editar Presença')
@section('page-title', 'Editar Presença')

@php
    $titleIcon = 'fas fa-edit';
@endphp

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Início</a></li>
    <li class="breadcrumb-item"><a href="{{ route('attendances.index') }}">Presenças</a></li>
    <li class="breadcrumb-item"><a href="{{ route('attendances.show', $attendance) }}">Detalhes</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <form action="{{ route('attendances.update', $attendance) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="school-card">
                <div class="school-card-header">
                    <i class="fas fa-edit"></i>
                    Editando presença de {{ $attendance->student->full_name }} para {{ $attendance->attendance_date->format('d/m/Y') }}
                </div>
                <div class="school-card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Aluno</label>
                            <input type="text" class="form-control" value="{{ $attendance->student->full_name }}" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Turma</label>
                            <input type="text" class="form-control" value="{{ $attendance->class->name }}" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status da Presença <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select" required>
                            @foreach($statuses as $key => $value)
                                <option value="{{ $key }}" {{ $attendance->status == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notas / Justificativa</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes', $attendance->notes) }}</textarea>
                        <small class="form-text text-muted">Adicione uma justificativa para ausência ou atraso, se aplicável.</small>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('attendances.show', $attendance) }}" class="btn-school btn-secondary-school">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                    <button type="submit" class="btn-school btn-primary-school">
                        <i class="fas fa-save"></i> Salvar Alterações
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection