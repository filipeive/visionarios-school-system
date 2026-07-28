<?php

namespace App\Console\Commands;

use App\Services\PaymentService;
use Illuminate\Console\Command;

class GenerateMonthlyTuitions extends Command
{
    protected $signature = 'payments:generate-monthly-fees
                            {--month= : Mês de referência (1-12)}
                            {--school-year= : Ano letivo}
                            {--calendar-year= : Ano civil do mês de referência}
                            {--no-notify : Não envia notificações aos encarregados}';

    protected $description = 'Gera propinas mensais para matrículas ativas e define vencimento no dia 05 do mês seguinte.';

    public function __construct(private readonly PaymentService $paymentService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $month = $this->option('month') ? (int) $this->option('month') : null;
        $schoolYear = $this->option('school-year') ? (int) $this->option('school-year') : null;
        $calendarYear = $this->option('calendar-year') ? (int) $this->option('calendar-year') : null;
        $notifyParents = !$this->option('no-notify');

        if ($month !== null && ($month < 1 || $month > 12)) {
            $this->error('O parâmetro --month deve estar entre 1 e 12.');
            return self::FAILURE;
        }

        $result = $this->paymentService->generateMonthlyTuitionsForActiveEnrollments(
            month: $month,
            schoolYear: $schoolYear,
            calendarYear: $calendarYear,
            notifyParents: $notifyParents
        );

        $this->info("Propinas geradas: {$result['created']}");
        $this->line("Ignoradas (já existentes): {$result['skipped']}");
        $this->line("Notificações enviadas: {$result['notified']}");
        $this->line("Vencimento aplicado: {$result['due_date']}");

        if (!empty($result['errors'])) {
            $this->warn('Ocorreram falhas de notificação:');
            foreach (array_slice($result['errors'], 0, 10) as $error) {
                $this->line("- {$error}");
            }
        }

        return self::SUCCESS;
    }
}
