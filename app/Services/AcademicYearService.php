<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AcademicYearService
{
    /**
     * Get the current academic year.
     */
    public function getCurrentYear(): int
    {
        if (!Schema::hasTable('settings')) {
            return (int) date('Y');
        }

        return (int) Setting::where('key', 'current_academic_year')->first()?->value ?? (int) date('Y');
    }

    /**
     * Get the next academic year.
     */
    public function getNextYear(): int
    {
        if (!Schema::hasTable('settings')) {
            return (int) date('Y') + 1;
        }

        return (int) Setting::where('key', 'next_academic_year')->first()?->value ?? ($this->getCurrentYear() + 1);
    }

    /**
     * Transition the system to the next academic year.
     */
    public function transitionToNextYear(): bool
    {
        if (!Schema::hasTable('settings')) {
            return false;
        }

        try {
            DB::beginTransaction();

            $currentYear = $this->getCurrentYear();
            $nextYear = $this->getNextYear();

            // 1. Update settings
            Setting::updateOrCreate(
                ['key' => 'current_academic_year'],
                ['value' => (string) $nextYear]
            );

            Setting::updateOrCreate(
                ['key' => 'next_academic_year'],
                ['value' => (string) ($nextYear + 1)]
            );

            // 2. Deactivate old enrollments
            Enrollment::where('school_year', $currentYear)
                ->where('status', 'active')
                ->update(['status' => 'completed']);

            // 3. Mark students as pending_renewal for the new year
            // Only students who were active in the year that just ended
            Student::where('status', 'active')->update(['status' => 'pending_renewal']);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return false;
        }
    }
}
