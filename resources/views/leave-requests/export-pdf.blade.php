<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Relatório de Licenças</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1f2937;
        }

        h1 {
            margin: 0 0 4px 0;
            font-size: 18px;
        }

        .muted {
            color: #6b7280;
            margin-bottom: 10px;
        }

        .meta {
            margin-bottom: 12px;
            padding: 8px;
            background: #f3f4f6;
            border-radius: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #d1d5db;
            padding: 6px;
            vertical-align: top;
        }

        th {
            background: #e5e7eb;
            text-align: left;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <h1>Relatório de Pedidos de Licença</h1>
    <div class="muted">Gerado em {{ $generatedAt->format('d/m/Y H:i') }} por {{ $generatedBy ?? 'Sistema' }}</div>

    <div class="meta">
        <strong>Filtros:</strong>
        Professor: {{ $filters['teacher'] ?? 'Todos' }} |
        Status: {{ $filters['status'] ?? 'Todos' }} |
        Tipo: {{ $filters['leave_type'] ?? 'Todos' }} |
        De: {{ $filters['date_from'] ?? '-' }} |
        Até: {{ $filters['date_to'] ?? '-' }}
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Professor</th>
                <th>Tipo</th>
                <th>Período</th>
                <th>Dias</th>
                <th>Status</th>
                <th>Solicitado</th>
                <th>Analisado por</th>
                <th>Motivo rejeição</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($leaveRequests as $leaveRequest)
                <tr>
                    <td>{{ $leaveRequest->id }}</td>
                    <td>{{ $leaveRequest->staff?->full_name }}</td>
                    <td>{{ $leaveRequest->leave_type_name }}</td>
                    <td>
                        {{ $leaveRequest->start_date?->format('d/m/Y') }}
                        -
                        {{ $leaveRequest->end_date?->format('d/m/Y') }}
                    </td>
                    <td>{{ $leaveRequest->days_requested }}</td>
                    <td>{{ $leaveRequest->status }}</td>
                    <td>{{ $leaveRequest->created_at?->format('d/m/Y H:i') }}</td>
                    <td>{{ $leaveRequest->approvedBy?->name ?? '-' }}</td>
                    <td>{{ $leaveRequest->rejection_reason ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">Nenhum pedido encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
