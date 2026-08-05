<?php

namespace App\Policies;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ExpensePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->hasRole('admin') || $user->hasRole('secretary');
    }

    public function view(User $user, Expense $expense): bool
    {
        return $user->hasRole('super_admin') || $user->hasRole('admin') || $user->hasRole('secretary');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->hasRole('admin') || $user->hasRole('secretary');
    }

    public function update(User $user, Expense $expense): bool
    {
        if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('secretary') && !$expense->approved_at) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Expense $expense): bool
    {
        if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('secretary') && !$expense->approved_at) {
            return true;
        }

        return false;
    }

    public function approve(User $user, Expense $expense): bool
    {
        return $user->hasRole('super_admin') || $user->hasRole('admin');
    }
}
