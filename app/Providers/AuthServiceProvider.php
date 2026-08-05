<?php

namespace App\Providers;

use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\Payment;
use App\Models\Student;
use App\Policies\EnrollmentPolicy;
use App\Policies\GradePolicy;
use App\Policies\PaymentPolicy;
use App\Policies\StudentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Student::class => StudentPolicy::class,
        Grade::class => GradePolicy::class,
        Payment::class => PaymentPolicy::class,
        Enrollment::class => EnrollmentPolicy::class,
        \App\Models\Expense::class => \App\Policies\ExpensePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            if ($user && ($user->hasRole('admin') || $user->hasRole('super_admin') || $user->hasRole('superadmin') || $user->role === 'admin' || $user->role === 'superadmin' || $user->role === 'super_admin')) {
                return true;
            }

            return null;
        });
    }
}
