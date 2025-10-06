<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Directives para permissões
        Blade::if('can', function ($permission) {
            return auth()->check() && auth()->user()->can($permission);
        });

        Blade::if('role', function ($role) {
            return auth()->check() && auth()->user()->hasRole($role);
        });

        Blade::if('anyrole', function ($roles) {
            if (!auth()->check()) return false;
            
            foreach ($roles as $role) {
                if (auth()->user()->hasRole($role)) {
                    return true;
                }
            }
            return false;
        });
    }
}