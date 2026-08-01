<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
        /**
     * Define the application's command schedule.
     */

    protected function schedule(Schedule $schedule)
    {
        // Geração automática de propinas no dia 20 de cada mês
        $schedule->command('payments:generate-monthly-fees')->monthlyOn(20, '06:00');

        // Aplicação de multas em pagamentos vencidos (dia a dia)
        $schedule->command('payments:apply-penalties')->dailyAt('08:00');

        // Verificação diária da licença ZamEdu
        $schedule->command('license:check')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
