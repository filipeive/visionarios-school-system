<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Certidão de Habilitações Literárias - {{ $student->first_name }} {{ $student->last_name }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #111;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
        }
        .header h3 {
            font-size: 12pt;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header h4 {
            font-size: 11pt;
            margin: 2px 0;
            font-weight: normal;
        }
        .header h2 {
            font-size: 14pt;
            margin: 15px 0 5px 0;
            text-transform: uppercase;
            border-bottom: 2px solid #000;
            display: inline-block;
            padding-bottom: 3px;
        }
        .cert-body {
            text-align: justify;
            margin-bottom: 20px;
        }
        .table-grades {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .table-grades th, .table-grades td {
            border: 1px solid #000;
            padding: 6px 10px;
            font-size: 10pt;
        }
        .table-grades th {
            background-color: #f2f2f2;
            text-align: left;
            text-transform: uppercase;
        }
        .footer {
            margin-top: 40px;
            width: 100%;
        }
        .signature-table {
            width: 100%;
            margin-top: 50px;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #000;
            width: 80%;
            margin: 0 auto;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h3>REPÚBLICA DE MOÇAMBIQUE</h3>
        <h4>MINISTÉRIO DA EDUCAÇÃO E DESENVOLVIMENTO HUMANO</h4>
        <h4>DIRECÇÃO PROVINCIAL DE EDUCAÇÃO DE {{ strtoupper($province) }}</h4>
        <h2>{{ strtoupper($schoolName) }}</h2>
    </div>

    <div class="header">
        <h2 style="border-bottom: none; text-decoration: underline;">CERTIDÃO DE HABILITAÇÕES LITERÁRIAS</h2>
    </div>

    <div class="cert-body">
        <p>
            <strong>CERTIFICO</strong>, para os devidos efeitos, que <strong>{{ strtoupper($student->first_name . ' ' . $student->last_name) }}</strong>, 
            filho(a) de {{ $student->parent?->full_name ?? 'N/A' }}, portador(a) do documento de identificação N.º <strong>{{ $student->bi_number ?? $student->student_number }}</strong>, 
            obteve no presente estabelecimento de ensino as seguintes classificações finais por disciplina:
        </p>

        <table class="table-grades">
            <thead>
                <tr>
                    <th style="width: 50%;">Disciplina</th>
                    <th style="width: 20%; text-align: center;">Nota (0-20)</th>
                    <th style="width: 30%;">Classificação por Extenso</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subjectsData as $sub)
                    <tr>
                        <td>{{ $sub['name'] }}</td>
                        <td style="text-align: center; font-weight: bold;">{{ number_format($sub['grade'], 1) }}</td>
                        <td>{{ $sub['grade_words'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center;">Nenhuma disciplina registada.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="background-color: #f9f9f9; font-weight: bold;">
                    <td>MÉDIA GERAL FINAL</td>
                    <td style="text-align: center; color: #000; font-size: 11pt;">{{ number_format($finalAverage, 1) }}</td>
                    <td>{{ $finalAverageWords }}</td>
                </tr>
            </tfoot>
        </table>

        <p>
            E, por ser verdade e ter sido me solicitado, mandei passar a presente certidão que vai por mim assinada e autenticada com o carimbo a óleo em uso neste estabelecimento de ensino.
        </p>

        <p style="text-align: right; margin-top: 30px;">
            {{ $district }}, {{ date('d') }} de {{ strtolower(\Carbon\Carbon::now()->locale('pt')->monthName) }} de {{ date('Y') }}.
        </p>
    </div>

    <table class="signature-table">
        <tr>
            <td style="width: 50%;">
                <div class="signature-line">
                    O Chefe da Secretaria<br>
                    <strong>{{ $secretaryName }}</strong>
                </div>
            </td>
            <td style="width: 50%;">
                <div class="signature-line">
                    O Director da Escola<br>
                    <strong>{{ $directorName }}</strong>
                </div>
            </td>
        </tr>
    </table>

</body>
</html>
