<?php

namespace App\Policies;

use App\Models\Enrollment;
use App\Models\User;

class EnrollmentPolicy
{
    /**
     * Determine if the user can view any enrollments.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_enrollments');
    }

    /**
     * Determine if the user can view the enrollment.
     */
    public function view(User $user, Enrollment $enrollment): bool
    {
        return $user->can('view_enrollments');
    }

    /**
     * Determine if the user can create enrollments.
     */
    public function create(User $user): bool
    {
        return $user->can('create_enrollments');
    }

    /**
     * Determine if the user can update enrollments.
     */
    public function update(User $user, Enrollment $enrollment): bool
    {
        if ($enrollment->status === 'cancelled' || $enrollment->status === 'transferred') {
            return false;
        }

        return $user->can('edit_enrollments');
    }

    /**
     * Determine if the user can activate enrollments.
     */
    public function activate(User $user, Enrollment $enrollment): bool
    {
        if ($enrollment->status !== 'pending') {
            return false;
        }

        return $user->can('edit_enrollments');
    }

    /**
     * Determine if the user can cancel enrollments.
     */
    public function cancel(User $user, Enrollment $enrollment): bool
    {
        if ($enrollment->status === 'cancelled' || $enrollment->status === 'transferred') {
            return false;
        }

        return $user->can('edit_enrollments');
    }

    /**
     * Determine if the user can transfer enrollments.
     */
    public function transfer(User $user, Enrollment $enrollment): bool
    {
        if ($enrollment->status !== 'active') {
            return false;
        }

        return $user->can('edit_enrollments');
    }

    /**
     * Determine if the user can renew enrollments.
     */
    public function renew(User $user, Enrollment $enrollment): bool
    {
        return $user->can('create_enrollments');
    }
}
