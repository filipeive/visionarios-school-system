<?php

namespace App\Policies;

use App\Models\Grade;
use App\Models\User;

class GradePolicy
{
    /**
     * Determine if the user can view any grades.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_grades');
    }

    /**
     * Determine if the user can view the grade.
     */
    public function view(User $user, Grade $grade): bool
    {
        return $user->can('view_grades');
    }

    /**
     * Determine if the user can create grades.
     */
    public function create(User $user): bool
    {
        return $user->can('create_grades');
    }

    /**
     * Determine if the user can update the grade.
     */
    public function update(User $user, Grade $grade): bool
    {
        if (! $user->can('edit_grades')) {
            return false;
        }

        // Teachers can only edit their own grades
        if ($user->hasRole('teacher') && $grade->teacher_id !== $user->teacher?->id) {
            return false;
        }

        return true;
    }

    /**
     * Determine if the user can delete the grade.
     */
    public function delete(User $user, Grade $grade): bool
    {
        if (! $user->can('edit_grades')) {
            return false;
        }

        if ($user->hasRole('teacher') && $grade->teacher_id !== $user->teacher?->id) {
            return false;
        }

        return true;
    }

    /**
     * Determine if the user can view report cards.
     */
    public function viewReportCard(User $user): bool
    {
        return $user->can('view_grades');
    }

    /**
     * Determine if the user can export grades.
     */
    public function export(User $user): bool
    {
        return $user->can('export_reports');
    }
}
