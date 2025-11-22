<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class ApplyPaymentPenalties extends Command
{
    protected $signature = 'payments:apply-penalties';
    protected $description = 'Aplica multas automáticas em pagamentos em atraso';

    public function handle()
    {
        $payments = Payment::where('status', 'pending')
            ->where('due_date', '<', now())
            ->whereNull('penalty_applied_at')
            ->get();

        $appliedCount = 0;

        foreach ($payments as $payment) {
            if ($payment->needsPenaltyApplication()) {
                $payment->applyAutomaticPenalty();
                $appliedCount++;
                
                activity()
                    ->performedOn($payment)
                    ->log("Multa automática aplicada: {$payment->penalty_percentage}%");
            }
        }

        $this->info("Multas aplicadas em {$appliedCount} pagamentos.");
        Log::info("Sistema de multas executado: {$appliedCount} pagamentos processados.");
    }
}