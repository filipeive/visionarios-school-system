@extends('layouts.app')

@section('title', 'Auditoria do Sistema')
@section('page-title', 'Logs de Atividade')
@section('title-icon', 'fas fa-history')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Auditoria</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="school-card">
                <div class="school-card-header">
                    <i class="fas fa-list me-2"></i> Registro de Atividades
                </div>
                <div class="school-card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Data/Hora</th>
                                    <th>Usuário</th>
                                    <th>Ação</th>
                                    <th>Objeto</th>
                                    <th>Detalhes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activities as $activity)
                                    <tr>
                                        <td>{{ $activity->created_at->format('d/m/Y H:i:s') }}</td>
                                        <td>
                                            @if($activity->causer)
                                                <a href="{{ route('admin.users.show', $activity->causer) }}">
                                                    {{ $activity->causer->name }}
                                                </a>
                                            @else
                                                <span class="text-muted">Sistema</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $activity->event == 'created' ? 'success' : ($activity->event == 'updated' ? 'warning' : ($activity->event == 'deleted' ? 'danger' : 'info')) }}">
                                                {{ ucfirst($activity->event) }}
                                            </span>
                                            <span class="ms-1 text-muted small">{{ $activity->description }}</span>
                                        </td>
                                        <td>
                                            @if($activity->subject_type)
                                                <span title="{{ $activity->subject_type }}">
                                                    {{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }}
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                data-bs-toggle="modal" data-bs-target="#activityModal{{ $activity->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="activityModal{{ $activity->id }}" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Detalhes da Atividade #{{ $activity->id }}
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row mb-3">
                                                                <div class="col-md-6">
                                                                    <strong>Usuário:</strong>
                                                                    {{ $activity->causer->name ?? 'Sistema' }}
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <strong>Data:</strong>
                                                                    {{ $activity->created_at->format('d/m/Y H:i:s') }}
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <strong>Descrição:</strong> {{ $activity->description }}
                                                            </div>
                                                            <div class="mb-3">
                                                                <strong>Mudanças:</strong>
                                                                <pre
                                                                    class="bg-light p-3 rounded">@json($activity->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)</pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Nenhuma atividade registrada.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $activities->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection