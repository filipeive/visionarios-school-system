<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;

class StudentPolicy
{
    /**
     * Determine if the user can view any students.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_students');
    }

    /**
     * Determine if the user can view the student.
     */
    public function view(User $user, Student $student): bool
    {
        return $user->can('view_students');
    }

    /**
     * Determine if the user can create students.
     */
    public function create(User $user): bool
    {
        return $user->can('create_students');
    }

    /**
     * Determine if the user can update the student.
     */
    public function update(User $user, Student $student): bool
    {
        return $user->can('edit_students');
    }

    /**
     * Determine if the user can delete the student.
     */
    public function delete(User $user, Student $student): bool
    {
        if ($student->payments()->exists() ||
            $student->attendances()->exists() ||
            $student->grades()->exists()) {
            return false;
        }

        return $user->can('delete_students');
    }

    /**
     * Determine if the user can view student grades.
     */
    public function viewGrades(User $user, Student $student): bool
    {
        return $user->can('view_students');
    }

    /**
     * Determine if the user can view student attendance.
     */
    public function viewAttendance(User $user, Student $student): bool
    {
        return $user->can('view_students');
    }

    /**
     * Determine if the user can view student payments.
     */
    public function viewPayments(User $user, Student $student): bool
    {
        return $user->can('view_students');
    }

    /**
     * Determine if the user can manage student support/observations.
     */
    public function manageSupport(User $user, Student $student): bool
    {
        return $user->can('manage_observations');
    }
}
