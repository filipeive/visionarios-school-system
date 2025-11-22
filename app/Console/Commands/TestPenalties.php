<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;

class TestPenalties extends Command
{
    protected $signature = 'test:penalties';
    protected $description = 'Testar sistema de multas';

    public function handle()
    {
        $this->info('=== TESTE DO SISTEMA DE MULTAS ===');

        // Listar pagamentos pendentes em atraso
        $payments = Payment::where('status', 'pending')
            ->where('due_date', '<', now())
            ->whereNull('penalty_applied_at')
            ->get();

        $this->info("Encontrados {$payments->count()} pagamentos para aplicar multa");

        foreach ($payments as $payment) {
            $this->info("\n--- Pagamento: {$payment->reference_number} ---");
            $this->info("Valor: {$payment->amount}");
            $this->info("Vencimento: {$payment->due_date->format('d/m/Y')}");
            $this->info("Dias em atraso: {$payment->days_late}");
            $this->info("Precisa de multa: " . ($payment->needsPenaltyApplication() ? 'SIM' : 'NÃO'));
            $this->info("Estágio da multa: {$payment->penalty_stage}");
            $this->info("Multa sugerida: {$payment->suggested_penalty_percentage}%");

            if ($payment->needsPenaltyApplication()) {
                $this->info("✅ APLICANDO MULTA...");
                $result = $payment->applyAutomaticPenalty();
                $this->info("Resultado: " . ($result ? 'SUCESSO' : 'FALHA'));
                $payment->refresh();
                $this->info("Multa aplicada: {$payment->penalty_percentage}%");
                $this->info("Valor da multa: {$payment->penalty}");
            }
        }

        $this->info("\n=== FIM DO TESTE ===");
    }
}