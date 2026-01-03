<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SuspendOldEnrollments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enrollments:suspend {year}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Suspend all active enrollments for a specific school year';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $year = $this->argument('year');

        $count = \App\Models\Enrollment::where('school_year', $year)
            ->where('status', 'active')
            ->update(['status' => 'inactive']);

        $this->info("Successfully suspended {$count} enrollments for the year {$year}.");
    }
}
