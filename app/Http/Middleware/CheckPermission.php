<?php
// app/Http/Middleware/CheckPermission.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->hasRole('super_admin')) {
            return $next($request);
        }

        if (!$request->user()->can($permission)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            
            return redirect()->route('dashboard')
                ->with('error', 'Você não tem permissão para acessar esta página.');
        }

        return $next($request);
    }
}