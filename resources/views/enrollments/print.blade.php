<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Comprovante de Matrícula - {{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }}
    </title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .school-name {
            font-size: 24px;
            font-weight: bold;
            color: #2E7D32;
        }

        .school-motto {
            font-size: 14px;
            color: #666;
        }

        .content {
            margin: 20px 0;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            background: #f8f9fa;
            padding: 8px;
            font-weight: bold;
            border-left: 4px solid #2E7D32;
        }

        .info-row {
            display: flex;
            margin-bottom: 8px;
        }

        .info-label {
            font-weight: bold;
            width: 200px;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .signature-area {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }

        .signature-line {
            border-top: 1px solid #000;
            width: 200px;
            text-align: center;
            padding-top: 5px;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="school-name">ESCOLA DOS VISIONÁRIOS</div>
        <div class="school-motto">AQUI SE PREPARA A NOVA GERAÇÃO</div>
        <h2>COMPROVANTE DE MATRÍCULA</h2>
    </div>

    <div class="content">
        <div class="section">
            <div class="section-title">DADOS DO ALUNO</div>
            <div class="info-row">
                <div class="info-label">Nome Completo:</div>
                <div>{{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Número do Estudante:</div>
                <div>{{ $enrollment->student->student_number ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Data de Nascimento:</div>
                <div>{{ $enrollment->student->birthdate ? $enrollment->student->birthdate->format('d/m/Y') : 'N/A' }}
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Idade:</div>
                <div>{{ $enrollment->student->age ?? 'N/A' }} anos</div>
            </div>
            <div class="info-row">
                <div class="info-label">Gênero:</div>
                <div>{{ $enrollment->student->gender ?? 'N/A' }}</div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">DADOS DA MATRÍCULA</div>
            <div class="info-row">
                <div class="info-label">Turma:</div>
                <div>{{ $enrollment->class->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Ano Letivo:</div>
                <div>{{ $enrollment->school_year }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Data da Matrícula:</div>
                <div>{{ $enrollment->enrollment_date->format('d/m/Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Status:</div>
                <div>{{ ucfirst($enrollment->status) }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Status da Matrícula:</div>
                <div>
                    {{ ucfirst($enrollment->status) }}
                    @if ($enrollmentPayment)
                        <br><small class="text-muted">
                            Taxa de Matrícula:
                            {{ number_format($enrollmentPayment->amount, 2, ',', '.') }} MZN
                            ({{ $enrollmentPayment->status == 'paid' ? 'Paga' : 'Pendente' }})
                        </small>
                    @endif
                </div>
            </div>

            <div class="section">
                <div class="section-title">INFORMAÇÕES FINANCEIRAS</div>
                <div class="info-row">
                    <div class="info-label">Mensalidade:</div>
                    <div>{{ number_format($enrollment->monthly_fee, 2, ',', '.') }} MZN</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Dia de Pagamento:</div>
                    <div>{{ $enrollment->payment_day }} de cada mês</div>
                </div>
            </div>

            @if ($enrollment->observations)
                <div class="section">
                    <div class="section-title">OBSERVAÇÕES</div>
                    <div>{{ $enrollment->observations }}</div>
                </div>
            @endif
        </div>

        <div class="signature-area">
            <div class="signature-line">Assinatura do Responsável</div>
            <div class="signature-line">Assinatura da Direção</div>
        </div>

        <div class="footer">
            Emitido em: {{ now()->format('d/m/Y H:i') }}<br>
            Escola dos Visionários - Sistema de Gestão Escolar
        </div>
</body>

</html>
