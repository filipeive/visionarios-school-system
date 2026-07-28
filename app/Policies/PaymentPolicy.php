<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    /**
     * Determine if the user can view any payments.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_payments');
    }

    /**
     * Determine if the user can view the payment.
     */
    public function view(User $user, Payment $payment): bool
    {
        return $user->can('view_payments');
    }

    /**
     * Determine if the user can create payments.
     */
    public function create(User $user): bool
    {
        return $user->can('create_payments');
    }

    /**
     * Determine if the user can process payments.
     */
    public function process(User $user, Payment $payment): bool
    {
        if ($payment->status === 'paid') {
            return false;
        }

        return $user->can('process_payments');
    }

    /**
     * Determine if the user can cancel payments.
     */
    public function cancel(User $user, Payment $payment): bool
    {
        if ($payment->status === 'paid') {
            return false;
        }

        return $user->can('process_payments');
    }

    /**
     * Determine if the user can apply penalties.
     */
    public function applyPenalty(User $user, Payment $payment): bool
    {
        if ($payment->status !== 'pending' && $payment->status !== 'overdue') {
            return false;
        }

        return $user->can('process_payments');
    }

    /**
     * Determine if the user can generate payment references.
     */
    public function generateReference(User $user): bool
    {
        return $user->can('generate_payment_references');
    }

    /**
     * Determine if the user can view financial reports.
     */
    public function viewFinancialReports(User $user): bool
    {
        return $user->can('view_financial_reports');
    }
}
