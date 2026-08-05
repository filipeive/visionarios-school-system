<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Pauta Oficial - {{ $class->name }} (Ano Lectivo {{ $year }})</title>
    @php
        $subjectCount = count($subjects);
        $colsPerSubject = $type === 'trimestral' ? 6 : ($type === 'anual' ? 4 : 3);
        $totalCols = ($subjectCount * $colsPerSubject) + 4;
        $paperSize = $paper ?? 'a4';

        if ($paperSize === 'a3') {
            $bodyFont = $totalCols > 40 ? 7 : ($totalCols > 30 ? 7.5 : 8);
            $cellPad = $totalCols > 40 ? '2px 1px' : '3px 2px';
            $cellFont = $totalCols > 40 ? 6.5 : ($totalCols > 30 ? 7 : 7.5);
            $headerFont = $totalCols > 40 ? 6 : ($totalCols > 30 ? 6.5 : 7);
            $nameWidth = $totalCols > 40 ? 130 : 160;
        } else {
            $bodyFont = $totalCols > 30 ? 6 : ($totalCols > 20 ? 7 : 8);
            $cellPad = $totalCols > 30 ? '1px 1px' : ($totalCols > 20 ? '2px 1px' : '3px 2px');
            $cellFont = $totalCols > 30 ? 5.5 : ($totalCols > 20 ? 6.5 : 7.5);
            $headerFont = $totalCols > 30 ? 5 : ($totalCols > 20 ? 6 : 7);
            $nameWidth = $totalCols > 30 ? 100 : ($totalCols > 20 ? 130 : 160);
        }
    @endphp
    <style>
        @page {
            margin: 8mm 6mm 8mm 6mm;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: {{ $bodyFont }}px;
            margin: 0;
            padding: 0;
            color: #111;
        }
        .header {
            text-align: center;
            margin-bottom: 8px;
            border-bottom: 2px solid #1a365d;
            padding-bottom: 6px;
        }
        .header h2 { margin: 0; font-size: 12px; color: #1a365d; text-transform: uppercase; letter-spacing: 1px; }
        .header h3 { margin: 2px 0; font-size: 10px; font-weight: normal; color: #2d3748; }
        .header p  { margin: 2px 0; font-size: 8px; color: #4a5568; }

        .meta-info { width: 100%; margin-bottom: 8px; font-size: {{ $bodyFont }}px; border-collapse: collapse; }
        .meta-info td { padding: 3px 5px; border: 1px solid #cbd5e0; background-color: #f7fafc; }

        table.pauta {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            table-layout: fixed;
        }
        table.pauta th, table.pauta td {
            border: 1px solid #4a5568;
            padding: {{ $cellPad }};
            text-align: center;
            font-size: {{ $cellFont }}px;
            word-wrap: break-word;
            overflow: hidden;
        }
        table.pauta th {
            background-color: #1a365d;
            color: #fff;
            text-transform: uppercase;
            font-size: {{ $headerFont }}px;
            font-weight: bold;
        }
        table.pauta th.sub-header {
            background-color: #edf2f7;
            color: #1a202c;
            font-weight: bold;
            font-size: {{ $headerFont }}px;
        }
        table.pauta td.student-name {
            text-align: left;
            padding-left: 3px;
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        table.pauta td.avg-col {
            font-weight: bold;
            background-color: #ebf8ff;
        }

        .text-left { text-align: left !important; padding-left: 3px !important; }
        .approved { color: #22543d; font-weight: bold; background-color: #c6f6d5; padding: 1px 3px; border-radius: 2px; font-size: {{ $cellFont - 0.5 }}px; }
        .failed   { color: #742a2a; font-weight: bold; background-color: #fed7d7; padding: 1px 3px; border-radius: 2px; font-size: {{ $cellFont - 0.5 }}px; }
        .pending  { color: #744210; font-weight: bold; background-color: #feebc8; padding: 1px 3px; border-radius: 2px; font-size: {{ $cellFont - 0.5 }}px; }

        .footer-signatures { width: 100%; margin-top: 20px; text-align: center; }
        .footer-signatures td { width: 33%; padding-top: 25px; font-size: 8px; }
        .signature-line { border-top: 1px solid #2d3748; width: 80%; margin: 0 auto; padding-top: 3px; font-weight: bold; }

        .graded-indicator { font-size: {{ $cellFont - 1 }}px; color: #718096; }
    </style>
</head>
<body>
    <div class="header">
        <h2>REPÚBLICA DE MOÇAMBIQUE</h2>
        <h3>MINISTÉRIO DA EDUCAÇÃO E DESENVOLVIMENTO HUMANO</h3>
        <h3>{{ strtoupper(\App\Models\Setting::get('school_name', 'ESCOLA SECUNDÁRIA ZAMEDU')) }}</h3>
        <p><strong>PAUTA OFICIAL DE FREQUÊNCIA E AVALIAÇÃO — ANO LECTIVO {{ $year }}</strong></p>
    </div>

    <table class="meta-info">
        <tr>
            <td><strong>Nível:</strong> {{ $class->education_level_name }}</td>
            <td><strong>Turma:</strong> {{ $class->name }} ({{ $class->grade_level_name }})</td>
            <td><strong>Tipo:</strong> {{ strtoupper($type) }} {{ $type === 'trimestral' ? "({$term}º Trim.)" : '' }}</td>
            <td><strong>Emissão:</strong> {{ date('d/m/Y') }}</td>
            <td><strong>Formato:</strong> {{ strtoupper($paperSize) }}</td>
        </tr>
    </table>

    <table class="pauta">
        <thead>
            <tr>
                <th rowspan="2" style="width: 18px;">N.º</th>
                <th rowspan="2" class="text-left" style="width: {{ $nameWidth }}px;">Nome do Aluno</th>
                @foreach($subjects as $subject)
                    <th colspan="{{ $colsPerSubject }}">{{ $subject->code ?? strtoupper(mb_substr($subject->name, 0, 5)) }}</th>
                @endforeach
                <th rowspan="2" style="width: 30px;">MG</th>
                <th rowspan="2" style="width: 50px;">Result.</th>
            </tr>
            <tr>
                @foreach($subjects as $subject)
                    @if($type === 'trimestral')
                        <th class="sub-header">AC1</th>
                        <th class="sub-header">AC2</th>
                        <th class="sub-header">AC3</th>
                        <th class="sub-header">MCS</th>
                        <th class="sub-header">ACP</th>
                        <th class="sub-header">MT</th>
                    @elseif($type === 'anual')
                        <th class="sub-header">T1</th>
                        <th class="sub-header">T2</th>
                        <th class="sub-header">T3</th>
                        <th class="sub-header">MF</th>
                    @else
                        <th class="sub-header">MF</th>
                        <th class="sub-header">EX</th>
                        <th class="sub-header">MFD</th>
                    @endif
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($matrix as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="student-name">{{ $item['student']->first_name }} {{ $item['student']->last_name }}</td>
                    @foreach($subjects as $subject)
                        @php $subData = $item['subjects'][$subject->id] ?? []; @endphp
                        @if($type === 'trimestral')
                            <td>{{ isset($subData['acs1']) && is_numeric($subData['acs1']) ? number_format($subData['acs1'], 1) : '-' }}</td>
                            <td>{{ isset($subData['acs2']) && is_numeric($subData['acs2']) ? number_format($subData['acs2'], 1) : '-' }}</td>
                            <td>{{ isset($subData['acs3']) && is_numeric($subData['acs3']) ? number_format($subData['acs3'], 1) : '-' }}</td>
                            <td><strong>{{ isset($subData['macs']) && is_numeric($subData['macs']) ? number_format($subData['macs'], 1) : '-' }}</strong></td>
                            <td>{{ isset($subData['acp']) && is_numeric($subData['acp']) ? number_format($subData['acp'], 1) : '-' }}</td>
                            <td><strong>{{ isset($subData['mt']) && is_numeric($subData['mt']) ? number_format($subData['mt'], 1) : '-' }}</strong></td>
                        @elseif($type === 'anual')
                            <td>{{ isset($subData['mt1']) && is_numeric($subData['mt1']) ? number_format($subData['mt1'], 1) : '-' }}</td>
                            <td>{{ isset($subData['mt2']) && is_numeric($subData['mt2']) ? number_format($subData['mt2'], 1) : '-' }}</td>
                            <td>{{ isset($subData['mt3']) && is_numeric($subData['mt3']) ? number_format($subData['mt3'], 1) : '-' }}</td>
                            <td><strong>{{ isset($subData['mf']) && is_numeric($subData['mf']) ? number_format($subData['mf'], 1) : '-' }}</strong></td>
                        @else
                            <td>{{ isset($subData['mf']) && is_numeric($subData['mf']) ? number_format($subData['mf'], 1) : '-' }}</td>
                            <td>{{ isset($subData['exam']) && is_numeric($subData['exam']) ? number_format($subData['exam'], 1) : '-' }}</td>
                            <td><strong>{{ isset($subData['mfd']) && is_numeric($subData['mfd']) ? number_format($subData['mfd'], 1) : '-' }}</strong></td>
                        @endif
                    @endforeach
                    <td class="avg-col">
                        {{ $item['overall_average'] > 0 ? number_format($item['overall_average'], 1) : '-' }}
                        @if(isset($item['graded_count']) && isset($item['total_subjects']) && $item['graded_count'] < $item['total_subjects'] && $item['graded_count'] > 0)
                            <br><span class="graded-indicator">({{ $item['graded_count'] }}/{{ $item['total_subjects'] }})</span>
                        @endif
                    </td>
                    <td>
                        @if($item['final_status'] === 'Aprovado')
                            <span class="approved">APROV.</span>
                        @elseif($item['final_status'] === 'Retido')
                            <span class="failed">RETIDO</span>
                        @else
                            <span class="pending">CURSO</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="footer-signatures">
        <tr>
            <td>
                <div class="signature-line">
                    O Director da Turma<br>
                    <strong>{{ $class->teacher ? ($class->teacher->first_name . ' ' . $class->teacher->last_name) : 'O Director da Turma' }}</strong>
                </div>
            </td>
            <td>
                <div class="signature-line">
                    O Director Pedagógico<br>
                    <strong>{{ \App\Models\Setting::get('pedagogical_director_name', 'O Director Pedagógico') }}</strong>
                </div>
            </td>
            <td>
                <div class="signature-line">
                    O Director da Escola<br>
                    <strong>{{ \App\Models\Setting::get('director_name', 'O Director da Escola') }}</strong>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
