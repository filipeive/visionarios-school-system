<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Pauta Oficial - {{ $class->name }} (Ano Lectivo {{ $year }})</title>
    <style>
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 9px; margin: 10px; color: #111; }
        .header { text-align: center; margin-bottom: 15px; border-bottom: 2px solid #1a365d; padding-bottom: 8px; }
        .header h2 { margin: 0; font-size: 14px; color: #1a365d; text-transform: uppercase; letter-spacing: 1px; }
        .header h3 { margin: 2px 0; font-size: 11px; font-weight: normal; color: #2d3748; }
        .header p { margin: 2px 0; font-size: 9px; color: #4a5568; }
        .meta-info { width: 100%; margin-bottom: 12px; font-size: 9px; border-collapse: collapse; }
        .meta-info td { padding: 4px 6px; border: 1px solid #cbd5e0; background-color: #f7fafc; }
        table.pauta { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table.pauta th, table.pauta td { border: 1px solid #2d3748; padding: 4px 2px; text-align: center; font-size: 8.5px; }
        table.pauta th { background-color: #1a365d; color: #fff; text-transform: uppercase; font-size: 8px; }
        table.pauta th.sub-header { background-color: #edf2f7; color: #1a202c; font-weight: bold; }
        .text-left { text-align: left !important; padding-left: 5px !important; }
        .approved { color: #22543d; font-weight: bold; background-color: #c6f6d5; padding: 1px 4px; border-radius: 3px; }
        .failed { color: #742a2a; font-weight: bold; background-color: #fed7d7; padding: 1px 4px; border-radius: 3px; }
        .pending { color: #744210; font-weight: bold; background-color: #feebc8; padding: 1px 4px; border-radius: 3px; }
        .footer-signatures { width: 100%; margin-top: 30px; text-align: center; }
        .footer-signatures td { width: 33%; padding-top: 30px; font-size: 9px; }
        .signature-line { border-top: 1px solid #2d3748; width: 80%; margin: 0 auto; padding-top: 4px; font-weight: bold; }
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
            <td><strong>Nível de Ensino:</strong> {{ $class->education_level_name }}</td>
            <td><strong>Turma / Classe:</strong> {{ $class->name }} ({{ $class->grade_level_name }})</td>
            <td><strong>Tipo de Pauta:</strong> {{ strtoupper($type) }} {{ $type === 'trimestral' ? "({$term}º Trimestre)" : '' }}</td>
            <td><strong>Data de Emissão:</strong> {{ date('d/m/Y') }}</td>
        </tr>
    </table>

    <table class="pauta">
        <thead>
            <tr>
                <th rowspan="2" style="width: 25px;">N.º</th>
                <th rowspan="2" class="text-left" style="width: 170px;">Nome Completo do Aluno</th>
                @foreach($subjects as $subject)
                    <th colspan="{{ $type === 'trimestral' ? 6 : ($type === 'anual' ? 4 : 3) }}">{{ $subject->code ?? strtoupper(substr($subject->name, 0, 7)) }}</th>
                @endforeach
                <th rowspan="2" style="width: 45px;">Média (MF)</th>
                <th rowspan="2" style="width: 70px;">Resultado Final</th>
            </tr>
            <tr>
                @foreach($subjects as $subject)
                    @if($type === 'trimestral')
                        <th class="sub-header">ACS 1</th>
                        <th class="sub-header">ACS 2</th>
                        <th class="sub-header">ACS 3</th>
                        <th class="sub-header">MACS</th>
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
                    <td class="text-left"><strong>{{ $item['student']->first_name }} {{ $item['student']->last_name }}</strong></td>
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
                            <td>{{ isset($subData['mt1']) ? number_format($subData['mt1'], 1) : '-' }}</td>
                            <td>{{ isset($subData['mt2']) ? number_format($subData['mt2'], 1) : '-' }}</td>
                            <td>{{ isset($subData['mt3']) ? number_format($subData['mt3'], 1) : '-' }}</td>
                            <td><strong>{{ isset($subData['mf']) ? number_format($subData['mf'], 1) : '-' }}</strong></td>
                        @else
                            <td>{{ isset($subData['mf']) ? number_format($subData['mf'], 1) : '-' }}</td>
                            <td>{{ isset($subData['exam']) ? number_format($subData['exam'], 1) : '-' }}</td>
                            <td><strong>{{ isset($subData['mfd']) ? number_format($subData['mfd'], 1) : '-' }}</strong></td>
                        @endif
                    @endforeach
                    <td><strong>{{ number_format($item['overall_average'], 1) }}</strong></td>
                    <td>
                        @if($item['final_status'] === 'Aprovado')
                            <span class="approved">APROVADO</span>
                        @elseif($item['final_status'] === 'Retido')
                            <span class="failed">RETIDO</span>
                        @else
                            <span class="pending">EM CURSO</span>
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
