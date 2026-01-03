@extends('layouts.app')

@section('title', 'Relatório de Frequência')
@section('page-title', 'Relatório de Frequência')
@section('page-title-icon', 'fas fa-calendar-check')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Relatórios</a></li>
    <li class="breadcrumb-item"><a href="{{ route('reports.academic') }}">Acadêmico</a></li>
    <li class="breadcrumb-item active">Frequência</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="school-card mb-4">
                <div class="school-card-body">
                    <form action="{{ route('reports.academic.attendance') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Turma</label>
                            <select name="class_id" class="form-select">
                                <option value="">Todas as turmas</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">De</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Até</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary-school w-100">
                                <i class="fas fa-filter"></i> Filtrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="school-card">
                <div class="school-card-header">
                    <h3 class="school-card-title">
                        <i class="fas fa-list"></i> Registros de Frequência
                    </h3>
                </div>
                <div class="school-card-body">
                    <div class="table-responsive">
                        <table class="table table-school">
                            <thead>
                                <tr>
                                    <th>Aluno</th>
                                    <th>Turma</th>
                                    <th>Data</th>
                                    <th>Status</th>
                                    <th>Observação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendances as $attendance)
                                    <tr>
                                        <td>{{ $attendance->student->first_name }} {{ $attendance->student->last_name }}</td>
                                        <td>{{ $attendance->class->name }}</td>
                                        <td>{{ $attendance->date->format('d/m/Y') }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $attendance->status === 'present' ? 'success' : ($attendance->status === 'absent' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($attendance->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $attendance->notes ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $attendances->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection