@extends('layouts.app')

@section('title', 'Gestão de Comunicados')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0 text-gray-800">Gestão de Comunicados</h1>
                @can('send_notifications')
                    <a href="{{ route('communications.create') }}" class="btn btn-primary-school rounded-pill px-4">
                        <i class="fas fa-plus me-2"></i> Novo Comunicado
                    </a>
                @endcan
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="school-card">
                    <div class="school-card-header">
                        <i class="fas fa-bullhorn me-2"></i> Histórico de Comunicados
                    </div>
                    <div class="school-card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Data</th>
                                        <th>Título</th>
                                        <th>Público-Alvo</th>
                                        <th>Prioridade</th>
                                        <th>Autor</th>
                                        <th>Status</th>
                                        <th class="text-end">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($communications as $comm)
                                        <tr>
                                            <td>{{ $comm->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <div class="fw-bold">{{ $comm->title }}</div>
                                                <div class="small text-muted text-truncate" style="max-width: 300px;">
                                                    {{ $comm->excerpt }}</div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary rounded-pill">
                                                    {{ $comm->target_audience_name }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $comm->priority_color }} rounded-pill">
                                                    {{ ucfirst($comm->priority) }}
                                                </span>
                                            </td>
                                            <td>{{ $comm->creator->name ?? 'N/A' }}</td>
                                            <td>
                                                @if($comm->is_published)
                                                    <span class="text-success small"><i class="fas fa-check-circle me-1"></i>
                                                        Publicado</span>
                                                @else
                                                    <span class="text-warning small"><i class="fas fa-clock me-1"></i>
                                                        Agendado</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <button class="btn btn-sm btn-light rounded-circle" title="Ver Detalhes">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">
                                                Nenhum comunicado enviado ainda.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $communications->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection