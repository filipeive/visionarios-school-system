<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DemoRestrictionMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->email === 'demo@visionarios.co.mz') {
            // Lista de métodos que alteram dados
            $restrictedMethods = ['POST', 'PUT', 'PATCH', 'DELETE'];

            if (in_array($request->method(), $restrictedMethods)) {
                // Permitir apenas o login e logout (embora o login seja GET no demo.access)
                $allowedRoutes = ['login', 'logout', 'demo.access'];

                if (!$request->routeIs($allowedRoutes)) {
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'message' => 'Esta é uma conta de demonstração. Alterações de dados não são permitidas.'
                        ], 403);
                    }

                    return back()->with('error', 'Esta é uma conta de demonstração. Alterações de dados não são permitidas.');
                }
            }
        }

        return $next($request);
    }
}
