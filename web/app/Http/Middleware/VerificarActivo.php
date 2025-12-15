<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificarActivo
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return $next($request);
        }

        if ($user->administrador) {
            return $next($request);
        }

        if ($request->routeIs('perfil.pendiente')) {
            return $next($request);
        }

        if (!$user->activo) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tu cuenta está pendiente de activación por el administrador.',
                ], 403);
            }

            return redirect()
                ->route('perfil.pendiente')
                ->with('error', 'Tu perfil aún no ha sido habilitado por el administrador.');
        }

        return $next($request);
    }
}
