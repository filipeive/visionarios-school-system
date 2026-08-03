<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Pauta Oficial - {{ $class->name }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10px; margin: 15px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #1B5E20; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 16px; color: #1B5E20; text-transform: uppercase; }
        .header h3 { margin: 3px 0; font-size: 13px; font-weight: normal; }
        .header p { margin: 2px 0; font-size: 10px; color: #666; }
        .meta-info { width: 100%; margin-bottom: 15px; font-size: 10px; }
        .meta-info td { padding: 3px; }
        table.pauta { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.pauta th, table.pauta td { border: 1px solid #444; padding: 4px 2px; text-align: center; font-size: 9px; }
        table.pauta th { background-color: #1B5E20; color: #fff; text-transform: uppercase; }
        table.pauta th.sub-header { background-color: #f0f0f0; color: #111; font-weight: normal; }
        .text-left { text-align: left !important; padding-left: 5px !important; }
        .approved { color: #2e7d32; font-weight: bold; }
        .failed { color: #c62828; font-weight: bold; }
        .footer-signatures { width: 100%; margin-top: 40px; text-align: center; }
        .footer-signatures td { width: 33%; padding-top: 40px; border-top: 1px solid #000; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>REPÚBLICA DE MOÇAMBIQUE</h2>
        <h3>MINISTÉRIO DA EDUCAÇÃO E DESENVOLVIMENTO HUMANO</h3>
        <p><strong>SISTEMA INTEGRADO DE GESTÃO ESCOLAR - ZAMEDU</strong></p>
        <h3 style="margin-top: 8px;">PAUTA DE {{ strtoupper($type) }} — ANO LECTIVO {{ $year }}</h3>
    </div>

    <table class="meta-info">
        <tr>
            <td><strong>Escola / Instituição:</strong> {{ setting('school_name', 'ZamEdu SIGE') }}</td>
            <td><strong>Nível de Ensino:</strong> {{ $class->education_level_name }}</td>
            <td><strong>Turma:</strong> {{ $class->name }} ({{ $class->grade_level_name }})</td>
            <td><strong>Data de Emissão:</strong> {{ date('d/m/Y') }}</td>
        </tr>
    </table>

    <table class="pauta">
        <thead>
            <tr>
                <th rowspan="2" style="width: 25px;">N.º</th>
                <th rowspan="2" class="text-left" style="width: 180px;">Nome Completo do Aluno</th>
                @foreach($subjects as $subject)
                    <th colspan="{{ $type === 'anual' ? 4 : 3 }}">{{ $subject->code ?? substr($subject->name, 0, 8) }}</th>
                @endforeach
                <th rowspan="2">Média</th>
                <th rowspan="2">Resultado</th>
            </tr>
            <tr>
                @foreach($subjects as $subject)
                    @if($type === 'trimestral')
                        <th class="sub-header">ACS</th>
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
                            <td>{{ $subData['acs'] ?? '-' }}</td>
                            <td>{{ $subData['acp'] ?? '-' }}</td>
                            <td><strong>{{ $subData['mt'] ?? '-' }}</strong></td>
                        @elseif($type === 'anual')
                            <td>{{ $subData['mt1'] ?? '-' }}</td>
                            <td>{{ $subData['mt2'] ?? '-' }}</td>
                            <td>{{ $subData['mt3'] ?? '-' }}</td>
                            <td><strong>{{ $subData['mf'] ?? '-' }}</strong></td>
                        @else
                            <td>{{ $subData['mf'] ?? '-' }}</td>
                            <td>{{ $subData['exam'] ?? '-' }}</td>
                            <td><strong>{{ $subData['mfd'] ?? '-' }}</strong></td>
                        @endif
                    @endforeach
                    <td><strong>{{ number_format($item['overall_average'], 1) }}</strong></td>
                    <td>
                        @if($item['final_status'] === 'Aprovado')
                            <span class="approved">APROVADO</span>
                        @elseif($item['final_status'] === 'Retido')
                            <span class="failed">RETIDO</span>
                        @else
                            <span>EM CURSO</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="footer-signatures">
        <tr>
            <td>O Director da Escola<br><br>___________________________________</td>
            <td>O Pedagógico / Conselho de Turma<br><br>___________________________________</td>
            <td>O Chefe da Secretaria<br><br>___________________________________</td>
        </tr>
    </table>
</body>
</html>
