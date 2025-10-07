@extends('layouts.app')

@section('title', 'Comunicados')
@section('page-title', 'Comunicados')
@section('page-title-icon', 'fas fa-bullhorn')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('teacher-portal.dashboard') }}">Portal</a></li>
    <li class="breadcrumb-item active">Comunicados</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Lista de Comunicados -->
            <div class="school-card">
                <div class="school-card-header">
                    <i class="fas fa-bullhorn"></i>
                    Comunicados
                </div>
                <div class="school-card-body">
                    @if ($communications->count() > 0)
                        <div class="communications-list">
                            @foreach ($communications as $communication)
                                <div class="communication-item">
                                    <div class="communication-header">
                                        <div class="communication-title">
                                            <h5>{{ $communication->title }}</h5>
                                            <span class="communication-meta">
                                                <i class="fas fa-calendar"></i>
                                                {{ $communication->created_at->format('d/m/Y H:i') }}
                                                <i class="fas fa-users ms-2"></i>
                                                {{ $communication->target_audience_name }}
                                            </span>
                                        </div>
                                        <div class="communication-priority">
                                            <span class="badge bg-{{ $communication->priority_color }}">
                                                {{ $communication->priority == 'high' ? 'Alta' : ($communication->priority == 'medium' ? 'Média' : 'Baixa') }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="communication-body">
                                        <p>{{ $communication->message }}</p>

                                        @if ($communication->attachments && count($communication->attachments) > 0)
                                            <div class="communication-attachments">
                                                <strong><i class="fas fa-paperclip"></i> Anexos:</strong>
                                                <div class="attachments-list mt-2">
                                                    @foreach ($communication->attachments as $attachment)
                                                        <span class="badge bg-secondary me-1">
                                                            <i class="fas fa-file"></i> {{ basename($attachment) }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="communication-footer">
                                        <small class="text-muted">
                                            <i class="fas fa-user"></i>
                                            Enviado por: {{ $communication->createdBy->name ?? 'Sistema' }}
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Paginação -->
                        @if ($communications->hasPages())
                            <div class="mt-4">
                                {{ $communications->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-bullhorn fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">Nenhum Comunicado</h4>
                            <p class="text-muted">Não há comunicados no momento.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .communication-item {
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            padding: 20px;
            margin-bottom: 20px;
            background: var(--card-bg);
            transition: all 0.3s ease;
        }

        .communication-item:hover {
            box-shadow: var(--shadow);
        }

        .communication-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .communication-title h5 {
            margin: 0;
            color: var(--text-primary);
        }

        .communication-meta {
            font-size: 12px;
            color: var(--text-muted);
        }

        .communication-priority {
            flex-shrink: 0;
        }

        .communication-body {
            margin-bottom: 15px;
        }

        .communication-body p {
            margin: 0;
            line-height: 1.6;
        }

        .communication-attachments {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid var(--border-color);
        }

        .attachments-list {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        .communication-footer {
            padding-top: 10px;
            border-top: 1px solid var(--border-color);
            font-size: 12px;
        }
    </style>
@endsection
