<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Boletim Escolar — {{ $student->full_name }}</title>
    <style>
        @page {
            margin: 10mm 8mm 10mm 8mm;
        }
        body {
            font-family: 'DejaVu Sans', Helvetica, Arial, sans-serif;
            color: #333333;
            font-size: 10px;
            line-height: 1.3;
            margin: 0;
            padding: 0;
        }
        .header-container {
            border-bottom: 2px solid #1b5e20;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .school-name {
            font-size: 16px;
            font-weight: bold;
            color: #1b5e20;
            text-transform: uppercase;
        }
        .school-subtitle {
            font-size: 9px;
            color: #666;
            margin-top: 2px;
        }
        .bulletin-title {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 12px 0;
            color: #111;
            letter-spacing: 1px;
        }
        .student-info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            background-color: #f9f9f9;
        }
        .student-info-table td {
            padding: 5px 8px;
            border: 1px solid #e2e8f0;
            font-size: 10px;
        }
        .info-label {
            font-weight: bold;
            color: #4a5568;
            width: 18%;
        }
        .info-value {
            color: #1a202c;
        }
        .grades-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: fixed;
        }
        .grades-table th {
            background-color: #1b5e20;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8px;
            padding: 6px 4px;
            border: 1px solid #1b5e20;
            text-align: center;
        }
        .grades-table th.left-align, .grades-table td.left-align {
            text-align: left;
        }
        .grades-table td {
            padding: 5px 4px;
            border: 1px solid #cbd5e1;
            text-align: center;
            font-size: 9px;
        }
        .grade-value {
            font-weight: bold;
        }
        .grade-positive {
            color: #15803d;
        }
        .grade-negative {
            color: #b91c1c;
        }
        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #dcfce7;
            color: #15803d;
        }
        .badge-danger {
            background-color: #fee2e2;
            color: #b91c1c;
        }
        .badge-warning {
            background-color: #fef9c3;
            color: #854d0e;
        }
        .signatures-container {
            margin-top: 35px;
            width: 100%;
        }
        .signature-box {
            width: 45%;
            display: inline-block;
            text-align: center;
            font-size: 9px;
        }
        .signature-line {
            border-top: 1px solid #000;
            width: 80%;
            margin: 30px auto 5px;
        }
        .footer-text {
            text-align: center;
            font-size: 8px;
            color: #718096;
            margin-top: 30px;
            border-top: 1px solid #e2e8f0;
            padding-top: 6px;
        }
    </style>
</head>
<body>

    <!-- Cabeçalho Oficial -->
    <div class="header-container">
        <table style="width: 100%;">
            <tr>
                <td>
                    <div class="school-name">{{ setting('school_name', 'ZamEdu') }}</div>
                    <div class="school-subtitle">República de Moçambique &bull; Ministério da Educação e Desenvolvimento Humano</div>
                    <div class="school-subtitle">Contacto: {{ setting('phone', '+258 84 000 0000') }} | Email: {{ setting('email', 'contacto@zamedu.co.mz') }}</div>
                </td>
                <td style="text-align: right; vertical-align: top;">
                    <div style="font-size: 14px; font-weight: bold; color: #1b5e20;">SIGE</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Título do Boletim -->
    <div class="bulletin-title">
        @if($term === 'annual')
            Ficha de Aproveitamento Anual
        @else
            Boletim Escolar do {{ $term }}º Trimestre
        @endif
    </div>

    <!-- Informações do Estudante -->
    <table class="student-info-table">
        <tr>
            <td class="info-label">Estudante:</td>
            <td class="info-value" colspan="3"><strong>{{ $student->full_name }}</strong></td>
        </tr>
        <tr>
            <td class="info-label">Nº Matrícula:</td>
            <td class="info-value">{{ $student->student_number }}</td>
            <td class="info-label">Turma:</td>
            <td class="info-value">{{ $currentEnrollment->class->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="info-label">Ano Lectivo:</td>
            <td class="info-value">{{ $currentEnrollment->school_year ?? date('Y') }}</td>
            <td class="info-label">Data Emissão:</td>
            <td class="info-value">{{ now()->format('d/m/Y H:i') }}</td>
        </tr>
    </table>

    <!-- Tabela de Notas -->
    <table class="grades-table">
        <thead>
            @if($term === 'annual')
                <tr>
                    <th class="left-align">Disciplina</th>
                    <th>1º Trim (MT1)</th>
                    <th>2º Trim (MT2)</th>
                    <th>3º Trim (MT3)</th>
                    <th>Média Final (MF)</th>
                    <th>Exame</th>
                    <th>Média Geral (MGD)</th>
                    <th>Situação</th>
                </tr>
            @else
                <tr>
                    <th class="left-align">Disciplina</th>
                    <th>ACS 1</th>
                    <th>ACS 2</th>
                    <th>ACS 3</th>
                    <th>Média ACS (MACS)</th>
                    <th>ACP</th>
                    <th>Média Trimestre (MT)</th>
                    <th>Aproveitamento</th>
                </tr>
            @endif
        </thead>
        <tbody>
            @forelse($matrix as $subjectId => $data)
                @if($term === 'annual')
                    @php
                        $annual = $data['annual'];
                        $isPositive = $annual['mfd'] !== null && $annual['mfd'] >= 10;
                    @endphp
                    <tr>
                        <td class="left-align"><strong>{{ $data['subject']->name }}</strong></td>
                        <td>{{ $annual['mt1'] !== null ? number_format($annual['mt1'], 1) : '-' }}</td>
                        <td>{{ $annual['mt2'] !== null ? number_format($annual['mt2'], 1) : '-' }}</td>
                        <td>{{ $annual['mt3'] !== null ? number_format($annual['mt3'], 1) : '-' }}</td>
                        <td class="grade-value">{{ $annual['mf'] !== null ? number_format($annual['mf'], 1) : '-' }}</td>
                        <td>{{ $annual['exam'] !== null ? number_format($annual['exam'], 1) : '-' }}</td>
                        <td class="grade-value {{ $isPositive ? 'grade-positive' : 'grade-negative' }}">
                            {{ $annual['mfd'] !== null ? number_format($annual['mfd'], 1) : '-' }}
                        </td>
                        <td>
                            @if($annual['status'] === 'Aprovado')
                                <span class="badge badge-success">Aprovado</span>
                            @elseif($annual['status'] === 'Reprovado')
                                <span class="badge badge-danger">Reprovado</span>
                            @else
                                <span class="badge badge-warning">Em Curso</span>
                            @endif
                        </td>
                    </tr>
                @else
                    @php
                        $tData = $data['terms'][$term];
                        $isPositive = $tData['mt'] !== null && $tData['mt'] >= 10;
                    @endphp
                    <tr>
                        <td class="left-align"><strong>{{ $data['subject']->name }}</strong></td>
                        <td>{{ $tData['acs1'] !== null ? number_format($tData['acs1'], 1) : '-' }}</td>
                        <td>{{ $tData['acs2'] !== null ? number_format($tData['acs2'], 1) : '-' }}</td>
                        <td>{{ $tData['acs3'] !== null ? number_format($tData['acs3'], 1) : '-' }}</td>
                        <td>{{ $tData['macs'] !== null ? number_format($tData['macs'], 1) : '-' }}</td>
                        <td>{{ $tData['acp'] !== null ? number_format($tData['acp'], 1) : '-' }}</td>
                        <td class="grade-value {{ $isPositive ? 'grade-positive' : 'grade-negative' }}">
                            {{ $tData['mt'] !== null ? number_format($tData['mt'], 1) : '-' }}
                        </td>
                        <td>
                            @if($tData['mt'] !== null)
                                @if($tData['mt'] >= 10)
                                    <span class="badge badge-success">Positiva</span>
                                @else
                                    <span class="badge badge-danger">Negativa</span>
                                @endif
                            @else
                                <span class="badge badge-warning">-</span>
                            @endif
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="8">Nenhuma nota lançada para este período.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Assinaturas -->
    <div class="signatures-container">
        <div class="signature-box" style="float: left;">
            <div class="signature-line"></div>
            O Encarregado de Educação
        </div>
        <div class="signature-box" style="float: right;">
            <div class="signature-line"></div>
            A Direção da Escola
        </div>
        <div style="clear: both;"></div>
    </div>

    <!-- Rodapé -->
    <div class="footer-text">
        Este documento foi gerado de forma automática e eletrónica pelo Sistema Integrado de Gestão Escolar (SIGE).
        <br>
        &copy; {{ date('Y') }} {{ setting('school_name', 'ZamEdu') }} — Todos os direitos reservados.
    </div>

</body>
</html>
