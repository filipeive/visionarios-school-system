<?php

use App\Services\AcademicYearService;

if (!function_exists('current_school_year')) {
    /**
     * Get the current academic year.
     */
    function current_school_year(): int
    {
        return app(AcademicYearService::class)->getCurrentYear();
    }
}

if (!function_exists('next_school_year')) {
    /**
     * Get the next academic year.
     */
    function next_school_year(): int
    {
        return app(AcademicYearService::class)->getNextYear();
    }
}
