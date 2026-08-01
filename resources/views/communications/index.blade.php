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
                                                <button type="button" class="btn btn-sm btn-light rounded-circle" 
                                                    title="Ver Detalhes"
                                                    onclick="openCommModal(@json($comm->title), @json($comm->content), @json($comm->target_audience_name), @json(ucfirst($comm->priority)), @json($comm->creator->name ?? 'N/A'), @json($comm->created_at->format('d/m/Y H:i')))">
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

    <!-- Modal de Detalhes do Comunicado -->
    <div class="modal fade" id="commDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-bold d-flex align-items-center gap-2" id="commTitle">
                        <i class="fas fa-bullhorn"></i> <span>Detalhes do Comunicado</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="d-flex flex-wrap gap-3 mb-3 p-3 bg-light rounded-2 text-xs">
                        <div><strong>Público-Alvo:</strong> <span id="commAudience" class="badge bg-secondary"></span></div>
                        <div><strong>Prioridade:</strong> <span id="commPriority" class="badge bg-info"></span></div>
                        <div><strong>Emitido por:</strong> <span id="commAuthor" class="text-muted"></span></div>
                        <div><strong>Data:</strong> <span id="commDate" class="text-muted"></span></div>
                    </div>
                    <div class="border rounded-2 p-3 bg-white text-sm leading-relaxed" id="commContent" style="min-height: 120px; max-height: 400px; overflow-y: auto;">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary text-xs rounded-pill px-4" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openCommModal(title, content, audience, priority, author, date) {
            document.getElementById('commTitle').querySelector('span').textContent = title;
            document.getElementById('commAudience').textContent = audience;
            document.getElementById('commPriority').textContent = priority;
            document.getElementById('commAuthor').textContent = author;
            document.getElementById('commDate').textContent = date;
            document.getElementById('commContent').innerHTML = content || '<p class="text-muted">Sem conteúdo detalhado.</p>';
            new bootstrap.Modal(document.getElementById('commDetailModal')).show();
        }
    </script>
    @endpush
@endsection