@extends('layouts.app')

@section('title', 'Desempenho Acadêmico')
@section('page-title', 'Relatório de Desempenho')
@section('page-title-icon', 'fas fa-chart-line')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Relatórios</a></li>
    <li class="breadcrumb-item"><a href="{{ route('reports.academic') }}">Acadêmico</a></li>
    <li class="breadcrumb-item active">Desempenho</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="school-card mb-4">
                <div class="school-card-body">
                    <form action="{{ route('reports.academic.performance') }}" method="GET" class="row g-3">
                        <div class="col-md-4">
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
                        <div class="col-md-4">
                            <label class="form-label">Trimestre</label>
                            <select name="term" class="form-select">
                                <option value="">Todos</option>
                                <option value="1" {{ request('term') == '1' ? 'selected' : '' }}>1º Trimestre</option>
                                <option value="2" {{ request('term') == '2' ? 'selected' : '' }}>2º Trimestre</option>
                                <option value="3" {{ request('term') == '3' ? 'selected' : '' }}>3º Trimestre</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
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
                        <i class="fas fa-list"></i> Notas Lançadas
                    </h3>
                </div>
                <div class="school-card-body">
                    <div class="table-responsive">
                        <table class="table table-school">
                            <thead>
                                <tr>
                                    <th>Aluno</th>
                                    <th>Turma</th>
                                    <th>Disciplina</th>
                                    <th>Trimestre</th>
                                    <th>Nota</th>
                                    <th>Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($grades as $grade)
                                    <tr>
                                        <td>{{ $grade->student->first_name }} {{ $grade->student->last_name }}</td>
                                        <td>{{ $grade->class->name }}</td>
                                        <td>{{ $grade->subject->name }}</td>
                                        <td>{{ $grade->term }}º</td>
                                        <td>
                                            <span class="badge bg-{{ $grade->value >= 10 ? 'success' : 'danger' }}">
                                                {{ number_format($grade->value, 1) }}
                                            </span>
                                        </td>
                                        <td>{{ $grade->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $grades->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection