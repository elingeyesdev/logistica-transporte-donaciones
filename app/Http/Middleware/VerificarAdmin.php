<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class VerificarAdmin
{
    public function handle(Request $request, Closure $next)
    {
         /** @var User|null $user */
        $user = Auth::user();
        
        if (!$user) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permisos para acceder a este recurso.'
                ], 403);
            }

            return redirect()
                ->route('home')
                ->with('error', 'No tienes permisos para acceder a esta sección.');
        }
        $tieneRolAdmin = method_exists($user, 'hasRole') && $user->hasRole('admin');
        $esAdminPrev = (bool) $user->administrador;
        if (!$tieneRolAdmin && !$esAdminPrev) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permisos para acceder a este recurso.'
                ], 403);
            }

            return redirect()
                ->route('home')
                ->with('error', 'No tienes permisos para acceder a esta sección.');
        }
        return $next($request);
    }
}
