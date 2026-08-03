<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Certificado de Conclusão - {{ $student->first_name }} {{ $student->last_name }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }
        body {
            font-family: 'Georgia', 'Times New Roman', serif;
            margin: 0;
            padding: 30px;
            background: #fff;
            color: #222;
        }
        .border-container {
            border: 8px double #1a365d;
            padding: 25px;
            height: 90%;
            position: relative;
            box-sizing: border-box;
        }
        .header {
            text-align: center;
        }
        .header h3 {
            font-size: 13pt;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #1a365d;
        }
        .header h4 {
            font-size: 11pt;
            margin: 3px 0;
            font-weight: normal;
            color: #4a5568;
        }
        .title {
            text-align: center;
            margin: 25px 0 15px 0;
        }
        .title h1 {
            font-size: 26pt;
            margin: 0;
            color: #9b2c2c;
            letter-spacing: 3px;
            font-family: 'Times New Roman', serif;
        }
        .content {
            font-size: 13pt;
            line-height: 1.8;
            text-align: center;
            margin: 20px 40px;
        }
        .student-name {
            font-size: 18pt;
            font-weight: bold;
            color: #1a365d;
            text-transform: uppercase;
            border-bottom: 2px solid #cbd5e0;
            display: inline-block;
            padding: 0 15px;
            margin: 5px 0;
        }
        .footer-table {
            width: 100%;
            margin-top: 40px;
            text-align: center;
            font-size: 11pt;
        }
        .signature-line {
            border-top: 1px solid #2d3748;
            width: 70%;
            margin: 0 auto;
            padding-top: 5px;
        }
        .reg-number {
            position: absolute;
            bottom: 20px;
            left: 30px;
            font-size: 9pt;
            color: #718096;
            font-family: monospace;
        }
    </style>
</head>
<body>

<div class="border-container">
    <div class="header">
        <h3>REPÚBLICA DE MOÇAMBIQUE</h3>
        <h4>MINISTÉRIO DA EDUCAÇÃO E DESENVOLVIMENTO HUMANO</h4>
        <h4>GOVERNO DA PROVÍNCIA DE {{ strtoupper($province) }}</h4>
        <h3 style="margin-top: 10px; color: #2b6cb0;">{{ strtoupper($schoolName) }}</h3>
    </div>

    <div class="title">
        <h1>CERTIFICADO DE CONCLUSÃO</h1>
    </div>

    <div class="content">
        Certifica-se que o(a) estudante<br>
        <span class="student-name">{{ $student->first_name }} {{ $student->last_name }}</span><br>
        concluiu com aproveitamento no presente estabelecimento de ensino o nível de<br>
        <strong>{{ strtoupper($educationLevelName) }} ({{ $className }})</strong><br>
        nos termos do Regulamento Geral do Sistema Nacional de Educação de Moçambique.
    </div>

    <table class="footer-table">
        <tr>
            <td style="width: 50%;">
                {{ $district }}, {{ date('d') }} de {{ strtolower(\Carbon\Carbon::now()->locale('pt')->monthName) }} de {{ date('Y') }}
            </td>
            <td style="width: 50%;">
                <div class="signature-line">
                    O Director da Escola<br>
                    <strong>{{ $directorName }}</strong>
                </div>
            </td>
        </tr>
    </table>

    <div class="reg-number">
        Registo N.º MINEDH-{{ $student->student_number }}-{{ date('Y') }}
    </div>
</div>

</body>
</html>
