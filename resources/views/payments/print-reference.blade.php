<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Referência de Pagamento - {{ $payment->reference_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #333;
            padding: 20px;
            background: white;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .school-name {
            font-size: 24px;
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 5px;
        }
        .document-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .reference-number {
            font-size: 20px;
            font-weight: bold;
            background: #f8f9fa;
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
            margin: 15px 0;
            letter-spacing: 2px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        .info-section {
            margin-bottom: 15px;
        }
        .info-label {
            font-weight: bold;
            color: #666;
            font-size: 11px;
            margin-bottom: 3px;
        }
        .info-value {
            font-size: 13px;
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }
        .amount-section {
            text-align: center;
            margin: 25px 0;
            padding: 15px;
            background: #f8f9fa;
            border: 1px solid #ddd;
        }
        .amount-label {
            font-size: 14px;
            color: #666;
        }
        .amount-value {
            font-size: 28px;
            font-weight: bold;
            color: #2c5aa0;
        }
        .barcode-area {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            border: 1px dashed #ccc;
        }
        .instructions {
            margin-top: 30px;
            padding: 15px;
            background: #f8f9fa;
            border-left: 4px solid #2c5aa0;
            font-size: 11px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        @media print {
            body { margin: 0; padding: 0; }
            .container { border: none; padding: 10px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- Cabeçalho --}}
        <div class="header">
            <div class="school-name">ESCOLA VISIONÁRIOS</div>
            <div class="document-title">REFERÊNCIA DE PAGAMENTO</div>
            <div>Av. Principal, Cidade - Tel: +258 84 123 4567</div>
        </div>

        {{-- Número da Referência --}}
        <div class="reference-number">
            {{ $payment->reference_number }}
        </div>

        {{-- Informações do Pagamento --}}
        <div class="info-grid">
            <div class="info-section">
                <div class="info-label">BENEFICIÁRIO</div>
                <div class="info-value">ESCOLA VISIONÁRIOS</div>

                <div class="info-label">NIF</div>
                <div class="info-value">123456789</div>

                <div class="info-label">CONTA BANCÁRIA</div>
                <div class="info-value">1234-5678-9012-3456</div>

                <div class="info-label">BANCO</div>
                <div class="info-value">BIM</div>
            </div>

            <div class="info-section">
                <div class="info-label">ALUNO</div>
                <div class="info-value">{{ $payment->student->full_name }}</div>

                <div class="info-label">Nº DE ALUNO</div>
                <div class="info-value">{{ $payment->student->student_number }}</div>

                <div class="info-label">TURMA</div>
                <div class="info-value">{{ $payment->enrollment?->class?->name ?? 'N/A' }}</div>

                <div class="info-label">TIPO</div>
                <div class="info-value">{{ ucfirst($payment->type) }}</div>
            </div>
        </div>

        {{-- Período e Vencimento --}}
        <div class="info-grid">
            <div class="info-section">
                <div class="info-label">PERÍODO</div>
                <div class="info-value">
                    @if($payment->month)
                        {{ \Carbon\Carbon::create()->month($payment->month)->locale('pt')->monthName }} / {{ $payment->year }}
                    @else
                        {{ $payment->year }}
                    @endif
                </div>
            </div>
            <div class="info-section">
                <div class="info-label">DATA DE VENCIMENTO</div>
                <div class="info-value">{{ $payment->due_date->format('d/m/Y') }}</div>
            </div>
        </div>

        {{-- Valor --}}
        <div class="amount-section">
            <div class="amount-label">VALOR A PAGAR</div>
            <div class="amount-value">{{ number_format($payment->total_amount, 2, ',', '.') }} MT</div>
        </div>

        {{-- Área do Código de Barras --}}
        <div class="barcode-area">
            <div style="margin-bottom: 10px; font-weight: bold;">PAGUE COM O CÓDIGO ACIMA</div>
            <div style="font-size: 10px; color: #666;">
                Utilize este código nos terminais Multicaixa, caixas automáticos ou homebanking
            </div>
            {{-- Aqui você pode adicionar um código de barras real se tiver uma biblioteca --}}
            <div style="margin-top: 15px; padding: 10px; background: #fff; border: 1px solid #000; display: inline-block;">
                *** CÓDIGO DE BARRAS AQUI ***
            </div>
        </div>

        {{-- Instruções --}}
        <div class="instructions">
            <strong>INSTRUÇÕES DE PAGAMENTO:</strong><br>
            1. Apresente esta referência em qualquer terminal Multicaixa Express<br>
            2. Ou utilize o código na opção "Pagamento de Serviços" no Multicaixa<br>
            3. Ou transfira o valor para a conta indicada acima<br>
            4. Guarde o comprovativo de pagamento
        </div>

        {{-- Rodapé --}}
        <div class="footer">
            Documento gerado em {{ now()->format('d/m/Y H:i') }} | 
            Referência: {{ $payment->reference_number }} |
            Página 1/1
        </div>

        {{-- Botão de Impressão --}}
        <div class="no-print" style="text-align: center; margin-top: 20px;">
            <button onclick="window.print()" style="padding: 10px 20px; background: #2c5aa0; color: white; border: none; border-radius: 5px; cursor: pointer;">
                🖨️ Imprimir Referência
            </button>
            <button onclick="window.close()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
                ✕ Fechar
            </button>
        </div>
    </div>

    <script>
        // Auto-print opcional
        window.onload = function() {
            // Descomente a linha abaixo para imprimir automaticamente
            // window.print();
        };
    </script>
</body>
</html>